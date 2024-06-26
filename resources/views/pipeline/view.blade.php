@extends('layouts.master')

@section('title', 'Agent Commander | Pipeline Create')

@section('content')
@vite(['resources/css/pipeline.css'])
@vite(['resources/js/toast.js'])


@if (!isset($deal['deal_name']) && !isset($deal['deal_id']))
    <div class="container-fluid">
        <div class="commonFlex ppipeDiv">
            No Deal Found
        </div>
    </div>
    
@else

<div class="container-fluid">
    <div class="commonFlex ppipeDiv">
        <p class="pText">{{ $deal['deal_name'] }}</p>
        <div class="npbtnsDiv">
            {{-- <div class="input-group-text text-white justify-content-center npdeleteBtn" id="btnGroupAddon"
                data-bs-toggle="modal" data-bs-target="#">
                <img src="{{ URL::asset('/images/delete.svg') }}" alt="Delete">
                Delete
            </div> --}}
            <a onclick="updateDataDeal('{{ $deal['id'] }}')">
                <div class="input-group-text text-white justify-content-center ppipeBtn" id="savebutton"
                    data-bs-toggle="modal" data-bs-target="#"><i class="fas fa-edit">
                    </i>
                    Update
                </div>
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-sm-12 dtasksection">
            <div class="d-flex justify-content-between">
                <p class="dFont800 dFont15">Tasks</p>
                <div class="input-group-text text-white justify-content-center taskbtn dFont400 dFont13"
                    id="btnGroupAddon" data-bs-toggle="modal" data-bs-target="#newTaskModalId{{ $deal['id'] }}"><i
                        class="fas fa-plus plusicon">
                    </i>
                    New Task
                </div>
               
            </div>
            <div class="row">
                <nav class="dtabs">
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link dtabsbtn active" id="nav-home-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-home" onclick="fetchPipelineTasks('In Progress','{{$deal['id']}}')"
                            data-tab='In Progress' type="button" role="tab" aria-controls="nav-home"
                            aria-selected="true">In
                            Progress</button>
                        <button class="nav-link dtabsbtn" data-tab='Upcoming'
                            onclick="fetchPipelineTasks('Upcoming','{{$deal['id']}}')" id="nav-profile-tab"
                            data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab"
                            aria-controls="nav-profile" aria-selected="false">Upcoming</button>
                        <button class="nav-link dtabsbtn" data-tab='Overdue'
                            onclick="fetchPipelineTasks('Overdue','{{$deal['id']}}')" id="nav-contact-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact"
                            aria-selected="false">Overdue</button>
                        <button class="nav-link dtabsbtn" data-tab='Completed'
                            onclick="fetchPipelineTasks('Completed','{{$deal['id']}}')" id="nav-contact-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact"
                            aria-selected="false">Completed</button>
                    </div>
                </nav>
               <div class="pipeline-task-container">
                </div>
            </div>
        </div>
        @include('common.notes.view', [
        'notesInfo' => $notesInfo,
        'retrieveModuleData' => $retrieveModuleData,
        'module' => 'Deals',
        ])
    </div>
    {{-- information form --}}
    <div class="updatePipelineform">
       
    </div>
    
</div>

<div class="dnotesBottomIcon" type="button" data-bs-toggle="modal"
    data-bs-target="#staticBackdropforNote_{{ $deal['id'] }}">
    <div class="tooltip-wrapper">
        <img src="{{ URL::asset('/images/notesIcon.svg') }}" alt="Notes icon">
        <span class="tooltiptext">Add Notes</span>
    </div>
</div>
{{-- task modal --}}
@include('common.tasks.create', [
    'deal' => $deal,
    'retrieveModuleData' => $retrieveModuleData,
    'type' => 'Deals',
])
{{-- Note Modal --}}
@include('common.notes.create', [
    'deal' => $deal,
    'retrieveModuleData' => $retrieveModuleData,
    'type' => 'Deals',
])


@vite(['resources/js/pipeline.js'])

<script src="https://code.jquery.com/jquery-3.6.0.min.js">
</script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    var dealId = @json($dealId);
    var deal = @json($deal);
    $(document).ready(function() {
        fetchPipelineTasks('In Progress',dealId)
        getCreateForm();
        
        
    });
    $(document).ready(function(){
        var defaultTab = "{{ $tab }}";
        localStorage.setItem('status', defaultTab);
        // Retrieve the status from local storage
        var status = localStorage.getItem('status');

        // Object to store status information
        var statusInfo = {
            'In Progress': false,
            'Overdue': false,
            'Completed': false,
            'Upcoming': false,
        };

        // Update the status information based on the current status
        statusInfo[status] = true;

        // Loop through statusInfo to set other statuses to false
        for (var key in statusInfo) {
            if (key !== status) {
                statusInfo[key] = false;
            }
        }
        // Example of accessing status information
        console.log(statusInfo);

        // Remove active class from all tabs
        var tabs = document.querySelectorAll('.nav-link');
        console.log(tabs, 'tabssss')
        tabs.forEach(function(tab) {
            tab.classList.remove('active');
        });

        // Set active class to the tab corresponding to the status
        console.log(status, 'status');
        var activeTab = document.querySelector('.nav-link[data-tab="' + status + '"]');
        if (activeTab) {
            activeTab.classList.add('active');
        }
    })
    function fetchPipelineTasks(tab, dealId) {
        // Make AJAX call
        $.ajax({
            url: '/task/for/pipeline/'+dealId,
            method: 'GET',
            data: {
                tab: tab,
            },
            dataType: 'html',
            success: function(data) {
                $('.pipeline-task-container').html(data);
            },
            error: function(xhr, status, error) {
                // Handle errors
                loading = false;
                console.error('Error:', error);
            }
        });

    }
    function getCreateForm() {
        $.ajax({
            url: `{{ url('/pipeline/detail/form/') }}/${dealId}`,
            method: 'GET',
            success: function(data) {
                 if (data.redirect) {
                    window.location.href = data.redirect;
                }else{
                    $('.updatePipelineform').html(data);
                }                 
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }
    
</script>
@endif
@endsection
