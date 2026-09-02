<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\VendorProductManagement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\ProductScraperService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
class ScraperController extends Controller
{
    /**
     * Display a listing of vendor products.
     */
    public function index(Request $request): View
    {
        $query = VendorProductManagement::withTrashed()->with(['category', 'subcategory', 'brand']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('vendor_code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->boolean('status'));
        }

        if ($request->filled('cat_id')) {
            $query->where('cat_id', $request->input('cat_id'));
        }

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->input('brand_id'));
        }

        $items = $query->latest()->paginate(25)->withQueryString();

        return view('dashboard.vpm.index', compact('items'));
    }

    /**
     * Scrape product links from Ayushmedi collection page with class 'card-link'.
     */
    public function getVendorPrice(Request $request)
    {
        $url = "https://ayushmedi.com/collections/sharmayu-pharma";
        // die($url);
        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            ])->timeout(30)->get($url);

            $html = $response->body();
        } catch (\Exception $e) {
            $opts = [
                'http' => [
                    'method' => 'GET',
                    'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36\r\n"
                ]
            ];
            $context = stream_context_create($opts);
            $html = @file_get_contents($url, false, $context);
        }

        $links = [];

        if (!empty($html)) {
            $dom = new \DOMDocument();
            libxml_use_internal_errors(true);
            $dom->loadHTML($html);
            libxml_clear_errors();

            $xpath = new \DOMXPath($dom);

            // Find all <a> elements with class 'card-link'
            $nodes = $xpath->query("//a[contains(concat(' ', normalize-space(@class), ' '), ' card-link ')]");

            foreach ($nodes as $node) {
                $href = trim($node->getAttribute('href'));
                if ($href !== '') {
                    if (!str_starts_with($href, 'http')) {
                        $href = 'https://ayushmedi.com' . (str_starts_with($href, '/') ? '' : '/') . $href;
                    }
                    $links[] = $href;
                }
            }

            $links = array_values(array_unique($links));
        }

        $scraper = new ProductScraperService();
        $scrapedData = [];

        foreach ($links as $k => $url) {
            // if ($k > 1) {
            //     continue;
            // }
            echo "<br>" . $k . '  ' . $url;
            $data = $scraper->scrapeProductLdJson($url);

            if ($data) {
                echo "<pre>";
                print_r($data);
                echo "</pre>";
                $scrapedData[] = $data;
            } else {
                // No Product JSON-LD found on the page, or fetch failed
                Log::warning("No product data found for: {$url}");
            }
        }

        // Reusable insertion/update for vendor products (vid = 1) after the for loop
        $vid = 1;
        $result = $this->saveVendorProducts($scrapedData, $vid);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'count' => count($links),
                'scraped' => count($scrapedData),
                'inserted' => $result['inserted'],
                'updated' => $result['updated'],
                'links' => $links,
            ]);
        }

        echo "<br>Processing completed! Inserted: {$result['inserted']}, Updated: {$result['updated']}";
    }

    /**
     * Reusable function to insert or update product data in vendor_product_management table.
     *
     * @param array $products Array of scraped product arrays (or a single product array).
     * @param int $vid Vendor ID (default: 1).
     * @return array Summary with 'inserted' and 'updated' counts.
     */
    public function saveVendorProducts(array $products, int $vid = 1): array
    {
        if (empty($products)) {
            return ['inserted' => 0, 'updated' => 0];
        }

        // If a single product array is passed, wrap it in a list
        if (isset($products['name']) || isset($products['url']) || isset($products['sku'])) {
            $products = [$products];
        }

        $insertedCount = 0;
        $updatedCount = 0;

        foreach ($products as $product) {
            $name = $product['name'] ?? $product['offers']['name'] ?? null;
            $description = $product['description'] ?? $product['offers']['description'] ?? null;
            $info = $product['info'] ?? null;
            $price = $product['offers']['price'] ?? $product['price'] ?? 0.00;
            $vendorProdUrl = $product['url'] ?? $product['offers']['url'] ?? null;

            $imgurl = $product['image'] ?? $product['offers']['image'] ?? null;
            if (is_array($imgurl)) {
                $imgurl = $imgurl[0] ?? null;
            }

            $catId = $product['cat_id'] ?? null;
            $subcatId = $product['subcat_id'] ?? null;

            // Brand lookup if brand name is provided
            $brandId = $product['brand_id'] ?? null;
            $brandName = $product['brand']['name'] ?? null;
            if (!$brandId && !empty($brandName)) {
                $brand = Brand::where('name', $brandName)->first();
                if ($brand) {
                    $brandId = $brand->id;
                }
            }

            $vendorCode = $product['productID'] ?? $product['vendor_code'] ?? null;
            $sku = $product['sku'] ?? $product['offers']['sku'] ?? null;
            $status = $product['status'] ?? true;

            if (empty($name)) {
                continue;
            }

            // Check if vendor_code or sku for vendor (vid) already exists
            $existing = null;
            if (!empty($vendorCode) || !empty($sku)) {
                $existing = VendorProductManagement::where('vid', $vid)
                    ->where(function ($q) use ($vendorCode, $sku) {
                        if (!empty($vendorCode) && !empty($sku)) {
                            $q->where('vendor_code', $vendorCode)->orWhere('sku', $sku);
                        } elseif (!empty($vendorCode)) {
                            $q->where('vendor_code', $vendorCode);
                        } elseif (!empty($sku)) {
                            $q->where('sku', $sku);
                        }
                    })
                    ->first();
            }

            if ($existing) {
                // If vendor_code/sku for vendor exists, update price
                $existing->update([
                    'price' => $price,
                ]);
                $updatedCount++;
            } else {
                // Insert new vendor product
                VendorProductManagement::create([
                    'name' => $name,
                    'description' => $description,
                    'info' => $info,
                    'price' => $price,
                    'vendor_prod_url' => $vendorProdUrl,
                    'imgurl' => $imgurl,
                    'vid' => $vid,
                    'cat_id' => $catId,
                    'subcat_id' => $subcatId,
                    'brand_id' => $brandId,
                    'vendor_code' => $vendorCode,
                    'sku' => $sku,
                    'status' => $status,
                ]);
                $insertedCount++;
            }
        }

        return [
            'inserted' => $insertedCount,
            'updated' => $updatedCount,
        ];
    }

    /**
     * Store a newly created vendor product in database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'info' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'imgurl' => 'nullable|string|max:500',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
            'vid' => 'nullable|integer',
            'cat_id' => 'nullable|integer',
            'subcat_id' => 'nullable|integer',
            'brand_id' => 'nullable|integer',
            'vendor_code' => 'nullable|string|max:100',
            'status' => 'nullable|boolean',
        ]);

        $validated['status'] = $request->has('status');

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = time() . '_vpm_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/vpm'), $filename);
            $validated['imgurl'] = 'images/vpm/' . $filename;
        }

        unset($validated['image_file']);

        VendorProductManagement::create($validated);

        return redirect()->route('dashboard.vpm.index')
            ->with('success', 'Vendor product created successfully.');
    }



}
