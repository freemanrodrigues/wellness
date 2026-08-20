@extends('layouts.app')

@section('title', $meta['title'])
@section('meta_description', $meta['description'])

@section('content')

    <div class="container py-4">

        {{-- ── Step Progress Indicator ───────────────────────── --}}
        <div class="row justify-content-center mb-4">
            <div class="col-lg-8">
                <div class="d-flex align-items-center justify-content-between position-relative checkout-steps">
                    <div class="step-item text-center position-relative z-1 completed">
                        <div
                            class="step-icon bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-1">
                            <i class="bi bi-check-lg fs-5"></i>
                        </div>
                        <div class="small fw-semibold text-muted">Cart</div>
                    </div>
                    <div class="step-line flex-grow-1 bg-success mx-2" style="height: 3px;"></div>
                    <div class="step-item text-center position-relative z-1 active">
                        <div
                            class="step-icon bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-1 shadow-sm">
                            <span class="fw-bold">2</span>
                        </div>
                        <div class="small fw-bold text-primary">Shipping Details</div>
                    </div>
                    <div class="step-line flex-grow-1 bg-light mx-2" style="height: 3px;"></div>
                    <div class="step-item text-center position-relative z-1 text-muted">
                        <div
                            class="step-icon bg-light text-secondary border rounded-circle d-inline-flex align-items-center justify-content-center mb-1">
                            <span class="fw-bold">3</span>
                        </div>
                        <div class="small text-muted">Payment</div>
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

            {{-- Main Shipping Section --}}
            <div class="col-lg-8">
                <form action="{{ route('checkout.shipping.store') }}" method="POST" id="shippingForm">
                    @csrf

                    {{-- Option 1: Saved / Previous Delivery Addresses --}}
                    @if (count($previousAddresses) > 0)
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body p-4">
                                <h2 class="h5 fw-bold mb-3">
                                    <i class="bi bi-geo-alt me-2 text-primary"></i>Select a Previous Delivery Address
                                </h2>
                                <p class="text-muted small mb-3">Choose from one of your previously used addresses or enter a
                                    new one below.</p>

                                <div class="row g-3">
                                    @foreach ($previousAddresses as $index => $addr)
                                        <div class="col-md-6">
                                            <div class="card h-100 border saved-address-card p-3 cursor-pointer position-relative">
                                                <div class="form-check">
                                                    <input class="form-check-input saved-address-radio" type="radio"
                                                        name="address_option" id="addr_{{ $index }}" value="saved_{{ $index }}"
                                                        data-firstname="{{ $addr->s_firstname }}"
                                                        data-lastname="{{ $addr->s_lastname }}" data-phone="{{ $addr->s_phone }}"
                                                        data-pincode="{{ $addr->s_pincode }}"
                                                        data-address1="{{ $addr->s_address1 }}"
                                                        data-address2="{{ $addr->s_address2 }}"
                                                        data-landmark="{{ $addr->s_landmark }}" data-city="{{ $addr->s_city }}"
                                                        data-state="{{ $addr->s_state }}"
                                                        data-country_id="{{ $addr->s_country_id }}"
                                                        data-email="{{ $addr->s_email }}">
                                                    <label class="form-check-label w-100 cursor-pointer" for="addr_{{ $index }}">
                                                        <span class="fw-bold d-block text-dark">{{ $addr->s_firstname }}
                                                            {{ $addr->s_lastname }}</span>
                                                        <span class="small text-muted d-block mt-1">
                                                            {{ $addr->s_address1 }}
                                                            @if($addr->s_address2), {{ $addr->s_address2 }}@endif
                                                            @if($addr->s_landmark)<br><span class="text-secondary">Near:
                                                            {{ $addr->s_landmark }}</span>@endif
                                                            <br>{{ $addr->s_city }}, {{ $addr->s_state }} - {{ $addr->s_pincode }}
                                                            <br>{{ $countries[$addr->s_country_id] ?? 'India' }}
                                                        </span>
                                                        <span class="small text-dark d-block mt-2">
                                                            <i class="bi bi-telephone me-1 text-muted"></i> {{ $addr->s_phone }}
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    {{-- Radio option for New Address --}}
                                    <div class="col-12">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="radio" name="address_option" id="addr_new"
                                                value="new" checked>
                                            <label class="form-check-label fw-bold text-dark cursor-pointer" for="addr_new">
                                                <i class="bi bi-plus-circle me-1 text-success"></i> Enter a new delivery address
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Option 2: Enter Delivery Address Form --}}
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h2 class="h5 fw-bold mb-3" id="addressFormTitle">
                                <i class="bi bi-house-door me-2 text-primary"></i>Delivery Address
                            </h2>

                            <div class="row g-3">
                                {{-- First Name --}}
                                <div class="col-md-6">
                                    <label for="s_firstname" class="form-label fw-semibold small">First Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('s_firstname') is-invalid @enderror"
                                        id="s_firstname" name="s_firstname"
                                        value="{{ old('s_firstname', auth()->user()->firstname ?? '') }}" required>
                                    @error('s_firstname')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Last Name --}}
                                <div class="col-md-6">
                                    <label for="s_lastname" class="form-label fw-semibold small">Last Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('s_lastname') is-invalid @enderror"
                                        id="s_lastname" name="s_lastname"
                                        value="{{ old('s_lastname', auth()->user()->lastname ?? '') }}" required>
                                    @error('s_lastname')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Mobile Number --}}
                                <div class="col-md-6">
                                    <label for="s_phone" class="form-label fw-semibold small">Mobile Number <span
                                            class="text-danger">*</span></label>
                                    <input type="tel" class="form-control @error('s_phone') is-invalid @enderror"
                                        id="s_phone" name="s_phone" value="{{ old('s_phone') }}"
                                        placeholder="10-digit mobile number" required>
                                    <div class="form-text text-muted small"><i class="bi bi-info-circle me-1"></i>May be
                                        used to assist delivery</div>
                                    @error('s_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Pincode --}}
                                <div class="col-md-6">
                                    <label for="s_pincode" class="form-label fw-semibold small">Pincode <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('s_pincode') is-invalid @enderror"
                                        id="s_pincode" name="s_pincode" value="{{ old('s_pincode') }}"
                                        placeholder="Postal/ZIP code" required>
                                    @error('s_pincode')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Address Line 1 --}}
                                <div class="col-12">
                                    <label for="s_address1" class="form-label fw-semibold small">Address Line 1 <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('s_address1') is-invalid @enderror"
                                        id="s_address1" name="s_address1" value="{{ old('s_address1') }}"
                                        placeholder="House No., Building Name, Street" required>
                                    @error('s_address1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Address Line 2 --}}
                                <div class="col-12">
                                    <label for="s_address2" class="form-label fw-semibold small">Address Line 2</label>
                                    <input type="text" class="form-control @error('s_address2') is-invalid @enderror"
                                        id="s_address2" name="s_address2" value="{{ old('s_address2') }}"
                                        placeholder="Area, Colony, Sector, Village (Optional)">
                                    @error('s_address2')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Landmark --}}
                                <div class="col-md-6">
                                    <label for="s_landmark" class="form-label fw-semibold small">Landmark</label>
                                    <input type="text" class="form-control @error('s_landmark') is-invalid @enderror"
                                        id="s_landmark" name="s_landmark" value="{{ old('s_landmark') }}"
                                        placeholder="E.g. Near Apollo Hospital (Optional)">
                                    @error('s_landmark')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- City --}}
                                <div class="col-md-6">
                                    <label for="s_city" class="form-label fw-semibold small">City <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('s_city') is-invalid @enderror"
                                        id="s_city" name="s_city" value="{{ old('s_city') }}" required>
                                    @error('s_city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- State --}}
                                <div class="col-md-6">
                                    <label for="s_state" class="form-label fw-semibold small">State <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('s_state') is-invalid @enderror"
                                        id="s_state" name="s_state" value="{{ old('s_state') }}" required>
                                    @error('s_state')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Country --}}
                                <div class="col-md-6">
                                    <label for="s_country_id" class="form-label fw-semibold small">Country <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select @error('s_country_id') is-invalid @enderror"
                                        id="s_country_id" name="s_country_id" required>
                                        <option value="">-- Select Country --</option>
                                        @php
                                            $countriesList = isset($countries) && (is_countable($countries) ? count($countries) > 0 : !empty($countries))
                                                ? $countries
                                                : \Illuminate\Support\Facades\DB::table('countries')->where('active', 1)->orderBy('countryname')->pluck('countryname', 'id');
                                        @endphp
                                        @foreach ($countriesList as $cId => $cName)
                                            <option value="{{ $cId }}" {{ old('s_country_id', 1) == $cId ? 'selected' : '' }}>
                                                {{ $cName }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('s_country_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>


                                {{-- Hidden Email Field --}}
                                <input type="hidden" name="s_email" id="s_email" value="{{ auth()->user()->email }}">

                            </div>

                            <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center">
                                <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-1"></i> Back to Cart
                                </a>
                                <button type="submit" class="btn btn-primary px-4 py-2 fw-bold" id="btnSubmitShipping">
                                    Save &amp; Continue to Payment <i class="bi bi-arrow-right ms-1"></i>
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
                                <div
                                    class="d-flex align-items-center justify-content-between mb-2 pb-2 border-bottom border-light">
                                    <div class="me-2">
                                        <div class="fw-semibold small text-dark">{{ $item->product_name }}</div>
                                        <div class="text-muted extra-small">Qty: {{ $item->qty }} &times;
                                            ${{ number_format($item->prodprice, 2) }}</div>
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

    <style>
        .step-icon {
            width: 38px;
            height: 38px;
            font-size: 0.95rem;
        }

        .checkout-steps .step-line {
            margin-top: -16px;
        }

        .saved-address-card {
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            border-radius: 0.5rem;
        }

        .saved-address-card:hover {
            border-color: var(--bs-primary) !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .saved-address-card.selected {
            border-color: var(--bs-primary) !important;
            background-color: rgba(var(--bs-primary-rgb), 0.03);
        }

        .cursor-pointer {
            cursor: pointer;
        }

        .extra-small {
            font-size: 0.75rem;
        }
    </style>

@endsection

@push('scripts')
    <script>
        $(function () {
            // When user selects a saved address radio button, auto-fill form fields
            $('.saved-address-radio').on('change', function () {
                const radio = $(this);

                $('.saved-address-card').removeClass('selected');
                radio.closest('.saved-address-card').addClass('selected');

                $('#s_firstname').val(radio.data('firstname'));
                $('#s_lastname').val(radio.data('lastname'));
                $('#s_phone').val(radio.data('phone'));
                $('#s_pincode').val(radio.data('pincode'));
                $('#s_address1').val(radio.data('address1'));
                $('#s_address2').val(radio.data('address2'));
                $('#s_landmark').val(radio.data('landmark'));
                $('#s_city').val(radio.data('city'));
                $('#s_state').val(radio.data('state'));
                $('#s_country_id').val(radio.data('country_id'));

                if (radio.data('email')) {
                    $('#s_email').val(radio.data('email'));
                }
            });

            // When user selects "Enter a new delivery address"
            $('#addr_new').on('change', function () {
                $('.saved-address-card').removeClass('selected');

                // Reset form fields to defaults or logged in user's name
                $('#s_firstname').val('{{ auth()->user()->firstname ?? "" }}');
                $('#s_lastname').val('{{ auth()->user()->lastname ?? "" }}');
                $('#s_phone').val('');
                $('#s_pincode').val('');
                $('#s_address1').val('');
                $('#s_address2').val('');
                $('#s_landmark').val('');
                $('#s_city').val('');
                $('#s_state').val('');
                $('#s_country_id').val(1);
            });
        });
    </script>
@endpush