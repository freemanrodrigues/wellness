@extends('layouts.app')

@section('title', $meta['title'] ?? 'Shop By Health Concern')
@section('meta_description', $meta['description'] ?? 'Browse health concerns')

@section('content')
    <style>
        .hc-hero {
            background: linear-gradient(135deg, #064e3b 0%, #047857 50%, #10b981 100%);
            border-radius: 16px;
            color: #ffffff;
            padding: 2.5rem 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 25px -5px rgba(6, 78, 59, 0.25);
        }

        .hc-search-input {
            border-radius: 50rem;
            padding: 0.75rem 1.5rem;
            font-size: 0.95rem;
            border: 2px solid #e5e7eb;
            transition: all 0.2s ease;
        }

        .hc-search-input:focus {
            border-color: #047857;
            box-shadow: 0 0 0 4px rgba(4, 120, 87, 0.15);
            outline: none;
        }

        .hc-card {
            background: #ffffff;
            border-radius: 14px;
            border: 1px solid #f0f0f0;
            padding: 1.25rem 1rem;
            transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
            text-decoration: none !important;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            min-height: 150px;
        }

        .hc-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px -6px rgba(0, 0, 0, 0.08);
            border-color: #047857;
        }

        .hc-card__icon-wrap {
            width: 64px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.85rem;
            border-radius: 14px;
            background: #f0fdf4;
            color: #047857;
            overflow: hidden;
            transition: background 0.2s ease, color 0.2s ease;
        }

        .hc-card:hover .hc-card__icon-wrap {
            background: #dcfce7;
            color: #065f46;
        }

        .hc-card__img {
            max-width: 80%;
            max-height: 80%;
            object-fit: contain;
        }

        .hc-card__avatar {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #047857 0%, #10b981 100%);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
        }

        .hc-card__name {
            font-weight: 600;
            font-size: 0.92rem;
            color: #1f2937;
            margin: 0;
            text-align: center;
            line-height: 1.35;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            transition: color 0.2s ease;
        }

        .hc-card:hover .hc-card__name {
            color: #047857;
        }
    </style>

    <div class="container-fluid px-0">

        {{-- Hero Section --}}
        <div class="hc-hero text-center position-relative">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <span class="badge bg-white text-dark fw-bold px-3 py-2 rounded-pill mb-3 text-uppercase"
                        style="letter-spacing:1px; font-size:0.75rem;">
                        Targeted Wellness Solutions
                    </span>
                    <h1 class="display-6 fw-bold mb-2">Shop By Health Concern</h1>
                    <p class="lead mb-4 text-white-50 fs-6">
                        Find natural remedies, supplements, and care products tailored for your specific health conditions.
                    </p>

                    {{-- Live Search Input --}}
                    <div class="position-relative mx-auto" style="max-width: 480px;">
                        <input type="text" id="hcSearchInput" class="form-control hc-search-input shadow-sm"
                            placeholder="Search health concern or condition..." autocomplete="off">
                    </div>
                </div>
            </div>
        </div>

        {{-- Health Concerns Grid Container --}}
        <div class="mb-5">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h5 class="fw-bold mb-0 text-dark">
                    All Health Concerns <span class="badge bg-light text-dark border ms-2 rounded-pill fw-normal fs-6"
                        id="hcCountBadge">{{ count($healthConcernsList) }}</span>
                </h5>
            </div>

            @if($healthConcernsList->isNotEmpty())
                <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-3 g-md-4" id="hcGrid">
                    @foreach($healthConcernsList as $hc)
                        @php
                            $hcUrl = url('/health/' . $hc->slug);
                            $imagePath = $hc->icon ?: ($hc->image ?: $hc->banner);
                            $hasImage = !empty($imagePath);
                            if ($hasImage && !str_starts_with($imagePath, 'http') && !str_starts_with($imagePath, '/')) {
                                $imagePath = asset($imagePath);
                            }
                        @endphp
                        <div class="col hc-item-col" data-hc-name="{{ strtolower($hc->name) }}">
                            <a href="{{ $hcUrl }}" class="hc-card shadow-sm">
                                <div class="hc-card__icon-wrap">
                                    @if($hasImage)
                                        <img src="{{ $imagePath }}" alt="{{ $hc->name }}" class="hc-card__img" loading="lazy"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="hc-card__avatar" style="display: none;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15"/>
                                            </svg>
                                        </div>
                                    @else
                                        <div class="hc-card__avatar">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <h6 class="hc-card__name" title="{{ $hc->name }}">
                                    {{ $hc->name }}
                                </h6>
                            </a>
                        </div>
                    @endforeach
                </div>

                {{-- No results placeholder for search --}}
                <div id="noHcResults" class="text-center py-5 d-none">
                    <div class="mb-3 text-muted">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-search"
                            viewBox="0 0 16 16">
                            <path
                                d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.099zm-5.242 1.156a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11" />
                        </svg>
                    </div>
                    <h5 class="fw-semibold text-dark">No health concerns matching your search</h5>
                    <p class="text-muted small mb-0">Try searching with a different term.</p>
                </div>
            @else
                <div class="text-center py-5 border rounded-4 bg-light">
                    <p class="text-muted mb-0">No health concerns available at the moment.</p>
                </div>
            @endif
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('hcSearchInput');
            const hcGrid = document.getElementById('hcGrid');
            const noResults = document.getElementById('noHcResults');
            const countBadge = document.getElementById('hcCountBadge');

            if (!searchInput || !hcGrid) return;

            const hcCols = hcGrid.querySelectorAll('.hc-item-col');
            const totalCount = hcCols.length;

            searchInput.addEventListener('input', function () {
                const query = this.value.trim().toLowerCase();
                let visibleCount = 0;

                hcCols.forEach(col => {
                    const name = col.getAttribute('data-hc-name') || '';
                    if (name.includes(query)) {
                        col.classList.remove('d-none');
                        visibleCount++;
                    } else {
                        col.classList.add('d-none');
                    }
                });

                if (countBadge) {
                    countBadge.textContent = visibleCount;
                }

                if (visibleCount === 0 && totalCount > 0) {
                    noResults.classList.remove('d-none');
                } else if (noResults) {
                    noResults.classList.add('d-none');
                }
            });
        });
    </script>
@endsection
