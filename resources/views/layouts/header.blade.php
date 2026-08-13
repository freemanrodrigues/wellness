<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>
    <meta name="description" content="@yield('meta_description', '')">

    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://code.jquery.com" crossorigin>

    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    {{-- Google Fonts: Inter (body) + Playfair Display (headings) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @stack('styles')

    <style>
/* ═══════════════════════════════════════════════════
   FONT  — Playfair Display (headlines) + Inter (body)
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
   SITE HEADER WRAPPER
═══════════════════════════════════════════════════ */
.pf-site-header {
    position: sticky;
    top: 0;
    z-index: 1030;
    background: #f9f6f2;
    box-shadow: 0 2px 12px rgba(0,0,0,.07);
}

/* ═══════════════════════════════════════════════════
   ROW 1 — TOP BAR
═══════════════════════════════════════════════════ */
.pf-topbar {
    background: #f9f6f2;
    border-bottom: 1px solid #ede9e4;
    padding: .55rem 0;
}

.pf-topbar__inner {
    display: flex;
    align-items: center;
    gap: 1rem;
}

/* Brand */
.pf-brand {
    display: flex;
    align-items: center;
    text-decoration: none;
    font-family: 'Playfair Display', serif;
    font-size: 1.45rem;
    font-weight: 600;
    color: #1a1a1a;
    letter-spacing: -.01em;
    white-space: nowrap;
    flex-shrink: 0;
}
.pf-brand:hover { color: #1a6644; }

/* Search bar */
.pf-search {
    flex: 1;
    display: flex;
    align-items: center;
    background: #fff;
    border: 1.5px solid #ddd9d4;
    border-radius: 2rem;
    overflow: hidden;
    transition: border-color .2s, box-shadow .2s;
    min-width: 0;
}
.pf-search:focus-within {
    border-color: #1a6644;
    box-shadow: 0 0 0 3px rgba(26,102,68,.12);
}
.pf-search__input {
    flex: 1;
    border: none;
    outline: none;
    background: transparent;
    font-family: 'Inter', sans-serif;
    font-size: .84rem;
    color: #1a1a1a;
    padding: .5rem 1rem;
    min-width: 0;
}
.pf-search__input::placeholder { color: #9ca3af; }
.pf-search__btn {
    display: flex;
    align-items: center;
    gap: .35rem;
    background: #1a6644;
    border: none;
    color: #fff;
    font-family: 'Inter', sans-serif;
    font-size: .8rem;
    font-weight: 600;
    padding: .5rem 1rem;
    cursor: pointer;
    white-space: nowrap;
    transition: background .18s;
    flex-shrink: 0;
}
.pf-search__btn:hover { background: #155537; }
.pf-search__btn-label { display: inline; }

/* Mobile search variant */
.pf-search--mobile {
    flex: none;
    border-radius: .5rem;
    width: 100%;
}

/* Right utility links */
.pf-topbar__right {
    display: flex;
    align-items: center;
    gap: .15rem;
    flex-shrink: 0;
}

.pf-util-link {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: .18rem;
    font-size: .7rem;
    font-weight: 600;
    color: #444;
    text-decoration: none;
    padding: .3rem .55rem;
    border-radius: .4rem;
    transition: color .18s, background .18s;
    white-space: nowrap;
    letter-spacing: .01em;
    text-transform: uppercase;
    cursor: pointer;
    background: none;
    border: none;
}
.pf-util-link:hover,
.pf-util-link:focus { color: #1a6644; background: #f0f9f4; }

.pf-util-icon { flex-shrink: 0; }
.pf-util-link__label { line-height: 1; }

/* User dropdown trigger */
.pf-util-link--user { color: #1a6644; }
.pf-util-link--user:hover { color: #0f4a2e; }
.pf-util-link--user.dropdown-toggle::after { display: none; }

/* Cart badge */
.pf-cart-wrap {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}
.pf-cart-badge {
    position: absolute;
    top: -6px; right: -8px;
    background: #e53e3e;
    color: #fff;
    font-size: .6rem;
    font-weight: 700;
    border-radius: 2rem;
    padding: 1px 5px;
    line-height: 1.3;
    min-width: 16px;
    text-align: center;
}

/* User dropdown menu */
.pf-user-menu {
    min-width: 190px;
    padding: .5rem 0;
    border-top: 2px solid #1a6644 !important;
    margin-top: .4rem !important;
}
.pf-user-menu__header { padding: .4rem 1rem .5rem; }
.pf-user-menu__name {
    font-family: 'Playfair Display', serif;
    font-size: .95rem;
    font-weight: 600;
    color: #1a1a1a;
}
.pf-user-menu__item {
    font-size: .84rem;
    color: #374151;
    padding: .45rem 1rem;
    display: flex;
    align-items: center;
    transition: background .15s, color .15s;
}
.pf-user-menu__item:hover { background: #f0f9f4; color: #1a6644; }
.pf-user-menu__item--danger { color: #c53030 !important; }
.pf-user-menu__item--danger:hover { background: #fff5f5 !important; color: #9b2c2c !important; }

/* Hamburger */
.pf-toggler {
    background: none;
    border: none;
    padding: 6px;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    gap: 5px;
    flex-shrink: 0;
    margin-left: auto;
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
   ROW 2 — DESKTOP CATEGORY BAR
═══════════════════════════════════════════════════ */
.pf-catbar {
    background: #f9f6f2;
    border-bottom: 1px solid #ddd9d4;
}
.pf-catbar__list { gap: 0; min-height: 46px; }
.pf-cat-item { display: flex; align-items: stretch; }
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

/* Mega menu */
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
.pf-mobile-drawer__header { border-bottom: 1px solid #f0ede8; }
.pf-mobile-section { padding: .5rem .75rem; }
.pf-mobile-divider { height: 1px; background: #f0ede8; margin: .25rem 0; }
.pf-mobile-section-label {
    font-size: .68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .09em;
    color: #9ca3af;
    margin: .5rem 0 .25rem;
}
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
.pf-mobile-cat-link {
    display: block;
    font-size: .9rem;
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
    color: #374151;
    padding: .5rem .75rem;
    border-radius: .35rem;
    cursor: pointer;
    transition: background .15s, color .15s;
}
.pf-mobile-cat-toggle:hover { background: #f0f9f4; color: #1a6644; }
.pf-chevron { transition: transform .22s ease; flex-shrink: 0; }
.pf-mobile-cat-toggle[aria-expanded="true"] .pf-chevron { transform: rotate(90deg); }
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
   RESPONSIVE
═══════════════════════════════════════════════════ */
@media (max-width: 991.98px) {
    .pf-topbar__right { display: none; }
    .pf-search { display: none; }
}
@media (min-width: 992px) {
    .pf-toggler { display: none; }
}

/* ═══════════════════════════════════════════════════
   FOOTER
═══════════════════════════════════════════════════ */
.pf-footer {
    background: #1a1a1a;
    padding: 1.5rem 0;
    margin-top: 3rem;
}
.pf-footer__inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
}
.pf-footer__brand { display: flex; flex-direction: column; gap: .35rem; }
.pf-footer__logo {
    display: flex;
    align-items: center;
    text-decoration: none;
    font-family: 'Playfair Display', serif;
    font-size: 1.1rem;
    font-weight: 600;
    color: #fff;
    opacity: .9;
}
.pf-footer__logo:hover { opacity: 1; color: #6ee7b0; }
.pf-footer__copy { font-size: .78rem; color: rgba(255,255,255,.45); margin: 0; }
.pf-footer__nav { display: flex; align-items: center; gap: .5rem; flex-wrap: wrap; }
.pf-footer__link {
    font-size: .8rem;
    font-weight: 500;
    color: rgba(255,255,255,.55);
    text-decoration: none;
    transition: color .18s;
}
.pf-footer__link:hover { color: #6ee7b0; }
.pf-footer__sep { color: rgba(255,255,255,.25); font-size: .8rem; }

/* ═══════════════════════════════════════════════════
   BANNER SECTION
═══════════════════════════════════════════════════ */
.banner-section { padding: 1rem 0 1.5rem; }
.banner-carousel-wrap, .banner-right-wrap {
    height: 300px; border-radius: .6rem; overflow: hidden;
}
.banner-carousel-wrap .carousel,
.banner-carousel-wrap .carousel-inner,
.banner-carousel-wrap .carousel-item { height: 100%; }
.banner-carousel-wrap .carousel-item img { width: 100%; height: 300px; object-fit: cover; }
.banner-carousel-wrap .carousel-control-prev,
.banner-carousel-wrap .carousel-control-next {
    width: 40px; height: 40px;
    background: rgba(255,255,255,.85);
    border-radius: 50%; top: 50%; transform: translateY(-50%);
    opacity: 1; box-shadow: 0 2px 8px rgba(0,0,0,.15);
}
.banner-carousel-wrap .carousel-control-prev { left: 12px; }
.banner-carousel-wrap .carousel-control-next { right: 12px; }
.banner-carousel-wrap .carousel-control-prev-icon,
.banner-carousel-wrap .carousel-control-next-icon {
    filter: invert(1) grayscale(1) brightness(0); width: 16px; height: 16px;
}
.banner-carousel-wrap .carousel-indicators [data-bs-target] {
    width: 8px; height: 8px; border-radius: 50%;
    background: rgba(255,255,255,.7); border: none;
}
.banner-carousel-wrap .carousel-indicators .active { background: #fff; }
.banner-right-wrap {
    position: relative;
    background: linear-gradient(135deg, #1a7f5a 0%, #0d5c3f 60%, #0a4531 100%);
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    text-align: center; padding: 1.5rem; color: #fff;
}
.banner-right-wrap img {
    position: absolute; inset: 0;
    width: 100%; height: 100%; object-fit: cover; opacity: .35;
}
.banner-right-wrap .banner-right-content { position: relative; z-index: 1; }
.banner-right-wrap .banner-right-badge {
    display: inline-block;
    background: rgba(255,255,255,.2); border: 1px solid rgba(255,255,255,.4);
    border-radius: 2rem; font-size: .7rem; font-weight: 600;
    letter-spacing: .08em; text-transform: uppercase;
    padding: .25rem .85rem; margin-bottom: .75rem;
}
.banner-right-wrap h3 {
    font-family: 'Playfair Display', serif;
    font-size: 1.5rem; font-weight: 600; line-height: 1.25;
    margin-bottom: .5rem; color: #fff;
}
.banner-right-wrap p { font-size: .82rem; opacity: .85; margin-bottom: 1rem; line-height: 1.5; }
.banner-right-wrap .btn-banner {
    background: #fff; color: #1a6644; font-size: .8rem; font-weight: 600;
    text-transform: uppercase; letter-spacing: .06em;
    padding: .5rem 1.4rem; border-radius: 2rem;
    text-decoration: none; transition: background .2s, color .2s;
}
.banner-right-wrap .btn-banner:hover { background: #f0faf5; color: #0f4a2e; }
    </style>
</head>

<body>