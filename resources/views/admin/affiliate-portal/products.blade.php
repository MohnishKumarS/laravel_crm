@extends('admin.layouts.main')

@section('title', 'My Products')

@section('content')
<style>
    .products-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 20px;
    }
    .store-link-banner {
        background: linear-gradient(135deg, #4e73df 0%, #6f42c1 100%);
        border-radius: 12px;
        padding: 18px 20px;
        color: #fff;
        margin-bottom: 20px;
    }
    .store-link-banner code {
        background: rgba(255,255,255,0.15);
        color: #fff;
        padding: 6px 10px;
        border-radius: 6px;
        font-size: 12px;
        word-break: break-all;
        display: inline-block;
        margin-top: 6px;
    }
    .store-link-banner .btn-copy {
        border-radius: 8px;
        font-weight: 600;
        background: #fff;
        border: none;
        color: #4e73df;
    }
    .product-card {
        border: 1px solid #eef0f4;
        border-radius: 14px;
        overflow: hidden;
        height: 100%;
        background: #fff;
        transition: box-shadow 0.15s ease;
    }
    .product-card:hover { box-shadow: 0 8px 20px rgba(0,0,0,0.08); }
    .product-card-img-wrap {
        width: 100%;
        aspect-ratio: 1 / 1;
        background: #f6f7fa;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .product-card-img-wrap img { width: 100%; height: 100%; object-fit: cover; }
    .product-card-img-wrap .no-image { color: #c3c8d1; font-size: 28px; }
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
    .product-price { font-size: 15px; font-weight: 700; color: #28a745; margin-bottom: 8px; }
    .promo-link-box {
        background: #f6f7fa;
        border-radius: 8px;
        padding: 8px 10px;
        font-size: 11px;
        word-break: break-all;
        margin-bottom: 10px;
        color: #6c757d;
        line-height: 1.4;
    }
    .product-actions { display: flex; flex-direction: column; gap: 6px; }
    .product-actions .btn { width: 100%; border-radius: 8px; font-size: 12px; font-weight: 600; white-space: nowrap; }
    .empty-state { text-align: center; padding: 60px 20px; color: #9aa1ac; }
    .empty-state i { font-size: 40px; margin-bottom: 12px; display: block; }

    @media (max-width: 575px) {
        .products-header { flex-direction: column; align-items: stretch; }
        .store-link-banner { padding: 16px; }
    }
</style>

<div class="products-header">
    <div>
        <h3 class="fw-bold mb-1">My Products</h3>
        <p class="text-muted mb-0 small">Products you're currently promoting.</p>
    </div>
    <a href="{{ route('affiliate.products.browse') }}" class="btn btn-primary btn-sm">
        + Browse &amp; Add Products
    </a>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if ($selected->isEmpty())
    <div class="empty-state">
        <i class="fas fa-box-open"></i>
        <p class="mb-2">You haven't selected any products to promote yet.</p>
        <a href="{{ route('affiliate.products.browse') }}" class="btn btn-primary btn-sm">Browse Products</a>
    </div>
@else
    <div class="store-link-banner d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <strong>Your Shared Store Link</strong>
            <div class="small" style="opacity:0.9;">One link for all your selected products</div>
            <code>{{ $affiliate->storeUrl() }}</code>
        </div>
        <button type="button" class="btn btn-sm btn-copy"
            onclick="navigator.clipboard.writeText('{{ $affiliate->storeUrl() }}'); this.innerText='Copied!'; setTimeout(() => this.innerText='Copy Link', 1500)">
            Copy Link
        </button>
    </div>

    <div class="row g-3">
        @foreach ($selected as $item)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="product-card">
                    <div class="product-card-img-wrap">
                        @if ($item->product_image)
                            <img src="{{ rtrim(config('app.marketplace_asset_url'), '/') . '/' . $item->product_image }}" alt="{{ $item->product_name }}">
                        @else
                            <i class="fas fa-image no-image"></i>
                        @endif
                    </div>
                    <div class="product-card-body">
                        <p class="product-name">{{ $item->product_name ?? 'Product #' . $item->product_id }}</p>
                        <p class="product-price">
                            {{ $item->product_price ? number_format($item->product_price, 2) : '—' }}
                        </p>

                        <div class="promo-link-box" id="link-{{ $item->id }}">{{ $item->promoUrl() }}</div>

                        <div class="product-actions">
                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                onclick="navigator.clipboard.writeText(document.getElementById('link-{{ $item->id }}').innerText); this.innerText='✓'; setTimeout(() => this.innerText='Copy', 1200)">
                                Copy
                            </button>
                            <form class="delete-product" method="POST" action="{{ route('affiliate.products.destroy', $item->id) }}">
                                @csrf @method('DELETE')
                                <button type="button" class="delete-btn btn btn-sm btn-outline-danger w-100">Remove</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.delete-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const productForm = btn.closest('.delete-product');

            Swal.fire({
                title: 'Remove this product?',
                text: 'You can always add it back later from Browse Products.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, remove it',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#dc3545',
            }).then(function (result) {
                if (result.isConfirmed) {
                    productForm.submit();
                }
            });
        });
    });
</script>
@endpush