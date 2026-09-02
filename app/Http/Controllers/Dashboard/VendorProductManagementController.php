<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\VendorProductManagement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class VendorProductManagementController extends Controller
{
    /**
     * Display a listing of vendor products (Summary Page).
     */
    public function index(Request $request): View
    {
        $query = VendorProductManagement::withTrashed()->with(['category', 'subcategory', 'brand']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('vendor_code', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
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

        $summary = [
            'total'    => VendorProductManagement::count(),
            'active'   => VendorProductManagement::where('status', true)->count(),
            'inactive' => VendorProductManagement::where('status', false)->count(),
            'deleted'  => VendorProductManagement::onlyTrashed()->count(),
        ];

        $items = $query->latest()->paginate(25)->withQueryString();

        return view('dashboard.vpm.index', compact('items', 'summary'));
    }

    /**
     * Show the form for creating a new vendor product.
     */
    public function create(): View
    {
        $categories = Category::whereNull('parent_id')
            ->orWhere('parent_id', 0)
            ->orderBy('name')
            ->get();

        $brands = Brand::where('status', true)->orderBy('sort_order')->orderBy('name')->get();

        return view('dashboard.vpm.create', compact('categories', 'brands'));
    }

    /**
     * Store a newly created vendor product in database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id'      => 'nullable|integer',
            'name'            => 'required|string|max:255',
            'description'     => 'nullable|string',
            'info'            => 'nullable|string',
            'price'           => 'required|numeric|min:0',
            'vendor_prod_url' => 'nullable|string|max:500',
            'imgurl'          => 'nullable|string|max:500',
            'image_file'      => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
            'vid'             => 'nullable|integer',
            'cat_id'          => 'nullable|integer',
            'subcat_id'       => 'nullable|integer',
            'brand_id'        => 'nullable|integer',
            'vendor_code'     => 'nullable|string|max:100',
            'sku'             => 'nullable|string|max:100',
            'status'          => 'nullable|boolean',
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

    /**
     * Display the specified vendor product.
     */
    public function show(VendorProductManagement $vpm): View
    {
        $vpm->load(['category', 'subcategory', 'brand']);
        return view('dashboard.vpm.show', compact('vpm'));
    }

    /**
     * Show the form for editing the specified vendor product.
     */
    public function edit(VendorProductManagement $vpm): View
    {
        $categories = Category::whereNull('parent_id')
            ->orWhere('parent_id', 0)
            ->orderBy('name')
            ->get();

        $brands = Brand::where('status', true)->orderBy('sort_order')->orderBy('name')->get();

        $subcategories = collect();
        $selectedCatId = old('cat_id', $vpm->cat_id);
        if ($selectedCatId) {
            $subcategories = Category::where('parent_id', $selectedCatId)
                ->orderBy('name')
                ->get();
        }

        return view('dashboard.vpm.edit', compact('vpm', 'categories', 'subcategories', 'brands'));
    }

    /**
     * Update the specified vendor product in database.
     */
    public function update(Request $request, VendorProductManagement $vpm): RedirectResponse
    {
        $validated = $request->validate([
            'product_id'      => 'nullable|integer',
            'name'            => 'required|string|max:255',
            'description'     => 'nullable|string',
            'info'            => 'nullable|string',
            'price'           => 'required|numeric|min:0',
            'vendor_prod_url' => 'nullable|string|max:500',
            'imgurl'          => 'nullable|string|max:500',
            'image_file'      => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
            'vid'             => 'nullable|integer',
            'cat_id'          => 'nullable|integer',
            'subcat_id'       => 'nullable|integer',
            'brand_id'        => 'nullable|integer',
            'vendor_code'     => 'nullable|string|max:100',
            'sku'             => 'nullable|string|max:100',
            'status'          => 'nullable|boolean',
        ]);

        $validated['status'] = $request->has('status');

        if ($request->hasFile('image_file')) {
            if ($vpm->imgurl && file_exists(public_path($vpm->imgurl))) {
                @unlink(public_path($vpm->imgurl));
            }
            $file = $request->file('image_file');
            $filename = time() . '_vpm_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/vpm'), $filename);
            $validated['imgurl'] = 'images/vpm/' . $filename;
        }

        unset($validated['image_file']);

        $vpm->update($validated);

        return redirect()->route('dashboard.vpm.index')
            ->with('success', 'Vendor product updated successfully.');
    }

    /**
     * Soft-delete the specified vendor product.
     */
    public function destroy(VendorProductManagement $vpm): RedirectResponse
    {
        $vpm->delete();

        return redirect()->route('dashboard.vpm.index')
            ->with('success', 'Vendor product deleted successfully.');
    }

    /**
     * Restore a soft-deleted vendor product.
     */
    public function restore(int $id): RedirectResponse
    {
        $item = VendorProductManagement::withTrashed()->findOrFail($id);
        $item->restore();

        return redirect()->route('dashboard.vpm.index')
            ->with('success', 'Vendor product restored successfully.');
    }

    /**
     * Export vendor product management data in CSV format.
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $query = VendorProductManagement::withTrashed();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('vendor_code', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
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

        $fileName = 'vendor_product_management_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            "Content-Type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=\"{$fileName}\"",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0",
        ];

        $columns = [
            'product_id',
            'name',
            'description',
            'info',
            'price',
            'vendor_prod_url',
            'imgurl',
            'vid',
            'cat_id',
            'subcat_id',
            'brand_id',
            'vendor_code',
            'sku',
            'status',
        ];

        $callback = function () use ($query, $columns) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF"); // Output UTF-8 BOM
            fputcsv($file, $columns);

            $query->orderBy('id')->chunk(500, function ($items) use ($file) {
                foreach ($items as $item) {
                    fputcsv($file, [
                        $item->product_id,
                        $item->name,
                        $item->description,
                        $item->info,
                        $item->price,
                        $item->vendor_prod_url,
                        $item->imgurl,
                        $item->vid,
                        $item->cat_id,
                        $item->subcat_id,
                        $item->brand_id,
                        $item->vendor_code,
                        $item->sku,
                        $item->status ? 1 : 0,
                    ]);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

