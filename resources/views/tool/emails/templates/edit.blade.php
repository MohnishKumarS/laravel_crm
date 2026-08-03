@extends('admin.layouts.main')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h4>
                    Edit Email Template
                </h4>

                <p class="text-muted mb-0">
                    Update your email template.
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

        <form action="{{ route('emails.templates.update', $template) }}" method="POST">

            @include('tool.emails.templates._form', [
                'template' => $template,
            ])

        </form>

    </div>
@endsection


@push('scripts')
    <script>
        function updatePreview(textareaId, iframeId) {
            const html = document.getElementById(textareaId).value;
            const iframe = document.getElementById(iframeId);
            const doc = iframe.contentDocument || iframe.contentWindow.document;
            doc.open();
            doc.write(html);
            doc.close();
        }

        $(document).ready(function() {

            updatePreview('emailBody', 'customer_preview');

        });
    </script>
@endpush
