@include('layouts.admin-header')

@include('layouts.admin-navbar')

<div class="d-flex" style="margin-top: 102px;">
    @include('layouts.admin-sidebar')

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

        @include('layouts.admin-footer')
    </div>
</div>