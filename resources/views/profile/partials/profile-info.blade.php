
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
                <label for="street" class="form-label">Street</label>
                <input type="text" class="form-control @error('street') is-invalid @enderror"
                        value="{{ Auth::user()->street }}" id="street" name="street"
                        placeholder="Enter street address">
                @error('street')
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
                <label for="country" class="form-label">Country</label>
                <input type="text" class="form-control @error('country') is-invalid @enderror"
                        value="{{ Auth::user()->country }}" id="country" name="country"
                        placeholder="Enter country">
                @error('country')
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
