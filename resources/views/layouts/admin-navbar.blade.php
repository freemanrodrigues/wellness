{{-- ═══════════════════════════════════════════════════════════
     Admin Top Bar — Row 1: Logo + Utility icons + Logout
     Row 2: Horizontal mega-nav with slide dropdowns (Desktop)
     Mobile: Offcanvas Drawer with smooth accordions
════════════════════════════════════════════════════════════ --}}

{{-- ROW 1 — Brand bar --}}
<div class="admin-topbar">
    <div class="admin-topbar__left">
        {{-- Mobile sidebar toggle --}}
        <button class="admin-topbar__hamburger d-lg-none" type="button"
                data-bs-toggle="offcanvas" data-bs-target="#adminMobileNav" aria-controls="adminMobileNav" aria-label="Toggle navigation">
            <i class="bi bi-list fs-3"></i>
        </button>

        {{-- Logo / Brand --}}
        <a href="{{ route('dashboard.index') }}" class="admin-topbar__brand">
            <img src="/images/logo.jpg" alt="{{ config('app.name') }}" style="height:30px;" class="me-2" onerror="this.style.display='none'">
            <span>{{ config('app.name') }}</span>
            <span class="admin-topbar__brand-badge">Admin</span>
        </a>
    </div>

    <div class="admin-topbar__right">
        {{-- Notifications --}}
        <div class="dropdown">
            <button class="admin-topbar__icon-btn" type="button"
                    data-bs-toggle="dropdown" aria-expanded="false" title="Notifications">
                <i class="bi bi-bell fs-5"></i>
                <span class="admin-topbar__badge">3</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end admin-dd">
                <li><h6 class="dropdown-header">Notifications</h6></li>
                <li><a class="dropdown-item" href="#">New order #10234 received</a></li>
                <li><a class="dropdown-item" href="#">Low stock: Ashwagandha Root Extract</a></li>
                <li><a class="dropdown-item" href="#">Vendor sync completed</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-center small" href="#">View all</a></li>
            </ul>
        </div>

        {{-- User menu --}}
        <div class="dropdown">
            <button class="admin-topbar__user-btn" type="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle fs-5"></i>
                <span class="d-none d-md-inline">{{ auth()->user()->firstname ?? auth()->user()->name ?? 'Admin' }}</span>
                <i class="bi bi-chevron-down" style="font-size:.65rem;"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end admin-dd">
                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                    <i class="bi bi-person me-2 text-muted"></i>My Profile
                </a></li>
                <li><a class="dropdown-item" href="{{ route('home') }}" target="_blank">
                    <i class="bi bi-box-arrow-up-right me-2 text-muted"></i>View Site
                </a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>

{{-- ROW 2 — Horizontal mega nav (Desktop) --}}
<nav class="admin-mainnav d-none d-lg-block" id="adminMainNav">
    <div class="admin-mainnav__inner">

        {{-- Dashboard (no dropdown) --}}
        <a href="{{ route('dashboard.index') }}"
           class="admin-mainnav__item {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
            <i class="bi bi-speedometer2 me-1"></i> Dashboard
        </a>

        {{-- Orders ↓ --}}
        <div class="admin-mainnav__dropdown-wrap">
            <button class="admin-mainnav__item {{ request()->routeIs('dashboard.orders.*') ? 'active' : '' }}"
                    type="button" aria-haspopup="true" aria-expanded="false">
                <i class="bi bi-bag-check me-1"></i> Orders
                <i class="bi bi-chevron-down admin-mainnav__caret"></i>
            </button>
            <div class="admin-mainnav__dropdown">
                <a href="#" class="admin-mainnav__dd-item">
                    <i class="bi bi-bag-plus text-success"></i>
                    <div><span>New Orders</span><small>Freshly placed orders</small></div>
                </a>
                <a href="#" class="admin-mainnav__dd-item">
                    <i class="bi bi-bag-check text-primary"></i>
                    <div><span>Executed Orders</span><small>Processed &amp; confirmed</small></div>
                </a>
                <a href="#" class="admin-mainnav__dd-item">
                    <i class="bi bi-truck text-warning"></i>
                    <div><span>Order For Delivery</span><small>Out for delivery</small></div>
                </a>
                <a href="#" class="admin-mainnav__dd-item">
                    <i class="bi bi-x-circle text-danger"></i>
                    <div><span>Failed Orders</span><small>Cancelled / failed</small></div>
                </a>
            </div>
        </div>

        {{-- Issues ↓ --}}
        <div class="admin-mainnav__dropdown-wrap">
            <button class="admin-mainnav__item" type="button" aria-haspopup="true" aria-expanded="false">
                <i class="bi bi-exclamation-triangle me-1"></i> Issues
                <i class="bi bi-chevron-down admin-mainnav__caret"></i>
            </button>
            <div class="admin-mainnav__dropdown">
                <a href="#" class="admin-mainnav__dd-item">
                    <i class="bi bi-folder2-open text-danger"></i>
                    <div><span>Open Issues</span><small>Require action</small></div>
                </a>
                <a href="#" class="admin-mainnav__dd-item">
                    <i class="bi bi-folder-check text-success"></i>
                    <div><span>Close Issues</span><small>Resolved tickets</small></div>
                </a>
                <a href="#" class="admin-mainnav__dd-item">
                    <i class="bi bi-hourglass-split text-warning"></i>
                    <div><span>Pending Issues</span><small>Awaiting response</small></div>
                </a>
            </div>
        </div>

        {{-- Users (no dropdown) --}}
        <a href="#" class="admin-mainnav__item">
            <i class="bi bi-people me-1"></i> Users
        </a>

        {{-- Products ↓ --}}
        <div class="admin-mainnav__dropdown-wrap">
            <button class="admin-mainnav__item {{ request()->routeIs('dashboard.products.*') ? 'active' : '' }}"
                    type="button" aria-haspopup="true" aria-expanded="false">
                <i class="bi bi-box-seam me-1"></i> Products
                <i class="bi bi-chevron-down admin-mainnav__caret"></i>
            </button>
            <div class="admin-mainnav__dropdown">
                <a href="{{ route('dashboard.products.index') }}" class="admin-mainnav__dd-item">
                    <i class="bi bi-grid text-primary"></i>
                    <div><span>View Products</span><small>Browse all products</small></div>
                </a>
                <a href="{{ route('dashboard.products.create') }}" class="admin-mainnav__dd-item">
                    <i class="bi bi-plus-circle text-success"></i>
                    <div><span>Add Products</span><small>Create a new listing</small></div>
                </a>
                <a href="{{ route('dashboard.products.import') }}" class="admin-mainnav__dd-item">
                    <i class="bi bi-file-earmark-spreadsheet text-info"></i>
                    <div><span>Import CSV</span><small>Upload products via CSV</small></div>
                </a>
            </div>
        </div>


        {{-- Vendors (no dropdown for now) --}}
        <a href="#" class="admin-mainnav__item">
            <i class="bi bi-truck me-1"></i> Vendors
        </a>

        {{-- Reports (no dropdown) --}}
        <a href="#" class="admin-mainnav__item">
            <i class="bi bi-graph-up me-1"></i> Reports
        </a>

        {{-- Affiliates (no dropdown) --}}
        <a href="#" class="admin-mainnav__item">
            <i class="bi bi-share me-1"></i> Affiliates
        </a>

        {{-- Miscellaneous ↓ --}}
        <div class="admin-mainnav__dropdown-wrap">
            <button class="admin-mainnav__item {{ request()->routeIs('dashboard.category.*') ? 'active' : '' }}"
                    type="button" aria-haspopup="true" aria-expanded="false">
                <i class="bi bi-three-dots me-1"></i> Miscellaneous
                <i class="bi bi-chevron-down admin-mainnav__caret"></i>
            </button>
            <div class="admin-mainnav__dropdown">
                <a href="{{ route('dashboard.category.index') }}" class="admin-mainnav__dd-item">
                    <i class="bi bi-tags text-indigo"></i>
                    <div><span>Categories</span><small>Manage product categories</small></div>
                </a>
                <a href="#" class="admin-mainnav__dd-item">
                    <i class="bi bi-gear text-muted"></i>
                    <div><span>Settings</span><small>App configuration</small></div>
                </a>
            </div>
        </div>

    </div>{{-- /.admin-mainnav__inner --}}
</nav>

{{-- Mobile Offcanvas Drawer --}}
<div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="adminMobileNav" aria-labelledby="adminMobileNavLabel">
    <div class="offcanvas-header bg-dark text-white">
        <h5 class="offcanvas-title d-flex align-items-center gap-2" id="adminMobileNavLabel">
            <img src="/images/logo.jpg" alt="{{ config('app.name') }}" style="height:24px;" onerror="this.style.display='none'">
            <span>{{ config('app.name') }} Admin</span>
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0">
        <div class="accordion accordion-flush" id="adminMobileAccordion">
            {{-- Dashboard --}}
            <div class="accordion-item">
                <a href="{{ route('dashboard.index') }}" class="accordion-button no-chevron py-3 px-3 fw-medium text-dark text-decoration-none {{ request()->routeIs('dashboard.index') ? 'active-link' : '' }}">
                    <i class="bi bi-speedometer2 me-2 text-primary"></i> Dashboard
                </a>
            </div>

            {{-- Orders --}}
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed py-3 px-3 fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#mobileOrders">
                        <i class="bi bi-bag-check me-2 text-success"></i> Orders
                    </button>
                </h2>
                <div id="mobileOrders" class="accordion-collapse collapse" data-bs-parent="#adminMobileAccordion">
                    <div class="accordion-body p-0 bg-light">
                        <div class="list-group list-group-flush">
                            <a href="#" class="list-group-item list-group-item-action bg-transparent border-0 ps-4 py-2">
                                <i class="bi bi-bag-plus text-success me-2"></i> New Orders
                            </a>
                            <a href="#" class="list-group-item list-group-item-action bg-transparent border-0 ps-4 py-2">
                                <i class="bi bi-bag-check text-primary me-2"></i> Executed Orders
                            </a>
                            <a href="#" class="list-group-item list-group-item-action bg-transparent border-0 ps-4 py-2">
                                <i class="bi bi-truck text-warning me-2"></i> Order For Delivery
                            </a>
                            <a href="#" class="list-group-item list-group-item-action bg-transparent border-0 ps-4 py-2">
                                <i class="bi bi-x-circle text-danger me-2"></i> Failed Orders
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Issues --}}
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed py-3 px-3 fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#mobileIssues">
                        <i class="bi bi-exclamation-triangle me-2 text-danger"></i> Issues
                    </button>
                </h2>
                <div id="mobileIssues" class="accordion-collapse collapse" data-bs-parent="#adminMobileAccordion">
                    <div class="accordion-body p-0 bg-light">
                        <div class="list-group list-group-flush">
                            <a href="#" class="list-group-item list-group-item-action bg-transparent border-0 ps-4 py-2">
                                <i class="bi bi-folder2-open text-danger me-2"></i> Open Issues
                            </a>
                            <a href="#" class="list-group-item list-group-item-action bg-transparent border-0 ps-4 py-2">
                                <i class="bi bi-folder-check text-success me-2"></i> Close Issues
                            </a>
                            <a href="#" class="list-group-item list-group-item-action bg-transparent border-0 ps-4 py-2">
                                <i class="bi bi-hourglass-split text-warning me-2"></i> Pending Issues
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Users --}}
            <div class="accordion-item">
                <a href="#" class="accordion-button no-chevron py-3 px-3 fw-medium text-dark text-decoration-none">
                    <i class="bi bi-people me-2 text-info"></i> Users
                </a>
            </div>

            {{-- Products --}}
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button {{ request()->routeIs('dashboard.products.*') ? '' : 'collapsed' }} py-3 px-3 fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#mobileProducts">
                        <i class="bi bi-box-seam me-2 text-primary"></i> Products
                    </button>
                </h2>
                <div id="mobileProducts" class="accordion-collapse collapse {{ request()->routeIs('dashboard.products.*') ? 'show' : '' }}" data-bs-parent="#adminMobileAccordion">
                    <div class="accordion-body p-0 bg-light">
                        <div class="list-group list-group-flush">
                            <a href="{{ route('dashboard.products.index') }}" class="list-group-item list-group-item-action bg-transparent border-0 ps-4 py-2 {{ request()->routeIs('dashboard.products.index') ? 'fw-bold text-primary' : '' }}">
                                <i class="bi bi-grid text-primary me-2"></i> View Products
                            </a>
                            <a href="{{ route('dashboard.products.create') }}" class="list-group-item list-group-item-action bg-transparent border-0 ps-4 py-2 {{ request()->routeIs('dashboard.products.create') ? 'fw-bold text-success' : '' }}">
                                <i class="bi bi-plus-circle text-success me-2"></i> Add Products
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Vendors --}}
            <div class="accordion-item">
                <a href="#" class="accordion-button no-chevron py-3 px-3 fw-medium text-dark text-decoration-none">
                    <i class="bi bi-truck me-2 text-secondary"></i> Vendors
                </a>
            </div>

            {{-- Reports --}}
            <div class="accordion-item">
                <a href="#" class="accordion-button no-chevron py-3 px-3 fw-medium text-dark text-decoration-none">
                    <i class="bi bi-graph-up me-2 text-success"></i> Reports
                </a>
            </div>

            {{-- Affiliates --}}
            <div class="accordion-item">
                <a href="#" class="accordion-button no-chevron py-3 px-3 fw-medium text-dark text-decoration-none">
                    <i class="bi bi-share me-2 text-primary"></i> Affiliates
                </a>
            </div>

            {{-- Miscellaneous --}}
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button {{ request()->routeIs('dashboard.category.*') ? '' : 'collapsed' }} py-3 px-3 fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#mobileMisc">
                        <i class="bi bi-three-dots me-2 text-dark"></i> Miscellaneous
                    </button>
                </h2>
                <div id="mobileMisc" class="accordion-collapse collapse {{ request()->routeIs('dashboard.category.*') ? 'show' : '' }}" data-bs-parent="#adminMobileAccordion">
                    <div class="accordion-body p-0 bg-light">
                        <div class="list-group list-group-flush">
                            <a href="{{ route('dashboard.category.index') }}" class="list-group-item list-group-item-action bg-transparent border-0 ps-4 py-2 {{ request()->routeIs('dashboard.category.*') ? 'fw-bold text-primary' : '' }}">
                                <i class="bi bi-tags me-2" style="color:#6366f1;"></i> Categories
                            </a>
                            <a href="#" class="list-group-item list-group-item-action bg-transparent border-0 ps-4 py-2">
                                <i class="bi bi-gear text-muted me-2"></i> Settings
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* ══════════════════════════════════════════════════════
   Admin Top Bar (Row 1)
══════════════════════════════════════════════════════ */
.admin-topbar {
    position: fixed;
    top: 0; left: 0; right: 0;
    z-index: 1050;
    height: 56px;
    background: #1a1a2e;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 1.25rem;
    border-bottom: 1px solid rgba(255,255,255,.08);
}

.admin-topbar__left {
    display: flex;
    align-items: center;
    gap: .75rem;
}

.admin-topbar__hamburger {
    background: transparent;
    border: none;
    color: rgba(255,255,255,.75);
    padding: .25rem;
    cursor: pointer;
    line-height: 1;
    transition: color .15s;
}
.admin-topbar__hamburger:hover { color: #fff; }

.admin-topbar__brand {
    display: flex;
    align-items: center;
    text-decoration: none;
    font-family: 'Playfair Display', serif;
    font-size: 1rem;
    font-weight: 700;
    color: #fff;
    letter-spacing: -.01em;
}
.admin-topbar__brand:hover { color: #a8e6cf; }
.admin-topbar__brand-badge {
    margin-left: .45rem;
    font-size: .62rem;
    font-weight: 600;
    font-family: 'Inter', sans-serif;
    background: rgba(26,102,68,.85);
    color: #a8e6cf;
    padding: .1rem .45rem;
    border-radius: 3rem;
    letter-spacing: .05em;
    text-transform: uppercase;
}

.admin-topbar__right {
    display: flex;
    align-items: center;
    gap: .5rem;
}

.admin-topbar__icon-btn {
    position: relative;
    background: transparent;
    border: none;
    color: rgba(255,255,255,.75);
    padding: .35rem .5rem;
    border-radius: .4rem;
    cursor: pointer;
    transition: background .15s, color .15s;
}
.admin-topbar__icon-btn:hover { background: rgba(255,255,255,.1); color: #fff; }

.admin-topbar__badge {
    position: absolute;
    top: 2px; right: 2px;
    background: #e53e3e;
    color: #fff;
    font-size: .6rem;
    font-weight: 700;
    width: 16px; height: 16px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
}

.admin-topbar__user-btn {
    display: flex;
    align-items: center;
    gap: .45rem;
    background: rgba(255,255,255,.07);
    border: 1px solid rgba(255,255,255,.12);
    color: rgba(255,255,255,.9);
    padding: .35rem .75rem;
    border-radius: .45rem;
    font-size: .84rem;
    font-weight: 500;
    cursor: pointer;
    transition: background .15s, border-color .15s;
    font-family: 'Inter', sans-serif;
}
.admin-topbar__user-btn:hover {
    background: rgba(255,255,255,.14);
    border-color: rgba(255,255,255,.25);
    color: #fff;
}

/* Dropdown overrides */
.admin-dd {
    border: 1px solid #e8eaf0;
    border-radius: .55rem;
    box-shadow: 0 8px 24px rgba(0,0,0,.12);
    padding: .4rem;
    min-width: 200px;
}
.admin-dd .dropdown-item {
    border-radius: .35rem;
    font-size: .84rem;
    padding: .45rem .75rem;
    transition: background .12s;
}
.admin-dd .dropdown-item:hover { background: #f0f9f4; color: #1a6644; }
.admin-dd .dropdown-item.text-danger:hover { background: #fff5f5; color: #9b2c2c !important; }
.admin-dd .dropdown-header { font-size: .72rem; color: #9ca3af; letter-spacing: .07em; text-transform: uppercase; padding: .4rem .75rem .2rem; }
.admin-dd .dropdown-divider { margin: .3rem 0; }

/* ══════════════════════════════════════════════════════
   Admin Main Nav (Row 2) — horizontal desktop
══════════════════════════════════════════════════════ */
.admin-mainnav {
    position: fixed;
    top: 56px; left: 0; right: 0;
    z-index: 1040;
    background: #fff;
    border-bottom: 1px solid #e8eaf0;
    box-shadow: 0 2px 8px rgba(0,0,0,.06);
}

.admin-mainnav__inner {
    display: flex;
    align-items: center;
    height: 46px;
    padding: 0 1.25rem;
    gap: .1rem;
}

/* Items (both <a> and <button>) */
.admin-mainnav__item {
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    white-space: nowrap;
    font-size: .82rem;
    font-weight: 500;
    color: #374151;
    background: transparent;
    border: none;
    padding: .5rem .75rem;
    border-radius: .4rem;
    text-decoration: none;
    cursor: pointer;
    transition: background .15s, color .15s;
    font-family: 'Inter', sans-serif;
    height: 34px;
}
.admin-mainnav__item:hover { background: #f0f9f4; color: #1a6644; }
.admin-mainnav__item.active { background: #e6f4ed; color: #1a6644; font-weight: 600; }

.admin-mainnav__caret {
    font-size: .6rem;
    margin-left: .1rem;
    transition: transform .2s ease;
}

/* Dropdown wrapper (hover + click slide mechanism) */
.admin-mainnav__dropdown-wrap {
    position: relative;
    display: inline-flex;
    align-items: center;
}

/* Dropdown panel with slide animation */
.admin-mainnav__dropdown {
    position: absolute;
    top: calc(100% + 4px);
    left: 0;
    min-width: 230px;
    background: #fff;
    border: 1px solid #e8eaf0;
    border-radius: .6rem;
    box-shadow: 0 12px 32px rgba(0,0,0,.13);
    padding: .4rem;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-8px);
    transition: opacity .2s ease, transform .2s ease, visibility .2s ease;
    pointer-events: none;
    z-index: 1100;
}

/* Hover or Active toggle class */
.admin-mainnav__dropdown-wrap:hover .admin-mainnav__dropdown,
.admin-mainnav__dropdown-wrap.show .admin-mainnav__dropdown {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
    pointer-events: auto;
}
.admin-mainnav__dropdown-wrap:hover .admin-mainnav__caret,
.admin-mainnav__dropdown-wrap.show .admin-mainnav__caret {
    transform: rotate(180deg);
}

/* Dropdown items */
.admin-mainnav__dd-item {
    display: flex;
    align-items: center;
    gap: .75rem;
    padding: .55rem .75rem;
    border-radius: .4rem;
    text-decoration: none;
    color: #374151;
    transition: background .13s, color .13s;
}
.admin-mainnav__dd-item:hover { background: #f0f9f4; color: #1a6644; }
.admin-mainnav__dd-item i { font-size: 1.05rem; flex-shrink: 0; }
.admin-mainnav__dd-item span { display: block; font-size: .835rem; font-weight: 600; line-height: 1.2; }
.admin-mainnav__dd-item small { display: block; font-size: .72rem; color: #9ca3af; line-height: 1.2; margin-top: .05rem; }
.admin-mainnav__dd-item:hover small { color: #6b9e88; }

.text-indigo { color: #6366f1 !important; }

/* Mobile Accordion Styles */
.accordion-button.no-chevron::after {
    display: none !important;
}
.accordion-button.active-link {
    background-color: #e6f4ed !important;
    color: #1a6644 !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Click toggle for desktop dropdowns (in addition to hover)
    const dropdownWraps = document.querySelectorAll('.admin-mainnav__dropdown-wrap');

    dropdownWraps.forEach(function (wrap) {
        const btn = wrap.querySelector('.admin-mainnav__item');
        if (btn) {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                // Close other open dropdowns
                dropdownWraps.forEach(function (other) {
                    if (other !== wrap) other.classList.remove('show');
                });
                wrap.classList.toggle('show');
            });
        }
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function () {
        dropdownWraps.forEach(function (wrap) {
            wrap.classList.remove('show');
        });
    });
});
</script>