<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\HealthConcern;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    /**
     * Public Blog Listing Page (http://127.0.0.1:8011/blog/)
     * Displays top 10 blog list with image, short description, and link to complete blog post.
     * Left sidebar displays last 10 blog posts.
     */
    public function index(Request $request): View
    {
        // Top 10 blogs for main content
        $blogs = Blog::with('category')
            ->where('status', true)
            ->where('page_show', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Last 10 blogs for left sidebar links
        $sidebarBlogs = Blog::where('status', true)
            ->latest('created_at')
            ->take(10)
            ->get();

        $meta = [
            'title' => 'Blog & Articles | Wellness',
            'description' => 'Read our latest blogs, health tips, and wellness articles.',
        ];

        $nav = Category::getTopCategoriesWithSubcategories();
        $healthConditions = HealthConcern::getAllActiveHealthConcerns();
        $brandList = Brand::getAllActiveBrands();
        $category = $nav['categories'];
        $subcategory = $nav['subcategories'];

        return view('blog.index', compact(
            'blogs',
            'sidebarBlogs',
            'meta',
            'category',
            'subcategory',
            'healthConditions',
            'brandList'
        ));
    }

    /**
     * Public Blog Detail Page (http://127.0.0.1:8011/blog/{url})
     * Displays blog description with images/videos, left sidebar with last 10 blog posts, and hashtags at bottom.
     */
    public function show(string $url): View
    {
        $blog = Blog::with('category')
            ->where('url', $url)
            ->where('status', true)
            ->firstOrFail();

        // Last 10 blogs for left sidebar links
        $sidebarBlogs = Blog::where('status', true)
            ->latest('created_at')
            ->take(10)
            ->get();

        $meta = [
            'title' => $blog->blog_meta_title ?: ($blog->title . ' | Wellness Blog'),
            'description' => $blog->blog_meta_description ?: \Illuminate\Support\Str::limit(strip_tags($blog->excerpt ?? $blog->description), 155),
        ];

        $nav = Category::getTopCategoriesWithSubcategories();
        $healthConditions = HealthConcern::getAllActiveHealthConcerns();
        $brandList = Brand::getAllActiveBrands();
        $category = $nav['categories'];
        $subcategory = $nav['subcategories'];

        return view('blog.show', compact(
            'blog',
            'sidebarBlogs',
            'meta',
            'category',
            'subcategory',
            'healthConditions',
            'brandList'
        ));
    }
}
