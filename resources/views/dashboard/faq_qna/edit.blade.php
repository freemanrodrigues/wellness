@include('layouts.admin-header')
@include('layouts.admin-navbar')
@include('layouts.dash_styles')

<div class="dash-wrapper" style="padding-top: 110px;">
    <main class="dash-main">

        <div class="dash-page-header">
            <div>
                <h1 class="dash-page-title">Edit FAQ / Question #{{ $faqQna->id }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.faq-qna.index') }}">FAQ &amp; Q&amp;A</a></li>
                        <li class="breadcrumb-item active">Edit #{{ $faqQna->id }}</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('dashboard.faq-qna.index') }}" class="btn-dash-secondary">
                &larr; Back to List
            </a>
        </div>

        @if ($errors->any())
            <div class="dash-alert dash-alert--danger mb-4">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="dash-table-card p-4">
            <form method="POST" action="{{ route('dashboard.faq-qna.update', $faqQna->id) }}" id="faqEditForm">
                @csrf
                @method('PUT')

                <div class="row g-4 mb-4">

                    {{-- Type --}}
                    <div class="col-md-4">
                        <label for="typeSelect" class="form-label fw-semibold">Entry Type <span class="text-danger">*</span></label>
                        <select name="type" id="typeSelect" class="form-select @error('type') is-invalid @enderror" required>
                            <option value="faq" {{ old('type', $faqQna->type) === 'faq' ? 'selected' : '' }}>Admin FAQ (Frequently Asked Question)</option>
                            <option value="question" {{ old('type', $faqQna->type) === 'question' ? 'selected' : '' }}>Product Customer Q&amp;A</option>
                        </select>
                    </div>

                    {{-- Target Category Selector --}}
                    @php
                        $currentTarget = 'product';
                        if ($faqQna->subcategory_id) $currentTarget = 'subcategory';
                        elseif ($faqQna->category_id) $currentTarget = 'category';
                        elseif ($faqQna->brand_id) $currentTarget = 'brand';
                        elseif ($faqQna->page_name) $currentTarget = 'page';
                    @endphp
                    <div class="col-md-4">
                        <label for="targetTypeSelect" class="form-label fw-semibold">Assign / Display Target</label>
                        <select id="targetTypeSelect" class="form-select" onchange="toggleTargetDropdowns(this.value)">
                            <option value="product" {{ old('target_type', $currentTarget) === 'product' ? 'selected' : '' }}>Product Page</option>
                            <option value="category" {{ old('target_type', $currentTarget) === 'category' ? 'selected' : '' }}>Category Page</option>
                            <option value="subcategory" {{ old('target_type', $currentTarget) === 'subcategory' ? 'selected' : '' }}>Subcategory Page</option>
                            <option value="brand" {{ old('target_type', $currentTarget) === 'brand' ? 'selected' : '' }}>Brand Page</option>
                            <option value="page" {{ old('target_type', $currentTarget) === 'page' ? 'selected' : '' }}>Website Static Page</option>
                        </select>
                    </div>

                    {{-- Sort Order --}}
                    <div class="col-md-4">
                        <label for="sort_order" class="form-label fw-semibold">Sort Order</label>
                        <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order', $faqQna->sort_order) }}" min="0">
                    </div>

                </div>

                {{-- Dynamic Target Dropdowns --}}
                <div class="card border-0 bg-light p-3 mb-4 rounded-3">
                    <h6 class="fw-bold mb-3 text-dark">Target Entity Selection</h6>

                    {{-- Product Target --}}
                    <div class="target-group" id="targetProductGroup">
                        <label for="product_id" class="form-label fw-semibold">Select Product</label>
                        <select name="product_id" id="product_id" class="form-select">
                            <option value="">-- Choose Product --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ old('product_id', $faqQna->product_id) == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Category Target --}}
                    <div class="target-group d-none" id="targetCategoryGroup">
                        <label for="category_id" class="form-label fw-semibold">Select Category</label>
                        <select name="category_id" id="category_id" class="form-select">
                            <option value="">-- Choose Category --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $faqQna->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Subcategory Target --}}
                    <div class="target-group d-none" id="targetSubcategoryGroup">
                        <label for="subcategory_id" class="form-label fw-semibold">Select Subcategory</label>
                        <select name="subcategory_id" id="subcategory_id" class="form-select">
                            <option value="">-- Choose Subcategory --</option>
                            @foreach($subcategories as $sub)
                                <option value="{{ $sub->id }}" {{ old('subcategory_id', $faqQna->subcategory_id) == $sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Brand Target --}}
                    <div class="target-group d-none" id="targetBrandGroup">
                        <label for="brand_id" class="form-label fw-semibold">Select Brand</label>
                        <select name="brand_id" id="brand_id" class="form-select">
                            <option value="">-- Choose Brand --</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id', $faqQna->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Page Target --}}
                    <div class="target-group d-none" id="targetPageGroup">
                        <label for="page_name" class="form-label fw-semibold">Select Website Page</label>
                        <select name="page_name" id="page_name" class="form-select">
                            <option value="">-- Choose Website Page --</option>
                            @foreach($pageOptions as $key => $label)
                                <option value="{{ $key }}" {{ old('page_name', $faqQna->page_name) == $key ? 'selected' : '' }}>{{ $label }} ({{ $key }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Question & Answer --}}
                <div class="mb-4">
                    <label for="question" class="form-label fw-semibold">Question <span class="text-danger">*</span></label>
                    <textarea name="question" id="question" rows="3" class="form-control @error('question') is-invalid @enderror" placeholder="Enter question..." required>{{ old('question', $faqQna->question) }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="answer" class="form-label fw-semibold">Answer / Admin Response</label>
                    <textarea name="answer" id="answer" rows="5" class="form-control @error('answer') is-invalid @enderror" placeholder="Enter answer or response...">{{ old('answer', $faqQna->answer) }}</textarea>
                </div>

                {{-- Submitter info (Optional) --}}
                <div class="row g-3 mb-4 p-3 bg-light rounded-3">
                    <div class="col-12"><h6 class="fw-bold mb-1 text-dark">Submitter Info</h6></div>
                    <div class="col-md-6">
                        <label for="user_name" class="form-label small">Name</label>
                        <input type="text" name="user_name" id="user_name" class="form-control form-control-sm" value="{{ old('user_name', $faqQna->user_name) }}">
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label small">Email Address</label>
                        <input type="email" name="email" id="email" class="form-control form-control-sm" value="{{ old('email', $faqQna->email) }}">
                    </div>
                </div>

                {{-- Status --}}
                <div class="form-check form-switch mb-4">
                    <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" {{ old('is_active', $faqQna->is_active) ? 'checked' : '' }}>
                    <label for="is_active" class="form-check-label fw-semibold">Active &amp; Published on Website</label>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn-dash-primary px-4">Update FAQ / Question</button>
                    <a href="{{ route('dashboard.faq-qna.index') }}" class="btn-dash-secondary">Cancel</a>
                </div>

            </form>
        </div>

    </main>
</div>

<script>
function toggleTargetDropdowns(selectedTarget) {
    const groups = ['Product', 'Category', 'Subcategory', 'Brand', 'Page'];
    groups.forEach(g => {
        const el = document.getElementById('target' + g + 'Group');
        if (el) el.classList.add('d-none');
    });

    const targetEl = document.getElementById('target' + selectedTarget.charAt(0).toUpperCase() + selectedTarget.slice(1) + 'Group');
    if (targetEl) targetEl.classList.remove('d-none');
}

document.addEventListener('DOMContentLoaded', function() {
    const targetSelect = document.getElementById('targetTypeSelect');
    if (targetSelect) {
        toggleTargetDropdowns(targetSelect.value);
    }
});
</script>

@include('layouts.admin-footer')
