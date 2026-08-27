<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    /**
     * Display a listing of blog posts in dashboard.
     */
    public function index(Request $request): View
    {
        $query = Blog::with('category');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('tags', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->boolean('status'));
        }

        if ($request->filled('page_show')) {
            $query->where('page_show', $request->boolean('page_show'));
        }

        if ($catId = $request->input('cat_id')) {
            $query->where('cat_id', $catId);
        }

        $blogs = $query->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('dashboard.blogs.index', compact('blogs', 'categories'));
    }

    /**
     * Show form to create a new blog post.
     */
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        return view('dashboard.blogs.create', compact('categories'));
    }

    /**
     * Store a newly created blog post.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:191|unique:blogs,url',
            'excerpt' => 'nullable|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'image_url' => 'nullable|string|max:255',
            'tags' => 'nullable|string|max:255',
            'blog_meta_title' => 'nullable|string|max:255',
            'blog_meta_description' => 'nullable|string',
            'cat_id' => 'nullable|exists:categories,id',
            'status' => 'nullable|boolean',
            'page_show' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        if (empty($data['url'])) {
            $data['url'] = Blog::generateSlug($data['title']);
        } else {
            $data['url'] = Str::slug($data['url']);
        }

        $data['status'] = $request->has('status') ? (bool) $request->input('status') : true;
        $data['page_show'] = $request->has('page_show') ? (bool) $request->input('page_show') : true;
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        // Handle image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/blogs'), $filename);
            $data['image'] = 'images/blogs/' . $filename;
        } elseif (!empty($request->input('image_url'))) {
            $data['image'] = $request->input('image_url');
        } else {
            $data['image'] = null;
        }

        unset($data['image_url']);

        Blog::create($data);

        return redirect()->route('dashboard.blog.index')
            ->with('success', 'Blog post created successfully.');
    }

    /**
     * Display the specified blog post in admin view.
     */
    public function show(Blog $blog): View
    {
        $blog->load('category');
        return view('dashboard.blogs.show', compact('blog'));
    }

    /**
     * Show form to edit an existing blog post.
     */
    public function edit(Blog $blog): View
    {
        $categories = Category::orderBy('name')->get();
        return view('dashboard.blogs.edit', compact('blog', 'categories'));
    }

    /**
     * Update the specified blog post.
     */
    public function update(Request $request, Blog $blog): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:191|unique:blogs,url,' . $blog->id,
            'excerpt' => 'nullable|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'image_url' => 'nullable|string|max:255',
            'tags' => 'nullable|string|max:255',
            'blog_meta_title' => 'nullable|string|max:255',
            'blog_meta_description' => 'nullable|string',
            'cat_id' => 'nullable|exists:categories,id',
            'status' => 'nullable|boolean',
            'page_show' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $data['url'] = Str::slug($data['url']);
        $data['status'] = $request->has('status') ? (bool) $request->input('status') : false;
        $data['page_show'] = $request->has('page_show') ? (bool) $request->input('page_show') : false;
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/blogs'), $filename);
            $data['image'] = 'images/blogs/' . $filename;
        } elseif ($request->filled('image_url')) {
            $data['image'] = $request->input('image_url');
        }

        unset($data['image_url']);

        $blog->update($data);

        return redirect()->route('dashboard.blog.index')
            ->with('success', 'Blog post updated successfully.');
    }

    /**
     * Remove the specified blog post (soft delete).
     */
    public function destroy(Blog $blog): RedirectResponse
    {
        $blog->delete();

        return redirect()->route('dashboard.blog.index')
            ->with('success', 'Blog post deleted successfully.');
    }

    /**
     * Restore a soft-deleted blog post.
     */
    public function restore($id): RedirectResponse
    {
        $blog = Blog::withTrashed()->findOrFail($id);
        $blog->restore();

        return redirect()->route('dashboard.blog.index')
            ->with('success', 'Blog post restored successfully.');
    }
}
