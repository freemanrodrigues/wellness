@extends('layouts.app')

@section('title', $meta['title'])
@section('meta_description', $meta['description'])

@section('content')

    {{-- Session Flash Alerts --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4 rounded-3 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {!! session('success') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('info'))
        <div class="alert alert-info alert-dismissible fade show mb-4 rounded-3 shadow-sm" role="alert">
            <i class="bi bi-info-circle-fill me-2"></i> {!! session('info') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item">
                <a href="" class="text-decoration-none">
                    {{-- $product->category->name --}}
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                {{-- $product->subcategory->name --}}
            </li>
        </ol>
    </nav>

    <div class="row g-5">

        {{-- LEFT: Product images --}}
        <div class="col-lg-6">
            <div class="mb-3">
                <img id="mainProductImage" src="{{ asset('images/products/' . $product->imgurl) }}"
                    alt="{{ $product->name }}" class="img-fluid rounded border w-100"
                    style="aspect-ratio: 1 / 1; object-fit: cover;" loading="eager" fetchpriority="high">
            </div>

            {{-- Thumbnail strip — hover shows an enlarged popup preview --}}
            <div class="d-flex gap-2 flex-wrap position-relative">
                @foreach (array_merge([$product->imgurl], $product->gallery ?? []) as $index => $thumb)
                    <div class="thumb-wrapper position-relative">
                        <img src="{{ asset('images/products/' . $thumb) }}"
                            alt="{{ $product->name }} thumbnail {{ $index + 1 }}" class="thumb-image border rounded" width="64"
                            height="64" loading="lazy" style="object-fit: cover; cursor: pointer;"
                            data-full="{{ asset('images/products/' . $thumb) }}">

                        <div class="thumb-popup shadow">
                            <img src="{{ asset('images/products/' . $thumb) }}" alt="{{ $product->name }} preview"
                                loading="lazy">
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
            <div class="mb-4">
                <p style="white-space: pre-line;">{{  $product->short_description }}</p>
            </div>
            @if ($product->isactive != 1)
                {{-- No purchasable options exist for this product --}}
                <div class="alert alert-warning">
                    This product is currently unavailable for purchase.
                </div>

            @else

                {{-- Price + discount --}}
                <div class="mb-3" id="priceBlock">
                    @if (!empty($product->variants))
                        <span class="h4 fw-bold text-danger" id="currentPrice">
                            ${{ number_format($product->variants[0]['price'], 2) }}
                        </span>
                        <span class="text-muted text-decoration-line-through ms-2 d-none" id="originalPrice"></span>
                        <span class="badge bg-danger ms-2 d-none" id="discountBadge"></span>
                    @else
                        <span class="h4 fw-bold text-danger" id="currentPrice">
                            ${{ number_format($product->price, 2) }}
                        </span>
                        @if (!empty($product->original_price) && $product->original_price > $product->price)
                            @php
                                $discountPct = round((($product->original_price - $product->price) / $product->original_price) * 100);
                            @endphp
                            <span class="text-muted text-decoration-line-through ms-2" id="originalPrice">
                                ${{ number_format($product->original_price, 2) }}
                            </span>
                            <span class="badge bg-danger ms-2" id="discountBadge">-{{ $discountPct }}%</span>
                        @else
                            <span class="text-muted text-decoration-line-through ms-2 d-none" id="originalPrice"></span>
                            <span class="badge bg-danger ms-2 d-none" id="discountBadge"></span>
                        @endif
                    @endif
                </div>

                {{-- Variant / size selector — only shown when the product actually has variants --}}
                @if (!empty($product->variants))
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
                @endif


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

                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap" id="cartActionGroup">
                    <button type="button" class="btn btn-primary px-4 py-2" id="addToCartBtn" style="min-width: 160px;">
                        <i class="bi bi-cart-plus me-1"></i> Add to Cart
                    </button>

                    <a href="{{ route('cart.index') }}" class="btn btn-primary px-4 py-2 d-none" id="viewCartBtn" style="min-width: 140px;">
                        <i class="bi bi-cart-check me-1"></i> View Cart
                    </a>

                    <a href="{{ route('home') }}" class="btn btn-outline-success px-4 py-2 d-none" id="shopMoreBtn" style="min-width: 140px;">
                        <i class="bi bi-bag-plus me-1"></i> Shop More
                    </a>

                    <form method="POST" action="{{ route('wishlist.add') }}" class="d-inline">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit" class="btn btn-outline-danger px-3 py-2" id="likeWishlistBtn"
                            title="{{ !empty($isWishlisted) ? 'In your wishlist' : 'Like & Save to wishlist' }}">
                            <i class="bi {{ !empty($isWishlisted) ? 'bi-heart-fill text-danger' : 'bi-heart' }}"></i>
                        </button>
                    </form>
                </div>

                <div id="cartMessageContainer" class="mb-3"></div>

                <p class="small text-muted mb-0">
                    <i class="bi bi-truck me-1"></i> Same-day delivery available.
                    @if (!empty($product->variants))
                        SKU: <span id="currentSku">{{ $product->variants[0]['sku'] }}</span>
                    @elseif (!empty($product->sku))
                        SKU: <span id="currentSku">{{ $product->sku }}</span>
                    @endif
                </p>

            @endif

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
                    @foreach (explode("\n", $product->info) as $line)
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
                {{--
                @forelse ($product->reviews as $review)
                <div class="mb-3 pb-3 border-bottom">
                    <div class="d-flex justify-content-between">
                        <strong>{{ $review['author'] }}</strong>
                        <span class="text-muted small">{{ \Carbon\Carbon::parse($review['date'])->format('M j, Y') }}</span>
                    </div>
                    <div class="text-warning small mb-1">
                        @for ($i = 1; $i <= 5; $i++) <i class="bi bi-star{{ $i <= $review['rating'] ? '-fill' : '' }}"></i>
                            @endfor
                    </div>
                    <p class="mb-0 small">{{ $review['comment'] }}</p>
                </div>
                @empty
                <p class="text-muted mb-0">No reviews yet. Be the first to review this product.</p>
                @endforelse
                --}}
                <p class="text-muted mb-0">No reviews yet. Be the first to review this product.</p>
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

            $('#addToCartBtn').on('click', function () {
                const btn = $(this);
                const hasVariants = $('.variant-btn').length > 0;

                const activePrice = hasVariants
                    ? parseFloat($('.variant-btn.active').data('price'))
                    : {{ $product->price ?? 0 }};

                const activeSku = hasVariants
                    ? $('.variant-btn.active').data('sku')
                    : @json($product->sku ?? '');

                const activeLabel = hasVariants
                    ? $('.variant-btn.active').text().trim()
                    : '';

                const qty = parseInt($('#quantity').val());

                btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Adding...');

                $.ajax({
                    url: '{{ route('cart.add') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        product_id: {{ $product->id ?? 'null' }},
                        product_name: @json($product->name) + (activeLabel ? ' (' + activeLabel + ')' : ''),
                        product_image: @json(asset('images/products/' . $product->imgurl)),
                        sku: activeSku,
                        price: activePrice,
                        qty: qty
                    },
                    success: function (response) {
                        // Update cart count badge in header/navbar
                        $('#cartCount').text(response.cart_count).removeClass('d-none');

                        // Display success message
                        $('#cartMessageContainer').html(
                            '<div class="alert alert-success alert-dismissible fade show rounded-3 p-2 px-3 small shadow-sm mb-0 d-flex align-items-center justify-content-between" role="alert">' +
                            '<span><i class="bi bi-check-circle-fill me-2"></i> Product added to basket successfully!</span>' +
                            '<button type="button" class="btn-close ms-2" data-bs-dismiss="alert" style="font-size:0.7rem;"></button>' +
                            '</div>'
                        );

                        // Hide Add to Cart button and Show View Cart & Shop More buttons
                        btn.addClass('d-none');
                        $('#viewCartBtn').removeClass('d-none');
                        $('#shopMoreBtn').removeClass('d-none');
                    },
                    error: function (xhr) {
                        btn.prop('disabled', false).html('<i class="bi bi-cart-plus me-1"></i> Add to Cart');
                        const msg = xhr.responseJSON?.message || 'Something went wrong adding this item. Please try again.';
                        alert(msg);
                    }
                });
            });
        });
    </script>
@endpush