@include('layouts.header')

@include('layouts.navbar')

<main class="py-4">
    <div class="container">
        @yield('content')
    </div>
</main>

@include('layouts.footer')