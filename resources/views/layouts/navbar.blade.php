{{-- ══════════════════════════════════════════════════
     SITE HEADER WRAPPER  (sticky, two rows)
══════════════════════════════════════════════════ --}}
<header class="pf-site-header" id="siteHeader">

    {{-- ── ROW 1: TOP BAR ──────────────────────────────── --}}
    <div class="pf-topbar">
        <div class="container">
            <div class="pf-topbar__inner">

                {{-- Brand / Logo --}}
                <a class="pf-brand" href="{{ url('/') }}" id="brandLogo">
                    <img src="/images/logo.jpg" alt="{{ config('app.name') }}" style="height:38px;" class="me-2">
                    <span>{{ config('app.name', 'Wellness') }}</span>
                </a>

                {{-- Search bar (center) --}}
                <form class="pf-search" action="{{ url('/search') }}" method="GET" role="search" id="siteSearchForm">
                    <input
                        class="pf-search__input"
                        type="search"
                        name="q"
                        id="siteSearchInput"
                        placeholder="Search products, articles, tips…"
                        value="{{ request('q') }}"
                        autocomplete="off"
                        aria-label="Site search"
                    >
                    <button class="pf-search__btn" type="submit" id="siteSearchBtn" aria-label="Search">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.099zm-5.242 1.156a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11"/>
                        </svg>
                        <span class="pf-search__btn-label">Search</span>
                    </button>
                </form>

                {{-- Right: utility links --}}
                <div class="pf-topbar__right" id="topbarRight">

                    @auth
                        {{-- Logged-in: first name + dropdown --}}
                        <div class="dropdown pf-user-dropdown" id="userDropdownWrap">
                            <a class="pf-util-link pf-util-link--user dropdown-toggle"
                               href="#"
                               id="userDropdownToggle"
                               data-bs-toggle="dropdown"
                               aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="pf-util-icon" viewBox="0 0 16 16">
                                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4"/>
                                </svg>
                                <span class="pf-util-link__label">{{ auth()->user()->firstname }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end pf-user-menu shadow border-0 rounded-3" aria-labelledby="userDropdownToggle">
                                <li class="pf-user-menu__header">
                                    <span class="pf-user-menu__name">{{ auth()->user()->firstname }}</span>
                                </li>
                                <li><hr class="dropdown-divider my-1"></li>
                                <li>
                                    <a class="dropdown-item pf-user-menu__item" href="{{ route('profile.edit') }}" id="menuProfile">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                                            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4"/>
                                        </svg>
                                        Profile
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item pf-user-menu__item" href="#" id="menuMyAccount">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                                            <path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5zM2.5 2a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5zm6.5.5A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5zM1 10.5A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5zm6.5.5A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5z"/>
                                        </svg>
                                        My Account
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider my-1"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                                        @csrf
                                        <button type="submit" class="dropdown-item pf-user-menu__item pf-user-menu__item--danger" id="menuLogout">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
                                                <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
                                            </svg>
                                            Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        {{-- Guest: Sign In --}}
                        <a class="pf-util-link" href="{{ route('login') }}" id="signInLink">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" class="pf-util-icon" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M6 3.5a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 0-1 0v2A1.5 1.5 0 0 0 6.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-8A1.5 1.5 0 0 0 5 3.5v2a.5.5 0 0 0 1 0z"/>
                                <path fill-rule="evenodd" d="M11.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L10.293 7.5H1.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
                            </svg>
                            <span class="pf-util-link__label">Sign In</span>
                        </a>
                    @endauth

                    {{-- Orders --}}
                    <a class="pf-util-link" href="#" id="ordersLink">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" class="pf-util-icon" viewBox="0 0 16 16">
                            <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                        </svg>
                        <span class="pf-util-link__label">Orders</span>
                    </a>

                    {{-- Cart --}}
                    <a class="pf-util-link pf-util-link--cart" href="#" id="cartLink">
                        <span class="pf-cart-wrap">
                            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="currentColor" class="pf-util-icon" viewBox="0 0 16 16">
                                <path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1m3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1z"/>
                            </svg>
                            {{-- Uncomment and wire up when cart feature is ready --}}
                            {{-- <span class="pf-cart-badge">0</span> --}}
                        </span>
                        <span class="pf-util-link__label">Cart</span>
                    </a>

                </div>{{-- /.pf-topbar__right --}}

                {{-- Hamburger (mobile only) --}}
                <button class="pf-toggler d-lg-none" type="button"
                    data-bs-toggle="collapse" data-bs-target="#mobileNav"
                    aria-controls="mobileNav" aria-expanded="false" aria-label="Toggle navigation"
                    id="mobileMenuToggle">
                    <span></span><span></span><span></span>
                </button>

            </div>{{-- /.pf-topbar__inner --}}
        </div>{{-- /.container --}}
    </div>{{-- /.pf-topbar --}}

    {{-- ── ROW 2: CATEGORY BAR (desktop only) ─────────── --}}
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
    </div>{{-- /.pf-catbar --}}

</header>{{-- /.pf-site-header --}}

{{-- ══════════════════════════════════════════════════
     MOBILE FULL-SCREEN SIDE DRAWER
══════════════════════════════════════════════════ --}}
<div class="collapse" id="mobileNav">
    <div class="pf-mobile-drawer">
        <div class="pf-mobile-drawer__header d-flex align-items-center justify-content-between px-3 py-3">
            <span class="fw-semibold" style="font-size:.95rem;">Menu</span>
            <button class="btn-close" data-bs-toggle="collapse" data-bs-target="#mobileNav"></button>
        </div>

        {{-- Mobile search --}}
        <div class="pf-mobile-section px-3 pb-2">
            <form class="pf-search pf-search--mobile" action="{{ url('/search') }}" method="GET" role="search">
                <input class="pf-search__input" type="search" name="q" placeholder="Search…" value="{{ request('q') }}" autocomplete="off" aria-label="Site search">
                <button class="pf-search__btn" type="submit" aria-label="Search">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.099zm-5.242 1.156a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11"/>
                    </svg>
                </button>
            </form>
        </div>

        {{-- Main links --}}
        <div class="pf-mobile-section">
            <a class="pf-mobile-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a>
            <a class="pf-mobile-link" href="#">Orders</a>
            <a class="pf-mobile-link" href="#">Cart</a>
            @guest
                <a class="pf-mobile-link" href="{{ route('login') }}">Sign In</a>
                <a class="pf-mobile-link" href="{{ route('register') }}">Register</a>
            @endguest
            @auth
                <a class="pf-mobile-link" href="{{ route('profile.edit') }}">Profile</a>
                <a class="pf-mobile-link" href="#">My Account</a>
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

{{-- All CSS is in layouts/header.blade.php --}}