@include('layouts.admin-header')

@include('layouts.admin-navbar')

<div class="dash-wrapper">

    {{-- Main --}}
    <main class="dash-main mt-4">

        {{-- Flash messages --}}
        @if (session('success'))
            <div class="dash-alert dash-alert--success" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"
                    class="me-2">
                    <path
                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                </svg>
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="dash-alert dash-alert--danger" role="alert">{{ session('error') }}</div>
        @endif

        {{-- ═══════════════════════════════════════════════
        PAGE CONTENT — PRODUCT INDEX
        ═══════════════════════════════════════════════ --}}

        <div class="dash-page-header">
            <h1 class="dash-page-title">Products</h1>
            <div class="d-flex gap-2">
                <a href="{{ route('dashboard.products.export', request()->query()) }}"
                    class="btn btn-outline-primary btn-sm px-3 fw-bold d-inline-flex align-items-center"
                    id="btnExportProductCsv">
                    <i class="bi bi-download me-1"></i> Export CSV
                </a>
                <a href="{{ route('dashboard.products.import') }}"
                    class="btn btn-outline-success btn-sm px-3 fw-bold d-inline-flex align-items-center"
                    id="btnImportProductCsv">
                    <i class="bi bi-file-earmark-spreadsheet me-1"></i> Import CSV
                </a>
                <a href="{{ route('dashboard.products.create') }}" class="btn-dash-primary" id="btnAddProduct">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                        viewBox="0 0 16 16">
                        <path
                            d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2" />
                    </svg>
                    Add Product
                </a>
            </div>
        </div>

        {{-- Filter bar --}}
        <form method="GET" action="{{ route('dashboard.products.index') }}" class="card p-3 mb-4 border-0 shadow-sm rounded-3" id="productFilterForm" style="background:#fff;">
            <div class="row g-2 align-items-center">
                {{-- Search --}}
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1 fw-medium">Search</label>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search name, SKU…"
                        value="{{ request('search') }}" id="productSearch">
                </div>

                {{-- Category --}}
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1 fw-medium">Category</label>
                    <select name="cat_id" class="form-select form-select-sm" id="catFilter">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('cat_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Subcategory --}}
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1 fw-medium">Subcategory</label>
                    <select name="subcat_id" class="form-select form-select-sm" id="subcatFilter">
                        <option value="">All Subcategories</option>
                        @foreach($allSubcategories as $subcat)
                            @if(!request('cat_id') || request('cat_id') == $subcat->parent_id)
                                <option value="{{ $subcat->id }}" data-parent="{{ $subcat->parent_id }}" {{ request('subcat_id') == $subcat->id ? 'selected' : '' }}>
                                    {{ $subcat->name }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>

                {{-- Brand --}}
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1 fw-medium">Brand</label>
                    <select name="brand_id" class="form-select form-select-sm" id="brandFilter">
                        <option value="">All Brands</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Status --}}
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1 fw-medium">Status</label>
                    <select name="isactive" class="form-select form-select-sm" id="productStatusFilter">
                        <option value="">All Status</option>
                        <option value="1" {{ request('isactive') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('isactive') === '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                {{-- Company (CID) --}}
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1 fw-medium">Company (CID)</label>
                    <select name="cid" class="form-select form-select-sm" id="cidFilter">
                        <option value="">All Companies</option>
                        @if(isset($cidList))
                            @foreach($cidList as $cidVal)
                                <option value="{{ $cidVal }}" {{ request('cid') == $cidVal ? 'selected' : '' }}>
                                    CID: {{ $cidVal }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                {{-- Vendor (VID) --}}
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1 fw-medium">Vendor (VID)</label>
                    <select name="vid" class="form-select form-select-sm" id="vidFilter">
                        <option value="">All Vendors</option>
                        @if(isset($vidList))
                            @foreach($vidList as $vidVal)
                                <option value="{{ $vidVal }}" {{ request('vid') == $vidVal ? 'selected' : '' }}>
                                    VID: {{ $vidVal }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                {{-- Created Date Range --}}
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1 fw-medium">Created From</label>
                    <input type="date" name="created_from" class="form-control form-control-sm" value="{{ request('created_from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1 fw-medium">Created To</label>
                    <input type="date" name="created_to" class="form-control form-control-sm" value="{{ request('created_to') }}">
                </div>

                {{-- Modified Date Range --}}
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1 fw-medium">Modified From</label>
                    <input type="date" name="updated_from" class="form-control form-control-sm" value="{{ request('updated_from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1 fw-medium">Modified To</label>
                    <input type="date" name="updated_to" class="form-control form-control-sm" value="{{ request('updated_to') }}">
                </div>

                {{-- Buttons --}}
                <div class="col-md-4 d-flex align-items-end gap-2 mt-auto">
                    <button type="submit" class="btn btn-primary btn-sm px-3 fw-semibold">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    <button type="submit" formaction="{{ route('dashboard.products.export') }}" class="btn btn-outline-primary btn-sm px-3 fw-semibold" title="Export filtered products to CSV">
                        <i class="bi bi-download me-1"></i> Export
                    </button>
                    @if(request()->hasAny(['search', 'isactive', 'cat_id', 'subcat_id', 'brand_id', 'cid', 'vid', 'created_from', 'created_to', 'updated_from', 'updated_to']))
                        <a href="{{ route('dashboard.products.index') }}" class="btn btn-outline-secondary btn-sm px-3">Clear</a>
                    @endif
                </div>
            </div>
        </form>

        {{-- Table --}}
        <div class="dash-table-wrap">
            <table class="dash-table" id="productsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category / Subcat</th>
                        <th>Brand</th>
                        <th>SKU</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Health Concerns</th>
                        <th>Created</th>
                        <th>Modified</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td style="font-size:.78rem;">
                                <a href="{{ route('product-details', $product->metaurl ?? $product->id) }}" target="_blank" class="text-decoration-none fw-semibold text-primary d-inline-flex align-items-center gap-1" title="View product page on website">
                                    #{{ $product->id }}
                                    <i class="bi bi-box-arrow-up-right" style="font-size:0.65rem;"></i>
                                </a>
                            </td>
                            <td>
                                @if ($product->imgurl)
                                    <img src="{{ asset('images/products/' . $product->imgurl) }}" alt="{{ $product->name }}"
                                        style="width:44px;height:44px;object-fit:cover;border-radius:.35rem;border:1px solid #e8eaf0;">
                                @else
                                    <div
                                        style="width:44px;height:44px;border-radius:.35rem;background:#f3f4f6;display:flex;align-items:center;justify-content:center;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#9ca3af"
                                            viewBox="0 0 16 16">
                                            <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0" />
                                            <path
                                                d="M1.5 2A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2zm13 1a.5.5 0 0 1 .5.5v6l-3.775-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12v.54L1 12.5v-9a.5.5 0 0 1 .5-.5z" />
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div style="font-weight:600;color:#1a1a1a;">{{ Str::limit($product->name, 40) }}</div>
                                @if($product->short_name)
                                    <div style="font-size:.75rem;color:#9ca3af;">{{ $product->short_name }}</div>
                                @endif
                            </td>
                            <td>
                                <div style="font-size:.82rem;font-weight:500;">{{ $product->category->name ?? '—' }}</div>
                                @if($product->subcategory)
                                    <div style="font-size:.75rem;color:#6c757d;">{{ $product->subcategory->name }}</div>
                                @endif
                            </td>
                            <td style="font-size:.82rem;">{{ $product->brand->name ?? '—' }}</td>
                            <td style="font-family:monospace;font-size:.8rem;">{{ $product->sku ?? '—' }}</td>
                            <td>₹{{ number_format($product->price, 2) }}</td>
                            <td>
                                <span class="{{ $product->isactive ? 'badge-active' : 'badge-inactive' }}">
                                    {{ $product->isactive ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    @forelse($product->healthConcerns as $hc)
                                        <span class="badge bg-light text-dark border" style="font-size:0.7rem;font-weight:500;">{{ $hc->name }}</span>
                                    @empty
                                        <span class="text-muted" style="font-size:.75rem;">None</span>
                                    @endforelse
                                </div>
                            </td>
                            <td style="font-size:.78rem;color:#9ca3af;">{{ $product->created_at ? $product->created_at->format('d M Y') : '—' }}</td>
                            <td style="font-size:.78rem;color:#9ca3af;">{{ $product->updated_at ? $product->updated_at->format('d M Y') : '—' }}</td>
                            <td>
                                <div style="display:flex;gap:.4rem;">
                                    <a href="{{ route('dashboard.products.show', $product) }}" class="btn-dash-secondary"
                                        style="padding:.3rem .6rem;" title="View">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor"
                                            viewBox="0 0 16 16">
                                            <path
                                                d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z" />
                                            <path
                                                d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('dashboard.products.edit', $product) }}" class="btn-dash-secondary"
                                        style="padding:.3rem .6rem;" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor"
                                            viewBox="0 0 16 16">
                                            <path
                                                d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z" />
                                        </svg>
                                    </a>
                                    <button type="button" class="btn-dash-secondary open-hc-modal"
                                        data-bs-toggle="modal"
                                        data-bs-target="#assignHealthConcernModal"
                                        style="padding:.3rem .6rem; color:#0d6efd;"
                                        title="Assign Health Concern"
                                        data-product-id="{{ $product->id }}"
                                        data-product-name="{{ e($product->name) }}"
                                        data-health-concerns="{{ json_encode($product->healthConcerns->pluck('id')) }}"
                                        data-action="{{ route('dashboard.products.assign-health-concerns', $product) }}">
                                        <i class="bi bi-heart-pulse-fill"></i>
                                    </button>
                                    <button type="button" class="btn-dash-secondary open-faq-modal"
                                        data-bs-toggle="modal"
                                        data-bs-target="#addProductFaqModal"
                                        style="padding:.3rem .6rem; color:#198754;"
                                        title="Add FAQ"
                                        data-product-id="{{ $product->id }}"
                                        data-product-name="{{ e($product->name) }}">
                                        <i class="bi bi-question-circle-fill"></i>
                                    </button>
                                    <form method="POST" action="{{ route('dashboard.products.destroy', $product) }}"
                                        onsubmit="return confirm('Delete this product?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-dash-danger" style="padding:.3rem .6rem;"
                                            title="Delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13"
                                                fill="currentColor" viewBox="0 0 16 16">
                                                <path
                                                    d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                                                <path
                                                    d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-center py-4" style="color:#9ca3af;">
                                No products found.
                                <a href="{{ route('dashboard.products.create') }}"
                                    style="color:#1a6644;text-decoration:none;">Add your first product →</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($products->hasPages())
            <div class="mt-3 d-flex justify-content-end">
                {{ $products->links() }}
            </div>
        @endif

        {{-- Assign Health Concern Modal --}}
        <div class="modal fade" id="assignHealthConcernModal" tabindex="-1" aria-labelledby="assignHealthConcernModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow">
                    <form id="assignHealthConcernForm" method="POST" action="">
                        @csrf
                        <div class="modal-header bg-light">
                            <h5 class="modal-title fw-bold text-dark" id="assignHealthConcernModalLabel">
                                <i class="bi bi-heart-pulse-fill text-danger me-2"></i>Assign Health Concerns
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="alert alert-info py-2 px-3 mb-3 small d-flex align-items-center" role="alert">
                                <i class="bi bi-info-circle me-2 fs-6"></i>
                                <div>Product: <strong id="modalProductName" class="text-dark"></strong></div>
                            </div>
                            <label class="form-label fw-semibold text-secondary mb-2">Select Health Concerns:</label>
                            <div class="row g-2" id="healthConcernCheckboxes">
                                @forelse($healthConcerns as $hc)
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-check p-2 border rounded bg-white hover-shadow-sm">
                                            <input class="form-check-input hc-checkbox ms-1" type="checkbox" name="health_concerns[]" value="{{ $hc->id }}" id="hc_check_{{ $hc->id }}">
                                            <label class="form-check-label fw-medium ms-2 text-dark" for="hc_check_{{ $hc->id }}" style="cursor:pointer;">
                                                {{ $hc->name }}
                                            </label>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-muted small">No active health concerns found.</div>
                                @endforelse
                            </div>
                        </div>
                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary btn-sm px-4 fw-bold">
                                <i class="bi bi-check-lg me-1"></i> Assign
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Add Product FAQ Modal --}}
        <div class="modal fade" id="addProductFaqModal" tabindex="-1" aria-labelledby="addProductFaqModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow">
                    <form id="addProductFaqForm" method="POST" action="{{ route('dashboard.faq-qna.store') }}">
                        @csrf
                        <input type="hidden" name="type" value="faq">
                        <input type="hidden" name="redirect_back" value="1">
                        <input type="hidden" name="product_id" id="faqModalProductId" value="">
                        
                        <div class="modal-header bg-light">
                            <h5 class="modal-title fw-bold text-dark" id="addProductFaqModalLabel">
                                <i class="bi bi-question-circle-fill text-success me-2"></i>Add Product FAQ
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4" style="max-height:70vh; overflow-y:auto;">
                            <div class="alert alert-info py-2 px-3 mb-3 small d-flex align-items-center" role="alert">
                                <i class="bi bi-info-circle me-2 fs-6"></i>
                                <div>Product: <strong id="faqModalProductName" class="text-dark"></strong></div>
                            </div>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="faqModalIsActive" checked>
                                <label class="form-check-label fw-semibold text-dark" for="faqModalIsActive">Publish immediately (Active)</label>
                            </div>

                            <div id="faqModalItemsContainer">
                                {{-- First FAQ Item --}}
                                <div class="faq-modal-item card mb-3 border p-3 bg-light">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="badge bg-primary faq-item-number">FAQ #1</span>
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-faq-btn d-none" onclick="removeModalFaqBlock(this)">
                                            <i class="bi bi-trash"></i> Remove
                                        </button>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold text-dark">Question <span class="text-danger">*</span></label>
                                        <textarea name="faqs[0][question]" class="form-control form-control-sm" rows="2" placeholder="e.g. How to use this product?" required></textarea>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label small fw-semibold text-dark">Answer / Response</label>
                                        <textarea name="faqs[0][answer]" class="form-control form-control-sm" rows="3" placeholder="Enter answer details..."></textarea>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-md-4">
                                            <label class="form-label small text-muted mb-0">Sort Order</label>
                                            <input type="number" name="faqs[0][sort_order]" class="form-control form-control-sm" value="0" min="0">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="button" class="btn btn-outline-success btn-sm fw-semibold" id="btnAddMoreFaqModal">
                                <i class="bi bi-plus-circle me-1"></i> Add One More Question
                            </button>
                        </div>
                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success btn-sm px-4 fw-bold">
                                <i class="bi bi-check-lg me-1"></i> Save FAQ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </main>
</div>{{-- /.dash-wrapper --}}

@include('layouts.dash_styles')

<script>
function removeModalFaqBlock(btn) {
    const item = btn.closest('.faq-modal-item');
    if (item) {
        item.remove();
        updateModalFaqNumbers();
    }
}

function updateModalFaqNumbers() {
    const items = document.querySelectorAll('#faqModalItemsContainer .faq-modal-item');
    items.forEach((item, index) => {
        const badge = item.querySelector('.faq-item-number');
        if (badge) badge.textContent = `FAQ #${index + 1}`;
        const removeBtn = item.querySelector('.remove-faq-btn');
        if (removeBtn) {
            if (items.length > 1) {
                removeBtn.classList.remove('d-none');
            } else {
                removeBtn.classList.add('d-none');
            }
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    // Dynamic Subcategory filtering based on Category selection
    const catSelect = document.getElementById('catFilter');
    const subcatSelect = document.getElementById('subcatFilter');

    if (catSelect && subcatSelect) {
        const subcatOptions = Array.from(subcatSelect.options);

        catSelect.addEventListener('change', function () {
            const selectedCatId = this.value;
            subcatSelect.innerHTML = '<option value="">All Subcategories</option>';

            subcatOptions.forEach(opt => {
                if (!opt.value) return;
                const parentId = opt.getAttribute('data-parent');
                if (!selectedCatId || parentId === selectedCatId) {
                    subcatSelect.appendChild(opt.cloneNode(true));
                }
            });
        });
    }

    // Assign Health Concern Modal handling
    const hcModalEl = document.getElementById('assignHealthConcernModal');
    if (hcModalEl) {
        const hcForm = document.getElementById('assignHealthConcernForm');
        const modalProdName = document.getElementById('modalProductName');
        const hcCheckboxes = document.querySelectorAll('.hc-checkbox');

        document.querySelectorAll('.open-hc-modal').forEach(btn => {
            btn.addEventListener('click', function () {
                const prodName = this.getAttribute('data-product-name');
                const actionUrl = this.getAttribute('data-action');
                let assignedHcIds = [];
                try {
                    assignedHcIds = JSON.parse(this.getAttribute('data-health-concerns')) || [];
                } catch (e) {
                    assignedHcIds = [];
                }

                if (modalProdName) modalProdName.textContent = prodName;
                if (hcForm) hcForm.action = actionUrl;

                hcCheckboxes.forEach(cb => {
                    cb.checked = assignedHcIds.includes(parseInt(cb.value)) || assignedHcIds.includes(cb.value);
                });

                if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    const hcModal = bootstrap.Modal.getOrCreateInstance(hcModalEl);
                    hcModal.show();
                }
            });
        });
    }

    // Add Product FAQ Modal handling
    const faqModalEl = document.getElementById('addProductFaqModal');
    if (faqModalEl) {
        const faqProductIdInput = document.getElementById('faqModalProductId');
        const faqProductNameSpan = document.getElementById('faqModalProductName');
        const faqContainer = document.getElementById('faqModalItemsContainer');
        const addMoreBtn = document.getElementById('btnAddMoreFaqModal');

        document.querySelectorAll('.open-faq-modal').forEach(btn => {
            btn.addEventListener('click', function () {
                const prodId = this.getAttribute('data-product-id');
                const prodName = this.getAttribute('data-product-name');

                if (faqProductIdInput) faqProductIdInput.value = prodId;
                if (faqProductNameSpan) faqProductNameSpan.textContent = prodName;

                // Reset container to initial state with 1 empty FAQ item
                if (faqContainer) {
                    faqContainer.innerHTML = `
                        <div class="faq-modal-item card mb-3 border p-3 bg-light">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-primary faq-item-number">FAQ #1</span>
                                <button type="button" class="btn btn-sm btn-outline-danger remove-faq-btn d-none" onclick="removeModalFaqBlock(this)">
                                    <i class="bi bi-trash"></i> Remove
                                </button>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-dark">Question <span class="text-danger">*</span></label>
                                <textarea name="faqs[0][question]" class="form-control form-control-sm" rows="2" placeholder="e.g. How to use this product?" required></textarea>
                            </div>
                            <div class="mb-2">
                                <label class="form-label small fw-semibold text-dark">Answer / Response</label>
                                <textarea name="faqs[0][answer]" class="form-control form-control-sm" rows="3" placeholder="Enter answer details..."></textarea>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <label class="form-label small text-muted mb-0">Sort Order</label>
                                    <input type="number" name="faqs[0][sort_order]" class="form-control form-control-sm" value="0" min="0">
                                </div>
                            </div>
                        </div>
                    `;
                }

                if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    const faqModal = bootstrap.Modal.getOrCreateInstance(faqModalEl);
                    faqModal.show();
                }
            });
        });

        if (addMoreBtn) {
            addMoreBtn.addEventListener('click', function () {
                const currentItems = faqContainer.querySelectorAll('.faq-modal-item').length;
                const nextIdx = currentItems;
                const newNum = currentItems + 1;
                const newItem = document.createElement('div');
                newItem.className = 'faq-modal-item card mb-3 border p-3 bg-light';
                newItem.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-primary faq-item-number">FAQ #${newNum}</span>
                        <button type="button" class="btn btn-sm btn-outline-danger remove-faq-btn" onclick="removeModalFaqBlock(this)">
                            <i class="bi bi-trash"></i> Remove
                        </button>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Question <span class="text-danger">*</span></label>
                        <textarea name="faqs[${nextIdx}][question]" class="form-control form-control-sm" rows="2" placeholder="e.g. What are the key ingredients?" required></textarea>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold text-dark">Answer / Response</label>
                        <textarea name="faqs[${nextIdx}][answer]" class="form-control form-control-sm" rows="3" placeholder="Enter answer details..."></textarea>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <label class="form-label small text-muted mb-0">Sort Order</label>
                            <input type="number" name="faqs[${nextIdx}][sort_order]" class="form-control form-control-sm" value="${nextIdx}" min="0">
                        </div>
                    </div>
                `;
                faqContainer.appendChild(newItem);
                updateModalFaqNumbers();
            });
        }
    }
});
</script>

@include('layouts.admin-footer')