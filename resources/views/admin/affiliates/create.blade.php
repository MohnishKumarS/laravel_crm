@extends('admin.layouts.main')

@section('title', 'Add Affiliate | Yuukke Dashboard')

@section('content')
    <div class="page-header">
        <h3 class="fw-bold mb-3">Add Affiliate</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home"><a href="{{ route('dashboard') }}"><i class="icon-home"></i></a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href="{{ route('affiliates.index') }}">Affiliate Program</a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href="#">Add</a></li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><div class="card-title">Create Affiliate Account</div></div>
                <div class="card-body">
                    @if (session('success'))
                        <h5 class="alert alert-success">{{ session('success') }}</h5>
                    @endif

                    <form action="{{ route('affiliates.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="user_mode" id="mode_existing"
                                    value="existing" {{ old('user_mode', 'new') === 'existing' ? 'checked' : '' }}
                                    onchange="toggleUserMode()">
                                <label class="form-check-label" for="mode_existing">Existing user</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="user_mode" id="mode_new"
                                    value="new" {{ old('user_mode', 'new') === 'new' ? 'checked' : '' }}
                                    onchange="toggleUserMode()">
                                <label class="form-check-label" for="mode_new">Create new user</label>
                            </div>
                        </div>

                        <div id="existing_user_block" class="form-group" style="display:none">
                            <label for="user_id">User</label>
                            <select name="user_id" id="user_id" class="form-control">
                                <option value="">Select a user...</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <span class="text-danger d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div id="new_user_block">
                            <div class="form-group">
                                <label for="new_name">Full name</label>
                                <input type="text" name="new_name" id="new_name" class="form-control"
                                    value="{{ old('new_name') }}" />
                                @error('new_name')
                                    <span class="text-danger d-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="new_email">Email</label>
                                <input type="email" name="new_email" id="new_email" class="form-control"
                                    value="{{ old('new_email') }}" />
                                @error('new_email')
                                    <span class="text-danger d-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="new_password">Password</label>
                                <input type="password" name="new_password" id="new_password" class="form-control" />
                                <small class="text-muted">Leave blank to auto-generate a random password.</small>
                                @error('new_password')
                                    <span class="text-danger d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="commission_rate">Commission Rate (%) — leave blank for default</label>
                            <input type="number" step="0.01" name="commission_rate" class="form-control"
                                value="{{ old('commission_rate') }}" />
                        </div>

                        <div class="form-group">
                            <label for="paypal_email">PayPal Email (optional)</label>
                            <input type="email" name="paypal_email" class="form-control"
                                value="{{ old('paypal_email') }}" />
                        </div>

                        <div class="card-action mt-3">
                            <button type="submit" class="btn btn-success">Create Affiliate</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleUserMode() {
            const isNew = document.getElementById('mode_new').checked;
            document.getElementById('new_user_block').style.display = isNew ? 'block' : 'none';
            document.getElementById('existing_user_block').style.display = isNew ? 'none' : 'block';
        }
        document.addEventListener('DOMContentLoaded', toggleUserMode);
    </script>
    @endpush
@endsection