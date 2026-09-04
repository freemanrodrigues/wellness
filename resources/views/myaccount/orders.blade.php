@extends('layouts.app')

@section('title', 'My Orders - My Account')
@section('meta_description', 'View order history, status, tracking information, and invoices.')

@section('content')
<div class="container py-4">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('myaccount.home') }}" class="text-decoration-none text-muted">My Account</a></li>
            <li class="breadcrumb-item active text-success fw-semibold" aria-current="page">Orders</li>
        </ol>
    </nav>

    <div class="row g-4">
        {{-- Left Sidebar --}}
        <div class="col-lg-3 col-md-4">
            @include('myaccount.sidebar')
        </div>

        {{-- Main Orders Content --}}
        <div class="col-lg-9 col-md-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom p-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div>
                        <h4 class="fw-bold mb-1 text-dark"><i class="bi bi-bag-check text-success me-2"></i> Order History & Tracking</h4>
                        <p class="text-muted small mb-0">View all your placed wellness orders, live delivery statuses, and invoices.</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-secondary rounded-pill px-3 active">All Orders</button>
                        <button class="btn btn-sm btn-outline-secondary rounded-pill px-3">In Transit</button>
                        <button class="btn btn-sm btn-outline-secondary rounded-pill px-3">Delivered</button>
                    </div>
                </div>

                <div class="card-body p-4">
                    @if(!empty($orders) && count($orders) > 0)
                        @foreach($orders as $order)
                            <div class="card border rounded-3 mb-4 shadow-sm overflow-hidden">
                                <div class="card-header bg-light py-3 px-4 d-flex flex-wrap justify-content-between align-items-center gap-2">
                                    <div>
                                        <span class="text-muted small">Order ID:</span>
                                        <strong class="text-dark ms-1">{{ $order['id'] }}</strong>
                                        <span class="mx-2 text-muted">•</span>
                                        <span class="text-muted small">Placed on:</span>
                                        <span class="text-dark fw-medium ms-1">{{ $order['date'] }}</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="badge {{ $order['status_class'] }} px-3 py-2 rounded-pill fw-semibold" style="font-size: 0.8rem;">
                                            <i class="bi bi-truck me-1"></i> {{ $order['status'] }}
                                        </span>
                                        <span class="fw-bold text-dark fs-6">${{ number_format($order['total'], 2) }}</span>
                                    </div>
                                </div>

                                <div class="card-body p-4">
                                    <div class="row g-3 align-items-center">
                                        <div class="col-md-8">
                                            @foreach($order['items'] as $item)
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="rounded-3 border me-3 p-1 bg-white" style="width: 55px; height: 55px;">
                                                        <img src="{{ asset($item['img']) }}" alt="{{ $item['name'] }}" class="img-fluid rounded" style="object-fit: cover; width:100%; height:100%;">
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-semibold text-dark">{{ $item['name'] }}</h6>
                                                        <small class="text-muted">Qty: {{ $item['qty'] }} × ${{ number_format($item['price'], 2) }}</small>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="col-md-4 text-md-end pt-3 pt-md-0 border-top border-md-0">
                                            <p class="text-muted small mb-2"><i class="bi bi-credit-card me-1"></i> {{ $order['payment_method'] }}</p>
                                            <div class="d-grid gap-2">
                                                <button class="btn btn-sm btn-outline-success rounded-pill fw-medium">
                                                    <i class="bi bi-geo-alt me-1"></i> Track Order
                                                </button>
                                                <button class="btn btn-sm btn-light border text-dark rounded-pill fw-medium">
                                                    <i class="bi bi-download me-1"></i> Download Invoice
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-bag-x text-muted" style="font-size: 3.5rem;"></i>
                            <h5 class="fw-bold mt-3">No orders found</h5>
                            <p class="text-muted small">You haven't placed any orders yet. Start exploring our wellness store!</p>
                            <a href="{{ url('/') }}" class="btn btn-success rounded-pill px-4 fw-semibold mt-2">Explore Products</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
