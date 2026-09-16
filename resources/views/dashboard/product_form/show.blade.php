@include('layouts.admin-header')

@include('layouts.admin-navbar')

<div class="dash-wrapper">
    <main class="dash-main mt-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="dash-page-title m-0">Product Form Details</h1>
            <a href="{{ route('dashboard.product-form.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back to Product Forms
            </a>
        </div>

        <div class="card border-0 shadow-sm p-4 rounded-3 bg-white" style="max-width: 600px;">
            <div class="table-responsive">
                <table class="table table-borderless align-middle mb-0">
                    <tbody>
                        <tr>
                            <th class="ps-0 text-muted" style="width: 140px;">ID:</th>
                            <td class="fw-bold">#{{ $productForm->id }}</td>
                        </tr>
                        <tr>
                            <th class="ps-0 text-muted">Product Form:</th>
                            <td class="fw-semibold text-dark fs-5">{{ $productForm->product_form }}</td>
                        </tr>
                        <tr>
                            <th class="ps-0 text-muted">Status:</th>
                            <td>
                                @if($productForm->isactive)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-warning text-dark">Inactive</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="ps-0 text-muted">Created At:</th>
                            <td>{{ $productForm->created_at ? $productForm->created_at->format('d M Y, h:i A') : '-' }}</td>
                        </tr>
                        <tr>
                            <th class="ps-0 text-muted">Updated At:</th>
                            <td>{{ $productForm->updated_at ? $productForm->updated_at->format('d M Y, h:i A') : '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="d-flex gap-2 mt-4 pt-3 border-top">
                <a href="{{ route('dashboard.product-form.edit', $productForm->id) }}" class="btn btn-primary px-4">
                    <i class="bi bi-pencil me-1"></i> Edit
                </a>
                <form method="POST" action="{{ route('dashboard.product-form.destroy', $productForm->id) }}" onsubmit="return confirm('Are you sure you want to delete this product form?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger px-4">
                        <i class="bi bi-trash me-1"></i> Delete
                    </button>
                </form>
            </div>
        </div>

    </main>
</div>

@include('layouts.admin-footer')
