@extends('admin.layouts.main')

@section('title', 'Browse Products')

@section('content')
    <div class="page-header d-flex justify-content-between align-items-center">
        <h3 class="fw-bold mb-3">Browse Products</h3>
        <a href="{{ route('affiliate.products') }}" class="btn btn-outline-secondary btn-sm">Back to My Products</a>
    </div>

    @if (session('success'))
        <h5 class="alert alert-success">{{ session('success') }}</h5>
    @endif

    <div class="card">
        <div class="card-body">
            <form method="GET" class="mb-3">
                <input type="text" name="search" class="form-control" style="max-width: 300px"
                    placeholder="Search products..." value="{{ $search }}">
            </form>

            <div class="row">
                @forelse ($catalog as $product)
                    <div class="col-md-3 mb-3">
                        <div class="card h-100">
                          @if (!empty($product->image))
                              <img src="{{ rtrim(env('MARKETPLACE_ASSET_URL'), '/') . '/' . $product->image }}" class="card-img-top" style="height:140px;object-fit:cover;">
                          @endif
                            <div class="card-body d-flex flex-column">
                                <p class="mb-1">{{ $product->name }}</p>
                                <p class="text-muted mb-2">{{ number_format($product->price ?? 0, 2) }}</p>

                                @if (in_array($product->id, $selectedIds))
                                    <button class="btn btn-sm btn-secondary mt-auto" disabled>Already Added</button>
                                @else
                                    <form method="POST" action="{{ route('affiliate.products.store') }}" class="mt-auto">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <button class="btn btn-sm btn-primary w-100">+ Add to My Products</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">No products found.</p>
                @endforelse
            </div>

            {{ $catalog->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
