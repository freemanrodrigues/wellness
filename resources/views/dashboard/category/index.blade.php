@include('layouts.admin-header')

@include('layouts.admin-navbar')

<div class="dash-wrapper">

    <main class="dash-main mt-4">

        {{-- Flash messages --}}
        @if (session('success'))
            <div class="dash-alert dash-alert--success" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"
                    class="me-2">
                    <path
                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                </svg>
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="dash-alert dash-alert--danger" role="alert">{{ session('error') }}</div>
        @endif

        <div class="dash-page-header">
            <h1 class="dash-page-title">Categories</h1>
            <a href="{{ route('dashboard.category.create') }}" class="btn-dash-primary" id="btnAddCategory">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                    <path
                        d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2" />
                </svg>
                Add Category
            </a>
        </div>

        {{-- Filter bar --}}
        <form method="GET" action="{{ route('dashboard.category.index') }}" class="dash-filter-bar"
            id="categoryFilterForm">
            <input type="text" name="search" class="dash-form-input" placeholder="Search name or slug…"
                value="{{ request('search') }}" id="categorySearch">
            <select name="parent_id" class="dash-form-select" style="max-width:180px;" id="parentFilter">
                <option value="">All Parents</option>
                <option value="0" {{ request('parent_id') === '0' ? 'selected' : '' }}>Root only</option>
                @foreach ($parents as $p)
                    <option value="{{ $p->id }}" {{ request('parent_id') == $p->id ? 'selected' : '' }}>
                        {{ $p->name }}
                    </option>
                @endforeach
            </select>
            <select name="status" class="dash-form-select" style="max-width:140px;" id="statusFilter">
                <option value="">All Status</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
            </select>
            <button type="submit" class="btn-dash-primary">Filter</button>
            @if(request()->hasAny(['search', 'parent_id', 'status']))
                <a href="{{ route('dashboard.category.index') }}" class="btn-dash-secondary">Clear</a>
            @endif
        </form>

        {{-- Table --}}
        <div class="dash-table-wrap">
            <table class="dash-table" id="categoriesTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Parent</th>
                        <th>Order</th>
                        <th>Menu</th>
                        <th>Homepage</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $cat)
                        <tr class="{{ $cat->trashed() ? 'table-row--deleted' : '' }}">
                            <td style="font-size:.78rem;color:#9ca3af;">{{ $cat->id }}</td>
                            <td>
                                @if ($cat->image)
                                    <img src="{{ $cat->image }}" alt="{{ $cat->name }}"
                                        style="width:42px;height:42px;object-fit:cover;border-radius:.35rem;border:1px solid #e8eaf0;">
                                @else
                                    <div
                                        style="width:42px;height:42px;border-radius:.35rem;background:#f3f4f6;display:flex;align-items:center;justify-content:center;">
                                        @if($cat->icon)
                                            <span style="font-size:1.1rem;">{{ $cat->icon }}</span>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#9ca3af"
                                                viewBox="0 0 16 16">
                                                <path
                                                    d="M2 2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2zm0 1h12a1 1 0 0 1 1 1v.217l-7 4.2-7-4.2V4a1 1 0 0 1 1-1m0 9a1 1 0 0 1-1-1V6.383l6.526 3.916a1 1 0 0 0 .948 0L15 6.383V11a1 1 0 0 1-1 1z" />
                                            </svg>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div style="font-weight:600;color:{{ $cat->trashed() ? '#9ca3af' : '#1a1a1a' }};">
                                    {{ $cat->name }}
                                    @if($cat->trashed())
                                        <span style="font-size:.7rem;color:#c53030;font-weight:500;"> (deleted)</span>
                                    @endif
                                </div>
                            </td>
                            <td style="font-family:monospace;font-size:.78rem;color:#6b7280;">{{ $cat->slug }}</td>
                            <td style="font-size:.82rem;">{{ $cat->parent?->name ?? '—' }}</td>
                            <td style="font-size:.82rem;text-align:center;">{{ $cat->sort_order }}</td>
                            <td style="text-align:center;">
                                @if($cat->show_in_menu)
                                    <span class="badge-active">Yes</span>
                                @else
                                    <span style="color:#9ca3af;font-size:.78rem;">No</span>
                                @endif
                            </td>
                            <td style="text-align:center;">
                                @if($cat->show_on_homepage)
                                    <span class="badge-active">Yes</span>
                                @else
                                    <span style="color:#9ca3af;font-size:.78rem;">No</span>
                                @endif
                            </td>
                            <td>
                                <span class="{{ $cat->status ? 'badge-active' : 'badge-inactive' }}">
                                    {{ $cat->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div style="display:flex;gap:.35rem;flex-wrap:wrap;">
                                    @if(!$cat->trashed())
                                        <a href="{{ route('dashboard.category.show', $cat) }}" class="btn-dash-secondary"
                                            style="padding:.3rem .55rem;" title="View">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor"
                                                viewBox="0 0 16 16">
                                                <path
                                                    d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z" />
                                                <path
                                                    d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('dashboard.category.edit', $cat) }}" class="btn-dash-secondary"
                                            style="padding:.3rem .55rem;" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor"
                                                viewBox="0 0 16 16">
                                                <path
                                                    d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z" />
                                            </svg>
                                        </a>
                                        <form method="POST" action="{{ route('dashboard.category.destroy', $cat) }}"
                                            onsubmit="return confirm('Delete \'{{ addslashes($cat->name) }}\'?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-dash-danger" style="padding:.3rem .55rem;"
                                                title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13"
                                                    fill="currentColor" viewBox="0 0 16 16">
                                                    <path
                                                        d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                                                    <path
                                                        d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                                                </svg>
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('dashboard.category.restore', $cat->id) }}">
                                            @csrf
                                            <button type="submit" class="btn-dash-secondary"
                                                style="padding:.3rem .65rem;font-size:.75rem;" title="Restore">
                                                ↩ Restore
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-4" style="color:#9ca3af;">
                                No categories found.
                                <a href="{{ route('dashboard.category.create') }}"
                                    style="color:#1a6644;text-decoration:none;">Add your first category →</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($categories->hasPages())
            <div class="mt-3 d-flex justify-content-end">
                {{ $categories->links() }}
            </div>
        @endif

    </main>
</div>

<style>
    .table-row--deleted td {
        opacity: .55;
    }
</style>

@include('layouts.dash_styles')
@include('layouts.admin-footer')