@extends('layouts.app')

@section('title', $meta['title'])
@section('meta_description', $meta['description'])

@section('content')
    <div class="row justify-content-center py-4">
        <div class="col-md-10 col-lg-8">

            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-md-5">

                    <h1 class="h4 mb-1 text-center">Create an Account</h1>
                    <p class="text-center text-muted small mb-4">Fill in the details below to get started</p>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <h6 class="text-uppercase text-muted fw-semibold mb-3" style="font-size:.7rem;letter-spacing:.08em;">Personal Information</h6>
                        <div class="row g-3 mb-3">

                            {{-- First Name --}}
                            <div class="col-md-6">
                                <label for="firstname" class="form-label">{{ __('First Name') }} <span class="text-danger">*</span></label>
                                <input id="firstname" type="text" name="firstname"
                                    class="form-control @error('firstname') is-invalid @enderror"
                                    value="{{ old('firstname') }}" required autofocus autocomplete="given-name">
                                @error('firstname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Last Name --}}
                            <div class="col-md-6">
                                <label for="lastname" class="form-label">{{ __('Last Name') }} <span class="text-danger">*</span></label>
                                <input id="lastname" type="text" name="lastname"
                                    class="form-control @error('lastname') is-invalid @enderror"
                                    value="{{ old('lastname') }}" required autocomplete="family-name">
                                @error('lastname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Email Address --}}
                            <div class="col-md-6">
                                <label for="email" class="form-label">{{ __('Email Address') }} <span class="text-danger">*</span></label>
                                <input id="email" type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}" required autocomplete="username">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Phone --}}
                            <div class="col-md-6">
                                <label for="phone" class="form-label">{{ __('Phone Number') }}</label>
                                <input id="phone" type="tel" name="phone"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    value="{{ old('phone') }}" autocomplete="tel">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Password --}}
                            <div class="col-md-6">
                                <label for="password" class="form-label">{{ __('Password') }} <span class="text-danger">*</span></label>
                                <input id="password" type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    required autocomplete="new-password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Confirm Password --}}
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }} <span class="text-danger">*</span></label>
                                <input id="password_confirmation" type="password" name="password_confirmation"
                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                    required autocomplete="new-password">
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <hr class="my-4">
                        <h6 class="text-uppercase text-muted fw-semibold mb-3" style="font-size:.7rem;letter-spacing:.08em;">Address Details</h6>
                        <div class="row g-3 mb-3">

                            {{-- Address Line 1 --}}
                            <div class="col-md-6">
                                <label for="address1" class="form-label">{{ __('Address Line 1') }}</label>
                                <input id="address1" type="text" name="address1"
                                    class="form-control @error('address1') is-invalid @enderror"
                                    value="{{ old('address1') }}" autocomplete="address-line1"
                                    placeholder="Street, building, flat no.">
                                @error('address1')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Address Line 2 --}}
                            <div class="col-md-6">
                                <label for="address2" class="form-label">{{ __('Address Line 2') }}</label>
                                <input id="address2" type="text" name="address2"
                                    class="form-control @error('address2') is-invalid @enderror"
                                    value="{{ old('address2') }}" autocomplete="address-line2"
                                    placeholder="Area, colony, sector">
                                @error('address2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Landmark --}}
                            <div class="col-md-6">
                                <label for="landmark" class="form-label">{{ __('Landmark') }}</label>
                                <input id="landmark" type="text" name="landmark"
                                    class="form-control @error('landmark') is-invalid @enderror"
                                    value="{{ old('landmark') }}" placeholder="Near school, temple etc.">
                                @error('landmark')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- City --}}
                            <div class="col-md-6">
                                <label for="city" class="form-label">{{ __('City') }}</label>
                                <input id="city" type="text" name="city"
                                    class="form-control @error('city') is-invalid @enderror"
                                    value="{{ old('city') }}" autocomplete="address-level2">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Pincode --}}
                            <div class="col-md-4">
                                <label for="pincode" class="form-label">{{ __('Pincode') }}</label>
                                <input id="pincode" type="text" name="pincode"
                                    class="form-control @error('pincode') is-invalid @enderror"
                                    value="{{ old('pincode') }}" autocomplete="postal-code"
                                    maxlength="10">
                                @error('pincode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- State --}}
                            <div class="col-md-4">
                                <label for="state" class="form-label">{{ __('State') }}</label>
                                <input id="state" type="text" name="state"
                                    class="form-control @error('state') is-invalid @enderror"
                                    value="{{ old('state') }}" autocomplete="address-level1">
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
                                    <option value="1"  {{ old('country_id') == 1  ? 'selected' : '' }}>India</option>
                                    <option value="2"  {{ old('country_id') == 2  ? 'selected' : '' }}>United States</option>
                                    <option value="3"  {{ old('country_id') == 3  ? 'selected' : '' }}>United Kingdom</option>
                                    <option value="4"  {{ old('country_id') == 4  ? 'selected' : '' }}>Canada</option>
                                    <option value="5"  {{ old('country_id') == 5  ? 'selected' : '' }}>Australia</option>
                                    <option value="6"  {{ old('country_id') == 6  ? 'selected' : '' }}>UAE</option>
                                    <option value="7"  {{ old('country_id') == 7  ? 'selected' : '' }}>Singapore</option>
                                </select>
                                @error('country_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="d-flex align-items-center justify-content-between mt-4 pt-2 border-top">
                            <a href="{{ route('login') }}" class="small text-decoration-underline text-muted">
                                {{ __('Already registered?') }}
                            </a>

                            <button type="submit" class="btn btn-primary px-4">
                                {{ __('Register') }}
                            </button>
                        </div>
                    </form>

                </div>
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