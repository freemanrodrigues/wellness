@include('layouts.admin-header')
@include('layouts.admin-navbar')
@include('layouts.dash_styles')

<div class="dash-wrapper" style="padding-top: 110px;">

    <main class="dash-main">

        {{-- Flash messages --}}
        @if (session('error'))
            <div class="dash-alert dash-alert--danger" role="alert">{{ session('error') }}</div>
        @endif

        <div class="dash-page-header mb-4">
            <div>
                <h1 class="dash-page-title">Add Health Concern</h1>
                <nav style="font-size:.85rem; color:#6c757d;">
                    <a href="{{ route('dashboard.health-concern.index') }}" style="color:#1a6644; text-decoration:none;">Health Concerns</a>
                    &rsaquo; Create
                </nav>
            </div>
        </div>

        <form action="{{ route('dashboard.health-concern.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-4">

                {{-- Left Side: Main Details --}}
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h2 class="h6 fw-bold mb-3">Health Concern General Details</h2>

                            <div class="row g-3">
                                {{-- Name --}}
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-semibold small">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Slug --}}
                                <div class="col-md-6">
                                    <label for="slug" class="form-label fw-semibold small">Slug</label>
                                    <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug') }}" placeholder="Auto-generated if empty">
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Description --}}
                                <div class="col-12">
                                    <label for="description" class="form-label fw-semibold small">Description</label>
                                    <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SEO Section --}}
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h2 class="h6 fw-bold mb-3">SEO / Metadata</h2>

                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="meta_title" class="form-label fw-semibold small">Meta Title</label>
                                    <input type="text" name="meta_title" id="meta_title" class="form-control @error('meta_title') is-invalid @enderror" value="{{ old('meta_title') }}">
                                    @error('meta_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="meta_description" class="form-label fw-semibold small">Meta Description</label>
                                    <textarea name="meta_description" id="meta_description" rows="3" class="form-control @error('meta_description') is-invalid @enderror">{{ old('meta_description') }}</textarea>
                                    @error('meta_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="meta_keywords" class="form-label fw-semibold small">Meta Keywords</label>
                                    <textarea name="meta_keywords" id="meta_keywords" rows="2" class="form-control @error('meta_keywords') is-invalid @enderror" placeholder="Comma separated keywords">{{ old('meta_keywords') }}</textarea>
                                    @error('meta_keywords')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Side: Settings & Media --}}
                <div class="col-lg-4">

                    {{-- Settings Card --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h2 class="h6 fw-bold mb-3">Status &amp; Visibility</h2>

                            <div class="mb-3 form-check form-switch">
                                <input type="checkbox" name="status" id="status" class="form-check-input" value="1" {{ old('status', 1) ? 'checked' : '' }}>
                                <label for="status" class="form-check-label fw-semibold small">Active Status</label>
                            </div>

                            <div class="mb-3 form-check form-switch">
                                <input type="checkbox" name="show_in_menu" id="show_in_menu" class="form-check-input" value="1" {{ old('show_in_menu') ? 'checked' : '' }}>
                                <label for="show_in_menu" class="form-check-label fw-semibold small">Show in Navigation Menu</label>
                            </div>

                            <div class="mb-3 form-check form-switch">
                                <input type="checkbox" name="show_on_homepage" id="show_on_homepage" class="form-check-input" value="1" {{ old('show_on_homepage') ? 'checked' : '' }}>
                                <label for="show_on_homepage" class="form-check-label fw-semibold small">Show on Homepage</label>
                            </div>

                            <div class="mb-3">
                                <label for="sort_order" class="form-label fw-semibold small">Sort Order</label>
                                <input type="number" name="sort_order" id="sort_order" class="form-control @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', 0) }}" min="0">
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Media Card --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h2 class="h6 fw-bold mb-3">Images &amp; Icons</h2>

                            {{-- Image --}}
                            <div class="mb-3">
                                <label for="image" class="form-label fw-semibold small">Main Image</label>
                                <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Icon --}}
                            <div class="mb-3">
                                <label for="icon" class="form-label fw-semibold small">Icon</label>
                                <input type="file" name="icon" id="icon" class="form-control @error('icon') is-invalid @enderror" accept="image/*">
                                @error('icon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Banner --}}
                            <div class="mb-3">
                                <label for="banner" class="form-label fw-semibold small">Banner Image</label>
                                <input type="file" name="banner" id="banner" class="form-control @error('banner') is-invalid @enderror" accept="image/*">
                                @error('banner')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary fw-bold py-2">
                            Save Health Concern
                        </button>
                        <a href="{{ route('dashboard.health-concern.index') }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>

                </div>

            </div>
        </form>

    </main>

</div>

@include('layouts.admin-footer')
