@extends('layouts.master')

@section('title', 'Agent Commander | Pipeline Create')

@section('content')
@vite(['resources/css/pipeline.css'])
@vite(['resources/js/toast.js'])
<div class="pipelineCreateForm">
        
</div>
<div class="dnotesBottomIcon" type="button" data-bs-toggle="modal"
    data-bs-target="#staticBackdropforNote_{{ $deal['id'] }}">
    <div class="tooltip-wrapper">
        <img src="{{ URL::asset('/images/notesIcon.svg') }}" alt="Notes icon">
        <span class="tooltiptext">Add Notes</span>
    </div>
</div>
@include('common.notes.create', ['deal' => $deal, 'type' => 'Deals'])

@vite(['resources/js/pipeline.js'])
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    var dealId = @json($dealId);
    $(document).ready(function() {
        getCreateForm();
        
    });

    function getCreateForm() {
        $.ajax({
            url: `{{ url('/pipeline/create/form/') }}/${dealId}`,
            method: 'GET',
            success: function(response) {   
                console.log(response,"response") 
                if (response.redirect) {
                    window.location.href = response.redirect;
                }else{

                    $('.pipelineCreateForm').html(response);
                }        
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }
</script>

@endsection
