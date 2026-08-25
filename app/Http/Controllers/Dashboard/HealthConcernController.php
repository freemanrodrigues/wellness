<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\HealthConcern;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HealthConcernController extends Controller
{
    /**
     * Display a listing of health concerns.
     */
    public function index(Request $request): View
    {
        $query = HealthConcern::withTrashed();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->boolean('status'));
        }

        $healthConcerns = $query->orderBy('sort_order')->orderBy('name')->paginate(25)->withQueryString();

        return view('health_concern.index', compact('healthConcerns'));
    }

    /**
     * Show the form for creating a new health concern.
     */
    public function create(): View
    {
        return view('health_concern.create');
    }

    /**
     * Store a newly created health concern in database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'slug'             => 'nullable|string|max:255|unique:health_concerns,slug',
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
            ? HealthConcern::generateSlug($validated['slug'])
            : HealthConcern::generateSlug($validated['name']);

        $validated['status']           = $request->has('status');
        $validated['show_in_menu']     = $request->has('show_in_menu');
        $validated['show_on_homepage'] = $request->has('show_on_homepage');
        $validated['sort_order']       = $validated['sort_order'] ?? 0;

        // Image uploads
        foreach (['image', 'icon', 'banner'] as $mediaField) {
            if ($request->hasFile($mediaField)) {
                $file = $request->file($mediaField);
                $filename = time() . '_' . $mediaField . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('images/health_concerns'), $filename);
                $validated[$mediaField] = 'images/health_concerns/' . $filename;
            }
        }

        HealthConcern::create($validated);

        return redirect()->route('dashboard.health-concern.index')->with('success', 'Health Concern created successfully.');
    }

    /**
     * Display the specified health concern details.
     */
    public function show(HealthConcern $healthConcern): View
    {
        return view('health_concern.show', compact('healthConcern'));
    }

    /**
     * Show the form for editing the specified health concern.
     */
    public function edit(HealthConcern $healthConcern): View
    {
        return view('health_concern.edit', compact('healthConcern'));
    }

    /**
     * Update the specified health concern in database.
     */
    public function update(Request $request, HealthConcern $healthConcern): RedirectResponse
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'slug'             => 'nullable|string|max:255|unique:health_concerns,slug,' . $healthConcern->id,
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

        if (empty($validated['slug']) || $validated['slug'] !== $healthConcern->slug) {
            $validated['slug'] = HealthConcern::generateSlug($validated['slug'] ?: $validated['name'], $healthConcern->id);
        }

        $validated['status']           = $request->has('status');
        $validated['show_in_menu']     = $request->has('show_in_menu');
        $validated['show_on_homepage'] = $request->has('show_on_homepage');

        // Image uploads
        foreach (['image', 'icon', 'banner'] as $mediaField) {
            if ($request->hasFile($mediaField)) {
                if ($healthConcern->$mediaField && file_exists(public_path($healthConcern->$mediaField))) {
                    @unlink(public_path($healthConcern->$mediaField));
                }
                $file = $request->file($mediaField);
                $filename = time() . '_' . $mediaField . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('images/health_concerns'), $filename);
                $validated[$mediaField] = 'images/health_concerns/' . $filename;
            }
        }

        $healthConcern->update($validated);

        return redirect()->route('dashboard.health-concern.index')->with('success', 'Health Concern updated successfully.');
    }

    /**
     * Soft-delete the specified health concern.
     */
    public function destroy(HealthConcern $healthConcern): RedirectResponse
    {
        $healthConcern->delete();

        return redirect()->route('dashboard.health-concern.index')->with('success', 'Health Concern deleted successfully.');
    }

    /**
     * Restore a soft-deleted health concern.
     */
    public function restore(int $id): RedirectResponse
    {
        $healthConcern = HealthConcern::withTrashed()->findOrFail($id);
        $healthConcern->restore();

        return redirect()->route('dashboard.health-concern.index')->with('success', 'Health Concern restored successfully.');
    }
}
