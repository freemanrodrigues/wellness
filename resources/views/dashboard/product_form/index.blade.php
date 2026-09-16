@include('layouts.admin-header')

@include('layouts.admin-navbar')
<br><br><br><br>
<div class="dash-wrapper ">
    <main class="dash-main mt-4 ">

        {{-- Flash messages --}}
        @if (session('success'))
            <div class="dash-alert dash-alert--success mb-3" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"
                    class="me-2">
                    <path
                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                </svg>
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="dash-alert dash-alert--danger mb-3" role="alert">{{ session('error') }}</div>
        @endif

        <div class="dash-page-header d-flex justify-content-between align-items-center mb-4">
            <h1 class="dash-page-title m-0">Product Forms</h1>
            <a href="{{ route('dashboard.product-form.create') }}" class="btn btn-primary btn-sm px-3 fw-semibold">
                <i class="bi bi-plus-lg me-1"></i> Add Product Form
            </a>
        </div>

        {{-- Filter Bar --}}
        <form method="GET" action="{{ route('dashboard.product-form.index') }}"
            class="card p-3 mb-4 border-0 shadow-sm rounded-3 bg-white">
            <div class="row g-2 align-items-center">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control form-control-sm"
                        placeholder="Search product form..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="isactive" class="form-select form-select-sm">
                        <option value="">All Status</option>
                        <option value="1" {{ request('isactive') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('isactive') === '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm px-3 fw-semibold">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    @if(request()->hasAny(['search', 'isactive']))
                        <a href="{{ route('dashboard.product-form.index') }}"
                            class="btn btn-outline-secondary btn-sm px-3">Clear</a>
                    @endif
                </div>
            </div>
        </form>

        {{-- Table --}}
        <div class="card border-0 shadow-sm rounded-3 bg-white">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width:70px;">ID</th>
                                <th>Product Form</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th class="text-end" style="width:160px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($productForms as $item)
                                <tr class="{{ $item->trashed() ? 'table-secondary opacity-75' : '' }}">
                                    <td><strong>#{{ $item->id }}</strong></td>
                                    <td class="fw-semibold text-dark">{{ $item->product_form }}</td>
                                    <td>
                                        @if($item->trashed())
                                            <span class="badge bg-secondary">Trashed</span>
                                        @elseif($item->isactive)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Inactive</span>
                                        @endif
                                    </td>
                                    <td><small
                                            class="text-muted">{{ $item->created_at ? $item->created_at->format('d M Y, h:i A') : '-' }}</small>
                                    </td>
                                    <td class="text-end">
                                        @if($item->trashed())
                                            <form method="POST"
                                                action="{{ route('dashboard.product-form.restore', $item->id) }}"
                                                class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success" title="Restore">
                                                    <i class="bi bi-arrow-counterclockwise"></i> Restore
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('dashboard.product-form.show', $item->id) }}"
                                                class="btn btn-sm btn-outline-info me-1" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('dashboard.product-form.edit', $item->id) }}"
                                                class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form method="POST"
                                                action="{{ route('dashboard.product-form.destroy', $item->id) }}"
                                                class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this product form?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                        No product forms found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($productForms->hasPages())
                <div class="card-footer bg-white border-0 py-3">
                    {{ $productForms->links() }}
                </div>
            @endif
        </div>

    </main>
</div>

@include('layouts.admin-footer')