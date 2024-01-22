<!-- resources/views/auth/passwords/email.blade.php -->
@extends('layouts.app')

@section('content')

<form method="POST" action="{{ route('password.email') }}">
    @csrf

    <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" class="form-control" name="email" id="email" required autofocus>
    </div>

    <button type="submit" class="btn btn-primary">
        Send Password Reset Link
    </button>
</form>

@endsection
