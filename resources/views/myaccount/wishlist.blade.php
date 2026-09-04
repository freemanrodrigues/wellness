@extends('layouts.app')

@section('title', 'My Wishlist - My Account')
@section('meta_description', 'View and manage your saved products and favorite items.')

@section('content')
<div class="container py-4">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('myaccount.home') }}" class="text-decoration-none text-muted">My Account</a></li>
            <li class="breadcrumb-item active text-success fw-semibold" aria-current="page">Wishlist</li>
        </ol>
    </nav>

    <div class="row g-4">
        {{-- Left Sidebar --}}
        <div class="col-lg-3 col-md-4">
            @include('myaccount.sidebar')
        </div>

        {{-- Main Wishlist Content --}}
        <div class="col-lg-9 col-md-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="fw-bold mb-1 text-dark"><i class="bi bi-heart text-danger me-2"></i> Saved Wishlist Items</h4>
                        <p class="text-muted small mb-0">Manage your saved favorite products for quick purchasing whenever you're ready.</p>
                    </div>
                    <span class="badge bg-danger px-3 py-2 rounded-pill fw-bold" style="font-size:0.85rem;">{{ count($wishlistItems) }} Saved Items</span>
                </div>

                <div class="card-body p-4">
                    @if(!empty($wishlistItems) && count($wishlistItems) > 0)
                        <div class="row g-4">
                            @foreach($wishlistItems as $item)
                                <div class="col-md-6 col-xl-6">
                                    <div class="card h-100 border rounded-3 p-3 shadow-sm hover-lift position-relative">
                                        @if(isset($item['wishlist_id']))
                                            <form method="POST" action="{{ route('wishlist.destroy', $item['wishlist_id']) }}" class="position-absolute top-0 end-0 m-3">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-close" aria-label="Remove from wishlist" title="Remove Item"></button>
                                            </form>
                                        @else
                                            <button type="button" class="btn-close position-absolute top-0 end-0 m-3" aria-label="Remove from wishlist" title="Remove Item"></button>
                                        @endif
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="rounded-3 border p-2 bg-light d-flex align-items-center justify-content-center" style="width: 90px; height: 90px; flex-shrink: 0;">
                                                <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}" class="img-fluid rounded" style="max-height: 100%; object-fit: contain;">
                                            </div>

                                            <div class="flex-grow-1 overflow-hidden">
                                                <small class="text-muted text-uppercase fw-semibold" style="font-size: 0.7rem;">{{ $item['category'] }}</small>
                                                <h6 class="fw-bold text-dark text-truncate mb-1" title="{{ $item['name'] }}">{{ $item['name'] }}</h6>
                                                
                                                <div class="d-flex align-items-center gap-2 mb-2">
                                                    <span class="fw-bold text-success fs-6">${{ number_format($item['price'], 2) }}</span>
                                                    @if(isset($item['original_price']))
                                                        <span class="text-muted text-decoration-line-through small">${{ number_format($item['original_price'], 2) }}</span>
                                                    @endif
                                                    <span class="badge bg-warning text-dark px-1.5 py-0.5 rounded" style="font-size:0.65rem;">
                                                        <i class="bi bi-star-fill text-dark me-0.5"></i> {{ $item['rating'] }}
                                                    </span>
                                                </div>

                                                <div class="d-flex align-items-center justify-content-between mt-2">
                                                    @if($item['in_stock'])
                                                        <span class="badge bg-soft-success text-success px-2 py-1 rounded-pill small" style="background-color: #d1fae5;">In Stock</span>
                                                        <form method="POST" action="{{ route('cart.add') }}" class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                                                            <input type="hidden" name="quantity" value="1">
                                                            <button type="submit" class="btn btn-sm btn-success rounded-pill px-3 fw-medium">
                                                                <i class="bi bi-cart-plus me-1"></i> Add to Cart
                                                            </button>
                                                        </form>
                                                    @else
                                                        <span class="badge bg-soft-secondary text-secondary px-2 py-1 rounded-pill small" style="background-color: #f3f4f6;">Out of Stock</span>
                                                        <button class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-medium" disabled>Out of Stock</button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-heartbreak text-muted" style="font-size: 3.5rem;"></i>
                            <h5 class="fw-bold mt-3">Your wishlist is empty</h5>
                            <p class="text-muted small">Explore products and click the heart icon to save items here.</p>
                            <a href="{{ url('/') }}" class="btn btn-success rounded-pill px-4 fw-semibold mt-2">Explore Products</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
