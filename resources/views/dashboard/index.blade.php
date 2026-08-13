@include('layouts.admin-header')

@include('layouts.admin-navbar')

<div class="d-flex" style="margin-top: 102px;">

    <div class="admin-content flex-grow-1">
        <main class="p-4">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Make sure you have Bootstrap Icons included in your <head> for the icons to show up -->
        <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"> -->

        <div class="container-fluid my-4">
            <div class="row g-4">

                <!-- Summary Card 1: Total Users -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.85rem;">
                                        Total Users</p>
                                    <h3 class="mb-0 fw-bold">14,205</h3>
                                    <small class="text-success fw-medium">
                                        <i class="bi bi-arrow-up-short"></i> +12% this week
                                    </small>
                                </div>
                                <div class="icon-box bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 50px; height: 50px;">
                                    <i class="bi bi-people-fill fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Summary Card 2: Total Orders -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.85rem;">
                                        Total Orders</p>
                                    <h3 class="mb-0 fw-bold">8,540</h3>
                                    <small class="text-success fw-medium">
                                        <i class="bi bi-arrow-up-short"></i> +5.2% this week
                                    </small>
                                </div>
                                <div class="icon-box bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 50px; height: 50px;">
                                    <i class="bi bi-cart-check-fill fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Summary Card 3: Revenue -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.85rem;">
                                        Total Revenue</p>
                                    <h3 class="mb-0 fw-bold">$42,890</h3>
                                    <small class="text-danger fw-medium">
                                        <i class="bi bi-arrow-down-short"></i> -1.5% this week
                                    </small>
                                </div>
                                <div class="icon-box bg-warning-subtle text-warning-emphasis rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 50px; height: 50px;">
                                    <i class="bi bi-currency-dollar fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Summary Card 4: Pending Actions -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.85rem;">
                                        Pending Orders</p>
                                    <h3 class="mb-0 fw-bold">124</h3>
                                    <small class="text-muted fw-medium">
                                        Needs attention
                                    </small>
                                </div>
                                <div class="icon-box bg-danger-subtle text-danger rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 50px; height: 50px;">
                                    <i class="bi bi-clock-history fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>


        @include('layouts.admin-footer')
    </div>
</div>