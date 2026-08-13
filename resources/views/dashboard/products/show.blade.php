@include('layouts.admin-header')

@include('layouts.admin-navbar')

<div class="dash-wrapper">
    <main class="dash-main mt-4">

        @if (session('success'))
            <div class="dash-alert dash-alert--success">{{ session('success') }}</div>
        @endif

        <div class="dash-page-header">
            <div>
                <h1 class="dash-page-title">{{ $product->name }}</h1>
                <nav style="font-size:.8rem;color:#9ca3af;">
                    <a href="{{ route('dashboard.products.index') }}" style="color:#1a6644;text-decoration:none;">Products</a>
                    &rsaquo; View
                </nav>
            </div>
            <div style="display:flex;gap:.5rem;">
                <a href="{{ route('dashboard.products.edit', $product) }}" class="btn-dash-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z"/>
                    </svg>
                    Edit
                </a>
                <form method="POST" action="{{ route('dashboard.products.destroy', $product) }}"
                      onsubmit="return confirm('Are you sure you want to delete this product?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-dash-danger">Delete</button>
                </form>
                <a href="{{ route('dashboard.products.index') }}" class="btn-dash-secondary">← Back</a>
            </div>
        </div>

        <div class="row g-3">
            {{-- Left: image + quick stats --}}
            <div class="col-md-3">
                <div class="dash-card text-center">
                    @if ($product->imgurl)
                        <img src="{{ $product->imgurl }}" alt="{{ $product->name }}"
                             style="width:100%;max-height:200px;object-fit:contain;border-radius:.45rem;margin-bottom:1rem;">
                    @else
                        <div style="height:160px;background:#f3f4f6;border-radius:.45rem;display:flex;align-items:center;justify-content:center;margin-bottom:1rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="#d1d5db" viewBox="0 0 16 16">
                                <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
                                <path d="M1.5 2A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2zm13 1a.5.5 0 0 1 .5.5v6l-3.775-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12v.54L1 12.5v-9a.5.5 0 0 1 .5-.5z"/>
                            </svg>
                        </div>
                    @endif

                    <span class="{{ $product->isactive ? 'badge-active' : 'badge-inactive' }}" style="font-size:.8rem;">
                        {{ $product->isactive ? 'Active' : 'Inactive' }}
                    </span>

                    <div class="mt-3" style="font-size:.8rem;color:#6b7280;text-align:left;">
                        <div class="d-flex justify-content-between py-1" style="border-bottom:1px solid #f3f4f6;">
                            <span>ID</span><strong>{{ $product->id }}</strong>
                        </div>
                        <div class="d-flex justify-content-between py-1" style="border-bottom:1px solid #f3f4f6;">
                            <span>SKU</span><strong>{{ $product->sku ?? '—' }}</strong>
                        </div>
                        <div class="d-flex justify-content-between py-1" style="border-bottom:1px solid #f3f4f6;">
                            <span>Rating</span><strong>{{ $product->ratingvalue }} ★ ({{ $product->reviewcount }})</strong>
                        </div>
                        <div class="d-flex justify-content-between py-1">
                            <span>Views</span><strong>{{ number_format($product->viewed) }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right: full details --}}
            <div class="col-md-9">
                <div class="dash-card mb-3">
                    <p class="dash-form-section mt-0">Core Information</p>
                    <div class="row g-2" style="font-size:.865rem;">
                        <div class="col-md-6"><span style="color:#9ca3af;">Name</span><br><strong>{{ $product->name }}</strong></div>
                        <div class="col-md-3"><span style="color:#9ca3af;">Short Name</span><br>{{ $product->short_name ?? '—' }}</div>
                        <div class="col-md-3"><span style="color:#9ca3af;">Vendor Name</span><br>{{ $product->vendor_product_name ?? '—' }}</div>
                        <div class="col-12 mt-2"><span style="color:#9ca3af;">Description</span><br>{{ $product->description ?? '—' }}</div>
                        <div class="col-12 mt-2"><span style="color:#9ca3af;">Info</span><br>{{ $product->info ?? '—' }}</div>
                    </div>
                </div>

                <div class="dash-card mb-3">
                    <p class="dash-form-section mt-0">Pricing</p>
                    <div class="row g-2 text-center" style="font-size:.865rem;">
                        <div class="col-md-2"><span style="color:#9ca3af;display:block;">Price</span><strong style="font-size:1.1rem;color:#1a6644;">₹{{ number_format($product->price,2) }}</strong></div>
                        <div class="col-md-2"><span style="color:#9ca3af;display:block;">Discount</span>₹{{ number_format($product->discount,2) }}</div>
                        <div class="col-md-2"><span style="color:#9ca3af;display:block;">Delivery</span>₹{{ number_format($product->deliverycharge,2) }}</div>
                        <div class="col-md-2"><span style="color:#9ca3af;display:block;">Vendor Price</span>₹{{ number_format($product->vendorprice,2) }}</div>
                        <div class="col-md-2"><span style="color:#9ca3af;display:block;">Vendor Delivery</span>₹{{ number_format($product->vendordeliveryprice,2) }}</div>
                        <div class="col-md-2"><span style="color:#9ca3af;display:block;">More Price</span>₹{{ number_format($product->more_price,2) }}</div>
                    </div>
                </div>

                <div class="dash-card mb-3">
                    <p class="dash-form-section mt-0">Identifiers & Category</p>
                    <div class="row g-2" style="font-size:.855rem;">
                        <div class="col-md-3"><span style="color:#9ca3af;">Barcode</span><br>{{ $product->barcode ?? '—' }}</div>
                        <div class="col-md-3"><span style="color:#9ca3af;">Model #</span><br>{{ $product->model_number ?? '—' }}</div>
                        <div class="col-md-3"><span style="color:#9ca3af;">Mfr Part #</span><br>{{ $product->manufacturer_part_number ?? '—' }}</div>
                        <div class="col-md-3"><span style="color:#9ca3af;">Vendor Code</span><br>{{ $product->vendor_code ?? '—' }}</div>
                        <div class="col-md-2"><span style="color:#9ca3af;">Company</span><br>{{ $product->cid ?? '—' }}</div>
                        <div class="col-md-2"><span style="color:#9ca3af;">Vendor</span><br>{{ $product->vid ?? '—' }}</div>
                        <div class="col-md-2"><span style="color:#9ca3af;">Category</span><br>{{ $product->cat_id ?? '—' }}</div>
                        <div class="col-md-2"><span style="color:#9ca3af;">Sub-cat</span><br>{{ $product->subcat_id ?? '—' }}</div>
                        <div class="col-md-2"><span style="color:#9ca3af;">Brand</span><br>{{ $product->brand_id ?? '—' }}</div>
                        <div class="col-md-2"><span style="color:#9ca3af;">Use Type</span><br>{{ $product->use_type ?? '—' }}</div>
                    </div>
                </div>

                <div class="dash-card">
                    <p class="dash-form-section mt-0">SEO</p>
                    <div class="row g-2" style="font-size:.855rem;">
                        <div class="col-md-6"><span style="color:#9ca3af;">Meta Title</span><br>{{ $product->metatitle ?? '—' }}</div>
                        <div class="col-md-6"><span style="color:#9ca3af;">Meta URL</span><br>{{ $product->metaurl ?? '—' }}</div>
                        <div class="col-md-6"><span style="color:#9ca3af;">Meta Description</span><br>{{ $product->metadesc ?? '—' }}</div>
                        <div class="col-md-6"><span style="color:#9ca3af;">Keywords</span><br>{{ $product->metakeyword ?? '—' }}</div>
                    </div>
                </div>
            </div>
        </div>

    </main>
</div>

@include('layouts.dash_styles')
@include('layouts.admin-footer')

