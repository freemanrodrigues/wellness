@extends('layouts.app')

@section('title', $meta['title'] ?? 'Payment')
@section('meta_description', $meta['description'] ?? 'Complete your payment')

@section('content')

<div class="container py-4">

    {{-- ── Step Progress Indicator ───────────────────────── --}}
    <div class="row justify-content-center mb-4">
        <div class="col-lg-8">
            <div class="d-flex align-items-center justify-content-between position-relative checkout-steps">
                <div class="step-item text-center position-relative z-1 completed">
                    <div class="step-icon bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-1">
                        <i class="bi bi-check-lg fs-5"></i>
                    </div>
                    <div class="small fw-semibold text-muted">Cart</div>
                </div>
                <div class="step-line flex-grow-1 bg-success mx-2" style="height: 3px;"></div>
                <div class="step-item text-center position-relative z-1 completed">
                    <div class="step-icon bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-1">
                        <i class="bi bi-check-lg fs-5"></i>
                    </div>
                    <div class="small fw-semibold text-muted">Shipping</div>
                </div>
                <div class="step-line flex-grow-1 bg-success mx-2" style="height: 3px;"></div>
                <div class="step-item text-center position-relative z-1 active">
                    <div class="step-icon bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-1 shadow-sm">
                        <span class="fw-bold">3</span>
                    </div>
                    <div class="small fw-bold text-primary">Payment</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Flash messages --}}
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">

        {{-- Payment Option Section --}}
        <div class="col-lg-8">
            <form action="{{ route('checkout.payment.store') }}" method="POST" id="paymentForm">
                @csrf

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h2 class="h5 fw-bold mb-3">
                            <i class="bi bi-credit-card-2-front me-2 text-primary"></i>Select Payment Method
                        </h2>
                        <p class="text-muted small mb-4">All transactions are secure and encrypted.</p>

                        <div class="accordion" id="paymentAccordion">

                            {{-- Option 1: Credit / Debit Card --}}
                            <div class="accordion-item border rounded mb-3 overflow-hidden">
                                <h3 class="accordion-header" id="headingCard">
                                    <button class="accordion-button py-3 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCard" aria-expanded="true" aria-controls="collapseCard">
                                        <input class="form-check-input me-2" type="radio" name="payment_method" id="pay_card" value="card" checked>
                                        <i class="bi bi-credit-card me-2 text-primary"></i> Credit / Debit Card
                                    </button>
                                </h3>
                                <div id="collapseCard" class="accordion-collapse collapse show" aria-labelledby="headingCard" data-bs-parent="#paymentAccordion">
                                    <div class="accordion-body bg-light">
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label class="form-label small fw-semibold">Card Number</label>
                                                <input type="text" class="form-control" placeholder="1234 5678 9101 1121">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold">Expiration Date</label>
                                                <input type="text" class="form-control" placeholder="MM / YY">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold">CVV / CVC</label>
                                                <input type="password" class="form-control" placeholder="123" maxlength="4">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Option 2: UPI / QR --}}
                            <div class="accordion-item border rounded mb-3 overflow-hidden">
                                <h3 class="accordion-header" id="headingUPI">
                                    <button class="accordion-button collapsed py-3 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseUPI" aria-expanded="false" aria-controls="collapseUPI">
                                        <input class="form-check-input me-2" type="radio" name="payment_method" id="pay_upi" value="upi">
                                        <i class="bi bi-qr-code-scan me-2 text-success"></i> UPI / PhonePe / Google Pay
                                    </button>
                                </h3>
                                <div id="collapseUPI" class="accordion-collapse collapse" aria-labelledby="headingUPI" data-bs-parent="#paymentAccordion">
                                    <div class="accordion-body bg-light">
                                        <label class="form-label small fw-semibold">Enter your UPI ID</label>
                                        <input type="text" class="form-control mb-2" placeholder="username@upi">
                                        <span class="text-muted extra-small">A payment request will be sent to your UPI app.</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Option 3: Net Banking --}}
                            <div class="accordion-item border rounded mb-3 overflow-hidden">
                                <h3 class="accordion-header" id="headingNet">
                                    <button class="accordion-button collapsed py-3 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNet" aria-expanded="false" aria-controls="collapseNet">
                                        <input class="form-check-input me-2" type="radio" name="payment_method" id="pay_net" value="netbanking">
                                        <i class="bi bi-bank me-2 text-info"></i> Net Banking
                                    </button>
                                </h3>
                                <div id="collapseNet" class="accordion-collapse collapse" aria-labelledby="headingNet" data-bs-parent="#paymentAccordion">
                                    <div class="accordion-body bg-light">
                                        <label class="form-label small fw-semibold">Select your Bank</label>
                                        <select class="form-select">
                                            <option>State Bank of India</option>
                                            <option>HDFC Bank</option>
                                            <option>ICICI Bank</option>
                                            <option>Axis Bank</option>
                                            <option>Kotak Mahindra Bank</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- Option 4: Cash on Delivery --}}
                            <div class="accordion-item border rounded mb-3 overflow-hidden">
                                <h3 class="accordion-header" id="headingCOD">
                                    <button class="accordion-button collapsed py-3 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCOD" aria-expanded="false" aria-controls="collapseCOD">
                                        <input class="form-check-input me-2" type="radio" name="payment_method" id="pay_cod" value="cod">
                                        <i class="bi bi-cash-stack me-2 text-warning"></i> Cash on Delivery (COD)
                                    </button>
                                </h3>
                                <div id="collapseCOD" class="accordion-collapse collapse" aria-labelledby="headingCOD" data-bs-parent="#paymentAccordion">
                                    <div class="accordion-body bg-light">
                                        <p class="small text-muted mb-0">Pay with cash upon delivery of your order.</p>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center">
                            <a href="{{ route('checkout.shipping') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Back to Shipping
                            </a>
                            <button type="submit" class="btn btn-primary px-4 py-2 fw-bold" id="btnPlaceOrder">
                                Place Order <i class="bi bi-check-circle me-1"></i>
                            </button>
                        </div>

                    </div>
                </div>

            </form>
        </div>

        {{-- Order Summary Side Card --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-top" style="top: 100px;">
                <div class="card-body p-4">
                    <h3 class="h6 fw-bold mb-3 pb-2 border-bottom">
                        <i class="bi bi-bag-check me-2 text-primary"></i>Order Summary ({{ $items->sum('qty') }} items)
                    </h3>

                    {{-- Items Preview --}}
                    <div class="checkout-items-list mb-3 pe-1" style="max-height: 240px; overflow-y: auto;">
                        @foreach ($items as $item)
                            <div class="d-flex align-items-center justify-content-between mb-2 pb-2 border-bottom border-light">
                                <div class="me-2">
                                    <div class="fw-semibold small text-dark">{{ $item->product_name }}</div>
                                    <div class="text-muted extra-small">Qty: {{ $item->qty }} &times; ${{ number_format($item->prodprice, 2) }}</div>
                                </div>
                                <div class="fw-bold small text-nowrap">
                                    ${{ number_format($item->prodprice * $item->qty, 2) }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-between mb-2 small text-muted">
                        <span>Subtotal</span>
                        <span class="text-dark">${{ number_format($subtotal, 2) }}</span>
                    </div>

                    @if ($discount > 0)
                        <div class="d-flex justify-content-between mb-2 small text-danger">
                            <span>Discount</span>
                            <span>-${{ number_format($discount, 2) }}</span>
                        </div>
                    @endif

                    <div class="d-flex justify-content-between mb-2 small text-muted">
                        <span>Delivery Charge</span>
                        <span class="text-success fw-semibold">FREE</span>
                    </div>

                    <hr class="my-3">

                    <div class="d-flex justify-content-between align-items-center mb-0">
                        <span class="fw-bold text-dark">Total Amount</span>
                        <span class="fw-bold fs-5 text-primary">${{ number_format($total, 2) }}</span>
                    </div>

                </div>
            </div>
        </div>

    </div>

</div>

@endsection
