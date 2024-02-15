@extends('layouts.app')

@section('title', 'Agent Commander | Contact Details')

@section('content')
<div class="container">
    <h1>Contact Details: {{ $contactDetails['Full_Name'] ?? 'N/A' }}</h1>
    <div>
        <p>Email: {{ $contactDetails['Email'] ?? 'N/A' }}</p>
        <p>Phone: {{ $contactDetails['Phone'] ?? 'N/A' }}</p>
        <p>Mobile: {{ $contactDetails['Mobile'] ?? 'N/A' }}</p>
        <p>Secondary Email: {{ $contactDetails['Secondary_Email'] ?? 'N/A' }}</p>
        <p>Envelope Salutation: {{ $contactDetails['Salutation_s'] ?? 'N/A' }}</p>
        <p>Relationship Type: {{ $contactDetails['Relationship_Type'] ?? 'N/A' }}</p>
        <p>Referred By: {{ $contactDetails['Referred_By'] ?? 'N/A' }}</p>
        <p>Lead Source: {{ $contactDetails['Lead_Source'] ?? 'N/A' }}</p>
        <p>Lead Source Detail: {{ $contactDetails['Lead_Source_Detail'] ?? 'N/A' }}</p>
        <p>Market Area: {{ $contactDetails['Market_Area'] ?? 'N/A' }}</p>
        <p>Business Info: {{ $contactDetails['Business_Info'] ?? 'N/A' }}</p>
        <p>Address: {{ $contactDetails['Mailing_Street'] ?? '' }}, {{ $contactDetails['Mailing_City'] ?? '' }}, {{ $contactDetails['Mailing_State'] ?? '' }}, {{ $contactDetails['Mailing_Zip'] ?? '' }}</p>
    </div>
</div>
@endsection