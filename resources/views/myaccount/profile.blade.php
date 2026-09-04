@extends('layouts.app')

@section('title', 'My Profile - My Account')
@section('meta_description', 'View and update your profile details and account security settings.')

@section('content')
<div class="container py-4">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('myaccount.home') }}" class="text-decoration-none text-muted">My Account</a></li>
            <li class="breadcrumb-item active text-success fw-semibold" aria-current="page">Profile</li>
        </ol>
    </nav>

    <div class="row g-4">
        {{-- Left Sidebar --}}
        <div class="col-lg-3 col-md-4">
            @include('myaccount.sidebar')
        </div>

        {{-- Main Profile Content --}}
        <div class="col-lg-9 col-md-8">
            {{-- Success flash --}}
            @if (session('status') === 'profile-updated')
                <div class="alert alert-success alert-dismissible fade show mb-4 rounded-3 shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> Your profile details have been updated successfully!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Profile Card --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white border-bottom p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="fw-bold mb-1 text-dark"><i class="bi bi-person-vcard text-primary me-2"></i> Personal Information</h4>
                        <p class="text-muted small mb-0">Update your name, contact details, and default delivery location.</p>
                    </div>
                    <span class="badge bg-soft-primary text-primary px-3 py-2 rounded-pill fw-semibold" style="background-color: #e0e7ff; color: #3730a3;">My Account / Profile</span>
                </div>

                <div class="card-body p-4 p-md-5">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PATCH')

                        <div class="row g-3 mb-4">
                            {{-- First Name --}}
                            <div class="col-md-6">
                                <label for="firstname" class="form-label fw-semibold text-secondary">First Name <span class="text-danger">*</span></label>
                                <input id="firstname" type="text" name="firstname"
                                    class="form-control rounded-3 py-2 @error('firstname') is-invalid @enderror"
                                    value="{{ old('firstname', $user->firstname) }}" required autocomplete="given-name">
                                @error('firstname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Last Name --}}
                            <div class="col-md-6">
                                <label for="lastname" class="form-label fw-semibold text-secondary">Last Name <span class="text-danger">*</span></label>
                                <input id="lastname" type="text" name="lastname"
                                    class="form-control rounded-3 py-2 @error('lastname') is-invalid @enderror"
                                    value="{{ old('lastname', $user->lastname) }}" required autocomplete="family-name">
                                @error('lastname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Email Address --}}
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold text-secondary">Email Address <span class="text-danger">*</span></label>
                                <input id="email" type="email" name="email"
                                    class="form-control rounded-3 py-2 @error('email') is-invalid @enderror"
                                    value="{{ old('email', $user->email) }}" required autocomplete="username">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Phone --}}
                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-semibold text-secondary">Phone Number</label>
                                <input id="phone" type="tel" name="phone"
                                    class="form-control rounded-3 py-2 @error('phone') is-invalid @enderror"
                                    value="{{ old('phone', $user->phone) }}" placeholder="+1 (555) 000-0000" autocomplete="tel">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-geo-alt text-danger me-2"></i> Primary Location Details</h6>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="address1" class="form-label text-secondary">Address Line 1</label>
                                <input id="address1" type="text" name="address1" class="form-control rounded-3 py-2"
                                    value="{{ old('address1', $user->address1) }}" placeholder="Street address or P.O. Box">
                            </div>

                            <div class="col-md-6">
                                <label for="address2" class="form-label text-secondary">Address Line 2 (Apartment/Suite)</label>
                                <input id="address2" type="text" name="address2" class="form-control rounded-3 py-2"
                                    value="{{ old('address2', $user->address) }}" placeholder="Apt, Suite, Unit, Building">
                            </div>

                            <div class="col-md-4">
                                <label for="city" class="form-label text-secondary">City</label>
                                <input id="city" type="text" name="city" class="form-control rounded-3 py-2"
                                    value="{{ old('city', $user->city) }}">
                            </div>

                            <div class="col-md-4">
                                <label for="state" class="form-label text-secondary">State / Province</label>
                                <input id="state" type="text" name="state" class="form-control rounded-3 py-2"
                                    value="{{ old('state', $user->state) }}">
                            </div>

                            <div class="col-md-4">
                                <label for="pincode" class="form-label text-secondary">Zip / Postal Code</label>
                                <input id="pincode" type="text" name="pincode" class="form-control rounded-3 py-2"
                                    value="{{ old('pincode', $user->pincode) }}">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('myaccount.home') }}" class="btn btn-outline-secondary rounded-pill px-4">Cancel</a>
                            <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none;">
                                <i class="bi bi-check2-circle me-1"></i> Save Profile Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Password Change Card --}}
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="fw-bold mb-1 text-dark"><i class="bi bi-shield-lock text-warning me-2"></i> Update Password & Security</h5>
                    <p class="text-muted small mb-0">Ensure your account is using a strong password for optimal protection.</p>
                </div>
                <div class="card-body p-4 p-md-5">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label for="current_password" class="form-label text-secondary">Current Password</label>
                                <input id="current_password" name="current_password" type="password" class="form-control rounded-3 py-2">
                            </div>

                            <div class="col-md-4">
                                <label for="password" class="form-label text-secondary">New Password</label>
                                <input id="password" name="password" type="password" class="form-control rounded-3 py-2">
                            </div>

                            <div class="col-md-4">
                                <label for="password_confirmation" class="form-label text-secondary">Confirm New Password</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" class="form-control rounded-3 py-2">
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-outline-dark rounded-pill px-4 fw-semibold">
                                <i class="bi bi-key me-1"></i> Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
