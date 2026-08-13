<footer class="pf-footer" id="siteFooter">
    <div class="container">
        <div class="pf-footer__inner">

            {{-- Brand & copyright --}}
            <div class="pf-footer__brand">
                <a class="pf-footer__logo" href="{{ url('/') }}">
                    <img src="/images/logo.jpg" alt="{{ config('app.name') }}" style="height:32px;" class="me-2">
                    <span>{{ config('app.name', 'Wellness') }}</span>
                </a>
                <p class="pf-footer__copy">&copy; {{ date('Y') }} {{ config('app.name', 'Wellness') }}. All rights reserved.</p>
            </div>

            {{-- Footer links --}}
            <nav class="pf-footer__nav" aria-label="Footer navigation">
                <a href="{{ route('about') }}" class="pf-footer__link" id="footerAbout">About</a>
                <span class="pf-footer__sep" aria-hidden="true">&middot;</span>
                <a href="{{ route('contact') }}" class="pf-footer__link" id="footerContact">Contact</a>
                <span class="pf-footer__sep" aria-hidden="true">&middot;</span>
                <a href="{{ route('privacy') }}" class="pf-footer__link" id="footerPrivacy">Privacy Policy</a>
                <span class="pf-footer__sep" aria-hidden="true">&middot;</span>
                <a href="{{ route('terms') }}" class="pf-footer__link" id="footerTerms">Terms of Service</a>
            </nav>

        </div>
    </div>
</footer>

{{-- Footer styles are in layouts/header.blade.php --}}

<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>

<script src="{{ asset('js/app.js') }}" defer></script>

@stack('scripts')
</body>

</html>