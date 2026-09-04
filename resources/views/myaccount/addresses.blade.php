@extends('layouts.app')

@section('title', 'Address Book - My Account')
@section('meta_description', 'Manage your saved shipping and billing addresses.')

@section('content')
<div class="container py-4">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('myaccount.home') }}" class="text-decoration-none text-muted">My Account</a></li>
            <li class="breadcrumb-item active text-success fw-semibold" aria-current="page">Address Book</li>
        </ol>
    </nav>

    <div class="row g-4">
        {{-- Left Sidebar --}}
        <div class="col-lg-3 col-md-4">
            @include('myaccount.sidebar')
        </div>

        {{-- Main Address Book Content --}}
        <div class="col-lg-9 col-md-8">
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white border-bottom p-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div>
                        <h4 class="fw-bold mb-1 text-dark"><i class="bi bi-geo-alt text-info me-2"></i> Saved Addresses</h4>
                        <p class="text-muted small mb-0">Manage multiple shipping locations for fast, one-click checkout.</p>
                    </div>
                    <button class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addAddressModal" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border:none;" id="btnAddNewAddress">
                        <i class="bi bi-plus-circle me-1"></i> Add New Address
                    </button>
                </div>

                <div class="card-body p-4">
                    <div class="row g-4">
                        @foreach($addresses as $addr)
                            <div class="col-md-6">
                                <div class="card border rounded-3 p-4 h-100 shadow-sm position-relative {{ $addr['is_default_shipping'] ? 'border-success bg-light-soft' : '' }}">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="fw-bold text-dark mb-0">{{ $addr['title'] }}</h6>
                                        <div>
                                            @if($addr['is_default_shipping'])
                                                <span class="badge bg-success rounded-pill px-2.5 py-1 small">Default Shipping</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="text-secondary small mb-3 flex-grow-1">
                                        <p class="mb-1 fw-semibold text-dark">{{ $addr['name'] }}</p>
                                        <p class="mb-1">{{ $addr['address1'] }}{{ $addr['address2'] ? ', ' . $addr['address2'] : '' }}</p>
                                        @if($addr['landmark'])
                                            <p class="mb-1">Landmark: {{ $addr['landmark'] }}</p>
                                        @endif
                                        <p class="mb-1">{{ $addr['city'] }}, {{ $addr['state'] }} - {{ $addr['pincode'] }}</p>
                                        <p class="mb-0"><i class="bi bi-telephone me-1"></i> {{ $addr['phone'] }}</p>
                                    </div>

                                    <div class="pt-3 border-top d-flex align-items-center justify-content-between">
                                        <button class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                            <i class="bi bi-pencil me-1"></i> Edit
                                        </button>
                                        @if(!$addr['is_default_shipping'])
                                            <button class="btn btn-sm btn-link text-danger p-0 text-decoration-none small">
                                                <i class="bi bi-trash me-1"></i> Delete
                                            </button>
                                        @endif
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

{{-- Add Address Modal --}}
<div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow rounded-3">
            <div class="modal-header border-bottom p-4">
                <h5 class="modal-title fw-bold text-dark" id="addAddressModalLabel"><i class="bi bi-geo-alt-fill text-success me-2"></i> Add New Delivery Address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-secondary fw-semibold">Address Title (e.g. Home, Office)</label>
                            <input type="text" class="form-control rounded-3 py-2" placeholder="Home / Office / Parents">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-secondary fw-semibold">Full Name</label>
                            <input type="text" class="form-control rounded-3 py-2" placeholder="Recipient's name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-secondary fw-semibold">Phone Number</label>
                            <input type="tel" class="form-control rounded-3 py-2" placeholder="+1 (555) 000-0000">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-secondary fw-semibold">Street Address / Line 1</label>
                            <input type="text" class="form-control rounded-3 py-2" placeholder="House/Flat No., Building, Street">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-secondary">City</label>
                            <input type="text" class="form-control rounded-3 py-2">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-secondary">State</label>
                            <input type="text" class="form-control rounded-3 py-2">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-secondary">Zip Code</label>
                            <input type="text" class="form-control rounded-3 py-2">
                        </div>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="setAsDefaultShipping" checked>
                        <label class="form-check-input-label text-secondary small" for="setAsDefaultShipping">
                            Set as default shipping address for fast checkout
                        </label>
                    </div>
                    <div class="modal-footer border-top pt-3 px-0 pb-0">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-success rounded-pill px-5 fw-bold" data-bs-dismiss="modal">Save Address</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
