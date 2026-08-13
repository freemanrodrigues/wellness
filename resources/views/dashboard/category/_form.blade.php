{{--
    Shared category form partial
    Variables: $category, $action, $method, $parents, $submitLabel
--}}

<form method="POST" action="{{ $action }}" id="categoryForm">
    @csrf
    @if($method === 'PUT')
        @method('PUT')
    @endif

    {{-- ── CORE INFO ─────────────────────────────────────── --}}
    <p class="dash-form-section">Core Information</p>
    <div class="row g-3">
        <div class="col-md-8">
            <label class="dash-form-label" for="name">Category Name <span class="text-danger">*</span></label>
            <input type="text"
                   class="dash-form-input @error('name') is-invalid @enderror"
                   name="name" id="name"
                   value="{{ old('name', $category->name ?? '') }}"
                   required
                   oninput="autoSlug(this.value)">
            @error('name') <div class="dash-form-error">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-4">
            <label class="dash-form-label" for="parent_id">Parent Category</label>
            <select class="dash-form-select @error('parent_id') is-invalid @enderror"
                    name="parent_id" id="parent_id">
                <option value="">— Root (no parent) —</option>
                @foreach ($parents as $p)
                    <option value="{{ $p->id }}"
                        {{ old('parent_id', $category->parent_id ?? '') == $p->id ? 'selected' : '' }}>
                        {{ $p->name }}
                    </option>
                @endforeach
            </select>
            @error('parent_id') <div class="dash-form-error">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6">
            <label class="dash-form-label" for="slug">
                Slug
                <span class="fw-normal text-muted" style="font-size:.72rem;">(auto-generated if blank)</span>
            </label>
            <input type="text"
                   class="dash-form-input @error('slug') is-invalid @enderror"
                   name="slug" id="slug"
                   value="{{ old('slug', $category->slug ?? '') }}"
                   placeholder="my-category-name">
            @error('slug') <div class="dash-form-error">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-2">
            <label class="dash-form-label" for="sort_order">Sort Order</label>
            <input type="number" min="0"
                   class="dash-form-input"
                   name="sort_order" id="sort_order"
                   value="{{ old('sort_order', $category->sort_order ?? 0) }}">
        </div>
        <div class="col-12">
            <label class="dash-form-label" for="description">Description</label>
            <textarea class="dash-form-textarea" name="description" id="description" rows="3">{{ old('description', $category->description ?? '') }}</textarea>
        </div>
    </div>

    {{-- ── MEDIA ────────────────────────────────────────── --}}
    <p class="dash-form-section">Media</p>
    <div class="row g-3">
        <div class="col-md-4">
            <label class="dash-form-label" for="image">Image URL</label>
            <input type="text" class="dash-form-input" name="image" id="image"
                   value="{{ old('image', $category->image ?? '') }}" placeholder="https://…">
            @if(!empty($category->image ?? null))
                <img src="{{ $category->image }}" alt="preview"
                     style="height:60px;margin-top:.5rem;border-radius:.35rem;object-fit:cover;border:1px solid #e8eaf0;">
            @endif
        </div>
        <div class="col-md-4">
            <label class="dash-form-label" for="banner">Banner URL</label>
            <input type="text" class="dash-form-input" name="banner" id="banner"
                   value="{{ old('banner', $category->banner ?? '') }}" placeholder="https://…">
        </div>
        <div class="col-md-4">
            <label class="dash-form-label" for="icon">Icon Class / URL</label>
            <input type="text" class="dash-form-input" name="icon" id="icon"
                   value="{{ old('icon', $category->icon ?? '') }}" placeholder="fa-tag or https://…">
        </div>
    </div>

    {{-- ── VISIBILITY ───────────────────────────────────── --}}
    <p class="dash-form-section">Visibility</p>
    <div class="row g-3 align-items-center">
        <div class="col-md-4">
            <div class="dash-toggle-row">
                <div>
                    <div class="dash-toggle-label">Status</div>
                    <div class="dash-toggle-hint">Active = visible on site</div>
                </div>
                <label class="dash-switch">
                    <input type="hidden" name="status" value="0">
                    <input type="checkbox" name="status" value="1" id="status"
                           {{ old('status', $category->status ?? true) ? 'checked' : '' }}>
                    <span class="dash-switch__slider"></span>
                </label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="dash-toggle-row">
                <div>
                    <div class="dash-toggle-label">Show in Menu</div>
                    <div class="dash-toggle-hint">Appears in navbar</div>
                </div>
                <label class="dash-switch">
                    <input type="hidden" name="show_in_menu" value="0">
                    <input type="checkbox" name="show_in_menu" value="1" id="show_in_menu"
                           {{ old('show_in_menu', $category->show_in_menu ?? true) ? 'checked' : '' }}>
                    <span class="dash-switch__slider"></span>
                </label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="dash-toggle-row">
                <div>
                    <div class="dash-toggle-label">Show on Homepage</div>
                    <div class="dash-toggle-hint">Featured on landing page</div>
                </div>
                <label class="dash-switch">
                    <input type="hidden" name="show_on_homepage" value="0">
                    <input type="checkbox" name="show_on_homepage" value="1" id="show_on_homepage"
                           {{ old('show_on_homepage', $category->show_on_homepage ?? false) ? 'checked' : '' }}>
                    <span class="dash-switch__slider"></span>
                </label>
            </div>
        </div>
    </div>

    {{-- ── SEO ──────────────────────────────────────────── --}}
    <p class="dash-form-section">SEO</p>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="dash-form-label" for="meta_title">Meta Title</label>
            <input type="text" class="dash-form-input" name="meta_title" id="meta_title"
                   value="{{ old('meta_title', $category->meta_title ?? '') }}">
        </div>
        <div class="col-md-6">
            <label class="dash-form-label" for="meta_keywords">Meta Keywords</label>
            <input type="text" class="dash-form-input" name="meta_keywords" id="meta_keywords"
                   value="{{ old('meta_keywords', $category->meta_keywords ?? '') }}">
        </div>
        <div class="col-12">
            <label class="dash-form-label" for="meta_description">Meta Description</label>
            <textarea class="dash-form-textarea" name="meta_description" id="meta_description" rows="2">{{ old('meta_description', $category->meta_description ?? '') }}</textarea>
        </div>
    </div>

    {{-- ── SUBMIT ───────────────────────────────────────── --}}
    <div class="d-flex gap-2 mt-4 pt-2" style="border-top:1px solid #f3f4f6;">
        <button type="submit" class="btn-dash-primary" id="btnSaveCategory">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                <path d="M2 1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4.5L10.5 0H2zm3 9.5A.5.5 0 0 1 5 10V7a.5.5 0 0 1 1 0v2.5h1.5a.5.5 0 0 1 0 1zm4-5V2.5l3 3H9z"/>
            </svg>
            {{ $submitLabel ?? 'Save Category' }}
        </button>
        <a href="{{ route('dashboard.category.index') }}" class="btn-dash-secondary">Cancel</a>
    </div>
</form>

<script>
function autoSlug(val) {
    const slugField = document.getElementById('slug');
    if (!slugField || slugField.dataset.manual === '1') return;
    slugField.value = val
        .toLowerCase()
        .trim()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-');
}
document.getElementById('slug')?.addEventListener('input', function () {
    this.dataset.manual = '1';
});
</script>

<style>
/* Toggle switch */
.dash-toggle-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #f8f9fb;
    border: 1px solid #e8eaf0;
    border-radius: .5rem;
    padding: .75rem 1rem;
}
.dash-toggle-label { font-size: .84rem; font-weight: 600; color: #374151; }
.dash-toggle-hint  { font-size: .72rem; color: #9ca3af; margin-top: .1rem; }

.dash-switch { position: relative; display: inline-block; width: 40px; height: 22px; flex-shrink: 0; }
.dash-switch input[type="checkbox"] { opacity: 0; width: 0; height: 0; }
.dash-switch input[type="hidden"] { display: none; }
.dash-switch__slider {
    position: absolute; inset: 0;
    background: #d1d5db; border-radius: 22px; cursor: pointer;
    transition: background .2s;
}
.dash-switch__slider::before {
    content: ''; position: absolute;
    width: 16px; height: 16px; left: 3px; bottom: 3px;
    background: #fff; border-radius: 50%;
    transition: transform .2s;
    box-shadow: 0 1px 3px rgba(0,0,0,.2);
}
.dash-switch input[type="checkbox"]:checked + .dash-switch__slider { background: #1a6644; }
.dash-switch input[type="checkbox"]:checked + .dash-switch__slider::before { transform: translateX(18px); }
</style>
