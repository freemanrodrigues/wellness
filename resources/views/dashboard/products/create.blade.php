@include('layouts.admin-header')

@include('layouts.admin-navbar')

<div class="dash-wrapper">


    <main class="dash-main mt-10">
        @if (session('success'))
            <div class="dash-alert dash-alert--success">{{ session('success') }}</div>
        @endif

        <div class="dash-page-header">
            <div>
                <h1 class="dash-page-title">Add New Product</h1>
                <nav style="font-size:.8rem;color:#9ca3af;">
                    <a href="{{ route('dashboard.products.index') }}"
                        style="color:#1a6644;text-decoration:none;">Products</a>
                    &rsaquo; Create
                </nav>
            </div>
        </div>

        <div class="dash-card">
            @include('dashboard.products._form', [
                'product' => new \App\Models\Product(),
                'action' => route('dashboard.products.store'),
                'method' => 'POST',
                'categories' => $categories ?? [],
                'submitLabel' => 'Create Product',
            ])
        </div>

    </main>
</div>

@include('layouts.dash_styles')
@include('layouts.admin-footer')