<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index(Request $request): View
    {
        $query = Category::withTrashed()->with('parent');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->boolean('status'));
        }

        if ($request->filled('parent_id')) {
            if ($request->input('parent_id') === '0') {
                $query->whereNull('parent_id');
            } else {
                $query->where('parent_id', $request->input('parent_id'));
            }
        }

        $categories = $query->orderBy('sort_order')->orderBy('name')->paginate(25)->withQueryString();

        // For the parent filter dropdown — only root-level, non-trashed
        $parents = Category::whereNull('parent_id')->orderBy('name')->get();

        return view('dashboard.category.index', compact('categories', 'parents'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create(): View
    {
        $parents = Category::whereNull('parent_id')->active()->orderBy('name')->get();

        return view('dashboard.category.create', compact('parents'));
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rules($request));

        $parentId = !empty($data['parent_id']) ? (int) $data['parent_id'] : null;

        // Auto-generate slug if empty
        if (empty($data['slug'])) {
            $data['slug'] = Category::generateSlug($data['name'], null, $parentId);
        }

        // Cast checkboxes
        $data['status'] = $request->boolean('status');
        $data['show_in_menu'] = $request->boolean('show_in_menu');
        $data['show_on_homepage'] = $request->boolean('show_on_homepage');

        Category::create($data);

        return redirect()->route('dashboard.category.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified category.
     */
    public function show(Category $category): View
    {
        $category->load(['parent', 'children']);

        return view('dashboard.category.show', compact('category'));
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category): View
    {
        // Exclude self and its own children from the parent dropdown
        $parents = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->orderBy('name')
            ->get();

        return view('dashboard.category.edit', compact('category', 'parents'));
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $data = $request->validate($this->rules($request, $category->id));

        $parentId = !empty($data['parent_id']) ? (int) $data['parent_id'] : null;

        if (empty($data['slug'])) {
            $data['slug'] = Category::generateSlug($data['name'], $category->id, $parentId);
        }

        $data['status'] = $request->boolean('status');
        $data['show_in_menu'] = $request->boolean('show_in_menu');
        $data['show_on_homepage'] = $request->boolean('show_on_homepage');

        $category->update($data);

        return redirect()->route('dashboard.category.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Soft-delete the specified category.
     */
    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return redirect()->route('dashboard.category.index')
            ->with('success', 'Category deleted successfully.');
    }

    /**
     * Restore a soft-deleted category.
     */
    public function restore(int $id): RedirectResponse
    {
        $category = Category::withTrashed()->findOrFail($id);
        $category->restore();

        return redirect()->route('dashboard.category.index')
            ->with('success', 'Category restored successfully.');
    }

    // ──────────────────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────────────────

    private function rules(Request $request, ?int $ignoreId = null): array
    {
        $parentId = $request->input('parent_id') ? (int) $request->input('parent_id') : null;

        $slugRule = Rule::unique('categories', 'slug')
            ->where(function ($query) use ($parentId) {
                return $parentId ? $query->where('parent_id', $parentId) : $query->whereNull('parent_id');
            });

        if ($ignoreId) {
            $slugRule->ignore($ignoreId);
        }

        return [
            'parent_id' => 'nullable|integer|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', $slugRule],
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:255',
            'banner' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'nullable|boolean',
            'show_in_menu' => 'nullable|boolean',
            'show_on_homepage' => 'nullable|boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ];
    }
}
