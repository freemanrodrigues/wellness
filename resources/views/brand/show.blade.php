@include('layouts.admin-header')
@include('layouts.admin-navbar')
@include('layouts.dash_styles')

<div class="dash-wrapper" style="padding-top: 110px;">

    <main class="dash-main">


        <div class="dash-page-header mb-4">
            <div>
                <h1 class="dash-page-title">Brand: {{ $brand->name }}</h1>
                <nav style="font-size:.85rem; color:#6c757d;">
                    <a href="{{ route('dashboard.brand.index') }}" style="color:#1a6644; text-decoration:none;">Brands</a>
                    &rsaquo; Detail #{{ $brand->id }}
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('dashboard.brand.edit', $brand) }}" class="btn btn-primary fw-bold">
                    <i class="bi bi-pencil me-1"></i> Edit Brand
                </a>
                <a href="{{ route('dashboard.brand.index') }}" class="btn btn-outline-secondary">
                    Back to List
                </a>
            </div>
        </div>

        <div class="row g-4">

            {{-- Main Info --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h2 class="h6 fw-bold mb-3 border-bottom pb-2">General Information</h2>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <span class="text-muted small d-block">Brand Name</span>
                                <span class="fw-bold text-dark fs-5">{{ $brand->name }}</span>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted small d-block">Slug</span>
                                <code class="text-dark fs-6">{{ $brand->slug }}</code>
                            </div>
                            <div class="col-12">
                                <span class="text-muted small d-block">Description</span>
                                <div class="bg-light p-3 rounded border text-dark mt-1">
                                    {{ $brand->description ?: 'No description provided.' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SEO Metadata --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h6 fw-bold mb-3 border-bottom pb-2">SEO / Metadata</h2>

                        <div class="row g-3">
                            <div class="col-12">
                                <span class="text-muted small d-block">Meta Title</span>
                                <span class="fw-semibold text-dark">{{ $brand->meta_title ?: '&mdash;' }}</span>
                            </div>
                            <div class="col-12">
                                <span class="text-muted small d-block">Meta Description</span>
                                <span class="text-dark">{{ $brand->meta_description ?: '&mdash;' }}</span>
                            </div>
                            <div class="col-12">
                                <span class="text-muted small d-block">Meta Keywords</span>
                                <span class="text-dark">{{ $brand->meta_keywords ?: '&mdash;' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar Info --}}
            <div class="col-lg-4">

                {{-- Status & Visibility --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h2 class="h6 fw-bold mb-3 border-bottom pb-2">Status &amp; Configuration</h2>

                        <ul class="list-group list-group-flush small">
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span class="text-muted">Status</span>
                                @if($brand->status)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span class="text-muted">Show in Menu</span>
                                <span>{{ $brand->show_in_menu ? 'Yes' : 'No' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span class="text-muted">Show on Homepage</span>
                                <span>{{ $brand->show_on_homepage ? 'Yes' : 'No' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span class="text-muted">Sort Order</span>
                                <span class="fw-bold">{{ $brand->sort_order }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span class="text-muted">Created At</span>
                                <span>{{ $brand->created_at ? $brand->created_at->format('M d, Y H:i') : '&mdash;' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span class="text-muted">Last Updated</span>
                                <span>{{ $brand->updated_at ? $brand->updated_at->format('M d, Y H:i') : '&mdash;' }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- Brand Media --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h6 fw-bold mb-3 border-bottom pb-2">Brand Media</h2>

                        <div class="mb-3">
                            <span class="text-muted small d-block mb-1">Logo / Image</span>
                            @if($brand->image)
                                <img src="{{ asset($brand->image) }}" alt="{{ $brand->name }}" class="img-fluid rounded border p-1" style="max-height:100px;">
                            @else
                                <span class="text-muted small">&mdash; No Image Uploaded</span>
                            @endif
                        </div>

                        <div class="mb-3">
                            <span class="text-muted small d-block mb-1">Icon</span>
                            @if($brand->icon)
                                <img src="{{ asset($brand->icon) }}" alt="{{ $brand->name }}" class="img-fluid rounded border p-1" style="max-height:60px;">
                            @else
                                <span class="text-muted small">&mdash; No Icon Uploaded</span>
                            @endif
                        </div>

                        <div>
                            <span class="text-muted small d-block mb-1">Banner</span>
                            @if($brand->banner)
                                <img src="{{ asset($brand->banner) }}" alt="{{ $brand->name }}" class="img-fluid rounded border p-1" style="max-height:100px;">
                            @else
                                <span class="text-muted small">&mdash; No Banner Uploaded</span>
                            @endif
                        </div>

                    </div>
                </div>

            </div>

        </div>

    </main>

</div>

@include('layouts.admin-footer')
