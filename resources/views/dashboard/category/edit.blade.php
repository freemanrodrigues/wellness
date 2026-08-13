@include('layouts.admin-header')

@include('layouts.admin-navbar')
<div class="dash-wrapper">


    <main class="dash-main mt-4">
        <div class="dash-page-header">
            <div>
                <h1 class="dash-page-title">Edit Category</h1>
                <nav style="font-size:.8rem;color:#9ca3af;">
                    <a href="{{ route('dashboard.category.index') }}"
                        style="color:#1a6644;text-decoration:none;">Categories</a>
                    &rsaquo;
                    <a href="{{ route('dashboard.category.show', $category) }}"
                        style="color:#1a6644;text-decoration:none;">{{ Str::limit($category->name, 30) }}</a>
                    &rsaquo; Edit
                </nav>
            </div>
            <a href="{{ route('dashboard.category.show', $category) }}" class="btn-dash-secondary">View</a>
        </div>

        <div class="dash-card">
            @include('dashboard.category._form', [
                'category' => $category,
                'action' => route('dashboard.category.update', $category),
                'method' => 'PUT',
                'parents' => $parents,
                'submitLabel' => 'Update Category',
            ])
        </div>
    </main>
</div>

@include('layouts.dash_styles')
@include('layouts.admin-footer')