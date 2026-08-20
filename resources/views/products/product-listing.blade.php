@extends('layouts.app')

@section('title', $meta['title'])
@section('meta_description', $meta['description'])

@section('content')

<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
    <h1 class="h4 mb-0">{{ $meta['title'] }}</h1>

    {{-- Sort dropdown --}}
    <div class="dropdown">
        <button
            class="btn btn-outline-secondary btn-sm dropdown-toggle"
            type="button"
            data-bs-toggle="dropdown"
            aria-expanded="false">
            <i class="bi bi-sort-down me-1"></i>
            Sort by:
            <span class="fw-semibold">
                @switch($sort)
                    @case('price_low') Price: Low to High @break
                    @case('price_high') Price: High to Low @break
                    @case('reviews') Top Reviewed @break
                    @default Newest @break
                @endswitch
            </span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            <li>
                <a class="dropdown-item {{ $sort === 'newest' ? 'active' : '' }}"
                   href="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}">
                    Newest
                </a>
            </li>
            <li>
                <a class="dropdown-item {{ $sort === 'price_low' ? 'active' : '' }}"
                   href="{{ request()->fullUrlWithQuery(['sort' => 'price_low']) }}">
                    Price: Low to High
                </a>
            </li>
            <li>
                <a class="dropdown-item {{ $sort === 'price_high' ? 'active' : '' }}"
                   href="{{ request()->fullUrlWithQuery(['sort' => 'price_high']) }}">
                    Price: High to Low
                </a>
            </li>
            <li>
                <a class="dropdown-item {{ $sort === 'reviews' ? 'active' : '' }}"
                   href="{{ request()->fullUrlWithQuery(['sort' => 'reviews']) }}">
                    Reviews
                </a>
            </li>
        </ul>
    </div>
</div>

@if ($products->isEmpty())
    <div class="alert alert-light border text-center py-5">
        No products found.
    </div>
@else

    <div class="row g-4">
        @foreach ($products as $index => $product)
            <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                <div class="card h-100 border-0 shadow-sm product-card">

                    <div class="position-relative">
                        <a href="{{ route('product-details', $product->metaurl) }}">
                            <img
                                src="{{ asset('images/products/' . $product->imgurl) }}"
                                alt="{{ $product->name }}"
                                class="card-img-top"
                                width="300"
                                height="300"
                                @if($index === 0)
                                    loading="eager" fetchpriority="high"
                                @else
                                    loading="lazy"
                                @endif
                            >
                        </a>

                        {{-- Top-right badge: New / Sale / Bestseller 
                        @if ($product->is_on_sale)
                            <span class="badge bg-danger position-absolute top-0 end-0 m-2">Sale</span>
                        @elseif ($product->is_bestseller)
                            <span class="badge bg-warning text-dark position-absolute top-0 end-0 m-2">Bestseller</span>
                        @elseif ($product->is_new)
                            <span class="badge bg-success position-absolute top-0 end-0 m-2">New</span>
                        @endif --}}
                    </div>

                    <div class="card-body d-flex flex-column">
                        <a href="{{ route('product-details', $product->metaurl) }}" class="text-decoration-none text-dark">
                            <h2 class="h6 card-title mb-1">{{ $product->name }}</h2>
                        </a>

                        {{-- Ratings — only shown if the product has reviews --}}
                        @if ($product->reviews_count > 0)
                            <div class="mb-2 small">
                                <span class="text-warning">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= floor($product->rating))
                                            <i class="bi bi-star-fill"></i>
                                        @elseif ($i - $product->rating < 1)
                                            <i class="bi bi-star-half"></i>
                                        @else
                                            <i class="bi bi-star"></i>
                                        @endif
                                    @endfor
                                </span>
                                <span class="text-muted">({{ $product->reviews_count }})</span>
                            </div>
                        @endif

                        <div class="mt-auto">
                            @if ($product->is_on_sale && $product->original_price)
                                <span class="text-decoration-line-through text-muted small me-1">
                                    ${{ number_format($product->original_price, 2) }}
                                </span>
                                <span class="fw-bold text-danger">
                                    ${{ number_format($product->price, 2) }}
                                </span>
                            @else
                                <span class="fw-bold">
                                    ${{ number_format($product->price, 2) }}
                                </span>
                            @endif
                        </div>

                        <button type="button" class="btn btn-sm btn-outline-primary w-100 mt-3">
                            Add to Cart
                        </button>
                    </div>

                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>

@endif

@endsection

@push('styles')
<style>
    .product-card img {
        object-fit: cover;
        aspect-ratio: 1 / 1;
    }
    .product-card:hover {
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.1) !important;
        transition: box-shadow .2s ease-in-out;
    }
</style>
@endpush