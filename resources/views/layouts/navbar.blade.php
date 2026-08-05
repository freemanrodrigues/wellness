{{-- ══════════════════════════════════════════════════
     MAIN NAVBAR
══════════════════════════════════════════════════ --}}
<nav class="pf-topnav" id="mainNav">
    <div class="container">
        <div class="pf-topnav__inner">

            {{-- Brand --}}
            <a class="pf-brand" href="{{ url('/') }}">
                <img src="/images/logo.jpg" alt="{{ config('app.name') }}" style="height:38px;" class="me-2">
                <span>{{ config('app.name', 'Wellness') }}</span>
            </a>

            {{-- Right: auth links --}}
            <div class="pf-topnav__right d-none d-lg-flex align-items-center gap-3">
                @auth
                    <div class="dropdown">
                        <a class="pf-util-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="me-1" viewBox="0 0 16 16"><path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4"/></svg>
                            {{ auth()->user()->firstname }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">My Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">Sign Out</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a class="pf-util-link" href="{{ route('login') }}">Sign In</a>
                    <a class="pf-util-link" href="{{ route('register') }}">Register</a>
                @endauth

                <a class="pf-util-link" href="{{ route('about') }}">About</a>
                <a class="pf-util-link" href="{{ route('contact') }}">Contact</a>
            </div>

            {{-- Hamburger (mobile only) --}}
            <button class="pf-toggler d-lg-none" type="button"
                data-bs-toggle="collapse" data-bs-target="#mobileNav"
                aria-controls="mobileNav" aria-expanded="false" aria-label="Toggle navigation">
                <span></span><span></span><span></span>
            </button>

        </div>
    </div>
</nav>

{{-- ══════════════════════════════════════════════════
     DESKTOP CATEGORY BAR  (ProFlowers style)
     — plain <div>, always visible, no collapse
══════════════════════════════════════════════════ --}}
<div class="pf-catbar d-none d-lg-block" id="desktopCatBar">
    <div class="container">
        <ul class="pf-catbar__list list-unstyled mb-0 d-flex align-items-stretch justify-content-center">

            {{-- Nutrition & Diet --}}
            <li class="pf-cat-item dropdown">
                <a class="pf-cat-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                    Nutrition &amp; Diet
                </a>
                <div class="dropdown-menu pf-megamenu shadow-lg border-0 p-4">
                    <p class="pf-megamenu__heading">Nutrition &amp; Diet</p>
                    <ul class="list-unstyled">
                        <li><a class="pf-megamenu__link" href="#">Diet Plans</a></li>
                        <li><a class="pf-megamenu__link" href="#">Supplements</a></li>
                        <li><a class="pf-megamenu__link" href="#">Healthy Eating</a></li>
                    </ul>
                </div>
            </li>

            {{-- Fitness & Movement --}}
            <li class="pf-cat-item">
                <a class="pf-cat-link" href="#">Fitness &amp; Movement</a>
            </li>

            {{-- Workouts --}}
            <li class="pf-cat-item dropdown">
                <a class="pf-cat-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                    Workouts
                </a>
                <div class="dropdown-menu pf-megamenu shadow-lg border-0 p-4">
                    <p class="pf-megamenu__heading">Workouts</p>
                    <ul class="list-unstyled">
                        <li><a class="pf-megamenu__link" href="#">Yoga &amp; Flexibility</a></li>
                        <li><a class="pf-megamenu__link" href="#">Sports &amp; Outdoors</a></li>
                        <li><a class="pf-megamenu__link" href="#">Gear &amp; Equipment</a></li>
                    </ul>
                </div>
            </li>

            {{-- Yoga & Flexibility --}}
            <li class="pf-cat-item">
                <a class="pf-cat-link" href="#">Yoga &amp; Flexibility</a>
            </li>

            {{-- Sports & Outdoors --}}
            <li class="pf-cat-item">
                <a class="pf-cat-link" href="#">Sports &amp; Outdoors</a>
            </li>

            {{-- Gear & Equipment --}}
            <li class="pf-cat-item">
                <a class="pf-cat-link" href="#">Gear &amp; Equipment</a>
            </li>

        </ul>
    </div>
</div>

{{-- ══════════════════════════════════════════════════
     MOBILE FULL-SCREEN SIDE DRAWER
══════════════════════════════════════════════════ --}}
<div class="collapse" id="mobileNav">
    <div class="pf-mobile-drawer">
        <div class="pf-mobile-drawer__header d-flex align-items-center justify-content-between px-3 py-3">
            <span class="fw-semibold" style="font-size:.95rem;">Menu</span>
            <button class="btn-close" data-bs-toggle="collapse" data-bs-target="#mobileNav"></button>
        </div>

        {{-- Main links --}}
        <div class="pf-mobile-section">
            <a class="pf-mobile-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a>
            <a class="pf-mobile-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">About</a>
            <a class="pf-mobile-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact</a>
            @guest
                <a class="pf-mobile-link" href="{{ route('login') }}">Sign In</a>
                <a class="pf-mobile-link" href="{{ route('register') }}">Register</a>
            @endguest
            @auth
                <a class="pf-mobile-link" href="{{ route('profile.edit') }}">My Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="pf-mobile-link text-danger w-100 text-start border-0 bg-transparent">Sign Out</button>
                </form>
            @endauth
        </div>

        <div class="pf-mobile-divider"></div>
        <p class="pf-mobile-section-label px-3">Categories</p>

        {{-- Mobile categories --}}
        <div class="pf-mobile-section">

            {{-- Nutrition & Diet --}}
            <div>
                <button class="pf-mobile-cat-toggle collapsed w-100 text-start border-0 bg-transparent"
                    data-bs-toggle="collapse" data-bs-target="#mob-nutrition" aria-expanded="false">
                    Nutrition &amp; Diet
                    <svg class="pf-chevron" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 16 16" fill="currentColor"><path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708"/></svg>
                </button>
                <div class="collapse pf-mobile-subcats" id="mob-nutrition">
                    <a class="pf-mobile-sublink" href="#">Diet Plans</a>
                    <a class="pf-mobile-sublink" href="#">Supplements</a>
                    <a class="pf-mobile-sublink" href="#">Healthy Eating</a>
                </div>
            </div>

            <a class="pf-mobile-cat-link" href="#">Fitness &amp; Movement</a>

            {{-- Workouts --}}
            <div>
                <button class="pf-mobile-cat-toggle collapsed w-100 text-start border-0 bg-transparent"
                    data-bs-toggle="collapse" data-bs-target="#mob-workouts" aria-expanded="false">
                    Workouts
                    <svg class="pf-chevron" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 16 16" fill="currentColor"><path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708"/></svg>
                </button>
                <div class="collapse pf-mobile-subcats" id="mob-workouts">
                    <a class="pf-mobile-sublink" href="#">Yoga &amp; Flexibility</a>
                    <a class="pf-mobile-sublink" href="#">Sports &amp; Outdoors</a>
                    <a class="pf-mobile-sublink" href="#">Gear &amp; Equipment</a>
                </div>
            </div>

            <a class="pf-mobile-cat-link" href="#">Yoga &amp; Flexibility</a>
            <a class="pf-mobile-cat-link" href="#">Sports &amp; Outdoors</a>
            <a class="pf-mobile-cat-link" href="#">Gear &amp; Equipment</a>
        </div>

    </div>
</div>

@push('styles')
<style>
/* ═══════════════════════════════════════════════════
   FONT  — Playfair Display (headlines) + Inter (body)
   mirrors ProFlowers' serif + clean sans combination
═══════════════════════════════════════════════════ */
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Inter:wght@300;400;500;600&display=swap');

*, *::before, *::after { box-sizing: border-box; }

body {
    font-family: 'Inter', sans-serif;
    font-size: 15px;
    color: #1a1a1a;
}

h1, h2, h3, h4, h5 {
    font-family: 'Playfair Display', serif;
}

/* ═══════════════════════════════════════════════════
   TOP NAVBAR
═══════════════════════════════════════════════════ */
.pf-topnav {
    background: #f9f6f2;
    border-bottom: 1px solid #ede9e4;
    padding: .6rem 0;
    position: sticky;
    top: 0;
    z-index: 1030;
}

.pf-topnav__inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
}

.pf-brand {
    display: flex;
    align-items: center;
    text-decoration: none;
    font-family: 'Playfair Display', serif;
    font-size: 1.45rem;
    font-weight: 600;
    color: #1a1a1a;
    letter-spacing: -.01em;
}
.pf-brand:hover { color: #1a6644; }

.pf-util-link {
    font-size: .82rem;
    font-weight: 500;
    color: #444;
    text-decoration: none;
    transition: color .18s;
}
.pf-util-link:hover { color: #1a6644; }
.pf-util-link.dropdown-toggle::after { border-top-color: #444; }

/* Hamburger */
.pf-toggler {
    background: none;
    border: none;
    padding: 6px;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    gap: 5px;
}
.pf-toggler span {
    display: block;
    width: 24px;
    height: 2px;
    background: #1a1a1a;
    border-radius: 2px;
    transition: all .25s;
}

/* ═══════════════════════════════════════════════════
   DESKTOP CATEGORY BAR  (ProFlowers style)
   — cream/offwhite background, plain text links,
     subtle bottom-border hover underline
═══════════════════════════════════════════════════ */
.pf-catbar {
    background: #f9f6f2;
    border-bottom: 1px solid #ddd9d4;
}

.pf-catbar__list {
    gap: 0;
    min-height: 48px;
}

.pf-cat-item {
    display: flex;
    align-items: stretch;
}

.pf-cat-link {
    display: flex;
    align-items: center;
    font-family: 'Inter', sans-serif;
    font-size: .875rem;
    font-weight: 500;
    color: #1a1a1a;
    text-decoration: none;
    padding: 0 1.1rem;
    border-bottom: 2px solid transparent;
    white-space: nowrap;
    transition: color .18s, border-color .18s;
    background: none;
}

.pf-cat-link:hover,
.pf-cat-item.dropdown.show > .pf-cat-link {
    color: #1a6644;
    border-bottom-color: #1a6644;
}

.pf-cat-link.dropdown-toggle::after {
    margin-left: .35em;
    vertical-align: .18em;
    border-top-color: currentColor;
}

/* Mega menu panel */
.pf-megamenu {
    border-radius: .5rem;
    min-width: 200px;
    margin-top: 0 !important;
    border-top: 2px solid #1a6644 !important;
}

.pf-megamenu__heading {
    font-family: 'Playfair Display', serif;
    font-size: .95rem;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: .5rem;
    padding-bottom: .4rem;
    border-bottom: 1px solid #eee;
}

.pf-megamenu__link {
    display: block;
    font-size: .84rem;
    font-weight: 400;
    color: #444;
    text-decoration: none;
    padding: .35rem 0;
    transition: color .15s;
}
.pf-megamenu__link:hover { color: #1a6644; }

/* ═══════════════════════════════════════════════════
   MOBILE DRAWER
═══════════════════════════════════════════════════ */
.pf-mobile-drawer {
    background: #fff;
    border-bottom: 1px solid #dee2e6;
    box-shadow: 0 4px 16px rgba(0,0,0,.08);
}

.pf-mobile-drawer__header {
    border-bottom: 1px solid #f0ede8;
}

.pf-mobile-section {
    padding: .5rem .75rem;
}

.pf-mobile-divider {
    height: 1px;
    background: #f0ede8;
    margin: .25rem 0;
}

.pf-mobile-section-label {
    font-size: .68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .09em;
    color: #9ca3af;
    margin: .5rem 0 .25rem;
}

/* Main nav links inside mobile drawer */
.pf-mobile-link {
    display: block;
    font-size: .93rem;
    font-weight: 500;
    color: #1a1a1a;
    text-decoration: none;
    padding: .55rem .75rem;
    border-radius: .4rem;
    transition: background .15s, color .15s;
}
.pf-mobile-link:hover,
.pf-mobile-link.active { background: #f0f9f4; color: #1a6644; }

/* Category links in mobile drawer */
.pf-mobile-cat-link {
    display: block;
    font-size: .9rem;
    font-weight: 400;
    color: #374151;
    text-decoration: none;
    padding: .5rem .75rem;
    border-radius: .35rem;
    transition: background .15s;
}
.pf-mobile-cat-link:hover { background: #f0f9f4; color: #1a6644; }

.pf-mobile-cat-toggle {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: .9rem;
    font-weight: 400;
    color: #374151;
    padding: .5rem .75rem;
    border-radius: .35rem;
    cursor: pointer;
    transition: background .15s, color .15s;
}
.pf-mobile-cat-toggle:hover { background: #f0f9f4; color: #1a6644; }

.pf-chevron {
    transition: transform .22s ease;
    flex-shrink: 0;
}
.pf-mobile-cat-toggle[aria-expanded="true"] .pf-chevron {
    transform: rotate(90deg);
}

.pf-mobile-subcats {
    padding-left: 1rem;
    border-left: 2px solid #c7e8d5;
    margin: .15rem 0 .15rem 1rem;
}

.pf-mobile-sublink {
    display: block;
    font-size: .84rem;
    color: #4b5563;
    text-decoration: none;
    padding: .35rem .5rem;
    border-radius: .3rem;
    transition: background .15s, color .15s;
}
.pf-mobile-sublink:hover { background: #e9f7ef; color: #1a6644; }

/* ═══════════════════════════════════════════════════
   BANNER SECTION
═══════════════════════════════════════════════════ */
.banner-section {
    padding: 1rem 0 1.5rem;
}

.banner-carousel-wrap,
.banner-right-wrap {
    height: 300px;
    border-radius: .6rem;
    overflow: hidden;
}

.banner-carousel-wrap .carousel,
.banner-carousel-wrap .carousel-inner,
.banner-carousel-wrap .carousel-item {
    height: 100%;
}

.banner-carousel-wrap .carousel-item img {
    width: 100%;
    height: 300px;
    object-fit: cover;
}

/* Carousel controls */
.banner-carousel-wrap .carousel-control-prev,
.banner-carousel-wrap .carousel-control-next {
    width: 40px;
    height: 40px;
    background: rgba(255,255,255,.85);
    border-radius: 50%;
    top: 50%;
    transform: translateY(-50%);
    opacity: 1;
    box-shadow: 0 2px 8px rgba(0,0,0,.15);
}
.banner-carousel-wrap .carousel-control-prev { left: 12px; }
.banner-carousel-wrap .carousel-control-next { right: 12px; }

.banner-carousel-wrap .carousel-control-prev-icon,
.banner-carousel-wrap .carousel-control-next-icon {
    filter: invert(1) grayscale(1) brightness(0);
    width: 16px;
    height: 16px;
}

.banner-carousel-wrap .carousel-indicators [data-bs-target] {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: rgba(255,255,255,.7);
    border: none;
}
.banner-carousel-wrap .carousel-indicators .active {
    background: #fff;
}

/* Right banner */
.banner-right-wrap {
    position: relative;
    background: linear-gradient(135deg, #1a7f5a 0%, #0d5c3f 60%, #0a4531 100%);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 1.5rem;
    color: #fff;
}

.banner-right-wrap img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: .35;
}

.banner-right-wrap .banner-right-content {
    position: relative;
    z-index: 1;
}

.banner-right-wrap .banner-right-badge {
    display: inline-block;
    background: rgba(255,255,255,.2);
    border: 1px solid rgba(255,255,255,.4);
    border-radius: 2rem;
    font-size: .7rem;
    font-weight: 600;
    letter-spacing: .08em;
    text-transform: uppercase;
    padding: .25rem .85rem;
    margin-bottom: .75rem;
}

.banner-right-wrap h3 {
    font-family: 'Playfair Display', serif;
    font-size: 1.5rem;
    font-weight: 600;
    line-height: 1.25;
    margin-bottom: .5rem;
    color: #fff;
}

.banner-right-wrap p {
    font-size: .82rem;
    opacity: .85;
    margin-bottom: 1rem;
    line-height: 1.5;
}

.banner-right-wrap .btn-banner {
    background: #fff;
    color: #1a6644;
    font-size: .8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .06em;
    padding: .5rem 1.4rem;
    border-radius: 2rem;
    text-decoration: none;
    transition: background .2s, color .2s;
}
.banner-right-wrap .btn-banner:hover {
    background: #f0faf5;
    color: #0f4a2e;
}
</style>
@endpush