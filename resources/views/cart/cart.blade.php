@extends('layouts.app')

@section('title', $meta['title'])
@section('meta_description', $meta['description'])

@section('content')

    <h1 class="h4 mb-4">Your Cart</h1>

    @if ($items->isEmpty())
        <div class="alert alert-light border text-center py-5">
            Your cart is empty. <a href="{{ route('home') }}">Continue shopping</a>.
        </div>
    @else

        <div class="row g-4">

            {{-- Cart table --}}
            <div class="col-lg-8">
                <div class="table-responsive">
                    <table class="table align-middle" id="cartTable">
                        <thead>
                            <tr class="text-muted small text-uppercase">
                                <th>Product</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Price</th>
                                <th class="text-end">Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr data-id="{{ $item->id }}">
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            {{-- <img
                                                src="{{ $item->product_image ? asset($item->product_image) : asset('images/no-image.png') }}"
                                                alt="{{ $item->product_name }}" width="64" height="64" class="rounded border"
                                                style="object-fit: cover;"> --}}
                                            <span class="fw-semibold">{{ $item->product_name }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center" style="width: 130px;">
                                        <div class="input-group input-group-sm">
                                            <button type="button" class="btn btn-outline-secondary qty-minus">&minus;</button>
                                            <input type="number" class="form-control text-center cart-qty-input"
                                                value="{{ $item->qty }}" min="1" max="20" data-price="{{ $item->prodprice }}">
                                            <button type="button" class="btn btn-outline-secondary qty-plus">&plus;</button>
                                        </div>
                                    </td>
                                    <td class="text-end">${{ number_format($item->prodprice, 2) }}</td>
                                    <td class="text-end fw-semibold line-subtotal">
                                        ${{ number_format($item->prodprice * $item->qty, 2) }}
                                    </td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-sm btn-link text-danger remove-item" title="Remove">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="border-top">
                                <td colspan="3" class="text-end fw-bold">Total</td>
                                <td class="text-end fw-bold fs-5" id="cartGrandTotal">${{ number_format($total, 2) }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Summary / promo / coupons --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <h2 class="h6 mb-3">Order Summary</h2>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span id="summarySubtotal">${{ number_format($subtotal, 2) }}</span>
                        </div>

                        <div class="d-flex justify-content-between mb-2 {{ $discount > 0 ? '' : 'd-none' }}" id="discountRow">
                            <span class="text-muted">Discount ({{ $promoCode }})</span>
                            <span class="text-danger" id="summaryDiscount">-${{ number_format($discount, 2) }}</span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-3">
                            <span class="fw-bold">Total</span>
                            <span class="fw-bold fs-5" id="summaryTotal">${{ number_format($total, 2) }}</span>
                        </div>

                        {{-- Promo code form --}}
                        <label for="promoCodeInput" class="form-label small fw-semibold">Promo Code</label>
                        <div class="input-group input-group-sm mb-2">
                            <input type="text" id="promoCodeInput" class="form-control" placeholder="Enter code"
                                value="{{ $promoCode }}">
                            <button type="button" class="btn btn-outline-primary" id="applyPromoBtn">Apply</button>
                        </div>
                        <div id="promoMessage" class="small mb-2"></div>

                        @if ($promoCode)
                            <button type="button" class="btn btn-sm btn-link text-danger p-0" id="removePromoBtn">
                                Remove promo code
                            </button>
                        @endif

                        <a href="{{ route('checkout.shipping') }}" class="btn btn-primary w-100 mt-3 py-2 fw-semibold"
                            id="btnProceedCheckout">
                            Proceed to Checkout <i class="bi bi-arrow-right ms-1"></i>
                        </a>

                    </div>
                </div>

                {{-- Available coupons --}}
                @if (!empty($availableCoupons))
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h2 class="h6 mb-3">
                                <i class="bi bi-tag me-1"></i> Discount Coupons Available
                            </h2>
                            @foreach ($availableCoupons as $coupon)
                                <div class="d-flex justify-content-between align-items-center border rounded p-2 mb-2">
                                    <div>
                                        <div class="fw-bold small">{{ $coupon['code'] }}</div>
                                        <div class="text-muted small">{{ $coupon['description'] }}</div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-secondary use-coupon-btn"
                                        data-code="{{ $coupon['code'] }}">
                                        Use
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

        </div>

    @endif

@endsection

@push('scripts')
    <script>
        $(function () {

            function recalcLine(row) {
                const priceInput = row.find('.cart-qty-input');
                const price = parseFloat(priceInput.data('price'));
                const qty = parseInt(priceInput.val());
                const lineTotal = price * qty;
                row.find('.line-subtotal').text('$' + lineTotal.toFixed(2));
            }

            function updateQtyOnServer(row) {
                const id = row.data('id');
                const qty = parseInt(row.find('.cart-qty-input').val());

                $.ajax({
                    url: '/cart/' + id,
                    method: 'PATCH',
                    data: {
                        _token: '{{ csrf_token() }}',
                        qty: qty
                    },
                    success: function (response) {
                        recalcLine(row);
                        $('#summarySubtotal').text('$' + response.cart_subtotal);
                        $('#cartGrandTotal').text('$' + response.cart_subtotal);
                        $('#summaryTotal').text('$' + response.cart_subtotal);
                    }
                });
            }

            $('.qty-plus').on('click', function () {
                const input = $(this).siblings('.cart-qty-input');
                input.val(Math.min(20, parseInt(input.val()) + 1));
                updateQtyOnServer($(this).closest('tr'));
            });

            $('.qty-minus').on('click', function () {
                const input = $(this).siblings('.cart-qty-input');
                input.val(Math.max(1, parseInt(input.val()) - 1));
                updateQtyOnServer($(this).closest('tr'));
            });

            $('.cart-qty-input').on('change', function () {
                updateQtyOnServer($(this).closest('tr'));
            });

            $('.remove-item').on('click', function () {
                const row = $(this).closest('tr');
                const id = row.data('id');

                if (!confirm('Remove this item from your cart?')) {
                    return;
                }

                $.ajax({
                    url: '/cart/' + id,
                    method: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function () {
                        row.fadeOut(200, function () {
                            row.remove();
                            if ($('#cartTable tbody tr').length === 0) {
                                location.reload(); // shows the empty-cart state
                            }
                        });
                    }
                });
            });

            $('#applyPromoBtn').on('click', function () {
                applyPromo($('#promoCodeInput').val());
            });

            $('.use-coupon-btn').on('click', function () {
                const code = $(this).data('code');
                $('#promoCodeInput').val(code);
                applyPromo(code);
            });

            function applyPromo(code) {
                if (!code) {
                    return;
                }

                $.ajax({
                    url: '{{ route('cart.promo') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        promo_code: code
                    },
                    success: function (response) {
                        $('#promoMessage').removeClass('text-danger').addClass('text-success').text(response.message);
                        $('#summaryDiscount').text('-$' + response.discount);
                        $('#discountRow').removeClass('d-none');
                        $('#summaryTotal').text('$' + response.total);
                        $('#cartGrandTotal').text('$' + response.total);
                    },
                    error: function (xhr) {
                        const msg = xhr.responseJSON?.message || 'Could not apply this promo code.';
                        $('#promoMessage').removeClass('text-success').addClass('text-danger').text(msg);
                    }
                });
            }

            $('#removePromoBtn').on('click', function () {
                $.ajax({
                    url: '{{ route('cart.promo.remove') }}',
                    method: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function () {
                        location.reload();
                    }
                });
            });

        });
    </script>
@endpush