@extends('layouts.master')

@section('title', 'Agent Commander | Contact Create')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@section('content')
    @vite(['resources/css/custom.css'])
    @vite(['resources/js/toast.js'])

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error')) 
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <div class="container full-width-container">
        <div class="commonFlex">
            <p class="ncText">Create new contact</p>
        </div>
        <div class="contactCreateForm ">
        </div>
    </div>
    <div class="dnotesBottomIcon" type="button" data-bs-toggle="modal"
        data-bs-target="#staticBackdropforNote_{{ $contactId }}">
        <div class="tooltip-wrapper">
            <img src="{{ URL::asset('/images/notesIcon.svg') }}" alt="Notes icon">
            <span class="tooltiptext">Add Notes</span>
        </div>
    </div>

    {{-- Note Modal --}}
    @include('common.notes.create', [
        'contact' => $contact,
        'retrieveModuleData' => $retrieveModuleData,
        'type' => 'Contacts',
    ])
    

@endsection
<script>
    var contactId = @json($contactId);
    $(document).ready(function() {
        getCreateForm();
        
    });

    function getCreateForm() {
        $.ajax({
            url: `{{ url('/contact/create/form/') }}/${contactId}`,
            method: 'GET',
            success: function(response) {   
                console.log(response,"response") 
                if (response.redirect) {
                    window.location.href = response.redirect;
                }else{

                    $('.contactCreateForm').html(response);
                }        
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }
</script>
