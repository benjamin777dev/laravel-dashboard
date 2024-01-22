<!-- resources/views/auth/passwords/reset.blade.php -->
@extends('layouts.app')

@section('content')

<form method="POST" action="{{ route('password.update') }}">
    @csrf

    <input type="hidden" name="token" value="{{ $token }}">

    <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" class="form-control" name="email" id="email" value="{{ $email ?? old('email') }}" required autofocus>
    </div>

    <div class="form-group">
        <label for="password">New Password</label>
        <input type="password" class="form-control" name="password" id="password" required>
    </div>

    <div class="form-group">
        <label for="password-confirm">Confirm New Password</label>
        <input type="password" class="form-control" name="password_confirmation" id="password-confirm" required>
    </div>

    <button type="submit" class="btn btn-primary">
        Reset Password
    </button>
</form>
@endsection
