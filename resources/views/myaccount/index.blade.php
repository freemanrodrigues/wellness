@extends('layouts.app')

@section('title', 'My Account - Dashboard')
@section('meta_description', 'Manage your wellness account profile, orders, coupons, wishlist, addresses, and preferences.')

@section('content')
<div class="container py-4">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item active text-success fw-semibold" aria-current="page">My Account</li>
        </ol>
    </nav>

    {{-- Welcome Banner --}}
    <div class="card border-0 rounded-4 shadow-sm mb-4 bg-white overflow-hidden position-relative" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%);">
        <div class="card-body p-4 p-md-5 text-white position-relative" style="z-index: 2;">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <span class="badge bg-white text-success px-3 py-1 mb-2 rounded-pill fw-semibold shadow-sm" style="font-size:0.75rem;">Account Overview</span>
                    <h1 class="h2 text-white fw-bold mb-2">Welcome back, {{ $user->firstname ?? 'Valued Customer' }}!</h1>
                    <p class="text-white-50 mb-0 fs-6">Manage your orders, personal details, discount coupons, wishlist, and notification preferences all in one place.</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <a href="{{ route('myaccount.profile') }}" class="btn btn-light text-success fw-bold rounded-pill px-4 py-2 shadow-sm hover-lift">
                        <i class="bi bi-pencil-square me-2"></i> Edit Profile
                    </a>
                </div>
            </div>
        </div>
        <div class="position-absolute end-0 bottom-0 opacity-25 pe-4 pb-2 d-none d-md-block" style="z-index: 1;">
            <i class="bi bi-person-circle" style="font-size: 14rem; line-height: 0; color: #ffffff;"></i>
        </div>
    </div>

    <div class="row g-4">
        {{-- Left Sidebar --}}
        <div class="col-lg-3 col-md-4">
            @include('myaccount.sidebar')
        </div>

        {{-- Main Content Grid --}}
        <div class="col-lg-9 col-md-8">

            <div class="d-flex align-items-center justify-content-between mb-3">
                <h4 class="fw-bold mb-0 text-dark">Quick Navigation Cards</h4>
                <small class="text-muted">7 Account Management Modules</small>
            </div>

            {{-- 7 Cards Grid --}}
            <div class="row g-4">

                {{-- 1. Profile Card --}}
                <div class="col-md-6 col-xl-4">
                    <div class="card h-100 border-0 shadow-sm rounded-3 myaccount-card hover-lift">
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="icon-shape bg-primary-soft text-primary rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background-color: #e0e7ff; color: #4f46e5;">
                                    <i class="bi bi-person-vcard fs-3"></i>
                                </div>
                                <span class="badge bg-light text-secondary rounded-pill px-2.5 py-1">Personal Info</span>
                            </div>
                            <h5 class="card-title fw-bold mb-2 text-dark">Profile</h5>
                            <p class="card-text text-muted small flex-grow-1 mb-3">View and edit your personal details, contact information, and security password settings.</p>
                            <a href="{{ route('myaccount.profile') }}" class="btn btn-outline-primary rounded-pill w-100 fw-semibold btn-sm mt-auto" id="cardProfileLink">
                                Manage Profile <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- 2. Orders Card --}}
                <div class="col-md-6 col-xl-4">
                    <div class="card h-100 border-0 shadow-sm rounded-3 myaccount-card hover-lift">
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="icon-shape bg-success-soft text-success rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background-color: #d1fae5; color: #059669;">
                                    <i class="bi bi-bag-check fs-3"></i>
                                </div>
                                <span class="badge bg-success text-white rounded-pill px-2.5 py-1">{{ $stats['orders_count'] ?? 3 }} Orders</span>
                            </div>
                            <h5 class="card-title fw-bold mb-2 text-dark">Orders</h5>
                            <p class="card-text text-muted small flex-grow-1 mb-3">Track live shipments, review order history details, download tax invoices, and reorder.</p>
                            <a href="{{ route('myaccount.orders') }}" class="btn btn-outline-success rounded-pill w-100 fw-semibold btn-sm mt-auto" id="cardOrdersLink">
                                View Orders <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- 3. Discount Coupon Card --}}
                <div class="col-md-6 col-xl-4">
                    <div class="card h-100 border-0 shadow-sm rounded-3 myaccount-card hover-lift">
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="icon-shape bg-warning-soft text-warning rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background-color: #fef3c7; color: #d97706;">
                                    <i class="bi bi-ticket-perforated fs-3"></i>
                                </div>
                                <span class="badge bg-warning text-dark rounded-pill px-2.5 py-1">{{ $stats['coupons_count'] ?? 4 }} Available</span>
                            </div>
                            <h5 class="card-title fw-bold mb-2 text-dark">Discount Coupon</h5>
                            <p class="card-text text-muted small flex-grow-1 mb-3">Explore active promo codes, reward vouchers, and special discount deals for your cart.</p>
                            <a href="{{ route('myaccount.coupons') }}" class="btn btn-outline-warning text-dark rounded-pill w-100 fw-semibold btn-sm mt-auto" id="cardCouponsLink">
                                My Coupons <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- 4. Wishlist Card --}}
                <div class="col-md-6 col-xl-4">
                    <div class="card h-100 border-0 shadow-sm rounded-3 myaccount-card hover-lift">
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="icon-shape bg-danger-soft text-danger rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background-color: #ffe4e6; color: #e11d48;">
                                    <i class="bi bi-heart fs-3"></i>
                                </div>
                                <span class="badge bg-danger text-white rounded-pill px-2.5 py-1">{{ $stats['wishlist_count'] ?? 5 }} Saved</span>
                            </div>
                            <h5 class="card-title fw-bold mb-2 text-dark">Wishlist</h5>
                            <p class="card-text text-muted small flex-grow-1 mb-3">Saved favorite wellness products. Quick add to cart, check stock status and price drops.</p>
                            <a href="{{ route('myaccount.wishlist') }}" class="btn btn-outline-danger rounded-pill w-100 fw-semibold btn-sm mt-auto" id="cardWishlistLink">
                                View Wishlist <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- 5. Address Book Card --}}
                <div class="col-md-6 col-xl-4">
                    <div class="card h-100 border-0 shadow-sm rounded-3 myaccount-card hover-lift">
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="icon-shape bg-info-soft text-info rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background-color: #cff4fc; color: #0891b2;">
                                    <i class="bi bi-geo-alt fs-3"></i>
                                </div>
                                <span class="badge bg-info text-dark rounded-pill px-2.5 py-1">{{ $stats['addresses_count'] ?? 2 }} Saved</span>
                            </div>
                            <h5 class="card-title fw-bold mb-2 text-dark">Address Book</h5>
                            <p class="card-text text-muted small flex-grow-1 mb-3">Add, edit, or delete shipping and billing addresses for fast and seamless checkout.</p>
                            <a href="{{ route('myaccount.addresses') }}" class="btn btn-outline-info text-dark rounded-pill w-100 fw-semibold btn-sm mt-auto" id="cardAddressesLink">
                                Address Book <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- 6. Communication Preferences Card --}}
                <div class="col-md-6 col-xl-4">
                    <div class="card h-100 border-0 shadow-sm rounded-3 myaccount-card hover-lift">
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="icon-shape bg-secondary-soft text-dark rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background-color: #f3f4f6; color: #374151;">
                                    <i class="bi bi-sliders fs-3"></i>
                                </div>
                                <span class="badge bg-secondary text-white rounded-pill px-2.5 py-1">Settings</span>
                            </div>
                            <h5 class="card-title fw-bold mb-2 text-dark">Communication Preferences</h5>
                            <p class="card-text text-muted small flex-grow-1 mb-3">Customize email, SMS, order updates, and marketing newsletter notifications settings.</p>
                            <a href="{{ route('myaccount.preferences') }}" class="btn btn-outline-secondary rounded-pill w-100 fw-semibold btn-sm mt-auto" id="cardPreferencesLink">
                                Preferences <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- 7. Gift Cards Card --}}
                <div class="col-md-6 col-xl-4">
                    <div class="card h-100 border-0 shadow-sm rounded-3 myaccount-card hover-lift">
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="icon-shape bg-purple-soft rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background-color: #f3e8ff; color: #9333ea;">
                                    <i class="bi bi-gift fs-3"></i>
                                </div>
                                <span class="badge bg-purple text-white rounded-pill px-2.5 py-1" style="background-color: #9333ea;">${{ number_format($stats['gift_balance'] ?? 150, 2) }}</span>
                            </div>
                            <h5 class="card-title fw-bold mb-2 text-dark">Gift Cards</h5>
                            <p class="card-text text-muted small flex-grow-1 mb-3">Redeem gift card codes, check remaining store credit balance, and send gifts to friends.</p>
                            <a href="{{ route('myaccount.gift-cards') }}" class="btn btn-outline-purple rounded-pill w-100 fw-semibold btn-sm mt-auto" style="border-color: #9333ea; color: #9333ea;" id="cardGiftCardsLink">
                                Gift Cards & Wallet <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

<style>
.myaccount-card {
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}
.myaccount-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08) !important;
}
.btn-outline-purple:hover {
    background-color: #9333ea !important;
    color: #ffffff !important;
}
</style>
@endsection
