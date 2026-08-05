<footer class="bg-dark text-white-50 py-4 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <p class="mb-0">&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="{{ route('privacy') }}" class="text-white-50 text-decoration-none me-3">Privacy Policy</a>
                <a href="{{ route('terms') }}" class="text-white-50 text-decoration-none">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>

<script src="{{ asset('js/app.js') }}" defer></script>

@stack('scripts')
</body>

</html>