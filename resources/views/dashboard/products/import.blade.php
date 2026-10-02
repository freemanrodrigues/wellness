@include('layouts.admin-header')
@include('layouts.admin-navbar')

<div class="dash-wrapper">

    <main class="dash-main mt-5 pt-3">

        {{-- Flash Messages --}}
        <div class="mt-2 mb-4">
            @if (session('success'))
                <div class="dash-alert dash-alert--success mb-3" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="dash-alert dash-alert--danger mb-3" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="dash-alert dash-alert--danger mb-3" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    @foreach ($errors->all() as $err)
                        <div>{{ $err }}</div>
                    @endforeach
                </div>
            @endif

            @if (session('import_errors'))
                <div class="card border-danger mb-4 shadow-sm">
                    <div class="card-header bg-danger text-white fw-bold d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-exclamation-octagon me-2"></i>Import Error Details</span>
                        <span class="badge bg-white text-danger">{{ count(session('import_errors')) }} Errors</span>
                    </div>
                    <div class="card-body bg-light" style="max-height: 200px; overflow-y: auto;">
                        <ul class="mb-0 text-danger small">
                            @foreach (session('import_errors') as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @if (session('import_logs'))
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-dark text-white fw-bold d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-list-check me-2 text-info"></i>Import Execution Log (Row-by-Row Details)</span>
                        <span class="badge bg-secondary">{{ count(session('import_logs')) }} Rows Processed</span>
                    </div>
                    <div class="card-body bg-white p-3" style="max-height: 320px; overflow-y: auto;">
                        <div class="list-group list-group-flush small font-monospace">
                            @foreach (session('import_logs') as $log)
                                <div class="list-group-item bg-transparent px-2 py-1 border-bottom-0 d-flex align-items-start">
                                    @if ($log['status'] === 'success')
                                        <i class="bi bi-check-circle-fill text-success me-2 mt-1 fs-6"></i>
                                        <span class="text-success fw-medium">{{ $log['message'] }}</span>
                                    @else
                                        <i class="bi bi-x-circle-fill text-danger me-2 mt-1 fs-6"></i>
                                        <span class="text-danger fw-medium">{{ $log['message'] }}</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Page Header --}}
        <div class="dash-page-header d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="dash-page-title mb-1">Product CSV Uploader</h1>
                <nav style="font-size:.85rem; color:#6c757d;">
                    <a href="{{ route('dashboard.products.index') }}" style="color:#1a6644; text-decoration:none;">Products</a>
                    &rsaquo; Import CSV
                </nav>
            </div>
            <a href="{{ route('dashboard.products.sample-csv') }}" class="btn btn-outline-success btn-sm px-3 fw-bold">
                <i class="bi bi-download me-1"></i> Download Sample CSV
            </a>
        </div>

        <div class="row g-4">

            {{-- CSV Upload Form --}}
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h6 fw-bold mb-3 text-dark">
                            <i class="bi bi-cloud-arrow-up me-2 text-primary"></i>Upload CSV File
                        </h2>

                        <form action="{{ route('dashboard.products.import.process') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-4">
                                <label for="csv_file" class="form-label fw-semibold small">Choose CSV File <span class="text-danger">*</span></label>
                                <div class="border rounded p-4 text-center bg-light cursor-pointer" id="dropzoneContainer" style="border-style: dashed !important; border-width: 2px !important; border-color: #cbd5e1 !important;">
                                    <i class="bi bi-file-earmark-spreadsheet display-4 text-success d-block mb-2"></i>
                                    <span class="fw-bold d-block text-dark mb-1" id="fileSelectText">Click to select or drag &amp; drop CSV file here</span>
                                    <span class="text-muted extra-small">Supports .csv files up to 10MB</span>
                                    <input type="file" name="csv_file" id="csv_file" class="form-control d-none" accept=".csv,text/csv" required>
                                </div>
                                @error('csv_file')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                <a href="{{ route('dashboard.products.index') }}" class="btn btn-light text-secondary">
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-primary px-4 fw-bold" id="btnUploadSubmit">
                                    <i class="bi bi-upload me-1"></i> Upload &amp; Insert Products
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- CSV Schema Specifications Sidebar --}}
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="h6 fw-bold mb-3 text-dark">
                            <i class="bi bi-info-circle me-2 text-info"></i>CSV Column Specifications
                        </h3>

                        {{-- Mandatory Column for Update --}}
                        <div class="mb-3">
                            <h4 class="small fw-bold text-primary mb-2">
                                <i class="bi bi-pencil-square me-1"></i>Mandatory for UPDATE Operation:
                            </h4>
                            <div class="d-flex flex-wrap gap-1 mb-1">
                                @foreach ($mandatoryUpdateColumns as $col)
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 font-monospace">
                                        {{ $col }}
                                    </span>
                                @endforeach
                            </div>
                            <span class="extra-small text-muted d-block mt-1">+ At least one column to be updated</span>
                        </div>

                        {{-- Mandatory Columns for Insert --}}
                        <div class="mb-4">
                            <h4 class="small fw-bold text-danger mb-2">
                                <i class="bi bi-plus-circle text-danger me-1"></i>Mandatory for INSERT Operation:
                            </h4>
                            <div class="d-flex flex-wrap gap-1">
                                @foreach ($mandatoryInsertColumns as $col)
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 font-monospace">
                                        {{ $col }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        {{-- Other Support/Optional Columns --}}
                        <div>
                            <h4 class="small fw-bold text-secondary mb-2">
                                <i class="bi bi-check2-circle me-1"></i>Other Supported Columns:
                            </h4>
                            <div class="d-flex flex-wrap gap-1">
                                @foreach (array_diff($allColumns, array_merge($mandatoryUpdateColumns, $mandatoryInsertColumns)) as $col)
                                    <span class="badge bg-light text-dark border px-2 py-1 font-monospace">
                                        {{ $col }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <hr class="my-3">

                        <div class="small text-muted">
                            <strong>Note:</strong>
                            <ul class="ps-3 mb-0 mt-1 extra-small">
                                <li>If row has <code>id</code> value and matches an existing product in your database, it will perform an <strong>UPDATE</strong>.</li>
                                <li>If row has blank <code>id</code>, it will perform a new product <strong>INSERT</strong> (requires all mandatory insert columns).</li>
                                <li><code>isactive</code> can be set to <code>1</code> (Active) or <code>0</code> (Inactive).</li>
                                <li>All price values should be numeric (e.g. <code>29.99</code>).</li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </main>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('dropzoneContainer');
    const input = document.getElementById('csv_file');
    const text = document.getElementById('fileSelectText');

    container.addEventListener('click', () => input.click());

    input.addEventListener('change', function () {
        if (input.files.length > 0) {
            text.innerHTML = '<i class="bi bi-check-circle-fill text-success me-1"></i> Selected: ' + input.files[0].name;
            container.style.borderColor = '#16a34a';
        }
    });

    container.addEventListener('dragover', (e) => {
        e.preventDefault();
        container.style.borderColor = '#16a34a';
        container.style.backgroundColor = '#f0fdf4';
    });

    container.addEventListener('dragleave', () => {
        container.style.borderColor = '#cbd5e1';
        container.style.backgroundColor = '#f8fafc';
    });

    container.addEventListener('drop', (e) => {
        e.preventDefault();
        if (e.dataTransfer.files.length > 0) {
            input.files = e.dataTransfer.files;
            text.innerHTML = '<i class="bi bi-check-circle-fill text-success me-1"></i> Selected: ' + input.files[0].name;
            container.style.borderColor = '#16a34a';
        }
    });
});
</script>

@include('layouts.admin-footer')
