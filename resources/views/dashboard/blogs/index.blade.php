@include('layouts.admin-header')

@include('layouts.admin-navbar')

<div class="dash-wrapper">
    <main class="dash-main mt-4">

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
            <h1 class="dash-page-title">Blog Posts</h1>
            <a href="{{ route('dashboard.blog.create') }}" class="btn-dash-primary" id="btnAddBlog">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2" />
                </svg>
                Add Blog Post
            </a>
        </div>

        {{-- Filter Bar --}}
        <form method="GET" action="{{ route('dashboard.blog.index') }}" class="card p-3 mb-4 border-0 shadow-sm rounded-3" style="background:#fff;">
            <div class="row g-2 align-items-center">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search title, excerpt, tags…" value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="cat_id" class="form-select form-select-sm">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('cat_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Status</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm px-3 fw-semibold">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    @if(request()->hasAny(['search', 'cat_id', 'status', 'page_show']))
                        <a href="{{ route('dashboard.blog.index') }}" class="btn btn-outline-secondary btn-sm px-3">Clear</a>
                    @endif
                </div>
            </div>
        </form>

        {{-- Table --}}
        <div class="dash-table-wrap">
            <table class="dash-table" id="blogsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>URL Slug</th>
                        <th>Category</th>
                        <th>Tags</th>
                        <th>Status</th>
                        <th>Page Show</th>
                        <th>Sort Order</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($blogs as $blog)
                        <tr>
                            <td class="text-muted" style="font-size:.78rem;">{{ $blog->id }}</td>
                            <td>
                                @if ($blog->image)
                                    <img src="{{ str_starts_with($blog->image, 'http') ? $blog->image : asset($blog->image) }}" alt="{{ $blog->title }}"
                                        style="width:48px;height:48px;object-fit:cover;border-radius:.35rem;border:1px solid #e8eaf0;">
                                @else
                                    <div style="width:48px;height:48px;border-radius:.35rem;background:#f3f4f6;display:flex;align-items:center;justify-content:center;color:#9ca3af;">
                                        <i class="bi bi-journal-text fs-5"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div style="font-weight:600;color:#1a1a1a;">{{ Str::limit($blog->title, 45) }}</div>
                                @if($blog->excerpt)
                                    <div style="font-size:.75rem;color:#9ca3af;">{{ Str::limit($blog->excerpt, 50) }}</div>
                                @endif
                            </td>
                            <td style="font-family:monospace;font-size:.8rem;">{{ $blog->url }}</td>
                            <td style="font-size:.82rem;">{{ $blog->category->name ?? '—' }}</td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    @forelse($blog->formatted_tags as $tag)
                                        <span class="badge bg-light text-dark border" style="font-size:.7rem;">{{ $tag }}</span>
                                    @empty
                                        <span class="text-muted small">—</span>
                                    @endforelse
                                </div>
                            </td>
                            <td>
                                <span class="{{ $blog->status ? 'badge-active' : 'badge-inactive' }}">
                                    {{ $blog->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <span class="{{ $blog->page_show ? 'badge bg-success-subtle text-success border border-success-subtle' : 'badge bg-secondary-subtle text-secondary' }}">
                                    {{ $blog->page_show ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td style="font-weight:600;font-size:.85rem;">{{ $blog->sort_order }}</td>
                            <td style="font-size:.78rem;color:#9ca3af;">{{ $blog->created_at ? $blog->created_at->format('d M Y') : '—' }}</td>
                            <td>
                                <div style="display:flex;gap:.4rem;">
                                    <a href="{{ route('dashboard.blog.show', $blog) }}" class="btn-dash-secondary" style="padding:.3rem .6rem;" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('dashboard.blog.edit', $blog) }}" class="btn-dash-secondary" style="padding:.3rem .6rem;" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="{{ route('blog.show', $blog->url) }}" target="_blank" class="btn-dash-secondary" style="padding:.3rem .6rem;color:#0d6efd;" title="Open Public Page">
                                        <i class="bi bi-box-arrow-up-right"></i>
                                    </a>
                                    <form method="POST" action="{{ route('dashboard.blog.destroy', $blog) }}" onsubmit="return confirm('Delete this blog post?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-dash-danger" style="padding:.3rem .6rem;" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center py-4 text-muted">
                                No blog posts found.
                                <a href="{{ route('dashboard.blog.create') }}" style="color:#1a6644;text-decoration:none;">Add your first blog post →</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($blogs->hasPages())
            <div class="mt-3 d-flex justify-content-end">
                {{ $blogs->links() }}
            </div>
        @endif

    </main>
</div>

@include('layouts.dash_styles')
@include('layouts.admin-footer')
