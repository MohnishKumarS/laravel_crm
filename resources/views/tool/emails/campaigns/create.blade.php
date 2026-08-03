@extends('admin.layouts.main')

@section('content')

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h4 class="mb-1">
                    Create Email Campaign
                </h4>

                <p class="text-muted mb-0">
                    Send a marketing email to users, sellers or custom recipients.
                </p>

            </div>

            <a href="{{ route('emails.campaigns.index') }}" class="btn btn-outline-secondary">
                Back
            </a>

        </div>


        {{-- Errors --}}
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


        <form method="POST" action="{{ route('emails.campaigns.store') }}" id="campaignForm">

            @csrf

            <div class="row">


                {{-- LEFT --}}
                <div class="col-lg-8">

                    {{-- Campaign Details --}}
                    <div class="card border-0 shadow-sm mb-4">

                        <div class="card-header bg-white">

                            <h5 class="mb-0">
                                Campaign Details
                            </h5>

                        </div>


                        <div class="card-body">

                            {{-- Campaign Name --}}
                            <div class="mb-3">

                                <label class="form-label">
                                    Campaign Name
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                    placeholder="Example: July Product Promotion" required>

                            </div>


                            {{-- Template --}}
                            <div class="mb-3">

                                <label class="form-label">

                                    Email Template
                                    <span class="text-danger">*</span>

                                </label>

                                <select name="template_id" class="form-select" required>

                                    <option value="">
                                        Select Template
                                    </option>

                                    @foreach ($templates as $template)
                                        <option value="{{ $template->id }}" @selected(old('template_id') == $template->id)>

                                            {{ $template->name }}
                                            -
                                            {{ $template->subject }}

                                        </option>
                                    @endforeach

                                </select>

                            </div>

                        </div>

                    </div>


                    {{-- Sender --}}
                    <div class="card border-0 shadow-sm mb-4">

                        <div class="card-header bg-white">
                            <h5 class="mb-0">Sender Email</h5>
                        </div>

                        <div class="card-body">

                            <p class="text-muted small">
                                Enter the email address that will be used as the sender.
                            </p>

                            <input type="email" name="sender_email" class="form-control" value="no-reply@yuukke.org"
                                placeholder="sender@example.com" value="{{ old('sender_email') }}" readonly required>

                            @error('sender_email')
                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>


                    {{-- Recipients --}}
                    <div class="card border-0 shadow-sm">

                        <div class="card-header bg-white">

                            <h5 class="mb-0">
                                Recipients
                            </h5>

                        </div>


                        <div class="card-body">


                            {{-- Recipient Type --}}
                            <div class="mb-4">

                                <label class="form-label">
                                    Send To
                                </label>


                                <div class="row">


                                    <div class="col-md-4">

                                        <div class="form-check border rounded p-3">

                                            <input type="radio" name="recipient_type" value="users" id="usersRadio"
                                                class="form-check-input" @checked(old('recipient_type') === 'users')>

                                            <label for="usersRadio" class="form-check-label">

                                                <strong>
                                                    Users
                                                </strong>

                                                <br>

                                                <small class="text-muted">
                                                    Select registered users
                                                </small>

                                            </label>

                                        </div>

                                    </div>


                                    <div class="col-md-4">

                                        <div class="form-check border rounded p-3">

                                            <input type="radio" name="recipient_type" value="sellers" id="sellersRadio"
                                                class="form-check-input" @checked(old('recipient_type') === 'sellers')>

                                            <label for="sellersRadio" class="form-check-label">

                                                <strong>
                                                    Sellers
                                                </strong>

                                                <br>

                                                <small class="text-muted">
                                                    Select sellers
                                                </small>

                                            </label>

                                        </div>

                                    </div>


                                    <div class="col-md-4">

                                        <div class="form-check border rounded p-3">

                                            <input type="radio" name="recipient_type" value="custom" id="customRadio"
                                                class="form-check-input" @checked(old('recipient_type') === 'custom')>

                                            <label for="customRadio" class="form-check-label">

                                                <strong>
                                                    Custom
                                                </strong>

                                                <br>

                                                <small class="text-muted">
                                                    Enter email addresses
                                                </small>

                                            </label>

                                        </div>

                                    </div>

                                </div>

                            </div>


                            {{-- Users --}}
                            <div id="usersBox" class="recipient-box d-none">
                                <label class="form-label">Select Users</label>

                                <div class="form-check mb-2">
                                    <input type="checkbox" class="form-check-input" id="selectAllUsers">
                                    <label class="form-check-label" for="selectAllUsers">
                                        Select All Users
                                    </label>
                                </div>

                                <select name="recipient_ids[]" id="usersSelect" class="form-select" multiple></select>

                                <small class="text-muted">
                                    Select one or more users.
                                </small>
                            </div>


                            {{-- Sellers --}}
                            <div id="sellersBox" class="recipient-box d-none">
                                <label class="form-label">Select Sellers</label>

                                <div class="form-check mb-2">
                                    <input type="checkbox" class="form-check-input" id="selectAllSellers">
                                    <label class="form-check-label" for="selectAllSellers">
                                        Select All Sellers
                                    </label>
                                </div>

                                <select name="recipient_ids[]" id="sellersSelect" class="form-select" multiple></select>

                                <small class="text-muted">
                                    Select one or more sellers.
                                </small>
                            </div>


                            {{-- Custom --}}
                            <div id="customBox" class="recipient-box d-none">

                                <label class="form-label">

                                    Email Addresses

                                </label>


                                <textarea name="custom_emails" class="form-control" rows="8"
                                    placeholder="Enter one email per line&#10;&#10;john@gmail.com&#10;test@gmail.com&#10;hello@example.com">{{ old('custom_emails') }}</textarea>


                                <small class="text-muted">

                                    You can enter one email per line,
                                    comma separated or semicolon separated.

                                </small>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- RIGHT --}}
                <div class="col-lg-4">

                    {{-- Summary --}}
                    <div class="card border-0 shadow-sm mb-4">

                        <div class="card-header bg-white">

                            <h5 class="mb-0">
                                Campaign Summary
                            </h5>

                        </div>


                        <div class="card-body">

                            <div class="mb-3">

                                <small class="text-muted">
                                    Template
                                </small>

                                <div id="summaryTemplate">
                                    Not selected
                                </div>

                            </div>


                            <div class="mb-3">

                                <small class="text-muted">
                                    Recipient Type
                                </small>

                                <div id="summaryRecipient">
                                    Not selected
                                </div>

                            </div>


                            <div class="mb-3">

                                <small class="text-muted">
                                    Sender Count
                                </small>

                                <div id="summarySenders">
                                    0
                                </div>

                            </div>


                            <hr>


                            <div class="alert alert-info mb-0">

                                <i class="bi bi-info-circle"></i>

                                Emails will be sent through the Laravel
                                queue.

                            </div>

                        </div>

                    </div>


                    {{-- Submit --}}
                    <div class="card border-0 shadow-sm">

                        <div class="card-body">

                            <button type="submit" class="btn btn-primary w-100" id="sendCampaignBtn">

                                <i class="bi bi-send"></i>

                                Create & Queue Campaign

                            </button>


                            <a href="{{ route('emails.campaigns.index') }}" class="btn btn-light w-100 mt-2">

                                Cancel

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </form>

    </div>

@endsection


@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .select2-container {
            width: 100% !important;
        }

        .select2-container .select2-selection--multiple {
            min-height: 44px;
            border-color: #dee2e6;
        }
    </style>
@endpush


@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <script>
        $(document).ready(function() {


            /*
            |--------------------------------------------------------------------------
            | Sender Select
            |--------------------------------------------------------------------------
            */

            // $('select[name="sender_ids[]"]').select2({
            //     placeholder: 'Select sender emails',
            //     allowClear: true
            // });


            /*
            |--------------------------------------------------------------------------
            | Users Select
            |--------------------------------------------------------------------------
            */

            $.ajax({
                url: "{{ route('emails.recipients.users') }}",
                type: "GET",
                dataType: "json",
                success: function(data) {
                    data.data.forEach(function(user) {
                        const option = new Option(
                            user.name + ' - ' + user.email,
                            user.id,
                            false,
                            false
                        );

                        $('#usersSelect').append(option);
                    });

                    $('#usersSelect').select2({
                        placeholder: 'Select users',
                        width: '100%'
                    });
                }
            });

            $('#selectAllUsers').on('change', function() {
                if ($(this).is(':checked')) {
                    const allUserIds = $('#usersSelect option').map(function() {
                        return $(this).val();
                    }).get();

                    $('#usersSelect').val(allUserIds).trigger('change');
                } else {
                    $('#usersSelect').val([]).trigger('change');
                }
            });


            /*
            |--------------------------------------------------------------------------
            | Sellers Select
            |--------------------------------------------------------------------------
            */


            $.ajax({
                url: "{{ route('emails.recipients.sellers') }}",
                type: "GET",
                dataType: "json",
                success: function(data) {
                    console.log(data);

                    data.data.forEach(function(seller) {

                        const option = new Option(
                            seller.first_name + ' - ' + seller.email,
                            seller.id,
                            false,
                            false
                        );

                        $('#sellersSelect').append(option);
                    });

                    $('#sellersSelect').select2({
                        placeholder: 'Select sellers',
                        width: '100%'
                    });
                }
            });

            $('#selectAllSellers').on('change', function() {

                if ($(this).is(':checked')) {

                    const allSellerIds = $('#sellersSelect option').map(function() {
                        return $(this).val();
                    }).get();

                    $('#sellersSelect').val(allSellerIds).trigger('change');

                } else {

                    $('#sellersSelect').val([]).trigger('change');

                }

            });

            /*
            |--------------------------------------------------------------------------
            | Recipient Type
            |--------------------------------------------------------------------------
            */

            $('input[name="recipient_type"]')
                .on('change', function() {

                    let type = $(this).val();


                    $('.recipient-box')
                        .addClass('d-none');


                    /*
                    | Prevent recipient_ids[] from
                    | submitting for hidden selects
                    */

                    $('#usersSelect')
                        .prop('disabled', true);


                    $('#sellersSelect')
                        .prop('disabled', true);


                    if (type === 'users') {

                        $('#usersBox')
                            .removeClass('d-none');

                        $('#usersSelect')
                            .prop('disabled', false);


                    } else if (type === 'sellers') {

                        $('#sellersBox')
                            .removeClass('d-none');

                        $('#sellersSelect')
                            .prop('disabled', false);


                    } else if (type === 'custom') {

                        $('#customBox')
                            .removeClass('d-none');

                    }


                    $('#summaryRecipient')
                        .text(
                            type ?
                            type.charAt(0).toUpperCase() +
                            type.slice(1) :
                            'Not selected'
                        );

                });


            /*
            |--------------------------------------------------------------------------
            | Sender Count
            |--------------------------------------------------------------------------
            */

            // $('select[name="sender_ids[]"]')
            //     .on('change', function() {

            //         $('#summarySenders')
            //             .text(
            //                 $(this).val()?.length || 0
            //             );

            //     });


            /*
            |--------------------------------------------------------------------------
            | Template Summary
            |--------------------------------------------------------------------------
            */

            $('select[name="template_id"]')
                .on('change', function() {

                    let text = $(this)
                        .find('option:selected')
                        .text();

                    $('#summaryTemplate')
                        .text(
                            $(this).val() ?
                            text.trim() :
                            'Not selected'
                        );

                });


            /*
            |--------------------------------------------------------------------------
            | Trigger selected recipient type
            |--------------------------------------------------------------------------
            */

            $('input[name="recipient_type"]:checked')
                .trigger('change');


            /*
            |--------------------------------------------------------------------------
            | Submit Button
            |--------------------------------------------------------------------------
            */

            $('#campaignForm')
                .on('submit', function() {

                    $('#sendCampaignBtn')
                        .prop('disabled', true)
                        .html(
                            '<span class="spinner-border spinner-border-sm me-2"></span>' +
                            'Creating Campaign...'
                        );

                });

        });
    </script>
@endpush
