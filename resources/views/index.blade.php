{{-- resources/views/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Home — ' . config('app.name'))
@section('meta_description', 'Your wellness journey starts here. Explore nutrition, fitness, yoga, and more.')

@section('content')

    {{-- ══════════════════════════════════════════════
         BANNER SECTION — carousel (66%) + right promo (34%)
    ══════════════════════════════════════════════ --}}
    <div class="banner-section">
        <div class="row g-3 align-items-stretch">

            {{-- LEFT: Carousel — 66% --}}
            <div class="col-12 col-lg-8">
                <div class="banner-carousel-wrap">
                    <div id="heroBannerCarousel" class="carousel slide h-100" data-bs-ride="carousel" data-bs-interval="4000">

                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#heroBannerCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                            <button type="button" data-bs-target="#heroBannerCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                            <button type="button" data-bs-target="#heroBannerCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                        </div>

                        <div class="carousel-inner h-100 rounded-3 overflow-hidden">

                            {{-- Slide 1 --}}
                            <div class="carousel-item active h-100">
                                <img src="{{ asset('images/banner_slide1.png') }}" alt="Fitness & Wellness">
                                <div class="carousel-caption d-none d-md-block text-start" style="left:5%;right:auto;bottom:1.5rem;">
                                    <span class="banner-slide-badge">New Season</span>
                                    <h2 class="banner-slide-title">Move. Feel. Thrive.</h2>
                                    <p class="banner-slide-sub">Explore our fitness & movement collection</p>
                                    <a href="#" class="btn-slide-cta">Shop Now</a>
                                </div>
                            </div>

                            {{-- Slide 2 --}}
                            <div class="carousel-item h-100">
                                <img src="{{ asset('images/banner_slide2.png') }}" alt="Nutrition & Diet">
                                <div class="carousel-caption d-none d-md-block text-start" style="left:5%;right:auto;bottom:1.5rem;">
                                    <span class="banner-slide-badge">Nutrition</span>
                                    <h2 class="banner-slide-title">Eat Well, Live Well.</h2>
                                    <p class="banner-slide-sub">Discover diet plans & supplements</p>
                                    <a href="#" class="btn-slide-cta">Explore</a>
                                </div>
                            </div>

                            {{-- Slide 3 --}}
                            <div class="carousel-item h-100">
                                <img src="{{ asset('images/banner_slide3.png') }}" alt="Yoga & Flexibility">
                                <div class="carousel-caption d-none d-md-block text-start" style="left:5%;right:auto;bottom:1.5rem;">
                                    <span class="banner-slide-badge">Mindfulness</span>
                                    <h2 class="banner-slide-title">Find Your Balance.</h2>
                                    <p class="banner-slide-sub">Yoga, meditation & flexibility training</p>
                                    <a href="#" class="btn-slide-cta">Learn More</a>
                                </div>
                            </div>

                        </div>

                        {{-- Controls --}}
                        <button class="carousel-control-prev" type="button" data-bs-target="#heroBannerCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#heroBannerCarousel" data-bs-slide="next">
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

    {{-- ══════════════════════════════════════════════
         PAGE CONTENT
    ══════════════════════════════════════════════ --}}
    <div class="mt-4">
        <h1 class="mb-2">Welcome to {{ config('app.name') }}</h1>
        <p class="text-muted">This is the homepage content.</p>
    </div>

@endsection

@push('styles')
<style>
/* ── Carousel caption elements ── */
.banner-slide-badge {
    display: inline-block;
    background: rgba(255,255,255,.2);
    backdrop-filter: blur(4px);
    border: 1px solid rgba(255,255,255,.5);
    border-radius: 2rem;
    font-size: .68rem;
    font-weight: 600;
    letter-spacing: .09em;
    text-transform: uppercase;
    padding: .25rem .85rem;
    margin-bottom: .5rem;
    color: #fff;
}

.banner-slide-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.8rem;
    font-weight: 600;
    line-height: 1.2;
    margin-bottom: .35rem;
    color: #fff;
    text-shadow: 0 1px 4px rgba(0,0,0,.35);
}

.banner-slide-sub {
    font-size: .85rem;
    color: rgba(255,255,255,.9);
    margin-bottom: .85rem;
    text-shadow: 0 1px 3px rgba(0,0,0,.3);
}

.btn-slide-cta {
    display: inline-block;
    background: #fff;
    color: #1a6644;
    font-size: .78rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .07em;
    padding: .5rem 1.4rem;
    border-radius: 2rem;
    text-decoration: none;
    transition: background .2s, transform .15s;
    box-shadow: 0 2px 8px rgba(0,0,0,.15);
}
.btn-slide-cta:hover {
    background: #f0faf5;
    color: #0f4a2e;
    transform: translateY(-1px);
}
</style>
@endpush

@push('scripts')
    <script>
        $(function () {
            console.log('Page-specific jQuery here');
        });
    </script>
@endpush