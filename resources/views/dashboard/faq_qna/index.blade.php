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
            <div>
                <h1 class="dash-page-title">FAQ &amp; Product Q&amp;A</h1>
                <p class="text-muted small mb-0">Manage website FAQs and customer product questions &amp; answers.</p>
            </div>
            <a href="{{ route('dashboard.faq-qna.create') }}" class="btn-dash-primary" id="btnAddFaq">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2" />
                </svg>
                Add FAQ / Question
            </a>
        </div>

        {{-- Summary Cards --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small">Total FAQs</span>
                            <h3 class="fw-bold mb-0 text-dark">{{ $counts['faq'] ?? 0 }}</h3>
                        </div>
                        <div class="bg-primary-subtle text-primary p-3 rounded-circle">
                            <i class="bi bi-question-circle fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small">Product Questions</span>
                            <h3 class="fw-bold mb-0 text-dark">{{ $counts['question'] ?? 0 }}</h3>
                        </div>
                        <div class="bg-info-subtle text-info p-3 rounded-circle">
                            <i class="bi bi-chat-dots fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small">Pending Response</span>
                            <h3 class="fw-bold mb-0 text-warning">{{ $counts['pending'] ?? 0 }}</h3>
                        </div>
                        <div class="bg-warning-subtle text-warning p-3 rounded-circle">
                            <i class="bi bi-clock-history fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small">Total Records</span>
                            <h3 class="fw-bold mb-0 text-success">{{ $counts['all'] ?? 0 }}</h3>
                        </div>
                        <div class="bg-success-subtle text-success p-3 rounded-circle">
                            <i class="bi bi-list-check fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter bar --}}
        <form method="GET" action="{{ route('dashboard.faq-qna.index') }}" class="dash-filter-bar mb-4" id="faqFilterForm">
            <input type="text" name="search" class="dash-form-input" placeholder="Search question, answer, user, email…"
                value="{{ request('search') }}" id="faqSearch">

            <select name="type" class="dash-form-select" style="max-width:130px;" id="typeFilter">
                <option value="">All Types</option>
                <option value="faq" {{ request('type') === 'faq' ? 'selected' : '' }}>FAQ</option>
                <option value="question" {{ request('type') === 'question' ? 'selected' : '' }}>Q&amp;A</option>
            </select>

            <select name="status" class="dash-form-select" style="max-width:150px;" id="statusFilter">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending Answer</option>
                <option value="answered" {{ request('status') === 'answered' ? 'selected' : '' }}>Answered</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>

            <select name="target" class="dash-form-select" style="max-width:160px;" id="targetFilter">
                <option value="">All Targets</option>
                <option value="product" {{ request('target') === 'product' ? 'selected' : '' }}>Product</option>
                <option value="category" {{ request('target') === 'category' ? 'selected' : '' }}>Category</option>
                <option value="subcategory" {{ request('target') === 'subcategory' ? 'selected' : '' }}>Subcategory</option>
                <option value="brand" {{ request('target') === 'brand' ? 'selected' : '' }}>Brand</option>
                <option value="page" {{ request('target') === 'page' ? 'selected' : '' }}>Website Page</option>
            </select>

            <button type="submit" class="btn-dash-primary">Filter</button>
            @if(request()->hasAny(['search', 'type', 'status', 'target', 'trashed']))
                <a href="{{ route('dashboard.faq-qna.index') }}" class="btn-dash-secondary">Clear</a>
            @endif
        </form>

        {{-- Table --}}
        <div class="dash-table-card">
            <div class="table-responsive">
                <table class="dash-table" id="faqTable">
                    <thead>
                        <tr>
                            <th style="width: 80px;">Type</th>
                            <th>Target / Association</th>
                            <th>Question</th>
                            <th>Answer</th>
                            <th>Submitted By</th>
                            <th class="text-center">Active</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                            <tr class="{{ $item->trashed() ? 'table-secondary opacity-75' : '' }}">
                                <td>
                                    @if($item->type === 'faq')
                                        <span class="badge bg-primary px-2 py-1">FAQ</span>
                                    @else
                                        <span class="badge bg-info text-dark px-2 py-1">Q&amp;A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->product)
                                        <div class="fw-semibold text-dark small"><i class="bi bi-box me-1 text-primary"></i> {{ $item->product->name }}</div>
                                        <span class="badge bg-light text-secondary border">Product</span>
                                    @elseif($item->subcategory)
                                        <div class="fw-semibold text-dark small"><i class="bi bi-diagram-2 me-1 text-info"></i> {{ $item->subcategory->name }}</div>
                                        <span class="badge bg-light text-secondary border">Subcategory</span>
                                    @elseif($item->category)
                                        <div class="fw-semibold text-dark small"><i class="bi bi-folder me-1 text-warning"></i> {{ $item->category->name }}</div>
                                        <span class="badge bg-light text-secondary border">Category</span>
                                    @elseif($item->brand)
                                        <div class="fw-semibold text-dark small"><i class="bi bi-tag me-1 text-success"></i> {{ $item->brand->name }}</div>
                                        <span class="badge bg-light text-secondary border">Brand</span>
                                    @elseif($item->page_name)
                                        <div class="fw-semibold text-dark small"><i class="bi bi-file-earmark-text me-1 text-danger"></i> {{ ucfirst($item->page_name) }}</div>
                                        <span class="badge bg-light text-secondary border">Website Page</span>
                                    @else
                                        <span class="text-muted small">— General —</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-medium text-dark" style="max-width: 260px;">
                                        {{ $item->question }}
                                    </div>
                                </td>
                                <td>
                                    @if(!empty($item->answer))
                                        <div class="text-muted small" style="max-width: 260px;">
                                            {{ Str::limit($item->answer, 90) }}
                                        </div>
                                    @else
                                        <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-circle me-1"></i> Awaiting Reply</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->user)
                                        <div class="fw-semibold small text-dark">{{ $item->user->firstname }} {{ $item->user->lastname }}</div>
                                        <div class="text-muted small">{{ $item->user->email }}</div>
                                    @elseif($item->user_name || $item->email)
                                        <div class="fw-semibold small text-dark">{{ $item->user_name ?: 'Visitor' }}</div>
                                        <div class="text-muted small">{{ $item->email ?: 'No email' }}</div>
                                    @else
                                        <span class="text-muted small">Admin Created</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($item->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        @if($item->type === 'question' && empty($item->answer))
                                            <button type="button" class="btn btn-sm btn-success px-2 py-1 me-1" data-bs-toggle="modal" data-bs-target="#replyModal{{ $item->id }}" title="Reply to Question">
                                                <i class="bi bi-reply-fill"></i> Reply
                                            </button>
                                        @endif

                                        <a href="{{ route('dashboard.faq-qna.edit', $item->id) }}" class="btn btn-sm btn-outline-primary px-2 py-1" title="Edit">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>

                                        @if($item->trashed())
                                            <form method="POST" action="{{ route('dashboard.faq-qna.restore', $item->id) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success px-2 py-1" title="Restore">
                                                    <i class="bi bi-arrow-counterclockwise"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('dashboard.faq-qna.destroy', $item->id) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger px-2 py-1" title="Delete">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                    {{-- Reply Modal for pending questions --}}
                                    @if($item->type === 'question')
                                        <div class="modal fade text-start" id="replyModal{{ $item->id }}" tabindex="-1" aria-labelledby="replyModalLabel{{ $item->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 shadow">
                                                    <form method="POST" action="{{ route('dashboard.faq-qna.reply', $item->id) }}">
                                                        @csrf
                                                        <div class="modal-header bg-light">
                                                            <h5 class="modal-title fs-6 fw-bold" id="replyModalLabel{{ $item->id }}">
                                                                <i class="bi bi-chat-quote me-1 text-primary"></i> Answer Question #{{ $item->id }}
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3 p-3 bg-light rounded border">
                                                                <span class="text-muted small fw-bold text-uppercase d-block mb-1">Customer Question:</span>
                                                                <p class="mb-0 text-dark fw-medium">{{ $item->question }}</p>
                                                                <div class="mt-2 text-muted small">
                                                                    Asked by: <strong>{{ $item->user_name ?: ($item->user ? $item->user->firstname : 'Visitor') }}</strong>
                                                                    @if($item->product)
                                                                        on <strong>{{ $item->product->name }}</strong>
                                                                    @endif
                                                                </div>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="answerText{{ $item->id }}" class="form-label fw-semibold">Admin Response / Answer <span class="text-danger">*</span></label>
                                                                <textarea name="answer" id="answerText{{ $item->id }}" rows="4" class="form-control" placeholder="Write your official response here..." required>{{ old('answer', $item->answer) }}</textarea>
                                                                <div class="form-text">Replying will automatically mark this Q&amp;A active and display it on the product page.</div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer bg-light">
                                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-primary btn-sm px-3"><i class="bi bi-check2-circle me-1"></i> Save &amp; Publish Answer</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    No FAQs or Questions found matching your criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($items->hasPages())
                <div class="p-3 border-top">
                    {{ $items->links() }}
                </div>
            @endif
        </div>

    </main>
</div>

@include('layouts.admin-footer')
