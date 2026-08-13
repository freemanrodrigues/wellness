<div class="offcanvas-lg offcanvas-start bg-white border-end" tabindex="-1" id="adminSidebar"
    aria-labelledby="adminSidebarLabel">

    <div class="offcanvas-header d-lg-none border-bottom">
        <h5 class="offcanvas-title" id="adminSidebarLabel">Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#adminSidebar"
            aria-label="Close"></button>
    </div>

    <div class="offcanvas-body d-flex flex-column p-0">
        <ul class="nav nav-pills flex-column mb-auto p-3">
            <li class="nav-item">
                <a href="{{ route('dashboard.index') }}"
                    class="nav-link {{ request()->routeIs('dashboard.index') ? 'active' : 'text-dark' }}">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
            </li>

        </ul>
    </div>
</div>