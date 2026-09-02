{{-- Shared form partial for Vendor Product Management --}}
<form method="POST" action="{{ $action }}" enctype="multipart/form-data" id="vpmForm">
    @csrf
    @if($method === 'PUT')
        @method('PUT')
    @endif

    {{-- Core Information --}}
    <p class="dash-form-section">Core Information</p>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="dash-form-label" for="name">Product Name <span class="text-danger">*</span></label>
            <input type="text" class="dash-form-input @error('name') is-invalid @enderror"
                   name="name" id="name" value="{{ old('name', $vpm->name ?? '') }}" required>
            @error('name') <div class="dash-form-error">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-3">
            <label class="dash-form-label" for="price">Price <span class="text-danger">*</span></label>
            <input type="number" step="0.01" min="0" class="dash-form-input @error('price') is-invalid @enderror"
                   name="price" id="price" value="{{ old('price', $vpm->price ?? 0) }}" required>
            @error('price') <div class="dash-form-error">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-3">
            <label class="dash-form-label" for="status">Status</label>
            <div class="form-check form-switch mt-2">
                <input class="form-check-input" type="checkbox" name="status" id="status" value="1"
                       {{ old('status', $vpm->status ?? true) ? 'checked' : '' }}>
                <label class="form-check-label" for="status">Active</label>
            </div>
        </div>

        <div class="col-12">
            <label class="dash-form-label" for="description">Description</label>
            <textarea class="dash-form-textarea" name="description" id="description" rows="3">{{ old('description', $vpm->description ?? '') }}</textarea>
        </div>

        <div class="col-12">
            <label class="dash-form-label" for="info">Additional Info</label>
            <textarea class="dash-form-textarea" name="info" id="info" rows="2">{{ old('info', $vpm->info ?? '') }}</textarea>
        </div>
    </div>

    {{-- Media & Images --}}
    <p class="dash-form-section mt-4">Media</p>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="dash-form-label" for="imgurl">Image URL</label>
            <input type="text" class="dash-form-input" name="imgurl" id="imgurl"
                   value="{{ old('imgurl', $vpm->imgurl ?? '') }}" placeholder="https://… or images/…">
        </div>
        <div class="col-md-6">
            <label class="dash-form-label" for="image_file">Upload Image File</label>
            <input type="file" class="dash-form-input" name="image_file" id="image_file" accept="image/*">
            @if(!empty($vpm->imgurl))
                <div class="mt-1 small text-muted">Current: {{ $vpm->imgurl }}</div>
            @endif
        </div>
    </div>

    {{-- Vendor & Category Details --}}
    <p class="dash-form-section mt-4">Vendor & Category Information</p>
    <div class="row g-3">
        <div class="col-md-3">
            <label class="dash-form-label" for="product_id">Product ID</label>
            <input type="number" class="dash-form-input" name="product_id" id="product_id"
                   value="{{ old('product_id', $vpm->product_id ?? '') }}">
        </div>
        <div class="col-md-3">
            <label class="dash-form-label" for="vid">Vendor ID (vid)</label>
            <input type="number" class="dash-form-input" name="vid" id="vid"
                   value="{{ old('vid', $vpm->vid ?? '') }}">
        </div>
        <div class="col-md-3">
            <label class="dash-form-label" for="vendor_code">Vendor Code</label>
            <input type="text" class="dash-form-input" name="vendor_code" id="vendor_code"
                   value="{{ old('vendor_code', $vpm->vendor_code ?? '') }}">
        </div>
        <div class="col-md-3">
            <label class="dash-form-label" for="sku">SKU</label>
            <input type="text" class="dash-form-input" name="sku" id="sku"
                   value="{{ old('sku', $vpm->sku ?? '') }}">
        </div>
        <div class="col-md-6">
            <label class="dash-form-label" for="vendor_prod_url">Vendor Product URL</label>
            <input type="url" class="dash-form-input" name="vendor_prod_url" id="vendor_prod_url"
                   value="{{ old('vendor_prod_url', $vpm->vendor_prod_url ?? '') }}" placeholder="https://…">
        </div>
        <div class="col-md-3">
            <label class="dash-form-label" for="cat_id">Category</label>
            <select class="dash-form-select @error('cat_id') is-invalid @enderror" name="cat_id" id="cat_id">
                <option value="">Select Category</option>
                @if(isset($categories))
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('cat_id', $vpm->cat_id ?? '') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="col-md-3">
            <label class="dash-form-label" for="subcat_id">Subcategory</label>
            <select class="dash-form-select @error('subcat_id') is-invalid @enderror" name="subcat_id" id="subcat_id">
                <option value="">Select Subcategory</option>
                @if(isset($subcategories))
                    @foreach($subcategories as $subcat)
                        <option value="{{ $subcat->id }}" {{ old('subcat_id', $vpm->subcat_id ?? '') == $subcat->id ? 'selected' : '' }}>
                            {{ $subcat->name }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="col-md-3">
            <label class="dash-form-label" for="brand_id">Brand</label>
            <select class="dash-form-select @error('brand_id') is-invalid @enderror" name="brand_id" id="brand_id">
                <option value="">Select Brand</option>
                @if(isset($brands))
                    @foreach($brands as $brandItem)
                        <option value="{{ $brandItem->id }}" {{ old('brand_id', $vpm->brand_id ?? '') == $brandItem->id ? 'selected' : '' }}>
                            {{ $brandItem->name }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>

    {{-- Actions --}}
    <div class="d-flex gap-2 mt-4 pt-3" style="border-top:1px solid #f3f4f6;">
        <button type="submit" class="btn-dash-primary">
            <i class="bi bi-check-lg me-1"></i> {{ $submitLabel ?? 'Save Vendor Product' }}
        </button>
        <a href="{{ route('dashboard.vpm.index') }}" class="btn-dash-secondary">Cancel</a>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const catSelect = document.getElementById('cat_id');
    const subcatSelect = document.getElementById('subcat_id');
    const initialSubcatId = "{{ old('subcat_id', $vpm->subcat_id ?? '') }}";

    if (!catSelect || !subcatSelect) return;

    catSelect.addEventListener('change', function () {
        const catId = this.value;
        subcatSelect.innerHTML = '<option value="">Select Subcategory</option>';

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

    if (catSelect.value && subcatSelect.options.length <= 1) {
        catSelect.dispatchEvent(new Event('change'));
    }
});
</script>
