{{-- My Account Navigation Sidebar Component --}}
<div class="card shadow-sm border-0 rounded-3 mb-4 me-lg-2">
    <div class="card-body p-4">
        {{-- User Header Info --}}
        <div class="d-flex align-items-center pb-3 mb-3 border-bottom">
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold me-3 shadow-sm" style="width: 52px; height: 52px; font-size: 1.3rem; background: linear-gradient(135deg, #10b981 0%, #047857 100%) !important;">
                {{ strtoupper(substr($user->firstname ?? 'U', 0, 1)) }}
            </div>
            <div class="overflow-hidden">
                <h6 class="mb-0 text-truncate fw-bold text-dark fs-6">{{ $user->firstname ?? 'User' }} {{ $user->lastname ?? '' }}</h6>
                <small class="text-muted text-truncate d-block" style="font-size: 0.8rem;">{{ $user->email ?? '' }}</small>
                <span class="badge bg-soft-success text-success fw-semibold mt-1 px-2 py-0" style="font-size:0.7rem; background-color: #d1fae5;">Verified Account</span>
            </div>
        </div>

        {{-- Nav Links List --}}
        <div class="nav flex-column nav-pills myaccount-nav">
            <a class="nav-link d-flex align-items-center py-2.5 px-3 rounded-3 mb-1 {{ request()->routeIs('myaccount.home') ? 'active bg-primary text-white shadow-sm' : 'text-secondary hover-bg-light' }}"
               href="{{ route('myaccount.home') }}" id="navHome">
                <i class="bi bi-grid-fill me-3 fs-5"></i>
                <span class="fw-medium">Dashboard</span>
            </a>

            <a class="nav-link d-flex align-items-center py-2.5 px-3 rounded-3 mb-1 {{ request()->routeIs('myaccount.profile') ? 'active bg-primary text-white shadow-sm' : 'text-secondary hover-bg-light' }}"
               href="{{ route('myaccount.profile') }}" id="navProfile">
                <i class="bi bi-person-badge-fill me-3 fs-5"></i>
                <span class="fw-medium">Profile Details</span>
            </a>

            <a class="nav-link d-flex align-items-center py-2.5 px-3 rounded-3 mb-1 {{ request()->routeIs('myaccount.orders') ? 'active bg-primary text-white shadow-sm' : 'text-secondary hover-bg-light' }}"
               href="{{ route('myaccount.orders') }}" id="navOrders">
                <i class="bi bi-bag-check-fill me-3 fs-5"></i>
                <span class="fw-medium">My Orders</span>
                <span class="badge bg-light text-dark rounded-pill ms-auto small">3</span>
            </a>

            <a class="nav-link d-flex align-items-center py-2.5 px-3 rounded-3 mb-1 {{ request()->routeIs('myaccount.coupons') ? 'active bg-primary text-white shadow-sm' : 'text-secondary hover-bg-light' }}"
               href="{{ route('myaccount.coupons') }}" id="navCoupons">
                <i class="bi bi-ticket-perforated-fill me-3 fs-5"></i>
                <span class="fw-medium">Discount Coupons</span>
                <span class="badge bg-success rounded-pill ms-auto small">4</span>
            </a>

            <a class="nav-link d-flex align-items-center py-2.5 px-3 rounded-3 mb-1 {{ request()->routeIs('myaccount.wishlist') ? 'active bg-primary text-white shadow-sm' : 'text-secondary hover-bg-light' }}"
               href="{{ route('myaccount.wishlist') }}" id="navWishlist">
                <i class="bi bi-heart-fill me-3 fs-5"></i>
                <span class="fw-medium">Wishlist</span>
                <span class="badge bg-danger rounded-pill ms-auto small">5</span>
            </a>

            <a class="nav-link d-flex align-items-center py-2.5 px-3 rounded-3 mb-1 {{ request()->routeIs('myaccount.addresses') ? 'active bg-primary text-white shadow-sm' : 'text-secondary hover-bg-light' }}"
               href="{{ route('myaccount.addresses') }}" id="navAddresses">
                <i class="bi bi-geo-alt-fill me-3 fs-5"></i>
                <span class="fw-medium">Address Book</span>
            </a>

            <a class="nav-link d-flex align-items-center py-2.5 px-3 rounded-3 mb-1 {{ request()->routeIs('myaccount.preferences') ? 'active bg-primary text-white shadow-sm' : 'text-secondary hover-bg-light' }}"
               href="{{ route('myaccount.preferences') }}" id="navPreferences">
                <i class="bi bi-sliders me-3 fs-5"></i>
                <span class="fw-medium">Communication Prefs</span>
            </a>

            <a class="nav-link d-flex align-items-center py-2.5 px-3 rounded-3 mb-1 {{ request()->routeIs('myaccount.gift-cards') ? 'active bg-primary text-white shadow-sm' : 'text-secondary hover-bg-light' }}"
               href="{{ route('myaccount.gift-cards') }}" id="navGiftCards">
                <i class="bi bi-gift-fill me-3 fs-5"></i>
                <span class="fw-medium">Gift Cards & Wallet</span>
            </a>
        </div>

        <hr class="my-3">

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}" id="sidebarLogoutForm">
            @csrf
            <button type="submit" class="btn btn-outline-danger w-100 btn-sm rounded-3 py-2 fw-medium d-flex align-items-center justify-content-center">
                <i class="bi bi-box-arrow-right me-2"></i> Log Out
            </button>
        </form>
    </div>
</div>

<style>
.myaccount-nav .nav-link {
    transition: all 0.2s ease-in-out;
    color: #4b5563;
}
.myaccount-nav .nav-link:hover:not(.active) {
    background-color: #f3f4f6;
    color: #059669;
    transform: translateX(3px);
}
.myaccount-nav .nav-link.active {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
}
</style>
