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

        @if (session('import_errors'))
            <div class="card border-danger mb-4 shadow-sm">
                <div class="card-header bg-danger text-white fw-bold d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-exclamation-octagon me-2"></i>Import Error Details</span>
                    <span class="badge bg-white text-danger">{{ count(session('import_errors')) }} Errors</span>
                </div>
                <div class="card-body bg-light" style="max-height: 250px; overflow-y: auto;">
                    <ul class="mb-0 text-danger small">
                        @foreach (session('import_errors') as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

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

                        {{-- Mandatory Columns --}}
                        <div class="mb-4">
                            <h4 class="small fw-bold text-danger mb-2">
                                <i class="bi bi-asterisk text-danger me-1"></i>Mandatory Columns (Required):
                            </h4>
                            <div class="d-flex flex-wrap gap-1">
                                @foreach ($mandatoryColumns as $col)
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 font-monospace">
                                        {{ $col }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        {{-- Optional Columns --}}
                        <div>
                            <h4 class="small fw-bold text-secondary mb-2">
                                <i class="bi bi-check2-circle me-1"></i>Optional Columns:
                            </h4>
                            <div class="d-flex flex-wrap gap-1">
                                @foreach (array_diff($allColumns, $mandatoryColumns) as $col)
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
                                <li>If <code>id</code> or <code>sku</code> matches an existing product in your catalog, that product will be updated.</li>
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
