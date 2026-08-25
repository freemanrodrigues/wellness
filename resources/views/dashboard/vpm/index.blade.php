@include('layouts.admin-header')
@include('layouts.admin-navbar')

<div class="dash-wrapper">
    <main class="dash-main mt-4">

        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="dash-alert dash-alert--success" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="dash-alert dash-alert--danger" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('error') }}
            </div>
        @endif

        <div class="dash-page-header">
            <h1 class="dash-page-title">Vendor Product Management</h1>
            <div class="d-flex gap-2">
                <a href="{{ route('dashboard.vpm.create') }}" class="btn-dash-primary" id="btnAddVpm">
                    <i class="bi bi-plus-lg me-1"></i> Add Vendor Product
                </a>
            </div>
        </div>

        {{-- Filter Bar --}}
        <form method="GET" action="{{ route('dashboard.vpm.index') }}" class="dash-filter-bar" id="vpmFilterForm">
            <input type="text" name="search" class="dash-form-input" placeholder="Search name or vendor code…"
                value="{{ request('search') }}" id="vpmSearch">
            <select name="status" class="dash-form-select" style="max-width:160px;" id="vpmStatusFilter">
                <option value="">All Status</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
            </select>
            <button type="submit" class="btn-dash-primary">Filter</button>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('dashboard.vpm.index') }}" class="btn-dash-secondary">Clear</a>
            @endif
        </form>

        {{-- Table --}}
        <div class="dash-table-wrap">
            <table class="dash-table" id="vpmTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Vendor Code</th>
                        <th>Vid</th>
                        <th>Category</th>
                        <th>Brand</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr class="{{ $item->trashed() ? 'table-secondary opacity-75' : '' }}">
                            <td class="text-muted" style="font-size:.78rem;">{{ $item->id }}</td>
                            <td>
                                @if ($item->imgurl)
                                    <img src="{{ str_starts_with($item->imgurl, 'http') ? $item->imgurl : asset($item->imgurl) }}" alt="{{ $item->name }}"
                                        style="width:40px;height:40px;object-fit:cover;border-radius:.35rem;border:1px solid #e8eaf0;"
                                        onerror="this.src='/images/products/default.jpg'">
                                @else
                                    <div style="width:40px;height:40px;border-radius:.35rem;background:#f3f4f6;display:flex;align-items:center;justify-content:center;">
                                        <i class="bi bi-box-seam text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div style="font-weight:600;color:#1a1a1a;">{{ Str::limit($item->name, 40) }}</div>
                                @if($item->info)
                                    <div style="font-size:.75rem;color:#9ca3af;">{{ Str::limit($item->info, 50) }}</div>
                                @endif
                            </td>
                            <td class="fw-semibold">₹{{ number_format($item->price, 2) }}</td>
                            <td style="font-family:monospace;font-size:.8rem;">{{ $item->vendor_code ?? '—' }}</td>
                            <td>{{ $item->vid ?? '—' }}</td>
                            <td>{{ $item->category->name ?? '—' }}</td>
                            <td>{{ $item->brand->name ?? '—' }}</td>
                            <td>
                                @if($item->trashed())
                                    <span class="badge bg-secondary">Deleted</span>
                                @else
                                    <span class="{{ $item->status ? 'badge-active' : 'badge-inactive' }}">
                                        {{ $item->status ? 'Active' : 'Inactive' }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div style="display:flex;gap:.4rem;">
                                    @if($item->trashed())
                                        <form method="POST" action="{{ route('dashboard.vpm.restore', $item->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Restore">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('dashboard.vpm.show', $item) }}" class="btn-dash-secondary" style="padding:.3rem .6rem;" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('dashboard.vpm.edit', $item) }}" class="btn-dash-secondary" style="padding:.3rem .6rem;" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="POST" action="{{ route('dashboard.vpm.destroy', $item) }}"
                                              onsubmit="return confirm('Are you sure you want to delete this item?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-dash-danger" style="padding:.3rem .6rem;" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-4 text-muted">No vendor products found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $items->links() }}
        </div>

    </main>
</div>

@include('layouts.dash_styles')
@include('layouts.admin-footer')
