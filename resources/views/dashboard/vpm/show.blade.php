@include('layouts.admin-header')
@include('layouts.admin-navbar')

<div class="dash-wrapper">
    <main class="dash-main mt-4">

        @if (session('success'))
            <div class="dash-alert dash-alert--success">{{ session('success') }}</div>
        @endif

        <div class="dash-page-header">
            <div>
                <h1 class="dash-page-title">{{ $vpm->name }}</h1>
                <nav style="font-size:.8rem;color:#9ca3af;">
                    <a href="{{ route('dashboard.vpm.index') }}" style="color:#1a6644;text-decoration:none;">Vendor Product Management</a>
                    &rsaquo; View
                </nav>
            </div>
            <div style="display:flex;gap:.5rem;">
                <a href="{{ route('dashboard.vpm.edit', $vpm) }}" class="btn-dash-primary">
                    <i class="bi bi-pencil me-1"></i> Edit
                </a>
                <form method="POST" action="{{ route('dashboard.vpm.destroy', $vpm) }}"
                      onsubmit="return confirm('Are you sure you want to delete this vendor product?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-dash-danger">Delete</button>
                </form>
                <a href="{{ route('dashboard.vpm.index') }}" class="btn-dash-secondary">← Back</a>
            </div>
        </div>

        <div class="row g-3">
            {{-- Left column --}}
            <div class="col-md-3">
                <div class="dash-card text-center">
                    @if ($vpm->imgurl)
                        <img src="{{ str_starts_with($vpm->imgurl, 'http') ? $vpm->imgurl : asset($vpm->imgurl) }}" alt="{{ $vpm->name }}"
                             style="width:100%;max-height:200px;object-fit:contain;border-radius:.45rem;margin-bottom:1rem;"
                             onerror="this.src='/images/products/default.jpg'">
                    @else
                        <div style="height:160px;background:#f3f4f6;border-radius:.45rem;display:flex;align-items:center;justify-content:center;margin-bottom:1rem;">
                            <i class="bi bi-box-seam fs-1 text-muted"></i>
                        </div>
                    @endif

                    <span class="{{ $vpm->status ? 'badge-active' : 'badge-inactive' }}" style="font-size:.8rem;">
                        {{ $vpm->status ? 'Active' : 'Inactive' }}
                    </span>

                    <div class="mt-3" style="font-size:.8rem;color:#6b7280;text-align:left;">
                        <div class="d-flex justify-content-between py-1" style="border-bottom:1px solid #f3f4f6;">
                            <span>ID</span><strong>{{ $vpm->id }}</strong>
                        </div>
                        <div class="d-flex justify-content-between py-1" style="border-bottom:1px solid #f3f4f6;">
                            <span>Vendor Code</span><strong>{{ $vpm->vendor_code ?? '—' }}</strong>
                        </div>
                        <div class="d-flex justify-content-between py-1">
                            <span>Price</span><strong style="color:#1a6644;">₹{{ number_format($vpm->price, 2) }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right column --}}
            <div class="col-md-9">
                <div class="dash-card mb-3">
                    <p class="dash-form-section mt-0">Core Information</p>
                    <div class="row g-2" style="font-size:.865rem;">
                        <div class="col-md-6"><span style="color:#9ca3af;">Name</span><br><strong>{{ $vpm->name }}</strong></div>
                        <div class="col-md-3"><span style="color:#9ca3af;">Price</span><br>₹{{ number_format($vpm->price, 2) }}</div>
                        <div class="col-md-3"><span style="color:#9ca3af;">Vendor Code</span><br>{{ $vpm->vendor_code ?? '—' }}</div>
                        <div class="col-12 mt-2"><span style="color:#9ca3af;">Description</span><br>{{ $vpm->description ?? '—' }}</div>
                        <div class="col-12 mt-2"><span style="color:#9ca3af;">Additional Info</span><br>{{ $vpm->info ?? '—' }}</div>
                    </div>
                </div>

                <div class="dash-card mb-3">
                    <p class="dash-form-section mt-0">Vendor & Category Identifiers</p>
                    <div class="row g-2" style="font-size:.855rem;">
                        <div class="col-md-3"><span style="color:#9ca3af;">Vendor ID (vid)</span><br>{{ $vpm->vid ?? '—' }}</div>
                        <div class="col-md-3"><span style="color:#9ca3af;">Category</span><br>{{ $vpm->category->name ?? $vpm->cat_id ?? '—' }}</div>
                        <div class="col-md-3"><span style="color:#9ca3af;">Subcategory</span><br>{{ $vpm->subcategory->name ?? $vpm->subcat_id ?? '—' }}</div>
                        <div class="col-md-3"><span style="color:#9ca3af;">Brand</span><br>{{ $vpm->brand->name ?? $vpm->brand_id ?? '—' }}</div>
                    </div>
                </div>
            </div>
        </div>

    </main>
</div>

@include('layouts.dash_styles')
@include('layouts.admin-footer')
