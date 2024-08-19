@extends('layouts.master')

@section('title') @lang('Profile') @endsection
<style>
    /* Non-active accordion items */
    .accordion-button.collapsed {
        background-color: #2222;
        color: #ffffff;
    }

    /* Active accordion item */
    .accordion-button:not(.collapsed) {
        background-color: #74788d; /* Replace with your dark gray color */
        color: #ffffff;
    }

    /* Optional: Hover state for non-active items */
    .accordion-button.collapsed:hover {
        background-color: #1c1c1c;
        color: #ffffff;
    }
</style>


@section('content')
@component('components.breadcrumb')
    @slot('li_1') Contacts @endslot
    @slot('title') Profile @endslot
@endcomponent

<div class="row">
    <div class="col-lg-3 col-md-4">
        <!-- User Info Panel -->
        <div class="card">
            <div class="card-body text-center">
                <h4>Welcome, {{ Auth::user()->name }}</h4>
                <p class="text-muted">{{ Auth::user()->email }}</p>
            </div>
        </div>

        <!-- Change Password Panel -->
        <div class="card mt-3">
            <div class="card-body">
                <h5>Change Password</h5>
                @include('profile.partials.change-password')
            </div>
        </div>
    </div>

    <div class="col-lg-9 col-md-8">
        <!-- Profile Info Section -->
        <div class="accordion" id="profileAccordion">
            <!-- Profile Info -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingProfileInfo">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseProfileInfo" aria-expanded="true" aria-controls="collapseProfileInfo">
                        Profile Information
                    </button>
                </h2>
                <div id="collapseProfileInfo" class="accordion-collapse collapse show" aria-labelledby="headingProfileInfo" data-bs-parent="#profileAccordion">
                    <div class="accordion-body">
                        @include('profile.partials.profile-info')
                    </div>
                </div>
            </div>

            <!-- Agent Info -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingAgentInfo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAgentInfo" aria-expanded="false" aria-controls="collapseAgentInfo">
                        Agent Information
                    </button>
                </h2>
                <div id="collapseAgentInfo" class="accordion-collapse collapse" aria-labelledby="headingAgentInfo" data-bs-parent="#profileAccordion">
                    <div class="accordion-body">
                        @include('profile.partials.agent-info')
                    </div>
                </div>
            </div>

            <!-- Marketing Info -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingMarketingInfo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMarketingInfo" aria-expanded="false" aria-controls="collapseMarketingInfo">
                        Marketing Information
                    </button>
                </h2>
                <div id="collapseMarketingInfo" class="accordion-collapse collapse" aria-labelledby="headingMarketingInfo" data-bs-parent="#profileAccordion">
                    <div class="accordion-body">
                        @include('profile.partials.marketing-info')
                    </div>
                </div>
            </div>

            <!-- Listing Submittal Defaults -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingListingDefaults">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseListingDefaults" aria-expanded="false" aria-controls="collapseListingDefaults">
                        Listing Submittal Defaults
                    </button>
                </h2>
                <div id="collapseListingDefaults" class="accordion-collapse collapse" aria-labelledby="headingListingDefaults" data-bs-parent="#profileAccordion">
                    <div class="accordion-body">
                        @include('profile.partials.listing-defaults')
                    </div>
                </div>
            </div>

            <!-- Buyer Submittal Defaults -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingBuyerDefaults">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBuyerDefaults" aria-expanded="false" aria-controls="collapseBuyerDefaults">
                        Buyer Submittal Defaults
                    </button>
                </h2>
                <div id="collapseBuyerDefaults" class="accordion-collapse collapse" aria-labelledby="headingBuyerDefaults" data-bs-parent="#profileAccordion">
                    <div class="accordion-body">
                        @include('profile.partials.buyer-defaults')
                    </div>
                </div>
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
