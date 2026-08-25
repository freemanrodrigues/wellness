<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\VendorProductManagement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

use Illuminate\Support\Facades\Http;

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
        $url = "https://ayushmedi.com/collections/dabur";

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

        foreach ($links as $k => $url) {
            echo "<br>" . $url;
        }


        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'count' => count($links),
                'links' => $links,
            ]);
        }


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
