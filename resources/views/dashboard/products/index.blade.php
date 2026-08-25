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
        <form method="GET" action="{{ route('dashboard.products.index') }}" class="dash-filter-bar"
            id="productFilterForm">
            <input type="text" name="search" class="dash-form-input" placeholder="Search name, SKU…"
                value="{{ request('search') }}" id="productSearch">
            <select name="isactive" class="dash-form-select" style="max-width:160px;" id="productStatusFilter">
                <option value="">All Status</option>
                <option value="1" {{ request('isactive') === '1' ? 'selected' : '' }}>Active</option>
                <option value="0" {{ request('isactive') === '0' ? 'selected' : '' }}>Inactive</option>
            </select>
            <button type="submit" class="btn-dash-primary">Filter</button>
            @if(request()->hasAny(['search', 'isactive']))
                <a href="{{ route('dashboard.products.index') }}" class="btn-dash-secondary">Clear</a>
            @endif
        </form>

        {{-- Table --}}
        <div class="dash-table-wrap">
            <table class="dash-table" id="productsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>SKU</th>
                        <th>Price</th>
                        <th>Discount</th>
                        <th>Status</th>
                        <th>Views</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td class="text-muted" style="font-size:.78rem;">{{ $product->id }}</td>
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
                            <td style="font-family:monospace;font-size:.8rem;">{{ $product->sku ?? '—' }}</td>
                            <td>₹{{ number_format($product->price, 2) }}</td>
                            <td>{{ $product->discount > 0 ? '₹' . number_format($product->discount, 2) : '—' }}</td>
                            <td>
                                <span class="{{ $product->isactive ? 'badge-active' : 'badge-inactive' }}">
                                    {{ $product->isactive ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ number_format($product->viewed) }}</td>
                            <td style="font-size:.78rem;color:#9ca3af;">{{ $product->created_at->format('d M Y') }}</td>
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
                            <td colspan="10" class="text-center py-4" style="color:#9ca3af;">
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

    </main>
</div>{{-- /.dash-wrapper --}}

@include('layouts.dash_styles')

@include('layouts.admin-footer')