@include('layouts.admin-header')

@include('layouts.admin-navbar')

<div class="dash-wrapper">
    <main class="dash-main mt-4">

        @if (session('success'))
            <div class="dash-alert dash-alert--success">{{ session('success') }}</div>
        @endif

        <div class="dash-page-header">
            <div>
                <h1 class="dash-page-title">{{ $category->name }}</h1>
                <nav style="font-size:.8rem;color:#9ca3af;">
                    <a href="{{ route('dashboard.category.index') }}" style="color:#1a6644;text-decoration:none;">Categories</a>
                    &rsaquo; View
                </nav>
            </div>
            <div style="display:flex;gap:.5rem;">
                <a href="{{ route('dashboard.category.edit', $category) }}" class="btn-dash-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z"/>
                    </svg>
                    Edit
                </a>
                <form method="POST" action="{{ route('dashboard.category.destroy', $category) }}"
                      onsubmit="return confirm('Delete this category?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-dash-danger">Delete</button>
                </form>
                <a href="{{ route('dashboard.category.index') }}" class="btn-dash-secondary">← Back</a>
            </div>
        </div>

        <div class="row g-3">
            {{-- Left panel: image + flags --}}
            <div class="col-md-3">
                <div class="dash-card text-center">
                    @if ($category->image)
                        <img src="{{ $category->image }}" alt="{{ $category->name }}"
                             style="width:100%;max-height:180px;object-fit:cover;border-radius:.45rem;margin-bottom:1rem;">
                    @elseif($category->banner)
                        <img src="{{ $category->banner }}" alt="{{ $category->name }}"
                             style="width:100%;max-height:180px;object-fit:cover;border-radius:.45rem;margin-bottom:1rem;">
                    @else
                        <div style="height:120px;background:#f3f4f6;border-radius:.45rem;display:flex;align-items:center;justify-content:center;margin-bottom:1rem;">
                            <span style="font-size:2.5rem;">{{ $category->icon ?? '📁' }}</span>
                        </div>
                    @endif

                    <span class="{{ $category->status ? 'badge-active' : 'badge-inactive' }}" style="font-size:.8rem;">
                        {{ $category->status ? 'Active' : 'Inactive' }}
                    </span>

                    <div class="mt-3" style="font-size:.8rem;text-align:left;color:#6b7280;">
                        <div class="d-flex justify-content-between py-1" style="border-bottom:1px solid #f3f4f6;">
                            <span>ID</span><strong>#{{ $category->id }}</strong>
                        </div>
                        <div class="d-flex justify-content-between py-1" style="border-bottom:1px solid #f3f4f6;">
                            <span>Parent</span>
                            <strong>{{ $category->parent?->name ?? '—' }}</strong>
                        </div>
                        <div class="d-flex justify-content-between py-1" style="border-bottom:1px solid #f3f4f6;">
                            <span>Sort Order</span><strong>{{ $category->sort_order }}</strong>
                        </div>
                        <div class="d-flex justify-content-between py-1" style="border-bottom:1px solid #f3f4f6;">
                            <span>In Menu</span>
                            <strong>{{ $category->show_in_menu ? '✓ Yes' : '✗ No' }}</strong>
                        </div>
                        <div class="d-flex justify-content-between py-1">
                            <span>On Homepage</span>
                            <strong>{{ $category->show_on_homepage ? '✓ Yes' : '✗ No' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right panel: details --}}
            <div class="col-md-9">
                <div class="dash-card mb-3">
                    <p class="dash-form-section mt-0">Details</p>
                    <div class="row g-3" style="font-size:.865rem;">
                        <div class="col-md-8"><span style="color:#9ca3af;">Name</span><br><strong>{{ $category->name }}</strong></div>
                        <div class="col-md-4"><span style="color:#9ca3af;">Slug</span><br><code style="font-size:.82rem;color:#1a6644;">{{ $category->slug }}</code></div>
                        <div class="col-12"><span style="color:#9ca3af;">Description</span><br>{{ $category->description ?? '—' }}</div>
                        @if($category->icon)
                            <div class="col-md-4"><span style="color:#9ca3af;">Icon</span><br>{{ $category->icon }}</div>
                        @endif
                        @if($category->banner)
                            <div class="col-md-8">
                                <span style="color:#9ca3af;">Banner</span><br>
                                <img src="{{ $category->banner }}" alt="banner"
                                     style="height:60px;object-fit:cover;border-radius:.35rem;margin-top:.4rem;border:1px solid #e8eaf0;">
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Children --}}
                @if($category->children->count())
                    <div class="dash-card mb-3">
                        <p class="dash-form-section mt-0">Sub-categories ({{ $category->children->count() }})</p>
                        <div style="display:flex;flex-wrap:wrap;gap:.5rem;">
                            @foreach($category->children as $child)
                                <a href="{{ route('dashboard.category.show', $child) }}"
                                   style="display:inline-flex;align-items:center;gap:.35rem;background:#f0f9f4;color:#1a6644;text-decoration:none;padding:.3rem .75rem;border-radius:2rem;font-size:.82rem;font-weight:500;border:1px solid #c7e8d5;">
                                    {{ $child->name }}
                                    @if(!$child->status)
                                        <span style="color:#c53030;font-size:.7rem;">(inactive)</span>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- SEO --}}
                <div class="dash-card">
                    <p class="dash-form-section mt-0">SEO</p>
                    <div class="row g-2" style="font-size:.855rem;">
                        <div class="col-md-6"><span style="color:#9ca3af;">Meta Title</span><br>{{ $category->meta_title ?? '—' }}</div>
                        <div class="col-md-6"><span style="color:#9ca3af;">Keywords</span><br>{{ $category->meta_keywords ?? '—' }}</div>
                        <div class="col-12"><span style="color:#9ca3af;">Meta Description</span><br>{{ $category->meta_description ?? '—' }}</div>
                    </div>
                </div>
            </div>
        </div>

    </main>
</div>

@include('layouts.dash_styles')
@include('layouts.admin-footer')

