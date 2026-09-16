<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ProductForm;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductFormController extends Controller
{
    /**
     * Display a listing of product forms.
     */
    public function index(Request $request): View
    {
        $query = ProductForm::withTrashed();

        if ($search = $request->input('search')) {
            $query->where('product_form', 'like', "%{$search}%");
        }

        if ($request->filled('isactive')) {
            $query->where('isactive', $request->boolean('isactive'));
        }

        $productForms = $query->orderBy('id', 'desc')->paginate(25)->withQueryString();

        return view('dashboard.product_form.index', compact('productForms'));
    }

    /**
     * Show the form for creating a new product form.
     */
    public function create(): View
    {
        return view('dashboard.product_form.create');
    }

    /**
     * Store a newly created product form in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_form' => 'required|string|max:255',
            'isactive'     => 'nullable|boolean',
        ]);

        $validated['isactive'] = $request->has('isactive');

        ProductForm::create($validated);

        return redirect()->route('dashboard.product-form.index')->with('success', 'Product form created successfully.');
    }

    /**
     * Display the specified product form.
     */
    public function show(ProductForm $productForm): View
    {
        return view('dashboard.product_form.show', compact('productForm'));
    }

    /**
     * Show the form for editing the specified product form.
     */
    public function edit(ProductForm $productForm): View
    {
        return view('dashboard.product_form.edit', compact('productForm'));
    }

    /**
     * Update the specified product form in storage.
     */
    public function update(Request $request, ProductForm $productForm): RedirectResponse
    {
        $validated = $request->validate([
            'product_form' => 'required|string|max:255',
            'isactive'     => 'nullable|boolean',
        ]);

        $validated['isactive'] = $request->has('isactive');

        $productForm->update($validated);

        return redirect()->route('dashboard.product-form.index')->with('success', 'Product form updated successfully.');
    }

    /**
     * Remove the specified product form from storage (Soft Delete).
     */
    public function destroy(ProductForm $productForm): RedirectResponse
    {
        $productForm->delete();

        return redirect()->route('dashboard.product-form.index')->with('success', 'Product form deleted successfully.');
    }

    /**
     * Restore a soft-deleted product form.
     */
    public function restore($id): RedirectResponse
    {
        $productForm = ProductForm::withTrashed()->findOrFail($id);
        $productForm->restore();

        return redirect()->route('dashboard.product-form.index')->with('success', 'Product form restored successfully.');
    }
}
