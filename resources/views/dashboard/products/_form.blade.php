{{--
    Shared form partial — used by both create.blade.php and edit.blade.php
    Variables:
        $product  — the Product model (or empty Product instance for create)
        $action   — the form action URL
        $method   — PUT | POST
--}}

<form method="POST" action="{{ $action }}" enctype="multipart/form-data" id="productForm">
    @csrf
    @if($method === 'PUT')
        @method('PUT')
    @endif

    {{-- ── CORE INFO ─────────────────────────────────────── --}}
    <p class="dash-form-section">Core Information</p>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="dash-form-label" for="name">Product Name <span class="text-danger">*</span></label>
            <input type="text" class="dash-form-input @error('name') is-invalid @enderror"
                   name="name" id="name" value="{{ old('name', $product->name ?? '') }}" required>
            @error('name') <div class="dash-form-error">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-3">
            <label class="dash-form-label" for="short_name">Short Name</label>
            <input type="text" class="dash-form-input @error('short_name') is-invalid @enderror"
                   name="short_name" id="short_name" value="{{ old('short_name', $product->short_name ?? '') }}">
            @error('short_name') <div class="dash-form-error">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-3">
            <label class="dash-form-label" for="vendor_product_name">Vendor Product Name</label>
            <input type="text" class="dash-form-input"
                   name="vendor_product_name" id="vendor_product_name"
                   value="{{ old('vendor_product_name', $product->vendor_product_name ?? '') }}">
        </div>
        <div class="col-12">
            <label class="dash-form-label" for="description">Description</label>
            <textarea class="dash-form-textarea" name="description" id="description" rows="4">{{ old('description', $product->description ?? '') }}</textarea>
        </div>
        <div class="col-12">
            <label class="dash-form-label" for="info">Additional Info</label>
            <textarea class="dash-form-textarea" name="info" id="info" rows="3">{{ old('info', $product->info ?? '') }}</textarea>
        </div>
        <div class="col-md-3">
            <label class="dash-form-label" for="imgurl">Image URL</label>
            <input type="text" class="dash-form-input" name="imgurl" id="imgurl"
                   value="{{ old('imgurl', $product->imgurl ?? '') }}" placeholder="https://…">
        </div>
        <div class="col-md-9">
            <label class="dash-form-label" for="more_img">Additional Image URLs (comma-separated)</label>
            <input type="text" class="dash-form-input" name="more_img" id="more_img"
                   value="{{ old('more_img', $product->more_img ?? '') }}">
        </div>
        <div class="col-12">
            <label class="dash-form-label" for="more_desc">Additional Description</label>
            <textarea class="dash-form-textarea" name="more_desc" id="more_desc" rows="2">{{ old('more_desc', $product->more_desc ?? '') }}</textarea>
        </div>
        <div class="col-md-3">
            <label class="dash-form-label" for="isactive">Status</label>
            <select class="dash-form-select" name="isactive" id="isactive">
                <option value="1" {{ old('isactive', $product->isactive ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                <option value="0" {{ old('isactive', $product->isactive ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="dash-form-label" for="use_type">Use Type</label>
            <input type="text" class="dash-form-input" name="use_type" id="use_type"
                   value="{{ old('use_type', $product->use_type ?? '') }}">
        </div>
    </div>

    {{-- ── PRICING ─────────────────────────────────────────── --}}
    <p class="dash-form-section">Pricing</p>
    <div class="row g-3">
        <div class="col-md-3">
            <label class="dash-form-label" for="price">Selling Price <span class="text-danger">*</span></label>
            <input type="number" step="0.01" min="0" class="dash-form-input @error('price') is-invalid @enderror"
                   name="price" id="price" value="{{ old('price', $product->price ?? 0) }}" required>
            @error('price') <div class="dash-form-error">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-3">
            <label class="dash-form-label" for="discount">Discount</label>
            <input type="number" step="0.01" min="0" class="dash-form-input"
                   name="discount" id="discount" value="{{ old('discount', $product->discount ?? 0) }}">
        </div>
        <div class="col-md-3">
            <label class="dash-form-label" for="deliverycharge">Delivery Charge</label>
            <input type="number" step="0.01" min="0" class="dash-form-input"
                   name="deliverycharge" id="deliverycharge" value="{{ old('deliverycharge', $product->deliverycharge ?? 0) }}">
        </div>
        <div class="col-md-3">
            <label class="dash-form-label" for="more_price">More Price</label>
            <input type="number" step="0.01" min="0" class="dash-form-input"
                   name="more_price" id="more_price" value="{{ old('more_price', $product->more_price ?? 0) }}">
        </div>
        <div class="col-md-3">
            <label class="dash-form-label" for="vendorprice">Vendor Price</label>
            <input type="number" step="0.01" min="0" class="dash-form-input"
                   name="vendorprice" id="vendorprice" value="{{ old('vendorprice', $product->vendorprice ?? 0) }}">
        </div>
        <div class="col-md-3">
            <label class="dash-form-label" for="vendordeliveryprice">Vendor Delivery Price</label>
            <input type="number" step="0.01" min="0" class="dash-form-input"
                   name="vendordeliveryprice" id="vendordeliveryprice"
                   value="{{ old('vendordeliveryprice', $product->vendordeliveryprice ?? 0) }}">
        </div>
    </div>

    {{-- ── IDENTIFIERS ─────────────────────────────────────── --}}
    <p class="dash-form-section">Identifiers</p>
    <div class="row g-3">
        <div class="col-md-3">
            <label class="dash-form-label" for="sku">SKU</label>
            <input type="text" class="dash-form-input @error('sku') is-invalid @enderror"
                   name="sku" id="sku" value="{{ old('sku', $product->sku ?? '') }}">
            @error('sku') <div class="dash-form-error">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-3">
            <label class="dash-form-label" for="barcode">Barcode</label>
            <input type="text" class="dash-form-input" name="barcode" id="barcode"
                   value="{{ old('barcode', $product->barcode ?? '') }}">
        </div>
        <div class="col-md-3">
            <label class="dash-form-label" for="model_number">Model Number</label>
            <input type="text" class="dash-form-input" name="model_number" id="model_number"
                   value="{{ old('model_number', $product->model_number ?? '') }}">
        </div>
        <div class="col-md-3">
            <label class="dash-form-label" for="manufacturer_part_number">Manufacturer Part #</label>
            <input type="text" class="dash-form-input" name="manufacturer_part_number" id="manufacturer_part_number"
                   value="{{ old('manufacturer_part_number', $product->manufacturer_part_number ?? '') }}">
        </div>
        <div class="col-md-3">
            <label class="dash-form-label" for="vendor_code">Vendor Code</label>
            <input type="text" class="dash-form-input" name="vendor_code" id="vendor_code"
                   value="{{ old('vendor_code', $product->vendor_code ?? '') }}">
        </div>
    </div>

    {{-- ── CATEGORY / BRAND ────────────────────────────────── --}}
    <p class="dash-form-section">Category & Brand</p>
    <div class="row g-3">
        <div class="col-md-2">
            <label class="dash-form-label" for="cid">Company ID</label>
            <input type="number" class="dash-form-input" name="cid" id="cid"
                   value="{{ old('cid', $product->cid ?? '') }}">
        </div>
        <div class="col-md-2">
            <label class="dash-form-label" for="vid">Vendor ID</label>
            <input type="number" class="dash-form-input" name="vid" id="vid"
                   value="{{ old('vid', $product->vid ?? '') }}">
        </div>
        <div class="col-md-3">
            <label class="dash-form-label" for="cat_id">Category</label>
            <select class="dash-form-select @error('cat_id') is-invalid @enderror" name="cat_id" id="cat_id">
                <option value="">Select Category</option>
                @if(isset($categories))
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('cat_id', $product->cat_id ?? '') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                @endif
            </select>
            @error('cat_id') <div class="dash-form-error">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-3">
            <label class="dash-form-label" for="subcat_id">Sub-category</label>
            <select class="dash-form-select @error('subcat_id') is-invalid @enderror" name="subcat_id" id="subcat_id">
                <option value="">Select Sub-category</option>
                @if(isset($subcategories))
                    @foreach($subcategories as $subcat)
                        <option value="{{ $subcat->id }}" {{ old('subcat_id', $product->subcat_id ?? '') == $subcat->id ? 'selected' : '' }}>
                            {{ $subcat->name }}
                        </option>
                    @endforeach
                @endif
            </select>
            @error('subcat_id') <div class="dash-form-error">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-2">
            <label class="dash-form-label" for="brand_id">Brand ID</label>
            <input type="number" class="dash-form-input" name="brand_id" id="brand_id"
                   value="{{ old('brand_id', $product->brand_id ?? '') }}">
        </div>
    </div>

    {{-- ── SEO ──────────────────────────────────────────────── --}}
    <p class="dash-form-section">SEO</p>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="dash-form-label" for="metatitle">Meta Title</label>
            <input type="text" class="dash-form-input" name="metatitle" id="metatitle"
                   value="{{ old('metatitle', $product->metatitle ?? '') }}">
        </div>
        <div class="col-md-6">
            <label class="dash-form-label" for="metaurl">Meta URL / Slug</label>
            <input type="text" class="dash-form-input" name="metaurl" id="metaurl"
                   value="{{ old('metaurl', $product->metaurl ?? '') }}">
        </div>
        <div class="col-md-6">
            <label class="dash-form-label" for="metadesc">Meta Description</label>
            <textarea class="dash-form-textarea" name="metadesc" id="metadesc" rows="2">{{ old('metadesc', $product->metadesc ?? '') }}</textarea>
        </div>
        <div class="col-md-6">
            <label class="dash-form-label" for="metakeyword">Meta Keywords</label>
            <textarea class="dash-form-textarea" name="metakeyword" id="metakeyword" rows="2">{{ old('metakeyword', $product->metakeyword ?? '') }}</textarea>
        </div>
    </div>

    {{-- ── STATS ────────────────────────────────────────────── --}}
    <p class="dash-form-section">Ratings & Stats</p>
    <div class="row g-3">
        <div class="col-md-2">
            <label class="dash-form-label" for="ratingvalue">Rating (0–5)</label>
            <input type="number" step="0.01" min="0" max="5" class="dash-form-input"
                   name="ratingvalue" id="ratingvalue" value="{{ old('ratingvalue', $product->ratingvalue ?? 0) }}">
        </div>
        <div class="col-md-2">
            <label class="dash-form-label" for="reviewcount">Review Count</label>
            <input type="number" min="0" class="dash-form-input"
                   name="reviewcount" id="reviewcount" value="{{ old('reviewcount', $product->reviewcount ?? 0) }}">
        </div>
        <div class="col-md-2">
            <label class="dash-form-label" for="viewed">Viewed</label>
            <input type="number" min="0" class="dash-form-input"
                   name="viewed" id="viewed" value="{{ old('viewed', $product->viewed ?? 0) }}">
        </div>
    </div>

    {{-- ── SUBMIT ───────────────────────────────────────────── --}}
    <div class="d-flex gap-2 mt-4 pt-2" style="border-top:1px solid #f3f4f6;">
        <button type="submit" class="btn-dash-primary" id="btnSaveProduct">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                <path d="M2 1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4.5L10.5 0H2zm3 9.5A.5.5 0 0 1 5 10V7a.5.5 0 0 1 1 0v2.5h1.5a.5.5 0 0 1 0 1zm4-5V2.5l3 3H9z"/>
            </svg>
            {{ $submitLabel ?? 'Save Product' }}
        </button>
        <a href="{{ route('dashboard.products.index') }}" class="btn-dash-secondary">Cancel</a>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const catSelect = document.getElementById('cat_id');
    const subcatSelect = document.getElementById('subcat_id');
    const initialSubcatId = "{{ old('subcat_id', $product->subcat_id ?? '') }}";

    if (!catSelect || !subcatSelect) return;

    catSelect.addEventListener('change', function () {
        const catId = this.value;
        subcatSelect.innerHTML = '<option value="">Select Sub-category</option>';

        if (!catId) return;

        fetch(`/dashboard/get-subcategories/${catId}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(sub => {
                    const option = document.createElement('option');
                    option.value = sub.id;
                    option.textContent = sub.name;
                    if (initialSubcatId && String(sub.id) === String(initialSubcatId)) {
                        option.selected = true;
                    }
                    subcatSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error fetching subcategories:', error);
            });
    });

    // If cat_id has a value on page load and subcategories aren't prepopulated, trigger change event
    if (catSelect.value && subcatSelect.options.length <= 1) {
        catSelect.dispatchEvent(new Event('change'));
    }
});
</script>

