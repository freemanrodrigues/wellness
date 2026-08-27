@extends('layouts.app')

@section('title', $meta['title'])
@section('meta_description', $meta['description'])

@section('content')
<div class="container py-3">

    {{-- Breadcrumb / Header --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item active fw-semibold text-success" aria-current="page">Blog</li>
        </ol>
    </nav>

    <div class="row g-4">

        {{-- MAIN CONTENT: Top 10 Blog List (LEFT SIDE) --}}
        <div class="col-lg-8 col-md-7">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h2 class="fw-bold text-dark m-0">Latest Articles & Health Tips</h2>
                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill fw-medium">
                    Top 10 Posts
                </span>
            </div>

            <div class="row g-4">
                @forelse($blogs as $blog)
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-3 overflow-hidden h-100 transition-hover">
                            <div class="row g-0 align-items-center">
                                @if($blog->image)
                                    <div class="col-md-4">
                                        <a href="{{ route('blog.show', $blog->url) }}">
                                            <img src="{{ str_starts_with($blog->image, 'http') ? $blog->image : asset($blog->image) }}" alt="{{ $blog->title }}" class="img-fluid h-100 w-100" style="object-fit:cover;min-height:220px;max-height:240px;">
                                        </a>
                                    </div>
                                @endif

                                <div class="{{ $blog->image ? 'col-md-8' : 'col-12' }}">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            @if($blog->category)
                                                <span class="badge bg-success text-white px-2 py-1" style="font-size:.72rem;">
                                                    {{ $blog->category->name }}
                                                </span>
                                            @endif
                                            <span class="text-muted small" style="font-size:.78rem;">
                                                <i class="bi bi-calendar-event me-1"></i>{{ $blog->created_at ? $blog->created_at->format('F d, Y') : '' }}
                                            </span>
                                        </div>

                                        <h3 class="card-title h5 fw-bold mb-2">
                                            <a href="{{ route('blog.show', $blog->url) }}" class="text-dark text-decoration-none hover-success">
                                                {{ $blog->title }}
                                            </a>
                                        </h3>

                                        <p class="card-text text-secondary small mb-3" style="display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;line-height:1.6;">
                                            {{ $blog->excerpt ?? Str::limit(strip_tags($blog->description), 150) }}
                                        </p>

                                        <a href="{{ route('blog.show', $blog->url) }}" class="btn btn-outline-success btn-sm fw-semibold rounded-pill px-3">
                                            Read Complete Blog Post <i class="bi bi-arrow-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card border-0 shadow-sm p-5 text-center text-muted rounded-3">
                            <i class="bi bi-journal-x fs-1 mb-3 text-secondary"></i>
                            <h5>No blog posts available at the moment.</h5>
                            <p class="small">Check back soon for latest health, wellness, and lifestyle articles!</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- RIGHT SIDEBAR: Last 10 Blog Posts (RIGHT SIDE) --}}
        <div class="col-lg-4 col-md-5">
            <div class="card border-0 shadow-sm rounded-3 p-3 sticky-top" style="top:100px;background:#f9fafb;">
                <h5 class="fw-bold text-dark border-bottom pb-3 mb-3 d-flex align-items-center">
                    <i class="bi bi-clock-history text-success me-2 fs-5"></i> Recent Posts
                </h5>

                <div class="list-group list-group-flush bg-transparent">
                    @forelse($sidebarBlogs as $sBlog)
                        <a href="{{ route('blog.show', $sBlog->url) }}" class="list-group-item list-group-item-action bg-transparent px-0 py-2 border-bottom border-light-subtle d-flex align-items-center gap-3">
                            @if($sBlog->image)
                                <img src="{{ str_starts_with($sBlog->image, 'http') ? $sBlog->image : asset($sBlog->image) }}" alt="{{ $sBlog->title }}" style="width:52px;height:52px;object-fit:cover;border-radius:.4rem;" class="border flex-shrink-0">
                            @else
                                <div style="width:52px;height:52px;border-radius:.4rem;background:#e5e7eb;display:flex;align-items:center;justify-content:center;color:#9ca3af;" class="flex-shrink-0">
                                    <i class="bi bi-file-earmark-post fs-5"></i>
                                </div>
                            @endif
                            <div class="flex-grow-1 min-w-0">
                                <h6 class="mb-1 text-dark fw-semibold lh-sm" style="font-size:.88rem;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                    {{ $sBlog->title }}
                                </h6>
                                <span class="text-muted small" style="font-size:.75rem;">
                                    <i class="bi bi-calendar3 me-1"></i>{{ $sBlog->created_at ? $sBlog->created_at->format('M d, Y') : '' }}
                                </span>
                            </div>
                        </a>
                    @empty
                        <p class="text-muted small mb-0">No recent articles found.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>

<style>
.transition-hover {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.transition-hover:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.08) !important;
}
.hover-success:hover {
    color: #198754 !important;
}
</style>
@endsection
