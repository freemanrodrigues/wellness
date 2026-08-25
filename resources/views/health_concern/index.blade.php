@include('layouts.admin-header')
@include('layouts.admin-navbar')
@include('layouts.dash_styles')

<div class="dash-wrapper" style="padding-top: 110px;">

    <main class="dash-main">

        {{-- Flash messages --}}
        @if (session('success'))
            <div class="dash-alert dash-alert--success" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="me-2">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                </svg>
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="dash-alert dash-alert--danger" role="alert">{{ session('error') }}</div>
        @endif

        <div class="dash-page-header">
            <h1 class="dash-page-title">Health Concerns</h1>
            <a href="{{ route('dashboard.health-concern.create') }}" class="btn-dash-primary" id="btnAddHealthConcern">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2" />
                </svg>
                Add Health Concern
            </a>
        </div>

        {{-- Filter bar --}}
        <form method="GET" action="{{ route('dashboard.health-concern.index') }}" class="dash-filter-bar" id="healthConcernFilterForm">
            <input type="text" name="search" class="dash-form-input" placeholder="Search name or slug…" value="{{ request('search') }}" id="healthConcernSearch">
            <select name="status" class="dash-form-select" style="max-width:140px;" id="statusFilter">
                <option value="">All Status</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
            </select>
            <button type="submit" class="btn-dash-primary">Filter</button>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('dashboard.health-concern.index') }}" class="btn-dash-secondary">Clear</a>
            @endif
        </form>

        {{-- Table --}}
        <div class="dash-table-card">
            <div class="table-responsive">
                <table class="dash-table" id="healthConcernTable">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th class="text-center">Sort</th>
                            <th class="text-center">Visibility</th>
                            <th class="text-center">Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($healthConcerns as $item)
                            <tr class="{{ $item->trashed() ? 'table-secondary opacity-75' : '' }}">
                                <td>
                                    @if($item->image)
                                        <img src="{{ asset($item->image) }}" alt="{{ $item->name }}" width="40" height="40" class="rounded border" style="object-fit:cover;">
                                    @elseif($item->icon)
                                        <img src="{{ asset($item->icon) }}" alt="{{ $item->name }}" width="40" height="40" class="rounded border" style="object-fit:cover;">
                                    @else
                                        <div class="bg-light rounded border text-muted d-flex align-items-center justify-content-center" style="width:40px; height:40px; font-size:.8rem;">
                                            <i class="bi bi-heart-pulse"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $item->name }}</div>
                                    @if($item->trashed())
                                        <span class="badge bg-danger extra-small">Deleted</span>
                                    @endif
                                </td>
                                <td class="text-muted font-monospace small">{{ $item->slug }}</td>
                                <td class="text-center small">{{ $item->sort_order }}</td>
                                <td class="text-center">
                                    @if($item->show_in_menu)
                                        <span class="badge bg-info-subtle text-info border border-info-subtle me-1" title="Show in Menu">Menu</span>
                                    @endif
                                    @if($item->show_on_homepage)
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle" title="Show on Homepage">Home</span>
                                    @endif
                                    @if(!$item->show_in_menu && !$item->show_on_homepage)
                                        <span class="text-muted small">&mdash;</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($item->status)
                                        <span class="dash-badge dash-badge--success">Active</span>
                                    @else
                                        <span class="dash-badge dash-badge--danger">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('dashboard.health-concern.show', $item) }}" class="btn btn-sm btn-light" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        @if($item->trashed())
                                            <form action="{{ route('dashboard.health-concern.restore', $item->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success" title="Restore">
                                                    <i class="bi bi-arrow-counterclockwise"></i>
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('dashboard.health-concern.edit', $item) }}" class="btn btn-sm btn-light" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('dashboard.health-concern.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this health concern?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light text-danger" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No health concerns found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($healthConcerns->hasPages())
                <div class="p-3 border-top">
                    {{ $healthConcerns->links() }}
                </div>
            @endif
        </div>

    </main>

</div>

@include('layouts.admin-footer')
