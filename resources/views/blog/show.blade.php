@extends('layouts.app')

@section('title', $meta['title'])
@section('meta_description', $meta['description'])

@section('content')
<div class="container py-3">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('blog.index') }}" class="text-decoration-none text-muted">Blog</a></li>
            <li class="breadcrumb-item active fw-semibold text-success" aria-current="page">{{ Str::limit($blog->title, 35) }}</li>
        </ol>
    </nav>

    <div class="row g-4">

        {{-- MAIN CONTENT: Blog Details Page (LEFT SIDE) --}}
        <div class="col-lg-8 col-md-7">
            <article class="card border-0 shadow-sm rounded-3 p-4 p-md-5 bg-white">
                
                {{-- Blog Meta & Header --}}
                <div class="mb-3">
                    @if($blog->category)
                        <span class="badge bg-success px-3 py-2 text-uppercase mb-2" style="font-size:.72rem;letter-spacing:0.5px;">
                            {{ $blog->category->name }}
                        </span>
                    @endif
                    <h1 class="fw-bold text-dark display-6 mb-3 lh-sm">{{ $blog->title }}</h1>
                    
                    <div class="d-flex align-items-center gap-3 text-muted small border-bottom pb-3">
                        <div><i class="bi bi-calendar3 me-1 text-success"></i>{{ $blog->created_at ? $blog->created_at->format('F d, Y') : '' }}</div>
                        <div><i class="bi bi-clock me-1 text-success"></i>{{ ceil(str_word_count(strip_tags($blog->description ?? '')) / 200) }} min read</div>
                    </div>
                </div>

                {{-- Featured Image --}}
                @if($blog->image)
                    <div class="my-4 text-center">
                        <img src="{{ str_starts_with($blog->image, 'http') ? $blog->image : asset($blog->image) }}" alt="{{ $blog->title }}" class="img-fluid rounded-3 shadow-sm w-100" style="max-height:460px;object-fit:cover;">
                    </div>
                @endif

                {{-- Excerpt --}}
                @if($blog->excerpt)
                    <div class="lead fw-normal text-dark border-start border-4 border-success ps-3 py-2 bg-light rounded-end mb-4" style="font-size:1.1rem;">
                        {{ $blog->excerpt }}
                    </div>
                @endif

                {{-- Blog Body (Description with HTML, images, videos) --}}
                <div class="blog-body lh-lg text-dark fs-6 my-3">
                    {!! $blog->description !!}
                </div>

                {{-- Bottom Hashtags Section --}}
                @if(!empty($blog->formatted_tags))
                    <div class="mt-5 pt-4 border-top">
                        <h6 class="fw-bold text-muted uppercase me-2 mb-3" style="letter-spacing:0.5px;">
                            <i class="bi bi-hash text-success me-1 fs-5"></i> HASHTAGS & TAGS
                        </h6>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($blog->formatted_tags as $tag)
                                <a href="{{ route('blog.index', ['search' => ltrim($tag, '#')]) }}" class="badge bg-light text-success border border-success-subtle px-3 py-2 text-decoration-none rounded-pill fs-6 hover-shadow-sm">
                                    {{ $tag }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Share / Return Button --}}
                <div class="mt-5 pt-3 border-top d-flex justify-content-between align-items-center">
                    <a href="{{ route('blog.index') }}" class="btn btn-outline-secondary btn-sm px-4 fw-semibold rounded-pill">
                        <i class="bi bi-arrow-left me-1"></i> Back to All Blogs
                    </a>
                </div>

            </article>
        </div>

        {{-- RIGHT SIDEBAR: Last 10 Blog Posts (RIGHT SIDE) --}}
        <div class="col-lg-4 col-md-5">
            <div class="card border-0 shadow-sm rounded-3 p-3 sticky-top" style="top:100px;background:#f9fafb;">
                <h5 class="fw-bold text-dark border-bottom pb-3 mb-3 d-flex align-items-center">
                    <i class="bi bi-clock-history text-success me-2 fs-5"></i> Recent Posts
                </h5>

                <div class="list-group list-group-flush bg-transparent">
                    @forelse($sidebarBlogs as $sBlog)
                        <a href="{{ route('blog.show', $sBlog->url) }}" class="list-group-item list-group-item-action bg-transparent px-0 py-2 border-bottom border-light-subtle d-flex align-items-center gap-3 {{ $sBlog->id === $blog->id ? 'fw-bold text-success' : '' }}">
                            @if($sBlog->image)
                                <img src="{{ str_starts_with($sBlog->image, 'http') ? $sBlog->image : asset($sBlog->image) }}" alt="{{ $sBlog->title }}" style="width:48px;height:48px;object-fit:cover;border-radius:.4rem;" class="border flex-shrink-0">
                            @else
                                <div style="width:48px;height:48px;border-radius:.4rem;background:#e5e7eb;display:flex;align-items:center;justify-content:center;color:#9ca3af;" class="flex-shrink-0">
                                    <i class="bi bi-file-earmark-post fs-5"></i>
                                </div>
                            @endif
                            <div class="flex-grow-1 min-w-0">
                                <h6 class="mb-1 fw-semibold lh-sm {{ $sBlog->id === $blog->id ? 'text-success' : 'text-dark' }}" style="font-size:.85rem;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                    {{ $sBlog->title }}
                                </h6>
                                <span class="text-muted small" style="font-size:.74rem;">
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
.blog-body img {
    max-width: 100%;
    height: auto;
    border-radius: 0.5rem;
    margin: 1rem 0;
}
.blog-body iframe, .blog-body video {
    max-width: 100%;
    border-radius: 0.5rem;
    margin: 1rem 0;
}
.hover-shadow-sm:hover {
    box-shadow: 0 4px 12px rgba(25,135,84,0.15);
}
</style>
@endsection
