@extends('layouts.master')

@section('content')
<div class="container">
    <h1 class="text-danger">RESTRICTED | AUTHORIZED USE ONLY</h1>
    <h2>Enter Passcode</h2>
    <form action="{{ route('telescope') }}" method="GET">
        @csrf
        <div class="form-group">
            <label for="passcode">Enter Passcode:</label>
            <input type="password" name="passcode" id="passcode" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection
