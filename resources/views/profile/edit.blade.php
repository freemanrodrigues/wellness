@extends('layouts.app')

@section('title', 'My Profile')
@section('meta_description', 'Update your account profile information.')

@section('content')
    <div class="row justify-content-center py-4">
        <div class="col-md-10 col-lg-8">

            {{-- Success flash --}}
            @if (session('status') === 'profile-updated')
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> Your profile has been updated successfully.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- ── Update Profile ── --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4 p-md-5">

                    <h1 class="h4 mb-1">My Profile</h1>
                    <p class="text-muted small mb-4">Update your personal information and address details.</p>

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PATCH')

                        {{-- ── Personal Information ── --}}
                        <h6 class="text-uppercase text-muted fw-semibold mb-3" style="font-size:.7rem;letter-spacing:.08em;">Personal Information</h6>
                        <div class="row g-3 mb-3">

                            {{-- First Name --}}
                            <div class="col-md-6">
                                <label for="firstname" class="form-label">{{ __('First Name') }} <span class="text-danger">*</span></label>
                                <input id="firstname" type="text" name="firstname"
                                    class="form-control @error('firstname') is-invalid @enderror"
                                    value="{{ old('firstname', $user->firstname) }}" required autofocus autocomplete="given-name">
                                @error('firstname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Last Name --}}
                            <div class="col-md-6">
                                <label for="lastname" class="form-label">{{ __('Last Name') }} <span class="text-danger">*</span></label>
                                <input id="lastname" type="text" name="lastname"
                                    class="form-control @error('lastname') is-invalid @enderror"
                                    value="{{ old('lastname', $user->lastname) }}" required autocomplete="family-name">
                                @error('lastname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="col-md-6">
                                <label for="email" class="form-label">{{ __('Email Address') }} <span class="text-danger">*</span></label>
                                <input id="email" type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $user->email) }}" required autocomplete="username">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                    <div class="form-text text-warning">
                                        {{ __('Your email address is unverified.') }}
                                        <form id="send-verification" method="POST" action="{{ route('verification.send') }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-link btn-sm p-0 align-baseline">
                                                {{ __('Re-send verification email') }}
                                            </button>
                                        </form>
                                    </div>
                                    @if (session('status') === 'verification-link-sent')
                                        <div class="form-text text-success">{{ __('A new verification link has been sent.') }}</div>
                                    @endif
                                @endif
                            </div>

                            {{-- Phone --}}
                            <div class="col-md-6">
                                <label for="phone" class="form-label">{{ __('Phone Number') }}</label>
                                <input id="phone" type="tel" name="phone"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    value="{{ old('phone', $user->phone) }}" autocomplete="tel">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <hr class="my-4">

                        {{-- ── Address Details ── --}}
                        <h6 class="text-uppercase text-muted fw-semibold mb-3" style="font-size:.7rem;letter-spacing:.08em;">Address Details</h6>
                        <div class="row g-3 mb-3">

                            {{-- Address Line 1 --}}
                            <div class="col-md-6">
                                <label for="address1" class="form-label">{{ __('Address Line 1') }}</label>
                                <input id="address1" type="text" name="address1"
                                    class="form-control @error('address1') is-invalid @enderror"
                                    value="{{ old('address1', $user->address1) }}"
                                    autocomplete="address-line1" placeholder="Street, building, flat no.">
                                @error('address1')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Address Line 2 --}}
                            <div class="col-md-6">
                                <label for="address2" class="form-label">{{ __('Address Line 2') }}</label>
                                <input id="address2" type="text" name="address2"
                                    class="form-control @error('address2') is-invalid @enderror"
                                    value="{{ old('address2', $user->address) }}"
                                    autocomplete="address-line2" placeholder="Area, colony, sector">
                                @error('address2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Landmark --}}
                            <div class="col-md-6">
                                <label for="landmark" class="form-label">{{ __('Landmark') }}</label>
                                <input id="landmark" type="text" name="landmark"
                                    class="form-control @error('landmark') is-invalid @enderror"
                                    value="{{ old('landmark', $user->landmark) }}"
                                    placeholder="Near school, temple etc.">
                                @error('landmark')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- City --}}
                            <div class="col-md-6">
                                <label for="city" class="form-label">{{ __('City') }}</label>
                                <input id="city" type="text" name="city"
                                    class="form-control @error('city') is-invalid @enderror"
                                    value="{{ old('city', $user->city) }}" autocomplete="address-level2">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Pincode --}}
                            <div class="col-md-4">
                                <label for="pincode" class="form-label">{{ __('Pincode') }}</label>
                                <input id="pincode" type="text" name="pincode"
                                    class="form-control @error('pincode') is-invalid @enderror"
                                    value="{{ old('pincode', $user->pincode) }}"
                                    autocomplete="postal-code" maxlength="10">
                                @error('pincode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- State --}}
                            <div class="col-md-4">
                                <label for="state" class="form-label">{{ __('State') }}</label>
                                <input id="state" type="text" name="state"
                                    class="form-control @error('state') is-invalid @enderror"
                                    value="{{ old('state', $user->state) }}" autocomplete="address-level1">
                                @error('state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Country --}}
                            <div class="col-md-4">
                                <label for="country_id" class="form-label">{{ __('Country') }}</label>
                                <select id="country_id" name="country_id"
                                    class="form-select @error('country_id') is-invalid @enderror">
                                    <option value="">-- Select Country --</option>
                                    @php $selCountry = old('country_id', $user->country_id); @endphp
                                    <option value="1" {{ $selCountry == 1 ? 'selected' : '' }}>India</option>
                                    <option value="2" {{ $selCountry == 2 ? 'selected' : '' }}>United States</option>
                                    <option value="3" {{ $selCountry == 3 ? 'selected' : '' }}>United Kingdom</option>
                                    <option value="4" {{ $selCountry == 4 ? 'selected' : '' }}>Canada</option>
                                    <option value="5" {{ $selCountry == 5 ? 'selected' : '' }}>Australia</option>
                                    <option value="6" {{ $selCountry == 6 ? 'selected' : '' }}>UAE</option>
                                    <option value="7" {{ $selCountry == 7 ? 'selected' : '' }}>Singapore</option>
                                </select>
                                @error('country_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="d-flex align-items-center justify-content-end mt-4 pt-2 border-top">
                            <button type="submit" id="btn-save-profile" class="btn btn-primary px-4">
                                {{ __('Save Changes') }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>

            {{-- ── Change Password ── --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4 p-md-5">

                    <h2 class="h5 mb-1">Change Password</h2>
                    <p class="text-muted small mb-4">Ensure your account is using a strong password.</p>

                    @if (session('status') === 'password-updated')
                        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i> Password updated successfully.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="current_password" class="form-label">{{ __('Current Password') }}</label>
                                <input id="current_password" type="password" name="current_password"
                                    class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                                    autocomplete="current-password">
                                @error('current_password', 'updatePassword')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password" class="form-label">{{ __('New Password') }}</label>
                                <input id="password" type="password" name="password"
                                    class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                                    autocomplete="new-password">
                                @error('password', 'updatePassword')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">{{ __('Confirm New Password') }}</label>
                                <input id="password_confirmation" type="password" name="password_confirmation"
                                    class="form-control" autocomplete="new-password">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4 pt-2 border-top">
                            <button type="submit" id="btn-update-password" class="btn btn-outline-primary px-4">
                                {{ __('Update Password') }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>

            {{-- ── Delete Account ── --}}
            <div class="card shadow-sm border-0 border-danger mb-4" style="border-width:1px !important;">
                <div class="card-body p-4 p-md-5">

                    <h2 class="h5 mb-1 text-danger">Delete Account</h2>
                    <p class="text-muted small mb-4">Once your account is deleted, all of its resources and data will be permanently deleted.</p>

                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                        {{ __('Delete Account') }}
                    </button>

                </div>
            </div>

        </div>
    </div>

    {{-- Delete Account Modal --}}
    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('DELETE')

                    <div class="modal-header border-0">
                        <h5 class="modal-title text-danger" id="deleteAccountModalLabel">Delete Account</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted">Are you sure you want to delete your account? This action cannot be undone. Please enter your password to confirm.</p>
                        <div class="mb-3">
                            <label for="delete_password" class="form-label">{{ __('Password') }}</label>
                            <input id="delete_password" type="password" name="password"
                                class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                                placeholder="Enter your current password">
                            @error('password', 'userDeletion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-danger">{{ __('Yes, Delete My Account') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        .form-label {
            font-size: .875rem;
            font-weight: 500;
        }
    </style>
@endpush
