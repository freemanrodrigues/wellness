@extends('layouts.app')

@section('title', 'Discount Coupons - My Account')
@section('meta_description', 'View available promo codes, reward vouchers, and discount coupons.')

@section('content')
<div class="container py-4">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('myaccount.home') }}" class="text-decoration-none text-muted">My Account</a></li>
            <li class="breadcrumb-item active text-success fw-semibold" aria-current="page">Discount Coupons</li>
        </ol>
    </nav>

    <div class="row g-4">
        {{-- Left Sidebar --}}
        <div class="col-lg-3 col-md-4">
            @include('myaccount.sidebar')
        </div>

        {{-- Main Coupons Content --}}
        <div class="col-lg-9 col-md-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="fw-bold mb-1 text-dark"><i class="bi bi-ticket-perforated text-warning me-2"></i> Discount Coupons & Vouchers</h4>
                        <p class="text-muted small mb-0">Apply these exclusive promo codes at checkout to unlock savings on your orders.</p>
                    </div>
                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold" style="font-size:0.85rem;">{{ count($coupons) }} Active Vouchers</span>
                </div>

                <div class="card-body p-4">

                    {{-- Toast Notification --}}
                    <div id="copyToast" class="alert alert-success border-0 shadow position-fixed bottom-0 end-0 m-4 rounded-3 d-none" style="z-index: 1050;" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> Coupon code copied to clipboard!
                    </div>

                    <div class="row g-4">
                        @foreach($coupons as $coupon)
                            <div class="col-md-6">
                                <div class="card border-dashed-custom rounded-3 h-100 p-4 shadow-sm bg-light-soft position-relative overflow-hidden" style="border: 2px dashed #cbd5e1; background-color: #f8fafc;">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <span class="badge {{ $coupon['badge_class'] }} px-3 py-1.5 rounded-pill fw-semibold" style="font-size: 0.75rem;">
                                            {{ $coupon['badge'] }}
                                        </span>
                                        <small class="text-muted"><i class="bi bi-clock me-1"></i> Expires: {{ $coupon['expiry'] }}</small>
                                    </div>

                                    <h4 class="fw-bold text-success mb-1">{{ $coupon['discount'] }}</h4>
                                    <h6 class="fw-bold text-dark mb-2">{{ $coupon['title'] }}</h6>
                                    <p class="text-muted small mb-3 flex-grow-1">{{ $coupon['description'] }}</p>

                                    <div class="pt-3 border-top d-flex align-items-center justify-content-between gap-2">
                                        <div>
                                            <span class="text-muted d-block" style="font-size: 0.7rem;">COUPON CODE</span>
                                            <span class="fw-bold text-dark font-monospace fs-5" id="code-{{ $loop->index }}">{{ $coupon['code'] }}</span>
                                        </div>
                                        <button type="button" class="btn btn-outline-success btn-sm rounded-pill px-3 fw-bold copy-btn" onclick="copyCouponCode('{{ $coupon['code'] }}')">
                                            <i class="bi bi-clipboard me-1"></i> Copy Code
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyCouponCode(code) {
    navigator.clipboard.writeText(code).then(function() {
        var toast = document.getElementById('copyToast');
        toast.classList.remove('d-none');
        setTimeout(function() {
            toast.classList.add('d-none');
        }, 2500);
    });
}
</script>
@endsection
