@extends('layouts.app')

@section('title', 'Gift Cards & Wallet - My Account')
@section('meta_description', 'View your store credit gift card balance, redeem code, and transaction history.')

@section('content')
<div class="container py-4">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('myaccount.home') }}" class="text-decoration-none text-muted">My Account</a></li>
            <li class="breadcrumb-item active text-success fw-semibold" aria-current="page">Gift Cards</li>
        </ol>
    </nav>

    <div class="row g-4">
        {{-- Left Sidebar --}}
        <div class="col-lg-3 col-md-4">
            @include('myaccount.sidebar')
        </div>

        {{-- Main Gift Cards Content --}}
        <div class="col-lg-9 col-md-8">
            {{-- Balance Card --}}
            <div class="card border-0 rounded-4 shadow-sm mb-4 text-white position-relative overflow-hidden" style="background: linear-gradient(135deg, #9333ea 0%, #6b21a8 100%);">
                <div class="card-body p-4 p-md-5 position-relative" style="z-index:2;">
                    <div class="row align-items-center">
                        <div class="col-md-7">
                            <span class="badge bg-white text-purple px-3 py-1 mb-2 rounded-pill fw-bold" style="color: #9333ea;">Available Credit</span>
                            <p class="text-white-50 mb-1">Total Gift Card Balance</p>
                            <h1 class="display-5 fw-extrabold text-white mb-0">${{ number_format($giftCardData['balance'] ?? 150.00, 2) }}</h1>
                        </div>
                        <div class="col-md-5 text-md-end mt-3 mt-md-0">
                            <button class="btn btn-light text-purple fw-bold rounded-pill px-4 py-2 shadow-sm" style="color: #6b21a8;" data-bs-toggle="collapse" data-bs-target="#redeemForm">
                                <i class="bi bi-plus-lg me-1"></i> Redeem Code
                            </button>
                        </div>
                    </div>
                </div>
                <div class="position-absolute end-0 bottom-0 opacity-25 pe-3 pb-2 d-none d-md-block" style="z-index:1;">
                    <i class="bi bi-gift-fill" style="font-size: 11rem; line-height:0; color: #ffffff;"></i>
                </div>
            </div>

            {{-- Redeem Code Form --}}
            <div class="collapse mb-4" id="redeemForm">
                <div class="card border-0 shadow-sm rounded-3 p-4 bg-light">
                    <h5 class="fw-bold text-dark mb-2"><i class="bi bi-qr-code-scan text-purple me-2" style="color: #9333ea;"></i> Redeem Gift Voucher / Card</h5>
                    <p class="text-muted small mb-3">Enter the 16-character gift card claim code printed on your voucher.</p>
                    <form class="row g-3" onsubmit="event.preventDefault(); alert('Gift Card code validated! $50.00 added to your account balance.');">
                        <div class="col-md-8">
                            <input type="text" class="form-control rounded-3 py-2 font-monospace text-uppercase" placeholder="e.g. GC-9821-4402-1200" required>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-purple text-white w-100 rounded-3 py-2 fw-bold" style="background-color: #9333ea;">
                                Apply Code
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Active Cards & History Tabs --}}
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom p-4">
                    <h4 class="fw-bold mb-1 text-dark"><i class="bi bi-wallet2 text-purple me-2" style="color:#9333ea;"></i> Active Cards & Transaction History</h4>
                    <p class="text-muted small mb-0">Overview of all linked digital gift cards and spending history.</p>
                </div>

                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-3">Linked Active Cards</h6>
                    <div class="row g-3 mb-4">
                        @foreach($giftCardData['active_cards'] as $card)
                            <div class="col-md-6">
                                <div class="card border rounded-3 p-3 bg-light-soft shadow-sm">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="font-monospace fw-bold text-dark">{{ $card['card_number'] }}</span>
                                        <span class="badge bg-success rounded-pill px-2.5 py-1">{{ $card['status'] }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-end">
                                        <div>
                                            <small class="text-muted d-block" style="font-size:0.75rem;">Balance</small>
                                            <span class="fw-bold text-purple fs-5" style="color:#9333ea;">${{ number_format($card['balance'], 2) }}</span>
                                        </div>
                                        <small class="text-muted">Expires: {{ $card['expiry'] }}</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <h6 class="fw-bold text-dark mb-3">Recent Transactions</h6>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle border rounded-3">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($giftCardData['transactions'] as $tx)
                                    <tr>
                                        <td class="text-muted small">{{ $tx['date'] }}</td>
                                        <td class="fw-medium text-dark">{{ $tx['description'] }}</td>
                                        <td class="text-end fw-bold {{ $tx['type'] === 'credit' ? 'text-success' : 'text-danger' }}">
                                            {{ $tx['amount'] }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
