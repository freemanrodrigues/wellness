@include('layouts.admin-header')

@include('layouts.admin-navbar')

<div class="dash-wrapper">
    <main class="dash-main mt-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="dash-page-title m-0">Add Product Form</h1>
            <a href="{{ route('dashboard.product-form.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back to Product Forms
            </a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('dashboard.product-form.store') }}"
            class="card border-0 shadow-sm p-4 rounded-3 bg-white" style="max-width: 600px;">
            @csrf

            <div class="mb-3">
                <label for="product_form" class="form-label fw-semibold">Product Form Name <span
                        class="text-danger">*</span></label>
                <input type="text" name="product_form" id="product_form"
                    class="form-control @error('product_form') is-invalid @enderror" value="{{ old('product_form') }}"
                    required placeholder="e.g. Capsule, Tablet, Liquid, Powder">
                @error('product_form')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4 form-check form-switch">
                <input type="checkbox" name="isactive" id="isactive" class="form-check-input" value="1" {{ old('isactive', true) ? 'checked' : '' }}>
                <label class="form-check-label fw-semibold" for="isactive">Is Active?</label>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-lg me-1"></i> Save Product Form
                </button>
                <a href="{{ route('dashboard.product-form.index') }}" class="btn btn-light border px-4">Cancel</a>
            </div>
        </form>

    </main>
</div>

@include('layouts.admin-footer')