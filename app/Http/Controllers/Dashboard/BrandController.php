<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BrandController extends Controller
{
    /**
     * Display a listing of brands.
     */
    public function index(Request $request): View
    {
        $query = Brand::withTrashed();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->boolean('status'));
        }

        $brands = $query->orderBy('sort_order')->orderBy('name')->paginate(25)->withQueryString();

        return view('brand.index', compact('brands'));
    }

    /**
     * Show the form for creating a new brand.
     */
    public function create(): View
    {
        return view('brand.create');
    }

    /**
     * Store a newly created brand in database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'slug'             => 'nullable|string|max:255|unique:brands,slug',
            'description'      => 'nullable|string',
            'image'            => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
            'icon'             => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'banner'           => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
            'sort_order'       => 'nullable|integer|min:0',
            'status'           => 'nullable|boolean',
            'show_in_menu'     => 'nullable|boolean',
            'show_on_homepage' => 'nullable|boolean',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords'    => 'nullable|string',
        ]);

        $validated['slug'] = $validated['slug']
            ? Brand::generateSlug($validated['slug'])
            : Brand::generateSlug($validated['name']);

        $validated['status']           = $request->has('status');
        $validated['show_in_menu']     = $request->has('show_in_menu');
        $validated['show_on_homepage'] = $request->has('show_on_homepage');
        $validated['sort_order']       = $validated['sort_order'] ?? 0;

        // Image uploads
        foreach (['image', 'icon', 'banner'] as $mediaField) {
            if ($request->hasFile($mediaField)) {
                $file = $request->file($mediaField);
                $filename = time() . '_' . $mediaField . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('images/brands'), $filename);
                $validated[$mediaField] = 'images/brands/' . $filename;
            }
        }

        Brand::create($validated);

        return redirect()->route('dashboard.brand.index')->with('success', 'Brand created successfully.');
    }

    /**
     * Display the specified brand details.
     */
    public function show(Brand $brand): View
    {
        return view('brand.show', compact('brand'));
    }

    /**
     * Show the form for editing the specified brand.
     */
    public function edit(Brand $brand): View
    {
        return view('brand.edit', compact('brand'));
    }

    /**
     * Update the specified brand in database.
     */
    public function update(Request $request, Brand $brand): RedirectResponse
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'slug'             => 'nullable|string|max:255|unique:brands,slug,' . $brand->id,
            'description'      => 'nullable|string',
            'image'            => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
            'icon'             => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'banner'           => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
            'sort_order'       => 'nullable|integer|min:0',
            'status'           => 'nullable|boolean',
            'show_in_menu'     => 'nullable|boolean',
            'show_on_homepage' => 'nullable|boolean',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords'    => 'nullable|string',
        ]);

        if (empty($validated['slug']) || $validated['slug'] !== $brand->slug) {
            $validated['slug'] = Brand::generateSlug($validated['slug'] ?: $validated['name'], $brand->id);
        }

        $validated['status']           = $request->has('status');
        $validated['show_in_menu']     = $request->has('show_in_menu');
        $validated['show_on_homepage'] = $request->has('show_on_homepage');

        // Image uploads
        foreach (['image', 'icon', 'banner'] as $mediaField) {
            if ($request->hasFile($mediaField)) {
                if ($brand->$mediaField && file_exists(public_path($brand->$mediaField))) {
                    @unlink(public_path($brand->$mediaField));
                }
                $file = $request->file($mediaField);
                $filename = time() . '_' . $mediaField . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('images/brands'), $filename);
                $validated[$mediaField] = 'images/brands/' . $filename;
            }
        }

        $brand->update($validated);

        return redirect()->route('dashboard.brand.index')->with('success', 'Brand updated successfully.');
    }

    /**
     * Soft-delete the specified brand.
     */
    public function destroy(Brand $brand): RedirectResponse
    {
        $brand->delete();

        return redirect()->route('dashboard.brand.index')->with('success', 'Brand deleted successfully.');
    }

    /**
     * Restore a soft-deleted brand.
     */
    public function restore(int $id): RedirectResponse
    {
        $brand = Brand::withTrashed()->findOrFail($id);
        $brand->restore();

        return redirect()->route('dashboard.brand.index')->with('success', 'Brand restored successfully.');
    }
}
