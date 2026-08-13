@extends('layouts.app')

@section('title', $meta['title'])
@section('meta_description', $meta['description'])

@section('content')

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item">
                <a href="" class="text-decoration-none">
                    {{ $product->category->name }}
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                {{ $product->subcategory->name }}
            </li>
        </ol>
    </nav>

    <div class="row g-5">

        {{-- LEFT: Product images --}}
        <div class="col-lg-6">
            <div class="mb-3">
                <img id="mainProductImage" src="{{ asset($product->main_image) }}" alt="{{ $product->name }}"
                    class="img-fluid rounded border w-100" style="aspect-ratio: 1 / 1; object-fit: cover;" loading="eager"
                    fetchpriority="high">
            </div>

            {{-- Thumbnail strip — hover shows an enlarged popup preview --}}
            <div class="d-flex gap-2 flex-wrap position-relative">
                @foreach (array_merge([$product->main_image], $product->gallery) as $index => $thumb)
                    <div class="thumb-wrapper position-relative">
                        <img src="{{ asset($thumb) }}" alt="{{ $product->name }} thumbnail {{ $index + 1 }}"
                            class="thumb-image border rounded" width="64" height="64" loading="lazy"
                            style="object-fit: cover; cursor: pointer;" data-full="{{ asset($thumb) }}">

                        <div class="thumb-popup shadow">
                            <img src="{{ asset($thumb) }}" alt="{{ $product->name }} preview" loading="lazy">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- RIGHT: Product details --}}
        <div class="col-lg-6">

            <h1 class="h3 mb-2">{{ $product->name }}</h1>

            {{-- Rating summary --}}
            @if ($product->reviews_count > 0)
                <div class="mb-3">
                    <span class="text-warning">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= floor($product->rating))
                                <i class="bi bi-star-fill"></i>
                            @elseif ($i - $product->rating < 1)
                                <i class="bi bi-star-half"></i>
                            @else
                                <i class="bi bi-star"></i>
                            @endif
                        @endfor
                    </span>
                    <a href="#reviews" class="text-muted small text-decoration-none">
                        {{ $product->rating }} ({{ $product->reviews_count }} reviews)
                    </a>
                </div>
            @endif

            {{-- Price + discount --}}
            <div class="mb-3" id="priceBlock">
                <span class="h4 fw-bold text-danger" id="currentPrice">
                    ${{ number_format($product->variants[0]['price'], 2) }}
                </span>
                <span class="text-muted text-decoration-line-through ms-2 d-none" id="originalPrice"></span>
                <span class="badge bg-danger ms-2 d-none" id="discountBadge"></span>
            </div>

            {{-- Variant / size selector --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">Select Size</label>
                <div class="d-flex flex-wrap gap-2" id="variantSelector">
                    @foreach ($product->variants as $index => $variant)
                        <button type="button"
                            class="btn btn-outline-secondary btn-sm variant-btn {{ $index === 0 ? 'active' : '' }}"
                            data-price="{{ $variant['price'] }}" data-original-price="{{ $variant['original_price'] ?? '' }}"
                            data-sku="{{ $variant['sku'] }}">
                            {{ $variant['label'] }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Quantity --}}
            <div class="mb-4">
                <label for="quantity" class="form-label fw-semibold">Quantity</label>
                <div class="input-group" style="max-width: 140px;">
                    <button type="button" class="btn btn-outline-secondary" id="qtyMinus">&minus;</button>
                    <input type="number" id="quantity" class="form-control text-center" value="1" min="1" max="20">
                    <button type="button" class="btn btn-outline-secondary" id="qtyPlus">&plus;</button>
                </div>
            </div>

            {{-- Brand --}}
            @if (!empty($product->brand))
                <p class="text-muted mb-4">
                    <span class="fw-semibold">Brand:</span> {{ $product->brand }}
                </p>
            @endif

            <div class="d-grid gap-2 d-sm-flex mb-4">
                <button type="button" class="btn btn-primary btn-lg flex-fill" id="addToCartBtn">
                    <i class="bi bi-cart-plus me-1"></i> Add to Cart
                </button>
                <button type="button" class="btn btn-outline-secondary btn-lg">
                    <i class="bi bi-heart"></i>
                </button>
            </div>

            <p class="small text-muted mb-0">
                <i class="bi bi-truck me-1"></i> Same-day delivery available. SKU: <span
                    id="currentSku">{{ $product->variants[0]['sku'] }}</span>
            </p>

        </div>
    </div>

    {{-- Tabbed details section --}}
    <div class="mt-5">
        <ul class="nav nav-tabs" id="productDetailTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab">
                    Description
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#productDetails" type="button" role="tab">
                    Product Details
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#useCase" type="button" role="tab">
                    Use Case
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab">
                    Reviews ({{ $product->reviews_count }})
                </button>
            </li>
        </ul>

        <div class="tab-content border border-top-0 p-4">

            <div class="tab-pane fade show active" id="description" role="tabpanel">
                <p class="mb-0">{{ $product->description }}</p>
            </div>

            <div class="tab-pane fade" id="productDetails" role="tabpanel">
                <ul class="mb-0">
                    @foreach (explode("\n", $product->details) as $line)
                        @if (trim($line) !== '')
                            <li>{{ trim($line) }}</li>
                        @endif
                    @endforeach
                </ul>
            </div>

            <div class="tab-pane fade" id="useCase" role="tabpanel">
                <p class="mb-0">{{ $product->use_case }}</p>
            </div>

            <div class="tab-pane fade" id="reviews" role="tabpanel">
                @forelse ($product->reviews as $review)
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="d-flex justify-content-between">
                            <strong>{{ $review['author'] }}</strong>
                            <span class="text-muted small">{{ \Carbon\Carbon::parse($review['date'])->format('M j, Y') }}</span>
                        </div>
                        <div class="text-warning small mb-1">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $i <= $review['rating'] ? '-fill' : '' }}"></i>
                            @endfor
                        </div>
                        <p class="mb-0 small">{{ $review['comment'] }}</p>
                    </div>
                @empty
                    <p class="text-muted mb-0">No reviews yet. Be the first to review this product.</p>
                @endforelse
            </div>

        </div>
    </div>

@endsection

@push('styles')
    <style>
        .thumb-wrapper {
            position: relative;
        }

        .thumb-image {
            display: block;
            transition: border-color .15s ease-in-out;
        }

        .thumb-image:hover {
            border-color: #0d6efd !important;
        }

        /* Hover popup preview */
        .thumb-popup {
            display: none;
            position: absolute;
            bottom: 72px;
            left: 0;
            z-index: 1000;
            background: #fff;
            border-radius: .375rem;
            padding: 6px;
        }

        .thumb-popup img {
            width: 260px;
            height: 260px;
            object-fit: cover;
            border-radius: .25rem;
        }

        .thumb-wrapper:hover .thumb-popup {
            display: block;
        }

        .variant-btn.active {
            background-color: #0d6efd;
            color: #fff;
            border-color: #0d6efd;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(function () {
            // Clicking a thumbnail swaps the main image
            $('.thumb-image').on('click', function () {
                $('#mainProductImage').attr('src', $(this).data('full'));
            });

            // Variant selection updates price, discount, and SKU
            $('.variant-btn').on('click', function () {
                $('.variant-btn').removeClass('active');
                $(this).addClass('active');

                const price = parseFloat($(this).data('price'));
                const originalPrice = $(this).data('original-price');
                const sku = $(this).data('sku');

                $('#currentPrice').text('$' + price.toFixed(2));
                $('#currentSku').text(sku);

                if (originalPrice && parseFloat(originalPrice) > price) {
                    const original = parseFloat(originalPrice);
                    const discountPct = Math.round(((original - price) / original) * 100);

                    $('#originalPrice').text('$' + original.toFixed(2)).removeClass('d-none');
                    $('#discountBadge').text('-' + discountPct + '%').removeClass('d-none');
                } else {
                    $('#originalPrice').addClass('d-none');
                    $('#discountBadge').addClass('d-none');
                }
            });

            // Quantity stepper
            $('#qtyPlus').on('click', function () {
                const input = $('#quantity');
                input.val(Math.min(20, parseInt(input.val()) + 1));
            });
            $('#qtyMinus').on('click', function () {
                const input = $('#quantity');
                input.val(Math.max(1, parseInt(input.val()) - 1));
            });
        });
    </script>
@endpush