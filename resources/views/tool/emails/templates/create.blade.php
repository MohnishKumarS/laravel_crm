@extends('admin.layouts.main')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h4>
                    Create Email Template
                </h4>

                <p class="text-muted mb-0">
                    Create a reusable marketing email template.
                </p>

            </div>


            <a href="{{ route('emails.templates.index') }}" class="btn btn-outline-secondary">
                Back
            </a>

        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Please fix the following:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('emails.templates.store') }}" method="POST">

            @csrf


            <div class="row">

                {{-- Left Side --}}
                <div class="col-lg-8">

                    <div class="card shadow-sm border-0">

                        <div class="card-body">

                            {{-- Template Name --}}
                            <div class="mb-3">

                                <label class="form-label">
                                    Template Name
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                    placeholder="Example: Summer Sale Campaign">

                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Subject --}}
                            <div class="mb-3">

                                <label class="form-label">
                                    Email Subject
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text" name="subject"
                                    class="form-control @error('subject') is-invalid @enderror" value="{{ old('subject') }}"
                                    placeholder="Example: Get 30% Off This Summer">

                                @error('subject')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Email Body --}}
                            <div class="mb-3">

                                {{-- <textarea name="body" id="emailBody" class="form-control @error('body') is-invalid @enderror">{{ old('body') }}</textarea> --}}
                                <label class="form-label">Email — Body (Raw HTML) <span class="text-danger"> *
                                    </span></label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="small text-muted mb-1">HTML Source</p>
                                        <textarea name="body" id="emailBody" class="form-control" rows="25"
                                            style="font-family: monospace; font-size: 12px; resize: vertical;"
                                            oninput="updatePreview('emailBody', 'customer_preview')">{{ old('body') }}</textarea>
                                        <small class="text-muted">
                                            Use the field's machine name wrapped in double curly braces to insert a
                                            submitted value.
                                        </small>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="small text-muted mb-1">Live Preview</p>
                                        <iframe id="customer_preview"
                                            style="width:100%; height:480px; border:1px solid #ddd; border-radius:6px; background:#fff;"></iframe>
                                    </div>
                                </div>


                                @error('body')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Right Side --}}
                <div class="col-lg-4">

                    <div class="card shadow-sm border-0">

                        <div class="card-body">

                            {{-- Category --}}
                            <div class="mb-3">

                                <label class="form-label">
                                    Category
                                </label>

                                <input type="text" name="category" class="form-control" value="{{ old('category') }}"
                                    placeholder="Promotion">

                            </div>


                            {{-- Description --}}
                            <div class="mb-3">

                                <label class="form-label">
                                    Description
                                </label>

                                <textarea name="description" rows="4" class="form-control" placeholder="Describe this template...">{{ old('description') }}</textarea>

                            </div>


                            {{-- Status --}}
                            <div class="mb-3">

                                <label class="form-label">
                                    Status
                                </label>

                                <select name="status" class="form-select">

                                    <option value="active" @selected(old('status', 'active') === 'active')>
                                        Active
                                    </option>

                                    <option value="inactive" @selected(old('status') === 'inactive')>
                                        Inactive
                                    </option>

                                </select>

                            </div>


                            {{-- Submit --}}
                            <button type="submit" class="btn btn-primary w-100">

                                <i class="bi bi-check-lg"></i>

                                Save Template

                            </button>

                        </div>

                    </div>


                    {{-- Available Variables --}}
                    <div class="card shadow-sm border-0 mt-3">

                        <div class="card-body">

                            <h6>
                                Available Variables
                            </h6>

                            <p class="text-muted small">
                                You can use these variables in your template.
                            </p>

                            <div class="d-flex flex-wrap gap-2">

                                <code>
                                    @{{ name }}
                                </code>

                                <code>
                                    @{{ email }}
                                </code>

                                <code>
                                    @{{ company_name }}
                                </code>

                                <code>
                                    @{{ unsubscribe_url }}
                                </code>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </form>

    </div>
@endsection


@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.css" rel="stylesheet">
@endpush


@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.js"></script>


    <script>
        function updatePreview(textareaId, iframeId) {
            const html = document.getElementById(textareaId).value;
            const iframe = document.getElementById(iframeId);
            const doc = iframe.contentDocument || iframe.contentWindow.document;
            doc.open();
            doc.write(html);
            doc.close();
        }

        document.addEventListener('DOMContentLoaded', function() {
            updatePreview('emailBody', 'customer_preview');
        });

        // $(document).ready(function() {

        //     $('#emailBody').summernote({

        //         height: 500,

        //         placeholder: 'Write your email HTML content here...',

        //         toolbar: [

        //             ['style', ['style']],

        //             [
        //                 'font',
        //                 [
        //                     'bold',
        //                     'italic',
        //                     'underline',
        //                     'clear'
        //                 ]
        //             ],

        //             [
        //                 'fontname',
        //                 ['fontname']
        //             ],

        //             [
        //                 'fontsize',
        //                 ['fontsize']
        //             ],

        //             [
        //                 'color',
        //                 ['color']
        //             ],

        //             [
        //                 'para',
        //                 [
        //                     'ul',
        //                     'ol',
        //                     'paragraph'
        //                 ]
        //             ],

        //             [
        //                 'insert',
        //                 [
        //                     'link',
        //                     'picture',
        //                     'table'
        //                 ]
        //             ],

        //             [
        //                 'view',
        //                 [
        //                     'fullscreen',
        //                     'codeview'
        //                 ]
        //             ]

        //         ]

        //     });

        // });
    </script>
@endpush
