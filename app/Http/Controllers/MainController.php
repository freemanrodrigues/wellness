<?php

namespace App\Http\Controllers;
use App\Models\{Brand, Category, HealthConcern, Product};
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MainController extends Controller
{


    public function home(): View
    {
        $meta = [
            'title' => 'Flower & Gift Delivery Online',
            'description' => 'Order fresh flowers, cakes, and gifts online with same-day delivery.',
        ];

        $productRows = [];

        // 1. Fetch categories set to show_on_homepage with active products
        $categories = Category::where('show_on_homepage', 1)->orderBy('sort_order')->orderBy('name')->get();
        foreach ($categories as $cat) {
            $catProducts = Product::where('cat_id', $cat->id)
                ->where('isactive', 1)
                ->latest()
                ->take(4)
                ->get();

            if ($catProducts->isNotEmpty()) {
                $productRows[] = [
                    'title' => $cat->name,
                    'view_all' => route('product-listing.category', $cat->slug),
                    'products' => $catProducts->map(function ($p) {
                        $img = $p->imgurl;
                        if ($img && !str_starts_with($img, 'http') && !str_starts_with($img, 'images/')) {
                            $img = 'images/products/' . $img;
                        }
                        return [
                            'id' => $p->id,
                            'name' => $p->name,
                            'price' => $p->price,
                            'image' => $img ?: 'images/products/default.jpg',
                            'metaurl' => $p->metaurl,
                        ];
                    })->toArray(),
                ];
            }
        }

        // 2. Fetch products by Health Concern ID = 6
        $healthConcern = HealthConcern::find(6);
        if ($healthConcern) {
            $hcProducts = $healthConcern->products()
                ->where('isactive', 1)
                ->latest()
                ->take(4)
                ->get();

            if ($hcProducts->isNotEmpty()) {
                $productRows[] = [
                    'title' => $healthConcern->name,
                    'view_all' => route('health.products', $healthConcern->slug),
                    'products' => $hcProducts->map(function ($p) {
                        $img = $p->imgurl;
                        if ($img && !str_starts_with($img, 'http') && !str_starts_with($img, 'images/')) {
                            $img = 'images/products/' . $img;
                        }
                        return [
                            'id' => $p->id,
                            'name' => $p->name,
                            'price' => $p->price,
                            'image' => $img ?: 'images/products/default.jpg',
                            'metaurl' => $p->metaurl,
                        ];
                    })->toArray(),
                ];
            }
        }

        // 3. Fetch products by Brand ID = 1
        $brand = Brand::find(1);
        if ($brand) {
            $brandProducts = Product::where('brand_id', $brand->id)
                ->where('isactive', 1)
                ->latest()
                ->take(4)
                ->get();

            if ($brandProducts->isNotEmpty()) {
                $productRows[] = [
                    'title' => $brand->name,
                    'view_all' => route('brand.products', $brand->slug),
                    'products' => $brandProducts->map(function ($p) {
                        $img = $p->imgurl;
                        if ($img && !str_starts_with($img, 'http') && !str_starts_with($img, 'images/')) {
                            $img = 'images/products/' . $img;
                        }
                        return [
                            'id' => $p->id,
                            'name' => $p->name,
                            'price' => $p->price,
                            'image' => $img ?: 'images/products/default.jpg',
                            'metaurl' => $p->metaurl,
                        ];
                    })->toArray(),
                ];
            }
        }

        $nav = Category::getTopCategoriesWithSubcategories();
        $healthConditions = HealthConcern::getAllActiveHealthConcerns();
        $brandList = Brand::getAllActiveBrands();

        return view('home', [
            'meta' => $meta,
            'productRows' => $productRows,
            'category' => $nav['categories'],
            'subcategory' => $nav['subcategories'],
            'healthConditions' => $healthConditions,
            'brandList' => $brandList
        ]);
    }

    /**
     * Display the About Us page.
     */
    public function about(): View
    {
        $meta = [
            'title' => 'About Us',
            'description' => 'Learn more about ' . config('app.name') . ' and what we do.',
        ];
        $nav = Category::getTopCategoriesWithSubcategories();
        $healthConditions = HealthConcern::getAllActiveHealthConcerns();
        $brandList = Brand::getAllActiveBrands();
        return view('about', ['meta' => $meta, 'category' => $nav['categories'], 'subcategory' => $nav['subcategories'], 'healthConditions' => $healthConditions, 'brandList' => $brandList]);
    }

    /**
     * Display the Privacy Policy page.
     */
    public function privacy(): View
    {
        $meta = [
            'title' => 'Privacy Policy',
            'description' => 'Learn how ' . config('app.name') . ' collects, uses, and protects your personal information.',
        ];
        $nav = Category::getTopCategoriesWithSubcategories();
        $healthConditions = HealthConcern::getAllActiveHealthConcerns();
        $brandList = Brand::getAllActiveBrands();
        return view('privacy', ['meta' => $meta, 'category' => $nav['categories'], 'subcategory' => $nav['subcategories'], 'healthConditions' => $healthConditions, 'brandList' => $brandList]);
    }

    /**
     * Display the Terms of Service page.
     */
    public function terms(): View
    {
        $meta = [
            'title' => 'Terms of Service',
            'description' => 'Read the Terms of Service governing your use of ' . config('app.name') . '.',
        ];
        $nav = Category::getTopCategoriesWithSubcategories();
        $healthConditions = HealthConcern::getAllActiveHealthConcerns();
        $brandList = Brand::getAllActiveBrands();
        return view('terms', ['meta' => $meta, 'category' => $nav['categories'], 'subcategory' => $nav['subcategories'], 'healthConditions' => $healthConditions, 'brandList' => $brandList]);
    }

    public function contact(): View
    {
        $meta = [
            'title' => 'Contact Us',
            'description' => 'Read the Terms of Service governing your use of ' . config('app.name') . '.',
        ];
        $nav = Category::getTopCategoriesWithSubcategories();
        $healthConditions = HealthConcern::getAllActiveHealthConcerns();
        $brandList = Brand::getAllActiveBrands();
        return view('contact', ['meta' => $meta, 'category' => $nav['categories'], 'subcategory' => $nav['subcategories'], 'healthConditions' => $healthConditions, 'brandList' => $brandList]);
    }

    public function test(): View
    {


        $meta = [
            'title' => 'Contact Us',
            'description' => 'Read the Terms of Service governing your use of ' . config('app.name') . '.',
        ];

        return view('test', ['meta' => $meta]);
    }
}