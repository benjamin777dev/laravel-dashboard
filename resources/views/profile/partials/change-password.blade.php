<div class="card mt-4">
            <div class="card-body">
                <h4 class="card-title mb-4">Change Password</h4>
                <form class="form-horizontal" id="change-password-form">
                    @csrf
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                            id="current_password" name="current_password"
                            placeholder="Enter current password">
                        @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" class="form-control @error('new_password') is-invalid @enderror"
                            id="new_password" name="new_password"
                            placeholder="Enter new password">
                        @error('new_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control @error('new_password_confirmation') is-invalid @enderror"
                            id="new_password_confirmation" name="new_password_confirmation"
                            placeholder="Confirm new password">
                        @error('new_password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mt-3 d-grid">
                        <button class="btn btn-primary waves-effect waves-light" type="submit">Change Password</button>
                    </div>
                </form>
            </div>
        </div>