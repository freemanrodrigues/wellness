@include('layouts.admin-header')
@include('layouts.admin-navbar')
@include('layouts.dash_styles')

<div class="dash-wrapper" style="padding-top: 110px;">
    <main class="dash-main">

        <div class="dash-page-header">
            <div>
                <h1 class="dash-page-title">Add New FAQ / Question</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.faq-qna.index') }}">FAQ &amp; Q&amp;A</a></li>
                        <li class="breadcrumb-item active">Create</li>
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
            <form method="POST" action="{{ route('dashboard.faq-qna.store') }}" id="faqCreateForm">
                @csrf

                <div class="row g-4 mb-4">

                    {{-- Type --}}
                    <div class="col-md-4">
                        <label for="typeSelect" class="form-label fw-semibold">Entry Type <span class="text-danger">*</span></label>
                        <select name="type" id="typeSelect" class="form-select @error('type') is-invalid @enderror" required>
                            <option value="faq" {{ old('type') === 'faq' ? 'selected' : '' }}>Admin FAQ (Frequently Asked Question)</option>
                            <option value="question" {{ old('type') === 'question' ? 'selected' : '' }}>Product Customer Q&amp;A</option>
                        </select>
                        <div class="form-text">Choose FAQ for general info or Question for customer Q&amp;A.</div>
                    </div>

                    {{-- Target Category Selector --}}
                    <div class="col-md-4">
                        <label for="targetTypeSelect" class="form-label fw-semibold">Assign / Display Target <span class="text-danger">*</span></label>
                        <select id="targetTypeSelect" class="form-select" onchange="toggleTargetDropdowns(this.value)">
                            <option value="product" {{ old('product_id') ? 'selected' : '' }}>Product Page</option>
                            <option value="category" {{ old('category_id') && !old('subcategory_id') ? 'selected' : '' }}>Category Page</option>
                            <option value="subcategory" {{ old('subcategory_id') ? 'selected' : '' }}>Subcategory Page</option>
                            <option value="brand" {{ old('brand_id') ? 'selected' : '' }}>Brand Page</option>
                            <option value="page" {{ old('page_name') ? 'selected' : '' }}>Website Static Page</option>
                        </select>
                        <div class="form-text">Select where this FAQ or Q&amp;A should be displayed.</div>
                    </div>

                    {{-- Sort Order --}}
                    <div class="col-md-4">
                        <label for="sort_order" class="form-label fw-semibold">Sort Order</label>
                        <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order', 0) }}" min="0">
                        <div class="form-text">Lower values display first.</div>
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
                                <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Category Target --}}
                    <div class="target-group d-none" id="targetCategoryGroup">
                        <label for="category_id" class="form-label fw-semibold">Select Category</label>
                        <select name="category_id" id="category_id" class="form-select">
                            <option value="">-- Choose Category --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Subcategory Target --}}
                    <div class="target-group d-none" id="targetSubcategoryGroup">
                        <label for="subcategory_id" class="form-label fw-semibold">Select Subcategory</label>
                        <select name="subcategory_id" id="subcategory_id" class="form-select">
                            <option value="">-- Choose Subcategory --</option>
                            @foreach($subcategories as $sub)
                                <option value="{{ $sub->id }}" {{ old('subcategory_id') == $sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Brand Target --}}
                    <div class="target-group d-none" id="targetBrandGroup">
                        <label for="brand_id" class="form-label fw-semibold">Select Brand</label>
                        <select name="brand_id" id="brand_id" class="form-select">
                            <option value="">-- Choose Brand --</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Page Target --}}
                    <div class="target-group d-none" id="targetPageGroup">
                        <label for="page_name" class="form-label fw-semibold">Select Website Page</label>
                        <select name="page_name" id="page_name" class="form-select">
                            <option value="">-- Choose Website Page --</option>
                            @foreach($pageOptions as $key => $label)
                                <option value="{{ $key }}" {{ old('page_name') == $key ? 'selected' : '' }}>{{ $label }} ({{ $key }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Dynamic Multi-FAQ Items Container --}}
                <div class="mb-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="fw-bold mb-0 text-dark">Question &amp; Answer Items</h6>
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="addFaqBlock()" id="btnAddAnotherFaq">
                            <i class="bi bi-plus-circle me-1"></i> Add Another Question &amp; Answer
                        </button>
                    </div>

                    <div id="faqItemsContainer" class="d-flex flex-column gap-3">
                        {{-- FAQ Item #1 --}}
                        <div class="faq-item-card card border p-3 rounded-3 shadow-sm bg-white position-relative">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="badge bg-primary px-2 py-1 faq-item-badge">Question &amp; Answer #1</span>
                                <div class="d-flex align-items-center gap-2">
                                    <label class="form-label small mb-0 fw-semibold text-muted">Sort:</label>
                                    <input type="number" name="faqs[0][sort_order]" class="form-control form-control-sm" style="width: 80px;" value="0" min="0">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Question <span class="text-danger">*</span></label>
                                <textarea name="faqs[0][question]" rows="2" class="form-control" placeholder="Enter question..." required></textarea>
                            </div>

                            <div>
                                <label class="form-label fw-semibold small">Answer / Response</label>
                                <textarea name="faqs[0][answer]" rows="3" class="form-control" placeholder="Enter answer or response..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="addFaqBlock()">
                            <i class="bi bi-plus-circle me-1"></i> Add Another Question &amp; Answer
                        </button>
                    </div>
                </div>

                {{-- Submitter info (Optional) --}}
                <div class="row g-3 mb-4 p-3 bg-light rounded-3">
                    <div class="col-12"><h6 class="fw-bold mb-1 text-dark">Submitter Info (Optional)</h6></div>
                    <div class="col-md-6">
                        <label for="user_name" class="form-label small">Name</label>
                        <input type="text" name="user_name" id="user_name" class="form-control form-control-sm" value="{{ old('user_name') }}" placeholder="e.g. John Doe or Admin">
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label small">Email Address</label>
                        <input type="email" name="email" id="email" class="form-control form-control-sm" value="{{ old('email') }}" placeholder="e.g. customer@example.com">
                    </div>
                </div>

                {{-- Status --}}
                <div class="form-check form-switch mb-4">
                    <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                    <label for="is_active" class="form-check-label fw-semibold">Active &amp; Published on Website</label>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn-dash-primary px-4"><i class="bi bi-check2-circle me-1"></i> Save FAQ / Question(s)</button>
                    <a href="{{ route('dashboard.faq-qna.index') }}" class="btn-dash-secondary">Cancel</a>
                </div>

            </form>
        </div>

    </main>
</div>

<script>
let faqItemIndex = 1;

function toggleTargetDropdowns(selectedTarget) {
    const groups = ['Product', 'Category', 'Subcategory', 'Brand', 'Page'];
    groups.forEach(g => {
        const el = document.getElementById('target' + g + 'Group');
        if (el) el.classList.add('d-none');
    });

    const targetEl = document.getElementById('target' + selectedTarget.charAt(0).toUpperCase() + selectedTarget.slice(1) + 'Group');
    if (targetEl) targetEl.classList.remove('d-none');
}

function addFaqBlock() {
    const container = document.getElementById('faqItemsContainer');
    if (!container) return;

    const blockId = faqItemIndex++;
    const count = container.querySelectorAll('.faq-item-card').length + 1;

    const card = document.createElement('div');
    card.className = 'faq-item-card card border p-3 rounded-3 shadow-sm bg-white position-relative mt-2';
    card.id = `faqCardBlock_${blockId}`;

    card.innerHTML = `
        <div class="d-flex align-items-center justify-content-between mb-2">
            <span class="badge bg-secondary px-2 py-1 faq-item-badge">Question &amp; Answer #${count}</span>
            <div class="d-flex align-items-center gap-2">
                <label class="form-label small mb-0 fw-semibold text-muted">Sort:</label>
                <input type="number" name="faqs[${blockId}][sort_order]" class="form-control form-control-sm" style="width: 80px;" value="${count - 1}" min="0">
                <button type="button" class="btn btn-outline-danger btn-sm px-2 py-0 ms-2" onclick="removeFaqBlock(this)" title="Remove this question">
                    &times; Remove
                </button>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold small">Question <span class="text-danger">*</span></label>
            <textarea name="faqs[${blockId}][question]" rows="2" class="form-control" placeholder="Enter question..." required></textarea>
        </div>

        <div>
            <label class="form-label fw-semibold small">Answer / Response</label>
            <textarea name="faqs[${blockId}][answer]" rows="3" class="form-control" placeholder="Enter answer or response..."></textarea>
        </div>
    `;

    container.appendChild(card);
    updateFaqBadges();
}

function removeFaqBlock(btn) {
    const card = btn.closest('.faq-item-card');
    if (card) {
        card.remove();
        updateFaqBadges();
    }
}

function updateFaqBadges() {
    const container = document.getElementById('faqItemsContainer');
    if (!container) return;
    const cards = container.querySelectorAll('.faq-item-card');
    cards.forEach((card, idx) => {
        const badge = card.querySelector('.faq-item-badge');
        if (badge) {
            badge.textContent = `Question & Answer #${idx + 1}`;
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const targetSelect = document.getElementById('targetTypeSelect');
    if (targetSelect) {
        toggleTargetDropdowns(targetSelect.value);
    }
});
</script>

@include('layouts.admin-footer')
