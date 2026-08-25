@include('layouts.admin-header')
@include('layouts.admin-navbar')

<div class="dash-wrapper">
    <main class="dash-main mt-4">

        <div class="dash-page-header">
            <div>
                <h1 class="dash-page-title">Add Vendor Product</h1>
                <nav style="font-size:.8rem;color:#9ca3af;">
                    <a href="{{ route('dashboard.vpm.index') }}" style="color:#1a6644;text-decoration:none;">Vendor Product Management</a>
                    &rsaquo; Add New
                </nav>
            </div>
            <a href="{{ route('dashboard.vpm.index') }}" class="btn-dash-secondary">← Back</a>
        </div>

        <div class="dash-card">
            @include('dashboard.vpm._form', [
                'vpm' => new \App\Models\VendorProductManagement(),
                'action' => route('dashboard.vpm.store'),
                'method' => 'POST',
                'submitLabel' => 'Create Vendor Product',
            ])
        </div>

    </main>
</div>

@include('layouts.dash_styles')
@include('layouts.admin-footer')
