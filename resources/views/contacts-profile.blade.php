@extends('layouts.master')

@section('title') @lang('Profile') @endsection

@section('content')

@component('components.breadcrumb')
    @slot('li_1') Contacts @endslot
    @slot('title') Profile @endslot
@endcomponent

<div class="row">
    <div class="col-xl-4">
        <div class="card overflow-hidden">
            <div class="bg-primary-subtle">
                <div class="row">
                    <div class="col-7">
                        <div class="text-primary p-3">
                            <h5 class="text-primary">Welcome Back, {{ Auth::user()->name }}!</h5>
                            <p>Edit your profile and agent information here.</p>
                        </div>
                    </div>
                    <div class="col-5 align-self-end">
                        <img src="{{ URL::asset('build/images/profile-img.png') }}" alt="" class="img-fluid">
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <h5 class="font-size-15 text-truncate">{{ Auth::user()->name }}</h5>
                        <p class="text-muted mb-0">{{ Auth::user()->email }}</p>
                        <p class="text-muted mb-0">{{ date('d-m-Y', strtotime(Auth::user()->email_verified_at)) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Agent Information Box -->
        <div class="card mt-4">
            <div class="card-body">
                <h4 class="card-title mb-4">Agent Information</h4>
                <form method="POST" id="update-agent-form">
                    @csrf
                    <input type="hidden" value="{{ Auth::user()->contact->id }}" id="id">

                    <div class="mb-3">
                        <label for="income_goal" class="form-label">Income Goal</label>
                        <input type="text" class="form-control currency-input @error('income_goal') is-invalid @enderror"
                            value="{{ number_format(Auth::user()->contact->income_goal, 2) }}" id="income_goal" name="income_goal"
                            @if(Auth::user()->contact->isPartOfTeam()) readonly @endif
                            placeholder="Enter income goal">
                        @error('income_goal')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="initial_cap" class="form-label">Initial Cap</label>
                        <input type="text" class="form-control currency-input @error('initial_cap') is-invalid @enderror"
                            value="{{ number_format(Auth::user()->contact->initial_cap, 2) }}" id="initial_cap" name="initial_cap"
                            @if(Auth::user()->contact->isPartOfTeam()) readonly @endif
                            placeholder="Enter initial cap">
                        @error('initial_cap')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="residual_cap" class="form-label">Residual Cap</label>
                        <input type="text" class="form-control currency-input @error('residual_cap') is-invalid @enderror"
                            value="{{ number_format(Auth::user()->contact->residual_cap, 2) }}" id="residual_cap" name="residual_cap"
                            @if(Auth::user()->contact->isPartOfTeam()) readonly @endif
                            placeholder="Enter residual cap">
                        @error('residual_cap')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mt-3 d-grid">
                        <button class="btn btn-primary waves-effect waves-light" type="submit">Update Agent Information</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        <!-- User Profile Edit Form -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Edit User Profile</h4>
                <form class="form-horizontal" id="update-profile-form">
                    @csrf
                    <input type="hidden" value="{{ Auth::user()->id }}" id="user_id">

                    <!-- User Profile Fields -->
                    <div class="mb-3">
                        <label for="useremail" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="useremail"
                               value="{{ Auth::user()->email }}" name="email" readonly placeholder="Enter email">
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               value="{{ Auth::user()->name }}" id="name" name="name" readonly
                               placeholder="Enter name">
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="mobile" class="form-label">Mobile</label>
                        <input type="text" class="form-control @error('mobile') is-invalid @enderror"
                               value="{{ Auth::user()->mobile }}" id="mobile" name="mobile"
                               placeholder="Enter mobile number">
                        @error('mobile')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="country" class="form-label">Country</label>
                        <input type="text" class="form-control @error('country') is-invalid @enderror"
                               value="{{ Auth::user()->country }}" id="country" name="country"
                               placeholder="Enter country">
                        @error('country')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="city" class="form-label">City</label>
                        <input type="text" class="form-control @error('city') is-invalid @enderror"
                               value="{{ Auth::user()->city }}" id="city" name="city"
                               placeholder="Enter city">
                        @error('city')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="state" class="form-label">State</label>
                        <input type="text" class="form-control @error('state') is-invalid @enderror"
                               value="{{ Auth::user()->state }}" id="state" name="state"
                               placeholder="Enter state">
                        @error('state')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="zip" class="form-label">Zip</label>
                        <input type="text" class="form-control @error('zip') is-invalid @enderror"
                               value="{{ Auth::user()->zip }}" id="zip" name="zip"
                               placeholder="Enter zip code">
                        @error('zip')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="street" class="form-label">Street</label>
                        <input type="text" class="form-control @error('street') is-invalid @enderror"
                               value="{{ Auth::user()->street }}" id="street" name="street"
                               placeholder="Enter street address">
                        @error('street')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="transaction_status_reports" class="form-label">Transaction Status Reports</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="transaction_status_reports" name="transaction_status_reports"
                                {{ Auth::user()->transaction_status_reports ? 'checked' : '' }}>
                        @error('transaction_status_reports')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="verified_sender_email" class="form-label">Verified Sender Email</label>
                        <input type="email" class="form-control @error('verified_sender_email') is-invalid @enderror"
                               value="{{ Auth::user()->verified_sender_email ?? Auth::user()->email }}" id="verified_sender_email"
                               name="verified_sender_email"
                               placeholder="Enter verified sender email">
                        @error('verified_sender_email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mt-3 d-grid">
                        <button class="btn btn-primary waves-effect waves-light" type="submit">Update Profile</button>
                    </div>
                </form>
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
        var userId = $('#user_id').val();
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
                if (response.isSuccess) {
                    alert(response.Message);
                } else {
                    alert(response.Message);
                }
            },
            error: function(response) {
                // Display errors
                $('#mobile').next('.invalid-feedback').text(response.responseJSON.errors.mobile);
                $('#country').next('.invalid-feedback').text(response.responseJSON.errors.country);
                $('#city').next('.invalid-feedback').text(response.responseJSON.errors.city);
                $('#state').next('.invalid-feedback').text(response.responseJSON.errors.state);
                $('#zip').next('.invalid-feedback').text(response.responseJSON.errors.zip);
                $('#street').next('.invalid-feedback').text(response.responseJSON.errors.street);
                $('#transaction_status_reports').next('.invalid-feedback').text(response.responseJSON.errors.transaction_status_reports);
                $('#verified_sender_email').next('.invalid-feedback').text(response.responseJSON.errors.verified_sender_email);
            }
        });
    });

    // Handle agent information update form submission
    $('#update-agent-form').on('submit', function(event) {
        event.preventDefault();
        var contactId = "{{ Auth::user()->contact->id }}";
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
                if (response.isSuccess) {
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                } else {
                    alert(response.Message);
                }
            },
            error: function(response) {
                // Display errors
                $('#income_goal').next('.invalid-feedback').text(response.responseJSON.errors.income_goal);
                $('#initial_cap').next('.invalid-feedback').text(response.responseJSON.errors.initial_cap);
                $('#residual_cap').next('.invalid-feedback').text(response.responseJSON.errors.residual_cap);
            }
        });
    });
</script>
@endsection
