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
            <div class="card mb-3" style="background: #f8f9fa;">
                     <div class="card-body">
                         <p class="mb-1"><strong>Your Shared Store Link</strong> — one link for all your selected products:</p>
                         <code style="word-break: break-all;">{{ $affiliate->storeUrl() }}</code>
                         <button type="button" class="btn btn-sm btn-outline-secondary ms-2"
                             onclick="navigator.clipboard.writeText('{{ $affiliate->storeUrl() }}'); this.innerText='Copied!'; setTimeout(() => this.innerText='Copy', 1500)">
                             Copy
                         </button>
                     </div>
             </div>
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
                                           <img src="{{ rtrim(env('MARKETPLACE_ASSET_URL'), '/') . '/' . $item->product_image }}" alt="" style="width:40px;height:40px;object-fit:cover;border-radius:4px;" class="me-2">
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
                                       <form class="delete-product" method="POST" action="{{ route('affiliate.products.destroy', $item->id) }}">
    @csrf @method('DELETE')
    <button type="button" class="delete-btn btn btn-sm btn-outline-danger">Remove</button>
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

@push('scripts')
    <script>
        document.querySelectorAll('.delete-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        const userForm = btn.closest('.delete-product');

                Swal.fire({
                    title: 'Delete this product?',
                    text: 'This cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#dc3545',
                }).then(function(result) {
                    if (result.isConfirmed) {
                        userForm.submit();
                    }
                });
            });
        });
    </script>
@endpush
