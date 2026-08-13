<footer class="admin-footer border-top py-3 px-4 text-muted small">
    &copy; {{ date('Y') }} {{ config('app.name') }}. Admin Panel v1.0.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>

<script src="{{ asset('js/admin.js') }}" defer></script>

@stack('scripts')
</body>

</html>