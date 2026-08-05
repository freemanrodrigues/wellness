@extends('layouts.app')

@section('title', 'Terms of Service')
@section('meta_description', 'Read the Terms of Service governing your use of ' . config('app.name') . '.')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">

        <h1 class="mb-3">Terms of Service</h1>
        <p class="text-muted">Last updated: {{ now()->format('F j, Y') }}</p>

        <p>
            Welcome to {{ config('app.name') }}. These Terms of Service ("Terms") govern your access to and use of
            our website, products, and services (collectively, the "Service"). By accessing or using the Service,
            you agree to be bound by these Terms. If you do not agree, please do not use the Service.
        </p>

        <h2 class="h4 mt-4">1. Eligibility</h2>
        <p>
            You must be at least 18 years old, or the age of legal majority in your jurisdiction, to create an
            account or make a purchase through the Service. By using the Service, you represent that you meet
            this requirement.
        </p>

        <h2 class="h4 mt-4">2. Account Registration</h2>
        <p>
            To access certain features, you may need to create an account. You agree to provide accurate, current,
            and complete information, and to keep this information up to date. You are responsible for maintaining
            the confidentiality of your account credentials and for all activity that occurs under your account.
            Notify us immediately at <a href="mailto:support@{{ request()->getHost() }}">support@{{ request()->getHost()
                }}</a>
            if you suspect unauthorized use of your account.
        </p>

        <h2 class="h4 mt-4">3. Orders and Payment</h2>
        <p>
            All orders placed through the Service are subject to acceptance and availability. We reserve the right
            to refuse or cancel any order
        </p>
    </div>
</div>
@endsections