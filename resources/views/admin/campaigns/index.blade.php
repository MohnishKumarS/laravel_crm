{{-- resources/views/admin/campaigns/index.blade.php --}}
@extends('admin.layouts.main')

@section('title', 'Campaigns | Yuukke Dashboard')

@section('content')
    {{-- Breadcrumb --}}
    <div class="page-header">
        <h3 class="fw-bold mb-3">Campaigns</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home">
                <a href="{{ route('dashboard') }}">
                    <i class="icon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.campaigns.index') }}">Campaigns List</a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">Create</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="card-title">Seasonal Campaigns</div>
                    <a href="{{ route('admin.campaigns.create') }}" class="btn btn-primary btn-sm">+ New Campaign</a>
                </div>
                <div class="card-body">

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <p class="text-muted">
                        The site automatically shows whichever campaign below is <strong>Published</strong> and inside its
                        date window. If none matches, it falls back to the campaign marked <strong>Default</strong>.
                    </p>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Window</th>
                                    <th>Theme</th>
                                    <th>Priority</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($campaigns as $campaign)
                                    <tr>
                                        <td>
                                            <strong>{{ $campaign->name }}</strong><br>
                                            <small class="text-muted">/{{ $campaign->slug }}</small>
                                        </td>
                                        <td>
                                            @php
                                                $badgeClass = match (true) {
                                                    str_starts_with($campaign->status_label, 'Active')
                                                        => 'badge-success',
                                                    $campaign->status_label === 'Scheduled' => 'badge-info',
                                                    $campaign->status_label === 'Ended' => 'badge-secondary',
                                                    default => 'badge-light',
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">{{ $campaign->status_label }}</span>
                                        </td>
                                        <td>
                                            @if ($campaign->start_at || $campaign->end_at)
                                                {{ optional($campaign->start_at)->format('d M Y') ?? '—' }}
                                                →
                                                {{ optional($campaign->end_at)->format('d M Y') ?? '—' }}
                                            @else
                                                <span class="text-muted">Always on</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span title="Gradient / accent"
                                                style="display:inline-block;width:16px;height:16px;border-radius:3px;background:{{ $campaign->theme_bg_start }};margin-right:2px;"></span>
                                            <span
                                                style="display:inline-block;width:16px;height:16px;border-radius:3px;background:{{ $campaign->theme_bg_end }};margin-right:2px;"></span>
                                            <span
                                                style="display:inline-block;width:16px;height:16px;border-radius:3px;background:{{ $campaign->accent_color }};"></span>
                                        </td>
                                        <td>{{ $campaign->priority }}</td>
                                        <td class="text-right">
                                            <a href="{{ route('admin.campaigns.edit', $campaign) }}"
                                                class="btn btn-sm btn-outline-secondary">Edit</a>
                                            <form action="{{ route('admin.campaigns.destroy', $campaign) }}" method="POST"
                                                class="d-inline" onsubmit="return confirm('Delete this campaign?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No campaigns yet — create one to
                                            get
                                            started.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
