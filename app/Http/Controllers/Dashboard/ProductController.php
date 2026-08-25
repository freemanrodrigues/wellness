<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\{Brand, Category, HealthConcern, Product};



use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(Request $request): View
    {
        $query = Product::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('short_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('isactive')) {
            $query->where('isactive', $request->boolean('isactive'));
        }

        $products = $query->latest()->paginate(20)->withQueryString();

        return view('dashboard.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(): View
    {
        $categories = Category::whereNull('parent_id')
            ->orWhere('parent_id', 0)
            ->orderBy('name')
            ->get();

        $brands = Brand::where('status', true)->orderBy('sort_order')->orderBy('name')->get();

        return view('dashboard.products.create', compact('categories', 'brands'));
    }


    /**
     * Store a newly created product in the database.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rules());

        Product::create($data);

        return redirect()->route('dashboard.products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product): View
    {
        return view('dashboard.products.show', compact('product'));
    }

    /**
     * Show CSV import form.
     */
    public function importForm(): View
    {
        $mandatoryColumns = [
            'name',
            'vendor_product_name',
            'description',
            'price',
            'deliverycharge',
            'isactive',
            'imgurl',
            'metatitle',
            'metadesc',
            'metaurl',
            'vid',
            'cat_id',
            'subcat_id',
            'vendor_code'
        ];

        $allColumns = [
            'id',
            'name',
            'short_name',
            'vendor_product_name',
            'short_description',
            'description',
            'info',
            'use_case',
            'price',
            'discount',
            'deliverycharge',
            'vendorprice',
            'vendordeliveryprice',
            'more_price',
            'isactive',
            'imgurl',
            'more_img',
            'metatitle',
            'metadesc',
            'metakeyword',
            'metaurl',
            'cid',
            'vid',
            'cat_id',
            'subcat_id',
            'brand_id',
            'vendor_code',
            'sku',
            'barcode'
        ];

        return view('dashboard.products.import', compact('mandatoryColumns', 'allColumns'));
    }

    /**
     * Process uploaded CSV file and insert/update products.
     */
    public function importProcess(Request $request): RedirectResponse
    {

        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');

        if (!$handle) {
            dd("148");
            return back()->with('error', 'Unable to open uploaded CSV file.');
        }

        // Read header row
        $rawHeader = fgetcsv($handle, 4096, ',');
        if (!$rawHeader) {
            fclose($handle);
            dd("156");
            return back()->with('error', 'CSV file is empty or formatted incorrectly.');
        }

        // Trim header column names
        $header = array_map('trim', $rawHeader);

        $mandatoryColumns = [
            'name',
            'vendor_product_name',
            'description',
            'price',
            'deliverycharge',
            'isactive',
            'imgurl',
            'metatitle',
            'metadesc',
            'metaurl',
            'vid',
            'cat_id',
            'subcat_id',
            'vendor_code'
        ];

        $missingColumns = array_diff($mandatoryColumns, $header);
        if (!empty($missingColumns)) {
            fclose($handle);
            dd("184");
            return back()->with('error', 'CSV file is missing mandatory columns: ' . implode(', ', $missingColumns));
        }

        $insertedCount = 0;
        $updatedCount = 0;
        $errors = [];
        $rowNum = 1;

        while (($rowValues = fgetcsv($handle, 4096, ',')) !== false) {
            $rowNum++;

            // Skip completely empty lines
            if (array_filter($rowValues) === []) {
                echo "<br> RRRRRRRRRRRRRRRRRR XZXXZRRRR";
                continue;
            }

            if (count($header) !== count($rowValues)) {
                $errors[] = "Row {$rowNum}: Column count does not match header.";
                echo "<br>  QQQQQ1";
                continue;
            }

            $row = array_combine($header, array_map('trim', $rowValues));

            // Check mandatory fields
            $rowMissing = [];
            foreach ($mandatoryColumns as $col) {
                if (!isset($row[$col]) || $row[$col] === '') {
                    $rowMissing[] = $col;
                }
            }

            if (!empty($rowMissing)) {
                $errors[] = "Row {$rowNum}: Missing required values for (" . implode(', ', $rowMissing) . ").";
                echo "<br>  RRRRRRRRRRRRRRRRRR RRRR";
                continue;
            }

            // Parse booleans and numeric values
            $isActiveVal = strtolower($row['isactive']);
            $isactive = in_array($isActiveVal, ['1', 'true', 'active', 'yes'], true) ? 1 : 0;

            $productData = [
                'name' => $row['name'],
                'short_name' => $row['short_name'] ?? null,
                'vendor_product_name' => $row['vendor_product_name'],
                'short_description' => $row['short_description'] ?? null,
                'description' => $row['description'],
                'info' => $row['info'] ?? null,
                'use_case' => $row['use_case'] ?? null,
                'price' => is_numeric($row['price']) ? (float) $row['price'] : 0,
                'discount' => isset($row['discount']) && is_numeric($row['discount']) ? (float) $row['discount'] : 0,
                'deliverycharge' => is_numeric($row['deliverycharge']) ? (float) $row['deliverycharge'] : 0,
                'vendorprice' => isset($row['vendorprice']) && is_numeric($row['vendorprice']) ? (float) $row['vendorprice'] : 0,
                'vendordeliveryprice' => isset($row['vendordeliveryprice']) && is_numeric($row['vendordeliveryprice']) ? (float) $row['vendordeliveryprice'] : 0,
                'more_price' => isset($row['more_price']) && in_array(strtolower($row['more_price']), ['1', 'true', 'yes'], true) ? 1 : 0,
                'isactive' => $isactive,
                'imgurl' => $row['imgurl'],
                'more_img' => isset($row['more_img']) && in_array(strtolower($row['more_img']), ['1', 'true', 'yes'], true) ? 1 : 0,
                'metatitle' => $row['metatitle'],
                'metadesc' => $row['metadesc'],
                'metakeyword' => $row['metakeyword'] ?? null,
                'metaurl' => $row['metaurl'],
                'cid' => isset($row['cid']) && is_numeric($row['cid']) ? (int) $row['cid'] : null,
                'vid' => (int) $row['vid'],
                'cat_id' => (int) $row['cat_id'],
                'subcat_id' => (int) $row['subcat_id'],
                'brand_id' => isset($row['brand_id']) && is_numeric($row['brand_id']) ? (int) $row['brand_id'] : null,
                'vendor_code' => $row['vendor_code'],
                'sku' => !empty($row['sku']) ? $row['sku'] : null,
                'barcode' => $row['barcode'] ?? null,
            ];

            try {
                if (!empty($row['id']) && is_numeric($row['id']) && Product::where('id', $row['id'])->exists()) {
                    Product::where('id', $row['id'])->update($productData);
                    $updatedCount++;
                } /* elseif (!empty($row['sku']) && Product::where('sku', $row['sku'])->exists()) {
Product::where('sku', $row['sku'])->update($productData);
$updatedCount++;
} */ else {
                    if (!empty($row['id']) && is_numeric($row['id'])) {
                        $productData['id'] = (int) $row['id'];
                    }
                    Product::create($productData);
                    $insertedCount++;
                }
            } catch (\Exception $e) {
                $errors[] = "Row {$rowNum}: Database error (" . $e->getMessage() . ").";
            }
        }

        fclose($handle);

        $msg = "Import finished! {$insertedCount} product(s) inserted, {$updatedCount} product(s) updated.";
        if (!empty($errors)) {
            $msg .= " " . count($errors) . " row error(s) occurred.";
            print_r($errors);
            dd("QQQQQ   $msg  ");
            return redirect()->route('dashboard.products.import')
                ->with('success', $msg)
                ->with('import_errors', $errors);
        }
        // dd("ZZZZ");
        return redirect()->route('dashboard.products.index')->with('success', $msg);
    }

    /**
     * Download sample CSV import template.
     */
    public function downloadSampleCsv()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="sample_products_import.csv"',
        ];

        $columns = [
            'id',
            'name',
            'short_name',
            'vendor_product_name',
            'short_description',
            'description',
            'info',
            'use_case',
            'price',
            'discount',
            'deliverycharge',
            'vendorprice',
            'vendordeliveryprice',
            'more_price',
            'isactive',
            'imgurl',
            'more_img',
            'metatitle',
            'metadesc',
            'metakeyword',
            'metaurl',
            'cid',
            'vid',
            'cat_id',
            'subcat_id',
            'brand_id',
            'vendor_code',
            'sku',
            'barcode'
        ];

        $sampleData = [
            [
                '',
                'Ashwagandha Organic Root Extract',
                'Ashwagandha Extract',
                'Vendor Ashwagandha 500mg',
                'Organic root extract for stress relief.',
                'Premium organic ashwagandha root extract capsules for stress relief.',
                'Take 2 capsules daily with meals.',
                'Daily wellness and stress management.',
                '29.99',
                '5.00',
                '4.99',
                '15.00',
                '2.50',
                '0',
                '1',
                'https://example.com/images/ashwagandha.jpg',
                '0',
                'Buy Organic Ashwagandha Extract 500mg',
                'Best Ashwagandha capsules for stress and anxiety.',
                'ashwagandha, stress relief, supplement',
                'ashwagandha-organic-root-extract',
                '1',
                '101',
                '1',
                '5',
                '2',
                'VEND-ASH-01',
                'SKU-ASH-500',
                '8901234567890'
            ]
        ];

        $callback = function () use ($columns, $sampleData) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($sampleData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product): View
    {
        $categories = Category::whereNull('parent_id')
            ->orWhere('parent_id', 0)
            ->orderBy('name')
            ->get();

        $brands = Brand::where('status', true)->orderBy('sort_order')->orderBy('name')->get();

        $selectedCatId = old('cat_id', $product->cat_id);
        $subcategories = collect();
        if ($selectedCatId) {
            $subcategories = Category::where('parent_id', $selectedCatId)
                ->orderBy('name')
                ->get();
        }

        return view('dashboard.products.edit', compact('product', 'categories', 'subcategories', 'brands'));
    }


    /**
     * AJAX endpoint to retrieve subcategories for a given parent category ID.
     */
    public function getSubcategories($parentId): JsonResponse
    {
        $subcategories = Category::where('parent_id', $parentId)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($subcategories);
    }


    /**
     * Update the specified product in the database.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate($this->rules($product->id));

        $product->update($data);

        return redirect()->route('dashboard.products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product from the database (soft delete).
     */
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('dashboard.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    // ──────────────────────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────────────────────

    /**
     * Shared validation rules.
     */
    private function rules(?int $ignoreId = null): array
    {
        $skuUnique = 'nullable|string|max:100|unique:products,sku';
        if ($ignoreId) {
            $skuUnique .= ",{$ignoreId}";
        }

        return [
            // Core
            'name' => 'required|string|max:255',
            'short_name' => 'nullable|string|max:255',
            'vendor_product_name' => 'nullable|string|max:255',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'info' => 'nullable|string',
            'use_case' => 'nullable|string',

            // Pricing
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'deliverycharge' => 'nullable|numeric|min:0',
            'vendorprice' => 'nullable|numeric|min:0',
            'vendordeliveryprice' => 'nullable|numeric|min:0',
            'more_price' => 'nullable|numeric|min:0',

            // Status & media
            'isactive' => 'nullable|boolean',
            'imgurl' => 'nullable|string|max:500',
            'more_img' => 'nullable|string',

            // SEO
            'metatitle' => 'nullable|string|max:255',
            'metadesc' => 'nullable|string',
            'metakeyword' => 'nullable|string',
            'metaurl' => 'nullable|string|max:255',

            // IDs
            'cid' => 'nullable|integer',
            'vid' => 'nullable|integer',
            'cat_id' => 'nullable|integer',
            'subcat_id' => 'nullable|integer',
            'brand_id' => 'nullable|integer',

            // Identifiers
            'vendor_code' => 'nullable|string|max:100',
            'sku' => $skuUnique,
            'barcode' => 'nullable|string|max:100',
            'model_number' => 'nullable|string|max:100',
            'manufacturer_part_number' => 'nullable|string|max:100',

            // Rating
            'ratingvalue' => 'nullable|numeric|min:0|max:5',
            'reviewcount' => 'nullable|integer|min:0',
            'viewed' => 'nullable|integer|min:0',
        ];
    }

    public function productListing(Request $request, $slug): View
    {
        $cat_id = Category::getCategoryId($slug);
        //    dd($slug);
        $sort = $request->get('sort', 'newest');

        $query = Product::query()->where('cat_id', $cat_id)->where('isactive', 1);

        match ($sort) {
            'price_low' => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            'reviews' => $query->orderByDesc('reviews_count'),
            default => $query->orderByDesc('created_at'), // 'newest'
        };

        $products = $query->paginate(12)->withQueryString();

        $meta = [
            'title' => 'Shop All Products',
            'description' => 'Browse our full range of flowers, cakes, and gifts.',
        ];


        $nav = Category::getTopCategoriesWithSubcategories();
        $healthConditions = HealthConcern::getAllActiveHealthConcerns();
        $brandList = Brand::getAllActiveBrands();

        $category = $nav['categories'];
        $subcategory = $nav['subcategories'];
        return view('products.product-listing', compact('products', 'meta', 'sort', 'category', 'subcategory', 'healthConditions', 'brandList'));
    }

    public function productSubListing(Request $request, $category, $subcategory): View
    {
        $cat_id = Category::getSubCategoryId($category, $subcategory);
        // echo "<h1>M : $cat_id | $subcategory    </h1>";
        $sort = $request->get('sort', 'newest');

        $query = Product::query()->where('subcat_id', $cat_id)->where('isactive', 1);

        match ($sort) {
            'price_low' => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            'reviews' => $query->orderByDesc('reviews_count'),
            default => $query->orderByDesc('created_at'), // 'newest'
        };

        $products = $query->paginate(12)->withQueryString();

        $meta = [
            'title' => 'Shop All Products',
            'description' => 'Browse our full range of flowers, cakes, and gifts.',
        ];



        $nav = Category::getTopCategoriesWithSubcategories();
        $healthConditions = HealthConcern::getAllActiveHealthConcerns();
        $brandList = Brand::getAllActiveBrands();
        $category = $nav['categories'];
        $subcategory = $nav['subcategories'];
        return view('products.product-listing', compact('products', 'meta', 'sort', 'category', 'subcategory', 'healthConditions', 'brandList'));
    }
    public function productDetails(string $slug): View
    {
        // Replace with a real query once your Product/Variant/Review models are ready, e.g.:
        // $product = Product::with(['category.parent', 'variants', 'reviews', 'images'])
        //     ->where('slug', $slug)->firstOrFail();

        $product = Product::where('metaurl', $slug)->firstOrFail();
        //dd($product);
        /*
        $product = (object) [
            'name' => 'Fields of Europe® Bliss',
            'brand' => 'FlowersGifting Select',
            'sku' => '1001-P-191113',
            'main_image' => '/images/products/fields-of-europe-bliss.jpg',
            'gallery' => [
                '/images/products/fields-of-europe-bliss-2.jpg',
                '/images/products/fields-of-europe-bliss-3.jpg',
                '/images/products/fields-of-europe-bliss-4.jpg',
            ],
            'description' => 'That feeling of bliss, captured in a bouquet. Inspired by the beauty of the European countryside, our best seller brings together radiant roses and lush lilies in a glass vase wrapped in red ribbon.',
            'details' => "Includes roses, lilies, and seasonal greenery\nArrives in a clear glass vase\nStem count varies by size\nFreshness guaranteed for 7 days",
            'use_case' => 'Perfect for birthdays, anniversaries, get-well wishes, or simply brightening someone\'s day. Pairs well with a box of chocolates or a greeting card add-on.',
            'rating' => 4.6,
            'reviews_count' => 128,
            'category' => (object) ['name' => 'Flowers', 'slug' => 'flowers'],
            'subcategory' => (object) ['name' => 'Best Sellers', 'slug' => 'best-sellers'],
            'variants' => [
                ['label' => 'Deluxe', 'price' => 79.99, 'original_price' => null, 'sku' => '191113DX'],
                ['label' => 'Extra Large', 'price' => 69.99, 'original_price' => 79.99, 'sku' => '191113XL'],
                ['label' => 'Large', 'price' => 59.99, 'original_price' => null, 'sku' => '191113L'],
                ['label' => 'Medium', 'price' => 49.99, 'original_price' => null, 'sku' => '191113M'],
            ],
            'reviews' => [
                ['author' => 'Sarah M.', 'rating' => 5, 'date' => '2026-07-12', 'comment' => 'Absolutely gorgeous, arrived fresh and on time!'],
                ['author' => 'James T.', 'rating' => 4, 'date' => '2026-07-05', 'comment' => 'Beautiful bouquet, lasted over a week.'],
            ],
        ];
*/
        $meta = [
            'title' => $product->name,
            'description' => \Illuminate\Support\Str::limit($product->description, 155),
        ];
        $nav = Category::getTopCategoriesWithSubcategories();
        $healthConditions = HealthConcern::getAllActiveHealthConcerns();
        $brandList = Brand::getAllActiveBrands();
        $category = $nav['categories'];
        $subcategory = $nav['subcategories'];
        return view('products.product-details', compact('product', 'meta', 'category', 'subcategory', 'healthConditions', 'brandList'));
    }


    public function productListingByHealthConcern(Request $request, string $slug): View
    {
        $healthConcern = HealthConcern::where('slug', $slug)
            ->where('status', 1)
            ->firstOrFail();

        $sort = $request->get('sort', 'newest');

        $query = $healthConcern->products()
            ->where('isactive', 1); // confirm this is the correct column name on `products`

        match ($sort) {
            'price_low' => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            'reviews' => $query->orderByDesc('reviews_count'),
            default => $query->orderByDesc('created_at'), // 'newest'
        };

        $products = $query->paginate(24)->withQueryString();
        //dd($products);
        $meta = [
            'title' => $healthConcern->name . ' Products',
            'description' => $healthConcern->description
                ?? "Browse our range of flowers, cakes, and gifts for {$healthConcern->name}.",
        ];

        $nav = Category::getTopCategoriesWithSubcategories();
        $healthConditions = HealthConcern::getAllActiveHealthConcerns();
        $brandList = Brand::getAllActiveBrands();
        return view('products.product-listing', [
            'products' => $products,
            'meta' => $meta,
            'sort' => $sort,
            'category' => $nav['categories'],
            'subcategory' => $nav['subcategories'],
            'healthConcern' => $healthConcern,
            'healthConditions' => $healthConditions,
            'brandList' => $brandList,
        ]);
    }


    public function productListingByBrand(Request $request, string $slug): View
    {
        $brand = Brand::where('slug', $slug)
            ->where('status', 1)
            ->firstOrFail();
        // dd($brand->id);
        $brandId = $brand->id;
        $sort = $request->get('sort', 'newest');

        $query = Product::where('brand_id', $brandId)
            ->where('isactive', 1); // confirm this is the correct column name on `products`

        match ($sort) {
            'price_low' => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            'reviews' => $query->orderByDesc('reviews_count'),
            default => $query->orderByDesc('created_at'), // 'newest'
        };

        $products = $query->paginate(24)->withQueryString();
        //dd($products);
        $meta = [
            'title' => $brand->name . ' Products',
            'description' => $brand->description
                ?? "Browse our range of flowers, cakes, and gifts for {$brand->name}.",
        ];

        $nav = Category::getTopCategoriesWithSubcategories();
        $healthConditions = HealthConcern::getAllActiveHealthConcerns();
        $brandList = Brand::getAllActiveBrands();
        return view('products.product-listing', [
            'products' => $products,
            'meta' => $meta,
            'sort' => $sort,
            'category' => $nav['categories'],
            'subcategory' => $nav['subcategories'],
            //    'healthConcern' => $healthConcern,
            'healthConditions' => $healthConditions,
            'brandList' => $brandList,
        ]);
    }
}
