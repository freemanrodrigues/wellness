@extends('layouts.app')

@section('title', $meta['title'])
@section('meta_description', $meta['description'])

@section('content')
    {{-- ══════════════════════════════════════════════
    BANNER SECTION — carousel (66%) + right promo (34%)
    ══════════════════════════════════════════════ --}}
    <div class="banner-section">
        <div class="row g-3 align-items-stretch">

            {{-- LEFT: Carousel — 66% --}}
            <div class="col-12 col-lg-8">
                <div class="banner-carousel-wrap">
                    <div id="heroBannerCarousel" class="carousel slide h-100" data-bs-ride="carousel"
                        data-bs-interval="4000">

                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#heroBannerCarousel" data-bs-slide-to="0" class="active"
                                aria-current="true" aria-label="Slide 1"></button>
                            <button type="button" data-bs-target="#heroBannerCarousel" data-bs-slide-to="1"
                                aria-label="Slide 2"></button>
                            <button type="button" data-bs-target="#heroBannerCarousel" data-bs-slide-to="2"
                                aria-label="Slide 3"></button>
                        </div>

                        <div class="carousel-inner h-100 rounded-3 overflow-hidden">

                            {{-- Slide 1 --}}
                            <div class="carousel-item active h-100">
                                <img src="{{ asset('images/banner_slide1.png') }}" alt="Fitness & Wellness">
                                <div class="carousel-caption d-none d-md-block text-start"
                                    style="left:5%;right:auto;bottom:1.5rem;">
                                    <span class="banner-slide-badge">New Season</span>
                                    <h2 class="banner-slide-title">Move. Feel. Thrive.</h2>
                                    <p class="banner-slide-sub">Explore our fitness & movement collection</p>
                                    <a href="#" class="btn-slide-cta">Shop Now</a>
                                </div>
                            </div>

                            {{-- Slide 2 --}}
                            <div class="carousel-item h-100">
                                <img src="{{ asset('images/banner_slide2.png') }}" alt="Nutrition & Diet">
                                <div class="carousel-caption d-none d-md-block text-start"
                                    style="left:5%;right:auto;bottom:1.5rem;">
                                    <span class="banner-slide-badge">Nutrition</span>
                                    <h2 class="banner-slide-title">Eat Well, Live Well.</h2>
                                    <p class="banner-slide-sub">Discover diet plans & supplements</p>
                                    <a href="#" class="btn-slide-cta">Explore</a>
                                </div>
                            </div>

                            {{-- Slide 3 --}}
                            <div class="carousel-item h-100">
                                <img src="{{ asset('images/banner_slide3.png') }}" alt="Yoga & Flexibility">
                                <div class="carousel-caption d-none d-md-block text-start"
                                    style="left:5%;right:auto;bottom:1.5rem;">
                                    <span class="banner-slide-badge">Mindfulness</span>
                                    <h2 class="banner-slide-title">Find Your Balance.</h2>
                                    <p class="banner-slide-sub">Yoga, meditation & flexibility training</p>
                                    <a href="#" class="btn-slide-cta">Learn More</a>
                                </div>
                            </div>

                        </div>

                        {{-- Controls --}}
                        <button class="carousel-control-prev" type="button" data-bs-target="#heroBannerCarousel"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#heroBannerCarousel"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Promo Banner — 34% --}}
            <div class="col-12 col-lg-4">
                <div class="banner-right-wrap">
                    <img src="{{ asset('images/banner_right.png') }}" alt="Special Offer">
                    <div class="banner-right-content">
                        <span class="banner-right-badge">Limited Time</span>
                        <h3>Start Your Wellness Journey</h3>
                        <p>Get personalised plans for nutrition, fitness & mindfulness — tailored just for you.</p>
                        <a href="{{ route('register') }}" class="btn-banner">Get Started</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
    {{-- /banner section --}}
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