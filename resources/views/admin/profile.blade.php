@extends('admin.layouts.main')

@section('content')
    {{-- Breadcrumb --}}
    <div class="page-header">
        <h3 class="fw-bold mb-3">Profile</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home">
                <a href="{{url('/')}}">
                    <i class="icon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="{{route('profile')}}">Proile</a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">View</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-lg-6 mx-auto">

            @if (session('message'))
                <div class="alert alert-{{ session('status', 'success') }} alert-dismissible fade show">
                    {{ session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>{{ $errors->first() }}</strong>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Profile Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Profile Information</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control"
                                value="{{ old('name', auth()->user()->name) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control"
                                value="{{ old('email', auth()->user()->email) }}">
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Update Profile
                        </button>
                    </form>
                </div>
            </div>

            <!-- Password Card -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Change Password</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-danger">
                            Change Password
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
