@extends('layouts.app')

@section('title', $meta['title'] ?? 'Order Confirmation')
@section('meta_description', $meta['description'] ?? 'Thank you for your order')

@section('content')

<div class="container py-5 text-center">

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm p-4 p-md-5">
                <div class="card-body">

                    <div class="mb-4">
                        <div class="icon-box bg-success-subtle text-success rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 80px; height: 80px;">
                            <i class="bi bi-check-circle-fill display-4"></i>
                        </div>
                    </div>

                    <h1 class="h3 fw-bold text-dark mb-2">Order Confirmed!</h1>
                    <p class="text-muted mb-4">Thank you for your purchase. We’ve received your order and are getting it ready for delivery.</p>

                    @if(isset($orderNumber))
                        <div class="bg-light border rounded p-3 mb-4 d-inline-block">
                            <span class="text-muted small d-block">Order Number</span>
                            <span class="fw-bold fs-5 text-primary">#{{ $orderNumber }}</span>
                        </div>
                    @endif

                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('product-listing') }}" class="btn btn-primary px-4 py-2 fw-semibold">
                            <i class="bi bi-shop me-1"></i> Continue Shopping
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary px-4 py-2">
                            Home Page
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>

@endsection
