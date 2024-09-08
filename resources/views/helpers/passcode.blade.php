@extends('layouts.master')

@section('content')
<div class="container">
    <h1>Enter Passcode</h1>
    <form action="{{ route('telescope.passcode') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="passcode">Enter Passcode:</label>
            <input type="password" name="passcode" id="passcode" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection
