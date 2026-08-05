@extends('layouts.app')

@section('title', 'Privacy Policy')
@section('meta_description', 'Learn how ' . config('app.name') . ' collects, uses, and protects your personal information.')

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">

            <h1 class="mb-3">Privacy Policy</h1>
            <p class="text-muted">Last updated: {{ now()->format('F j, Y') }}</p>

            <p>
                {{ config('app.name') }} ("we", "us", or "our") respects your privacy and is committed to protecting
                the personal information you share with us. This Privacy Policy explains what information we collect,
                how we use it, and the choices you have regarding your information.
            </p>

            <h2 class="h4 mt-4">1. Information We Collect</h2>
            <p>We may collect the following types of information:</p>
            <ul>
                <li><strong>Account information:</strong> name, email address, phone number, and password when you register
                </li>
                <li><strong>Order information:</strong> billing and delivery addresses, recipient details, and order history
                </li>
                <li><strong>Payment information:</strong> processed securely through our third-party payment providers; we
                    do not store full card numbers on our servers</li>
                <li><strong>Usage data:</strong> pages visited, browser type, device information, and IP address, collected
                    automatically via cookies and similar technologies</li>
                <li><strong>Communications:</strong> any information you provide when contacting customer support</li>
            </ul>

            <h2 class="h4 mt-4">2. How We Use Your Information</h2>
            <p>We use the information we collect to:</p>
            <ul>
                <li>Process and fulfill your orders, including coordinating delivery</li>
                <li>Communicate with you about your orders, account, or customer support inquiries</li>
                <li>Send promotional emails and offers, where you have opted in (you may unsubscribe at any time)</li>
                <li>Improve our website, products, and services</li>
                <li>Detect, prevent, and address fraud, security issues, or technical problems</li>
                <li>Comply with legal obligations</li>
            </ul>

            <h2 class="h4 mt-4">3. Cookies and Tracking Technologies</h2>
            <p>
                We use cookies and similar tracking technologies to operate and improve the Service, remember your
                preferences, and analyze site traffic. You can control cookies through your browser settings; note
                that disabling cookies may affect the functionality of certain features, such as your shopping cart.
            </p>

            <h2 class="h4 mt-4">4. How We Share Your Information</h2>
            <p>We do not sell your personal information. We may share your information with:</p>
            <ul>
                <li><strong>Service providers:</strong> payment processors, delivery couriers, and vendors who fulfill
                    orders on our behalf, solely for the purpose of completing your order</li>
                <li><strong>Legal authorities:</strong> where required to comply with a legal obligation, court order, or to
                    protect our rights, property, or safety, or that of others</li>
                <li><strong>Business transfers:</strong> in connection with a merger, acquisition, or sale of assets,
                    subject to standard confidentiality arrangements</li>
            </ul>

            <h2 class="h4 mt-4">5. Data Retention</h2>
            <p>
                We retain your personal information for as long as necessary to fulfill the purposes outlined in this
                Policy, comply with legal obligations, resolve disputes, and enforce our agreements. When information
                is no longer needed, we take reasonable steps to delete or anonymize it.
            </p>

            <h2 class="h4 mt-4">6. Your Rights and Choices</h2>
            <p>Depending on your location, you may have the right to:</p>
            <ul>
                <li>Access the personal information we hold about you</li>
                <li>Request correction of inaccurate or incomplete information</li>
                <li>Request deletion of your personal information</li>
                <li>Opt out of marketing communications at any time</li>
                <li>Object to or restrict certain processing of your information</li>
            </ul>
            <p>
                To exercise any of these rights, contact us at
                <a href="mailto:privacy@{{ request()->getHost() }}">privacy@{{ request()->getHost() }}</a>.
                We will respond within the timeframe required by applicable law.
            </p>

            <h2 class="h4 mt-4">7. Data Security</h2>
            <p>
                We implement reasonable technical and organizational measures designed to protect your personal
                information from unauthorized access, loss, misuse, or alteration. However, no method of transmission
                over the internet or electronic storage is completely secure, and we cannot guarantee absolute
                security.
            </p>

            <h2 class="h4 mt-4">8. International Data Transfers</h2>
            <p>
                If you access the Service from outside [COUNTRY], your information may be transferred to, stored, and
                processed in [COUNTRY] or other countries where our servers or service providers operate. By using the
                Service, you consent to this transfer.
            </p>

            <h2 class="h4 mt-4">9. Children's Privacy</h2>
            <p>
                The Service is not directed to individuals under the age of 18, and we do not knowingly collect
                personal information from children. If you believe a child has provided us with personal information,
                please contact us so we can take appropriate action.
            </p>

            <h2 class="h4 mt-4">10. Changes to This Policy</h2>
            <p>
                We may update this Privacy Policy from time to time. Changes will be reflected by a revised "Last
                updated" date at the top of this page. We encourage you to review this Policy periodically.
            </p>

            <h2 class="h4 mt-4">11. Contact Us</h2>
            <p>
                If you have questions or concerns about this Privacy Policy or our data practices, please contact us
                at <a href="mailto:privacy@{{ request()->getHost() }}">privacy@{{ request()->getHost() }}</a>
                or write to us at [COMPANY ADDRESS].
            </p>

        </div>
    </div>
@endsection