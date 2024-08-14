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
                            <p>Edit your profile information here.</p>
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
                <div class="mt-4 text-center">
                    <a href="{{ route('contacts.show', Auth::user()->contact->zoho_contact_id) }}" class="btn btn-primary waves-effect waves-light btn-sm">
                        View/Edit Contact Profile <i class="mdi mdi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        <!-- User Profile Edit Form -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Edit User Profile</h4>
                <form class="form-horizontal" method="POST" id="update-profile">
                    @csrf
                    <input type="hidden" value="{{ Auth::user()->id }}" id="data_id">

                    <!-- User Profile Fields -->
                    <div class="mb-3">
                        <label for="useremail" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="useremail" value="{{ Auth::user()->email }}" name="email" placeholder="Enter email" autofocus>
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" value="{{ Auth::user()->name }}" id="username" name="name" autofocus placeholder="Enter username">
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="mobile" class="form-label">Mobile</label>
                        <input type="text" class="form-control @error('mobile') is-invalid @enderror" value="{{ Auth::user()->mobile }}" id="mobile" name="mobile" placeholder="Enter mobile number">
                        @error('mobile')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="country" class="form-label">Country</label>
                        <input type="text" class="form-control @error('country') is-invalid @enderror" value="{{ Auth::user()->country }}" id="country" name="country" placeholder="Enter country">
                        @error('country')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="city" class="form-label">City</label>
                        <input type="text" class="form-control @error('city') is-invalid @enderror" value="{{ Auth::user()->city }}" id="city" name="city" placeholder="Enter city">
                        @error('city')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="state" class="form-label">State</label>
                        <input type="text" class="form-control @error('state') is-invalid @enderror" value="{{ Auth::user()->state }}" id="state" name="state" placeholder="Enter state">
                        @error('state')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="zip" class="form-label">Zip</label>
                        <input type="text" class="form-control @error('zip') is-invalid @enderror" value="{{ Auth::user()->zip }}" id="zip" name="zip" placeholder="Enter zip code">
                        @error('zip')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="street" class="form-label">Street</label>
                        <input type="text" class="form-control @error('street') is-invalid @enderror" value="{{ Auth::user()->street }}" id="street" name="street" placeholder="Enter street address">
                        @error('street')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="transaction_status_reports" class="form-label">Transaction Status Reports</label>
                        <input type="text" class="form-control @error('transaction_status_reports') is-invalid @enderror" value="{{ Auth::user()->transaction_status_reports }}" id="transaction_status_reports" name="transaction_status_reports" placeholder="Enter transaction status reports">
                        @error('transaction_status_reports')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="verified_sender_email" class="form-label">Verified Sender Email</label>
                        <input type="email" class="form-control @error('verified_sender_email') is-invalid @enderror" value="{{ Auth::user()->verified_sender_email }}" id="verified_sender_email" name="verified_sender_email" placeholder="Enter verified sender email">
                        @error('verified_sender_email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mt-3 d-grid">
                        <button class="btn btn-primary waves-effect waves-light UpdateProfile" data-id="{{ Auth::user()->id }}" type="submit">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ URL::asset('build/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/profile.init.js') }}"></script>

<script>
    $('#update-profile').on('submit', function(event) {
        event.preventDefault();
        var Id = $('#data_id').val();
        let formData = new FormData(this);
        
        // Reset errors
        $('.invalid-feedback').text('');

        $.ajax({
            url: "{{ url('update-profile') }}" + "/" + Id,
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
                $('#useremail').next('.invalid-feedback').text(response.responseJSON.errors.email);
                $('#username').next('.invalid-feedback').text(response.responseJSON.errors.name);
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
</script>
@endsection
