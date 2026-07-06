@extends('admin.layouts.main')

@section('title', 'Settings | Yuukke Dashboard')

@section('content')
    {{-- Breadcrumb --}}
    <div class="page-header">
        <h3 class="fw-bold mb-3">Settings</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home">
                <a href="{{ url('/') }}">
                    <i class="icon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="{{ route('settings') }}">Settings</a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">View</a>
            </li>
        </ul>
    </div>

    <div class="container-fluid">
        @if (session('message'))
            <div class="alert alert-{{ session('status', 'success') }} alert-dismissible fade show">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow-sm">

            <div class="card-header">
                <h4 class="mb-0">
                    <i class="bi bi-gear"></i>
                    Website Settings
                </h4>
            </div>

            <div class="card-body">

                <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">

                    @csrf

                    <ul class="nav nav-tabs mb-4">

                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" type="button" data-bs-target="#general">
                                General
                            </button>
                        </li>

                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" type="button" data-bs-target="#seo">
                                SEO
                            </button>
                        </li>

                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" type="button" data-bs-target="#contact">
                                Contact
                            </button>
                        </li>

                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" type="button" data-bs-target="#smtp">
                                SMTP
                            </button>
                        </li>



                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" type="button" data-bs-target="#social">
                                Social
                            </button>
                        </li>

                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" type="button" data-bs-target="#system">
                                System
                            </button>
                        </li>

                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="general">

                            <div class="row">

                                <div class="col-md-12 mb-3">
                                    <label>Site Name</label>
                                    <input type="text" name="site_name" class="form-control" required
                                        value="{{ old('site_name', $setting->site_name ?? null) }}">
                                </div>



                                <div class="col-md-6 mb-3">
                                    <label>Logo</label>
                                    <input type="file" name="site_logo" class="form-control">

                                    @if ($setting->site_logo)
                                        <img src="{{ asset('uploads/logo/' . $setting->site_logo ?? null) }}"
                                            class="img-thumbnail mt-2" width="150">
                                    @endif

                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Favicon</label>

                                    <input type="file" name="site_favicon" class="form-control">

                                    @if ($setting->site_favicon)
                                        <img src="{{ asset('uploads/logo/' . $setting->site_favicon ?? null) }}"
                                            class="img-thumbnail mt-2" width="50">
                                    @endif

                                </div>

                                {{-- <div class="col-md-12">

                                    <label>Footer Text</label>

                                    <textarea class="form-control" rows="3" name="footer_text">{{ old('footer_text', $setting->footer_text ?? null) }}</textarea>

                                </div> --}}

                            </div>

                        </div>
                        <div class="tab-pane fade" id="seo">

                            <div class="row">

                                <div class="col-md-12 mb-3">
                                    <label>Meta Title</label>

                                    <input type="text" class="form-control" name="meta_title"
                                        value="{{ $setting->meta_title ?? null }}">
                                </div>

                                <div class="col-md-12 mb-3">

                                    <label>Meta Description</label>

                                    <textarea class="form-control" rows="4" name="meta_description">{{ $setting->meta_description ?? null }}</textarea>

                                </div>

                                <div class="col-md-12">

                                    <label>Meta Keywords</label>

                                    <textarea class="form-control" rows="3" name="meta_keywords">{{ $setting->meta_keywords ?? null }}</textarea>

                                </div>

                            </div>

                        </div>
                        <div class="tab-pane fade" id="contact">

                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label>Email</label>

                                    <input type="email" class="form-control" name="email"
                                        value="{{ $setting->email ?? null }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Phone</label>

                                    <input type="text" class="form-control" name="phone"
                                        value="{{ $setting->phone ?? null }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>WhatsApp</label>

                                    <input type="text" class="form-control" name="whatsapp"
                                        value="{{ $setting->whatsapp ?? null }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Country</label>

                                    <input type="text" class="form-control" name="country"
                                        value="{{ $setting->country ?? null }}">
                                </div>

                                <div class="col-md-12">

                                    <label>Address</label>

                                    <textarea class="form-control" rows="3" name="address">{{ $setting->address ?? null }}</textarea>

                                </div>

                            </div>

                        </div>
                        <div class="tab-pane fade" id="smtp">

                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label>SMTP Host</label>
                                    <input class="form-control" name="smtp_host"
                                        value="{{ $setting->smtp_host ?? null }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>SMTP Port</label>
                                    <input class="form-control" name="smtp_port"
                                        value="{{ $setting->smtp_port ?? null }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Username</label>
                                    <input class="form-control" name="smtp_username"
                                        value="{{ $setting->smtp_username ?? null }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Password</label>
                                    <input type="password" class="form-control" name="smtp_password"
                                        value="{{ $setting->smtp_password ?? null }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Encryption</label>

                                    <select class="form-select" name="smtp_encryption">

                                        <option value="">Select</option>

                                        <option value="tls"
                                            {{ optional($setting)->smtp_encryption == 'tls' ? 'selected' : '' }}>
                                            TLS
                                        </option>

                                        <option value="ssl"
                                            {{ optional($setting)->smtp_encryption == 'ssl' ? 'selected' : '' }}>
                                            SSL
                                        </option>

                                    </select>

                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>From Email</label>
                                    <input class="form-control" name="smtp_from_email"
                                        value="{{ $setting->smtp_from_email }}">
                                </div>

                                <div class="col-md-6">
                                    <label>From Name</label>
                                    <input class="form-control" name="smtp_from_name"
                                        value="{{ $setting->smtp_from_name }}">
                                </div>

                            </div>

                        </div>

                        <div class="tab-pane fade" id="social">

                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label>Facebook</label>
                                    <input class="form-control" name="facebook" value="{{ $setting->facebook }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Instagram</label>
                                    <input class="form-control" name="instagram" value="{{ $setting->instagram }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Twitter</label>
                                    <input class="form-control" name="twitter" value="{{ $setting->twitter }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>LinkedIn</label>
                                    <input class="form-control" name="linkedin" value="{{ $setting->linkedin }}">
                                </div>

                                <div class="col-md-6">
                                    <label>YouTube</label>
                                    <input class="form-control" name="youtube" value="{{ $setting->youtube }}">
                                </div>

                            </div>

                        </div>
                        <div class="tab-pane fade" id="system">

                            <div class="form-check form-switch">

                                <input class="form-check-input" type="checkbox" name="maintenance_mode" value="1"
                                    {{ $setting->maintenance_mode ? 'checked' : '' }}>

                                <label class="form-check-label">

                                    Enable Maintenance Mode

                                </label>

                            </div>

                        </div>
                    </div>

                    <div class="text-end mt-4">

                        <button class="btn btn-primary" type="submit">

                            <i class="bi bi-check-circle"></i>

                            Save Settings

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>
@endsection
