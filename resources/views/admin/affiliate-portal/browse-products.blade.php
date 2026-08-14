@extends('admin.layouts.main')

@section('title', 'Browse Products')

@section('content')
<style>
    .browse-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 20px;
    }
    .browse-search {
        position: relative;
        max-width: 340px;
        width: 100%;
    }
    .browse-search input {
        border-radius: 24px;
        padding: 10px 16px 10px 40px;
        border: 1px solid #e2e5ec;
        font-size: 14px;
        width: 100%;
    }
    .browse-search i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #9aa1ac;
        font-size: 13px;
    }
    .product-card {
        border: 1px solid #eef0f4;
        border-radius: 14px;
        overflow: hidden;
        height: 100%;
        transition: box-shadow 0.15s ease, transform 0.15s ease;
        background: #fff;
    }
    .product-card:hover {
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        transform: translateY(-2px);
    }
    .product-card-img-wrap {
        width: 100%;
        aspect-ratio: 1 / 1;
        background: #f6f7fa;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .product-card-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .product-card-img-wrap .no-image {
        color: #c3c8d1;
        font-size: 28px;
    }
    .product-card-body { padding: 12px 14px 14px; }
    .product-name {
        font-size: 14px;
        font-weight: 600;
        line-height: 1.3;
        margin-bottom: 4px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 36px;
    }
    .product-price {
        font-size: 15px;
        font-weight: 700;
        color: #28a745;
        margin-bottom: 10px;
    }
    .btn-add-product {
        border-radius: 8px;
        font-weight: 600;
        font-size: 13px;
    }
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #9aa1ac;
    }
    .empty-state i { font-size: 40px; margin-bottom: 12px; display: block; }

    @media (max-width: 575px) {
        .browse-header { flex-direction: column; align-items: stretch; }
        .browse-search { max-width: 100%; }
    }
</style>

<div class="browse-header">
    <div>
        <h3 class="fw-bold mb-1">Browse Products</h3>
        <p class="text-muted mb-0 small">Pick products to promote and earn commission on.</p>
    </div>
    <a href="{{ route('affiliate.products') }}" class="btn btn-outline-secondary btn-sm">
        ← My Products
    </a>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<form method="GET" class="browse-search mb-3">
    <i class="fas fa-search"></i>
    <input type="text" name="search" placeholder="Search products..." value="{{ $search }}"
        onchange="this.form.submit()">
</form>

<div class="row g-3">
    @forelse ($catalog as $product)
        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
            <div class="product-card">
                <div class="product-card-img-wrap">
                    @if (!empty($product->image))
                        <img src="{{ rtrim(config('app.marketplace_asset_url'), '/') . '/' . $product->image }}" alt="{{ $product->name }}">
                    @else
                        <i class="fas fa-image no-image"></i>
                    @endif
                </div>
                <div class="product-card-body">
                    <p class="product-name">{{ $product->name }}</p>
                    <p class="product-price">{{ number_format($product->price ?? 0, 2) }}</p>

                    <button
                        class="btn btn-sm btn-add-product w-100 add-product-btn {{ in_array($product->id, $selectedIds) ? 'btn-light' : 'btn-primary' }}"
                        data-product-id="{{ $product->id }}"
                        {{ in_array($product->id, $selectedIds) ? 'disabled' : '' }}>
                        {{ in_array($product->id, $selectedIds) ? '✓ Added' : '+ Add' }}
                    </button>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="empty-state">
                <i class="fas fa-box-open"></i>
                <p class="mb-0">
                    No products found
                    @if($search)
                        for "{{ $search }}"
                    @endif
                </p>
            </div>
        </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $catalog->links('pagination::bootstrap-5') }}
</div>

<script>
    document.querySelectorAll('.add-product-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const productId = btn.dataset.productId;
            const originalText = btn.innerText;

            btn.disabled = true;
            btn.innerText = 'Adding...';

            fetch('{{ route('affiliate.products.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ product_id: productId }),
            })
            .then(function (res) {
                const contentType = res.headers.get('content-type') || '';

                if (!contentType.includes('application/json')) {
                    if (res.url.includes('/login')) {
                        throw new Error('Your session expired. Please refresh the page and log in again.');
                    }
                    if (res.url.includes('/onboarding')) {
                        const err = new Error('Complete KYC verification and training before adding products.');
                        err.redirect = res.url;
                        throw err;
                    }
                    throw new Error('Unexpected server response (status ' + res.status + '). Please refresh and try again.');
                }

                return res.json().then(function (data) {
                    if (!res.ok) {
                        const err = new Error(data.message || ('Request failed with status ' + res.status));
                        if (data.redirect) err.redirect = data.redirect;
                        throw err;
                    }
                    return data;
                });
            })
            .then(function () {
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-light');
                btn.innerText = '✓ Added';

                Swal.fire({
                    icon: 'success',
                    title: 'Added!',
                    text: 'Product added to your promotion list.',
                    timer: 1500,
                    showConfirmButton: false,
                });
            })
            .catch(function (err) {
                btn.disabled = false;
                btn.innerText = originalText;

                Swal.fire({
                    icon: 'error',
                    title: 'Could not add product',
                    text: err.message || 'Please try again.',
                }).then(function () {
                    if (err.redirect) {
                        window.location.href = err.redirect;
                    }
                });
            });
        });
    });
</script>
@endsection