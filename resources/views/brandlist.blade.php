@extends('layouts.app')

@section('title', $meta['title'] ?? 'Shop By Brand')
@section('meta_description', $meta['description'] ?? 'Browse all brands')

@section('content')
    <style>
        .brand-hero {
            background: linear-gradient(135deg, #0f4c3a 0%, #1a6644 50%, #2d8a5e 100%);
            border-radius: 16px;
            color: #ffffff;
            padding: 2.5rem 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 25px -5px rgba(15, 76, 58, 0.25);
        }

        .brand-search-input {
            border-radius: 50rem;
            padding: 0.75rem 1.5rem;
            font-size: 0.95rem;
            border: 2px solid #e5e7eb;
            transition: all 0.2s ease;
        }

        .brand-search-input:focus {
            border-color: #1a6644;
            box-shadow: 0 0 0 4px rgba(26, 102, 68, 0.15);
            outline: none;
        }

        .brand-card {
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
            min-height: 140px;
        }

        .brand-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px -6px rgba(0, 0, 0, 0.08);
            border-color: #1a6644;
        }

        .brand-card__icon-wrap {
            width: 64px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.75rem;
            border-radius: 12px;
            background: #f8fafc;
            overflow: hidden;
            transition: background 0.2s ease;
        }

        .brand-card:hover .brand-card__icon-wrap {
            background: #f0fdf4;
        }

        .brand-card__img {
            max-width: 80%;
            max-height: 80%;
            object-fit: contain;
        }

        .brand-card__avatar {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #1a6644 0%, #2d8a5e 100%);
            color: #ffffff;
            font-weight: 700;
            font-size: 1.2rem;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
        }

        .brand-card__name {
            font-weight: 600;
            font-size: 0.9rem;
            color: #1f2937;
            margin: 0;
            text-align: center;
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            transition: color 0.2s ease;
        }

        .brand-card:hover .brand-card__name {
            color: #1a6644;
        }
    </style>

    <div class="container-fluid px-0">

        {{-- Hero Section --}}
        <div class="brand-hero text-center position-relative">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <span class="badge bg-white text-dark fw-bold px-3 py-2 rounded-pill mb-3 text-uppercase"
                        style="letter-spacing:1px; font-size:0.75rem;">
                        Authentic &amp; Trusted Brands
                    </span>
                    <h1 class="display-6 fw-bold mb-2">Shop By Brand</h1>
                    <p class="lead mb-4 text-white-50 fs-6">
                        Explore our curated collection of leading health, wellness, and ayurvedic brands.
                    </p>

                    {{-- Live Search Input --}}
                    <div class="position-relative mx-auto" style="max-width: 480px;">
                        <input type="text" id="brandSearchInput" class="form-control brand-search-input shadow-sm"
                            placeholder="Search brand by name..." autocomplete="off">
                    </div>
                </div>
            </div>
        </div>

        {{-- Brand Grid Container --}}
        <div class="mb-5">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h5 class="fw-bold mb-0 text-dark">
                    All Brands <span class="badge bg-light text-dark border ms-2 rounded-pill fw-normal fs-6"
                        id="brandCountBadge">{{ count($brands) }}</span>
                </h5>
            </div>

            @if($brands->isNotEmpty())
                <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-3 g-md-4" id="brandGrid">
                    @foreach($brands as $brand)
                        @php
                            $brandUrl = url('/brand/' . $brand->slug);
                            $imagePath = "/images/icons/" . $brand->icon ?: ($brand->image ?: $brand->banner);
                            $hasImage = !empty($imagePath);
                            if ($hasImage && !str_starts_with($imagePath, 'http') && !str_starts_with($imagePath, '/')) {
                                $imagePath = asset($imagePath);
                            }
                        @endphp
                        <div class="col brand-item-col" data-brand-name="{{ strtolower($brand->name) }}">
                            <a href="{{ $brandUrl }}" class="brand-card shadow-sm">
                                <div class="brand-card__icon-wrap">
                                    @if($hasImage)
                                        <img src="{{ $imagePath }}" alt="{{ $brand->name }}" class="brand-card__img" loading="lazy"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="brand-card__avatar" style="display: none;">
                                            {{ strtoupper(substr($brand->name, 0, 2)) }}
                                        </div>
                                    @else
                                        <div class="brand-card__avatar">
                                            {{ strtoupper(substr($brand->name, 0, 2)) }}
                                        </div>
                                    @endif
                                </div>
                                <h6 class="brand-card__name" title="{{ $brand->name }}">
                                    {{ $brand->name }}
                                </h6>
                            </a>
                        </div>
                    @endforeach
                </div>

                {{-- No results placeholder for search --}}
                <div id="noBrandResults" class="text-center py-5 d-none">
                    <div class="mb-3 text-muted">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-search"
                            viewBox="0 0 16 16">
                            <path
                                d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.099zm-5.242 1.156a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11" />
                        </svg>
                    </div>
                    <h5 class="fw-semibold text-dark">No brands matching your search</h5>
                    <p class="text-muted small mb-0">Try searching with a different term.</p>
                </div>
            @else
                <div class="text-center py-5 border rounded-4 bg-light">
                    <p class="text-muted mb-0">No brands available at the moment.</p>
                </div>
            @endif
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('brandSearchInput');
            const brandGrid = document.getElementById('brandGrid');
            const noResults = document.getElementById('noBrandResults');
            const countBadge = document.getElementById('brandCountBadge');

            if (!searchInput || !brandGrid) return;

            const brandCols = brandGrid.querySelectorAll('.brand-item-col');
            const totalCount = brandCols.length;

            searchInput.addEventListener('input', function () {
                const query = this.value.trim().toLowerCase();
                let visibleCount = 0;

                brandCols.forEach(col => {
                    const name = col.getAttribute('data-brand-name') || '';
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