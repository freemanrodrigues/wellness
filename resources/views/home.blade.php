@extends('layouts.app')

@section('title', $meta['title'])
@section('meta_description', $meta['description'])

@section('content')

    @foreach($productRows as $rowIndex => $row)
        <section class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h4 mb-0">{{ $row['title'] }}</h2>
                <a href="{{ $row['view_all'] }}" class="text-decoration-none small">
                    View All <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <div class="row g-4">
                @foreach($row['products'] as $productIndex => $product)
                    <div class="col-6 col-md-3">
                        <div class="card h-100 border-0 shadow-sm product-card">
                            <a href="#" class="text-decoration-none">
                                <img src="{{ asset($product['image']) }}" alt="{{ $product['name'] }}" class="card-img-top"
                                    width="300" height="300" @if($rowIndex === 0 && $productIndex === 0) loading="eager"
                                    fetchpriority="high" @else loading="lazy" @endif>
                            </a>
                            <div class="card-body d-flex flex-column">
                                <a href="#" class="text-decoration-none text-dark">
                                    <h3 class="h6 card-title mb-1">{{ $product['name'] }}</h3>
                                </a>
                                <p class="card-text fw-bold mb-2">${{ number_format($product['price'], 2) }}</p>
                                <button type="button" class="btn btn-sm btn-outline-primary mt-auto">
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endforeach

    <section class="border-top pt-4 mt-5 text-muted">
        <div class="row">
            <div class="col-lg-8">
                <h2 class="h5">Fresh Flowers & Gifts, Delivered With Care</h2>
                <p class="small">
                    From same-day flower delivery to celebration cakes and curated gift baskets, we help you
                    mark life's moments — big and small — with something thoughtful. Every bouquet is hand-arranged
                    by trusted local florists and delivered fresh, so your gift arrives exactly the way you intended.
                    Whether you're celebrating a birthday, sending a get-well wish, or planning ahead for an
                    anniversary, our collection is designed to make sending flowers simple, reliable, and personal.
                </p>
                <p class="small mb-0">
                    We proudly deliver across multiple countries with local florist partners on the ground,
                    ensuring your gift is fresh, on time, and just as beautiful as pictured — no matter where
                    your recipient lives.
                </p>
            </div>
        </div>
    </section>

@endsection

@push('styles')
    <style>
        .product-card img {
            object-fit: cover;
            aspect-ratio: 1 / 1;
        }

        .product-card:hover {
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .1) !important;
            transition: box-shadow .2s ease-in-out;
        }
    </style>
@endpush