{{-- LOG STATUS --}}
@if (session('message'))
    <div class="alert alert-{{ session('status', 'success') }} alert-dismissible fade show">
        {{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
