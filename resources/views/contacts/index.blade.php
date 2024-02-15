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
                <td>{{ $contact['Full_Name'] ?? '' }}</td>
                <td class="{{ $contact['abcdBackColor'] ?? '' }}" style="color:{{ $contact['abcdForeColor'] ?? '' }}">{{ $contact['ABCD'] ?? ''}}</td>
                <td>{{ $contact['Email'] ?? '' }}</td>
                <td>{{ $contact['Phone'] ?? '' }}</td>
                <td>{{ $contact['Mobile'] ?? '' }}</td>
                <td>{{ $contact['Mailing_Street'] ?? '' }} {{ $contact['Mailing_City'] ?? '' }} {{ $contact['Mailing_State'] ?? '' }} {{ $contact['Mailing_Zip'] ?? '' }}</td>
                <td>
                    <input type="checkbox" disabled {{ $contact['HasMissingImportantDate'] ?? false ? '' : 'checked' }}>
                </td>
                <td>
                    <input type="checkbox" disabled {{ $contact['perfect'] ?? false ? 'checked' : '' }}>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
