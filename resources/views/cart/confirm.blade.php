@extends('layouts.app')

@section('title', $meta['title'] ?? 'Order Confirmation')
@section('meta_description', $meta['description'] ?? 'Thank you for your order')

@section('content')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm p-4 p-md-5 rounded-3">
                <div class="card-body text-center">

                    <div class="mb-4">
                        <div class="icon-box bg-success-subtle text-success rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 80px; height: 80px;">
                            <i class="bi bi-check-circle-fill display-4"></i>
                        </div>
                    </div>

                    <h1 class="h3 fw-bold text-dark mb-2">Order Placed Successfully!</h1>
                    <p class="text-muted mb-4">Thank you for your purchase. Your order summary has been saved and is being processed.</p>

                    @if(isset($orderId))
                        <div class="bg-light border rounded-3 p-3 mb-4 d-inline-block text-start" style="min-width: 280px;">
                            <div class="d-flex justify-content-between align-items-center gap-4 mb-2 border-bottom pb-2">
                                <span class="text-muted small">Order ID</span>
                                <strong class="text-primary font-monospace fs-6">{{ $orderId }}</strong>
                            </div>
                            @if(isset($invoice))
                                <div class="d-flex justify-content-between align-items-center gap-4 mb-2 border-bottom pb-2">
                                    <span class="text-muted small">Total Amount</span>
                                    <strong class="text-dark fs-6">${{ number_format($invoice->totalamount, 2) }}</strong>
                                </div>
                                <div class="d-flex justify-content-between align-items-center gap-4 mb-2 border-bottom pb-2">
                                    <span class="text-muted small">Payment Method</span>
                                    <span class="badge bg-primary text-capitalize px-3 py-1">{{ $invoice->cardname ?? 'PayPal' }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center gap-4">
                                    <span class="text-muted small">Status</span>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1">{{ $invoice->orderstatus }}</span>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Items from fin_basket --}}
                    @if(isset($finItems) && $finItems->isNotEmpty())
                        <div class="text-start mb-4 border rounded-3 p-3 bg-white">
                            <h6 class="fw-bold mb-3 text-dark border-bottom pb-2">
                                <i class="bi bi-bag-check me-2 text-success"></i>Purchased Items ({{ $finItems->count() }})
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-borderless table-sm align-middle mb-0">
                                    <thead>
                                        <tr class="text-muted small border-bottom">
                                            <th>Product</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-end">Price</th>
                                            <th class="text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($finItems as $item)
                                            <tr class="border-bottom border-light">
                                                <td class="fw-semibold text-dark">{{ $item->product_name }}</td>
                                                <td class="text-center">{{ $item->qty }}</td>
                                                <td class="text-end">${{ number_format($item->product_price, 2) }}</td>
                                                <td class="text-end fw-bold">${{ number_format($item->product_price * $item->qty, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            @if($finItems->first()->s_firstname)
                                <div class="mt-3 pt-3 border-top small text-muted">
                                    <strong class="text-dark">Shipping Address:</strong> 
                                    {{ $finItems->first()->s_firstname }} {{ $finItems->first()->s_lastname }}, 
                                    {{ $finItems->first()->s_address1 }}, {{ $finItems->first()->s_city }}, {{ $finItems->first()->s_state }} - {{ $finItems->first()->s_pincode }} 
                                    (Phone: {{ $finItems->first()->s_phone }})
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="d-flex justify-content-center gap-3 mt-4">
                        <a href="{{ route('product-listing.category', 'flowers') }}" class="btn btn-primary px-4 py-2 fw-semibold rounded-pill">
                            <i class="bi bi-shop me-1"></i> Continue Shopping
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary px-4 py-2 rounded-pill">
                            Home Page
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
