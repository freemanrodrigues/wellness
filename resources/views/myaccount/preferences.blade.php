@extends('layouts.app')

@section('title', 'Communication Preferences - My Account')
@section('meta_description', 'Manage your email, SMS, and marketing notification settings.')

@section('content')
<div class="container py-4">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('myaccount.home') }}" class="text-decoration-none text-muted">My Account</a></li>
            <li class="breadcrumb-item active text-success fw-semibold" aria-current="page">Communication Preferences</li>
        </ol>
    </nav>

    <div class="row g-4">
        {{-- Left Sidebar --}}
        <div class="col-lg-3 col-md-4">
            @include('myaccount.sidebar')
        </div>

        {{-- Main Preferences Content --}}
        <div class="col-lg-9 col-md-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom p-4">
                    <h4 class="fw-bold mb-1 text-dark"><i class="bi bi-sliders text-secondary me-2"></i> Communication & Notification Preferences</h4>
                    <p class="text-muted small mb-0">Control how and when we communicate order updates, discounts, and health tips with you.</p>
                </div>

                <div class="card-body p-4 p-md-5">
                    <form>
                        {{-- 1. Order & Shipment Notifications --}}
                        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-bell text-primary me-2"></i> Transactional & Order Alerts</h6>
                        <div class="list-group list-group-flush mb-4 border rounded-3 overflow-hidden">
                            <div class="list-group-item p-3 d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="mb-1 text-dark fw-semibold">Order Status Email Notifications</h6>
                                    <p class="text-muted small mb-0">Receive email confirmations, shipping tracking numbers, and delivery receipts.</p>
                                </div>
                                <div class="form-check form-switch fs-4">
                                    <input class="form-check-input" type="checkbox" id="prefOrderEmail" {{ ($preferences['order_updates_email'] ?? true) ? 'checked' : '' }}>
                                </div>
                            </div>
                            <div class="list-group-item p-3 d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="mb-1 text-dark fw-semibold">SMS Delivery Alerts</h6>
                                    <p class="text-muted small mb-0">Receive instant SMS text updates when your parcel is out for delivery.</p>
                                </div>
                                <div class="form-check form-switch fs-4">
                                    <input class="form-check-input" type="checkbox" id="prefOrderSms" {{ ($preferences['order_updates_sms'] ?? true) ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>

                        {{-- 2. Marketing & Promotions --}}
                        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-megaphone text-success me-2"></i> Offers & Marketing Updates</h6>
                        <div class="list-group list-group-flush mb-4 border rounded-3 overflow-hidden">
                            <div class="list-group-item p-3 d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="mb-1 text-dark fw-semibold">Promotional Discounts & Flash Sale Emails</h6>
                                    <p class="text-muted small mb-0">Get exclusive coupon codes, seasonal discounts, and member-only promotions.</p>
                                </div>
                                <div class="form-check form-switch fs-4">
                                    <input class="form-check-input" type="checkbox" id="prefPromo" {{ ($preferences['promotional_emails'] ?? true) ? 'checked' : '' }}>
                                </div>
                            </div>
                            <div class="list-group-item p-3 d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="mb-1 text-dark fw-semibold">Weekly Wellness Newsletter</h6>
                                    <p class="text-muted small mb-0">Expert health tips, organic product guides, and lifestyle advice.</p>
                                </div>
                                <div class="form-check form-switch fs-4">
                                    <input class="form-check-input" type="checkbox" id="prefNewsletter" {{ ($preferences['newsletter'] ?? false) ? 'checked' : '' }}>
                                </div>
                            </div>
                            <div class="list-group-item p-3 d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="mb-1 text-dark fw-semibold">Back-In-Stock & Price Drop Notifications</h6>
                                    <p class="text-muted small mb-0">Alerts when items in your wishlist or cart drop in price or return to stock.</p>
                                </div>
                                <div class="form-check form-switch fs-4">
                                    <input class="form-check-input" type="checkbox" id="prefStock" {{ ($preferences['back_in_stock'] ?? true) ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('myaccount.home') }}" class="btn btn-outline-secondary rounded-pill px-4">Cancel</a>
                            <button type="button" class="btn btn-success rounded-pill px-5 fw-bold shadow-sm" onclick="alert('Your preferences have been saved successfully!')">
                                <i class="bi bi-check2-circle me-1"></i> Save Preferences
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
