@include('layouts.admin-header')

@include('layouts.admin-navbar')

<div class="dash-wrapper">


    <main class="dash-main mt-4">
        <div class="dash-page-header">
            <div>
                <h1 class="dash-page-title">Add New Category</h1>
                <nav style="font-size:.8rem;color:#9ca3af;">
                    <a href="{{ route('dashboard.category.index') }}"
                        style="color:#1a6644;text-decoration:none;">Categories</a>
                    &rsaquo; Create
                </nav>
            </div>
        </div>

        <div class="dash-card">
            @include('dashboard.category._form', [
                'category' => new \App\Models\Category(),
                'action' => route('dashboard.category.store'),
                'method' => 'POST',
                'parents' => $parents,
                'submitLabel' => 'Create Category',
            ])
        </div>
    </main>
</div>

@include('layouts.dash_styles')
@include('layouts.admin-footer')