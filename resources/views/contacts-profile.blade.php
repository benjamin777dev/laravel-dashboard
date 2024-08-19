@extends('layouts.master')

@section('title') @lang('Profile') @endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Contacts @endslot
    @slot('title') Profile @endslot
@endcomponent

<div class="row">
    <div class="col-xl-12">
        <ul class="nav nav-tabs" id="profileTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="profile-info-tab" data-bs-toggle="tab" href="#profile-info" role="tab" aria-controls="profile-info" aria-selected="true">Profile Information</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="agent-info-tab" data-bs-toggle="tab" href="#agent-info" role="tab" aria-controls="agent-info" aria-selected="false">Agent Information</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="marketing-info-tab" data-bs-toggle="tab" href="#marketing-info" role="tab" aria-controls="marketing-info" aria-selected="false">Marketing Information</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="listing-defaults-tab" data-bs-toggle="tab" href="#listing-defaults" role="tab" aria-controls="listing-defaults" aria-selected="false">Listing Submittal Defaults</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="buyer-defaults-tab" data-bs-toggle="tab" href="#buyer-defaults" role="tab" aria-controls="buyer-defaults" aria-selected="false">Buyer Submittal Defaults</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="change-password-tab" data-bs-toggle="tab" href="#change-password" role="tab" aria-controls="change-password" aria-selected="false">Change Password</a>
            </li>
        </ul>

        <div class="tab-content mt-3" id="profileTabContent">
            <!-- Profile Information Tab -->
            <div class="tab-pane fade show active" id="profile-info" role="tabpanel" aria-labelledby="profile-info-tab">
                <!-- Profile Info Form Content -->
                @include('profile.partials.profile-info')
            </div>

            <!-- Agent Information Tab -->
            <div class="tab-pane fade" id="agent-info" role="tabpanel" aria-labelledby="agent-info-tab">
                <!-- Agent Info Form Content -->
                @include('profile.partials.agent-info')
            </div>

            <!-- Marketing Information Tab -->
            <div class="tab-pane fade" id="marketing-info" role="tabpanel" aria-labelledby="marketing-info-tab">
                <!-- Marketing Info Form Content -->
                @include('profile.partials.marketing-info')
            </div>

            <!-- Listing Submittal Defaults Tab -->
            <div class="tab-pane fade" id="listing-defaults" role="tabpanel" aria-labelledby="listing-defaults-tab">
                <!-- Listing Submittal Defaults Form Content -->
                @include('profile.partials.listing-defaults')
            </div>

            <!-- Buyer Submittal Defaults Tab -->
            <div class="tab-pane fade" id="buyer-defaults" role="tabpanel" aria-labelledby="buyer-defaults-tab">
                <!-- Buyer Submittal Defaults Form Content -->
                @include('profile.partials.buyer-defaults')
            </div>

            <!-- Change Password Tab -->
            <div class="tab-pane fade" id="change-password" role="tabpanel" aria-labelledby="change-password-tab">
                <!-- Change Password Form Content -->
                @include('profile.partials.change-password')
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ URL::asset('build/js/pages/profile.init.js') }}"></script>

<script>
    // Handle profile update form submission
    $('#update-profile-form').on('submit', function(event) {
        event.preventDefault();
        let formData = new FormData(this);

        // Reset errors
        $('.invalid-feedback').text('');

        $.ajax({
            url: "{{ route('profile.update', Auth::user()->id) }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                alert(response.Message);
            },
            error: function(response) {
                // Display errors
                $.each(response.responseJSON.errors, function(field, message) {
                    $(`#${field}`).next('.invalid-feedback').text(message);
                });
            }
        });
    });

    // General function to handle agent information form submissions
    function handleAgentInfoFormSubmission(formId) {
        $(`#${formId}`).on('submit', function(event) {
            event.preventDefault();
            let formData = new FormData(this);

            // Reset errors
            $('.invalid-feedback').text('');

            // Strip currency formatting before sending data
            $('.currency-input').each(function() {
                let value = $(this).val();
                $(this).val(value.replace(/,/g, '').replace('$', ''));
            });

            $.ajax({
                url: "{{ route('profile.updateAgentInfo', Auth::user()->id) }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    alert(response.Message);
                },
                error: function(response) {
                    // Display errors
                    $.each(response.responseJSON.errors, function(field, message) {
                        $(`#${field}`).next('.invalid-feedback').text(message);
                    });
                }
            });
        });
    }

    // Attach the handler to all the agent-related forms
    handleAgentInfoFormSubmission('update-agent-form');
    handleAgentInfoFormSubmission('update-agent-marketing-information-form');
    handleAgentInfoFormSubmission('update-agent-listing-submittal-defaults-form');
    handleAgentInfoFormSubmission('update-agent-buyer-submittal-defaults-form');

    // Handle change password form submission
    $('#change-password-form').on('submit', function(event) {
        event.preventDefault();
        let formData = new FormData(this);

        // Reset errors
        $('.invalid-feedback').text('');

        $.ajax({
            url: "{{ route('profile.changePassword') }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                alert(response.Message);
            },
            error: function(response) {
                // Display errors
                $.each(response.responseJSON.errors, function(field, message) {
                    $(`#${field}`).next('.invalid-feedback').text(message);
                });
            }
        });
    });
</script>


@endsection
