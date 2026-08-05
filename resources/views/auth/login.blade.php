@extends('layouts.app')

@section('title', $meta['title'])
@section('meta_description', $meta['description'])

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">

                    <h1 class="h4 mb-4 text-center">Log In</h1>

                    {{-- Session Status --}}
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        {{-- Email Address --}}
                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Email') }}</label>
                            <input id="email" type="email" name="email"
                                class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"
                                required autofocus autocomplete="username">
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('Password') }}</label>
                            <input id="password" type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror" required
                                autocomplete="current-password">
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Remember Me --}}
                        <div class="mb-3 form-check">
                            <input id="remember_me" type="checkbox" name="remember" class="form-check-input">
                            <label for="remember_me" class="form-check-label">
                                {{ __('Remember me') }}
                            </label>
                        </div>

                        <div class="d-flex align-items-center justify-content-between mt-4">
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="small text-decoration-underline text-muted">
                                    {{ __('Forgot your password?') }}
                                </a>
                            @endif

                            <button type="submit" class="btn btn-primary ms-auto">
                                {{ __('Log in') }}
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
        .product-card img {
            object-fit: cover;
            aspect-ratio: 1 / 1;
        }

        .product-card:hover {
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .1) !important;
            transition: box-shadow .2s ease-in-out;
        }
    </style>
@endpush