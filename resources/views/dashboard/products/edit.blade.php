@include('layouts.admin-header')

@include('layouts.admin-navbar')

<div class="dash-wrapper">
    <main class="dash-main mt-4">
        @if (session('success'))
            <div class="dash-alert dash-alert--success">{{ session('success') }}</div>
        @endif

        <div class="dash-page-header">
            <div>
                <h1 class="dash-page-title">Edit Product</h1>
                <nav style="font-size:.8rem;color:#9ca3af;">
                    <a href="{{ route('dashboard.products.index') }}" style="color:#1a6644;text-decoration:none;">Products</a>
                    &rsaquo;
                    <a href="{{ route('dashboard.products.show', $product) }}" style="color:#1a6644;text-decoration:none;">{{ Str::limit($product->name, 30) }}</a>
                    &rsaquo; Edit
                </nav>
            </div>
            <a href="{{ route('dashboard.products.show', $product) }}" class="btn-dash-secondary">View</a>
        </div>

        <div class="dash-card">
            @include('dashboard.products._form', [
                'product'       => $product,
                'action'        => route('dashboard.products.update', $product),
                'method'        => 'PUT',
                'categories'    => $categories ?? [],
                'subcategories' => $subcategories ?? [],
                'submitLabel'   => 'Update Product',
            ])
        </div>

    </main>
</div>

@include('layouts.dash_styles')
@include('layouts.admin-footer')

