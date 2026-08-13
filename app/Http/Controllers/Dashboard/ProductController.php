<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

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

        return view('dashboard.products.create', compact('categories'));
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
     * Show the form for editing the specified product.
     */
    public function edit(Product $product): View
    {
        $categories = Category::whereNull('parent_id')
            ->orWhere('parent_id', 0)
            ->orderBy('name')
            ->get();

        $selectedCatId = old('cat_id', $product->cat_id);
        $subcategories = collect();
        if ($selectedCatId) {
            $subcategories = Category::where('parent_id', $selectedCatId)
                ->orderBy('name')
                ->get();
        }

        return view('dashboard.products.edit', compact('product', 'categories', 'subcategories'));
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
            'description' => 'nullable|string',
            'info' => 'nullable|string',

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
            'more_desc' => 'nullable|string',

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
            'use_type' => 'nullable|string|max:100',
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

    public function productListing(Request $request): View
    {
        $sort = $request->get('sort', 'newest');

        $query = Product::query();

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

        return view('products.product-listing', compact('products', 'meta', 'sort'));
    }

    public function productDetails(string $slug): View
    {
        // Replace with a real query once your Product/Variant/Review models are ready, e.g.:
        // $product = Product::with(['category.parent', 'variants', 'reviews', 'images'])
        //     ->where('slug', $slug)->firstOrFail();

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

        $meta = [
            'title' => $product->name,
            'description' => \Illuminate\Support\Str::limit($product->description, 155),
        ];

        return view('products.product-details', compact('product', 'meta'));
    }
}
