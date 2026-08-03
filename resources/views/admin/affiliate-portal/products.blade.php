@extends('admin.layouts.main')

@section('title', 'My Products')

@section('content')
    <div class="page-header d-flex justify-content-between align-items-center">
        <h3 class="fw-bold mb-3">My Products</h3>
        <a href="{{ route('affiliate.products.browse') }}" class="btn btn-primary btn-sm">+ Browse & Add Products</a>
    </div>

    @if (session('success'))
        <h5 class="alert alert-success">{{ session('success') }}</h5>
    @endif

    <div class="card">
        <div class="card-body">
            @if ($selected->isEmpty())
                <p class="text-muted">You haven't selected any products to promote yet. Click "Browse & Add Products" to get started.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr><th>Product</th><th>Price</th><th>Promo Link</th><th></th></tr>
                        </thead>
                        <tbody>
                            @foreach ($selected as $item)
                                <tr>
                                    <td>
                                        @if ($item->product_image)
                                            <img src="{{ $item->product_image }}" alt="" style="width:40px;height:40px;object-fit:cover;border-radius:4px;" class="me-2">
                                        @endif
                                        {{ $item->product_name ?? ('Product #' . $item->product_id) }}
                                    </td>
                                    <td>{{ $item->product_price ? number_format($item->product_price, 2) : '—' }}</td>
                                    <td>
                                        <code style="word-break: break-all; font-size: 12px;">{{ $item->promoUrl() }}</code>
                                        <button type="button" class="btn btn-sm btn-outline-secondary ms-1"
                                            onclick="navigator.clipboard.writeText('{{ $item->promoUrl() }}'); this.innerText='Copied!'; setTimeout(() => this.innerText='Copy', 1500)">
                                            Copy
                                        </button>
                                    </td>
                                    <td>
                                        <form method="POST" action="{{ route('affiliate.products.destroy', $item->id) }}" onsubmit="return confirm('Remove this product from your promotion list?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
