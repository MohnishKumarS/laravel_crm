@extends('admin.layouts.main')

@section('title', $form->title . ' | Yuukke Dashboard')

@section('content')
    {{-- Breadcrumb --}}
    <div class="page-header">
        <h3 class="fw-bold mb-3">Form Page</h3>
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
                <a href="{{ route('forms.index') }}">Forms</a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">Show</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">{{ $form->title }}</div>
                </div>
                <div class="card-body">
                    <p><strong>Slug:</strong> <code>{{ $form->slug }}</code></p>
                    <p><strong>Public API:</strong> <code>GET /api/forms/{{ $form->slug }}</code></p>

                    <h5 class="mt-4">Fields</h5>
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Label</th>
                                <th>Type</th>
                                <th>Required</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($form->fields as $field)
                                <tr>
                                    <td>{{ $field['name'] }}</td>
                                    <td>{{ $field['label'] }}</td>
                                    <td>{{ $field['type'] }}</td>
                                    <td>{{ !empty($field['required']) ? 'Yes' : 'No' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <h5 class="mt-4">Submissions ({{ $form->submissions->count() }})</h5>
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Data</th>
                                <th>Submitted At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @forelse ($form->submissions as $submission)
                                <tr>
                                    <td>{{ $i++}}</td>
                                    <td>
                                        <pre class="mb-0">{{ json_encode($submission->data, JSON_PRETTY_PRINT) }}</pre>
                                    </td>
                                    <td>{{ $submission->created_at->format('d M Y, h:i A') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">No submissions yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <a href="{{ route('forms.index') }}" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
