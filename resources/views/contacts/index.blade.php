@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Contacts</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Contact Name</th>
                <th>ABCD</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Mobile</th>
                <th>Address</th>
                <th>Imp Date</th>
                <th>Perfect</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($contacts as $contact)
            <tr>
                <td>{{ $contact['Full_Name'] ?? 'N/A' }}</td>
                <td>{{ $contact['ABCD'] ?? ''}}</td>
                <td>{{ $contact['Email'] ?? 'N/A' }}</td>
                <td>{{ $contact['Phone'] ?? 'N/A' }}</td>
                <td>{{ $contact['Mobile'] ?? 'N/A' }}</td>
                <td>{{ $contact['Mailing_Street'] ?? '' }} {{ $contact['Mailing_City'] ?? '' }} {{ $contact['Mailing_State'] ?? '' }} {{ $contact['Mailing_Zip'] ?? '' }}</td>
                <td>{{ !$contact['HasMissingImportantDate'] ?? true }}</td>
                <td>{{ $contact['perfect'] ?? false }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
