@include('layouts.admin-header')

@include('layouts.admin-navbar')

<div class="dash-wrapper">
    <main class="dash-main mt-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="dash-page-title m-0">{{ $blog->title }}</h1>
                <div class="text-muted small mt-1">
                    URL: <code>/blog/{{ $blog->url }}</code> | Created: {{ $blog->created_at->format('d M Y, H:i') }}
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('dashboard.blog.edit', $blog) }}" class="btn btn-primary btn-sm px-3 fw-bold">
                    <i class="bi bi-pencil me-1"></i> Edit Post
                </a>
                <a href="{{ route('blog.show', $blog->url) }}" target="_blank" class="btn btn-outline-primary btn-sm px-3">
                    <i class="bi bi-box-arrow-up-right me-1"></i> Public Page
                </a>
                <a href="{{ route('dashboard.blog.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        <div class="card border-0 shadow-sm p-4 rounded-3 bg-white">
            <div class="row g-4">
                <div class="col-md-8">
                    @if($blog->image)
                        <div class="mb-4">
                            <img src="{{ str_starts_with($blog->image, 'http') ? $blog->image : asset($blog->image) }}" alt="{{ $blog->title }}" class="img-fluid rounded-3 shadow-sm" style="max-height:400px;width:100%;object-fit:cover;">
                        </div>
                    @endif

                    @if($blog->excerpt)
                        <div class="alert alert-light border p-3 mb-4 rounded-3">
                            <h6 class="fw-bold text-dark mb-1">Excerpt</h6>
                            <p class="mb-0 text-secondary fs-6">{{ $blog->excerpt }}</p>
                        </div>
                    @endif

                    <div class="blog-content border-top pt-4">
                        <h5 class="fw-bold mb-3">Blog Content</h5>
                        <div class="lh-lg">
                            {!! $blog->description !!}
                        </div>
                    </div>

                    @if(!empty($blog->formatted_tags))
                        <div class="mt-4 pt-3 border-top">
                            <h6 class="fw-bold mb-2">Hashtags</h6>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($blog->formatted_tags as $tag)
                                    <span class="badge bg-light text-primary border px-3 py-2 fs-6 rounded-pill">{{ $tag }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <div class="col-md-4 border-start">
                    <div class="ps-md-3">
                        <h6 class="fw-bold mb-3">Post Details</h6>
                        <ul class="list-group list-group-flush small">
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span class="text-muted">Status:</span>
                                <span class="{{ $blog->status ? 'badge bg-success' : 'badge bg-secondary' }}">
                                    {{ $blog->status ? 'Active' : 'Inactive' }}
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span class="text-muted">Page Show:</span>
                                <span class="{{ $blog->page_show ? 'badge bg-info text-dark' : 'badge bg-secondary' }}">
                                    {{ $blog->page_show ? 'Visible' : 'Hidden' }}
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span class="text-muted">Category:</span>
                                <strong class="text-dark">{{ $blog->category->name ?? 'None' }}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span class="text-muted">Sort Order:</span>
                                <strong class="text-dark">{{ $blog->sort_order }}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span class="text-muted">Last Updated:</span>
                                <span class="text-dark">{{ $blog->updated_at ? $blog->updated_at->format('d M Y, H:i') : '—' }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </main>
</div>

@include('layouts.dash_styles')
@include('layouts.admin-footer')
