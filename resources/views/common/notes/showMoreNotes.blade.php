@extends('layouts.master')

@section('title', 'zPortal | Dashboard')
@section('content')
    @vite(['resources/css/dashboard.css'])
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
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
    <div id="loader" style="display: none;">
        <img src="{{ URL::asset('/images/Spinner-5.gif') }}" alt="Loading...">
    </div>
    <div class="container-fluid">
        <div class="loader" id="loaderfor" style="display: none;"></div>
        <div class="loader-overlay" id="loaderOverlay" style="display: none;"></div>
        <div class="col-sm-12 dtasksection">
            <div class="d-flex justify-content-between">
                <p class="dFont800 dFont15">Notes</p>
            </div>
            <div class="row">
                <div class="d-flex flex-column">
                    @if ($notes->count() > 0)
                        @foreach ($notesInfo as $note)
                            <div class="card mb-2 shadow-sm border-0">
                                <div class="card-body p-3">
                                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                                        <div class="w-100">
                                            <h5 class="m-0">
                                                <span class="text-dark">{{ $note['note_content'] ?? 'General Note' }}</span>
                                            </h5>
                                            <small class="text-muted">
                                                Created: {{ \Carbon\Carbon::parse($note['created_time'])->format('M d, Y') ?? '' }},
                                                related to
                                                @if ($note['related_to_type'] == 'Contacts' && isset($note->contactData->zoho_contact_id))
                                                    <a href="{{ url('/contacts-view/' . $note->contactData->id ?? '') }}" class="text-primary">
                                                        {{ $note->contactData->first_name ?? '' }} {{ $note->contactData->last_name ?? '' }}
                                                    </a>
                                                @elseif ($note['related_to_type'] == 'Deals' && isset($note->dealData->zoho_deal_id))
                                                    <a href="{{ url('/pipeline-view/' . $note->dealData->id ?? '') }}" class="text-primary">
                                                        {{ $note->dealData->deal_name ?? 'General Deal' }}
                                                    </a>
                                                @else
                                                    <span class="text-secondary">General</span>
                                                @endif
                                            </small>
                                        </div>
                                        <div class="d-flex flex-md-shrink-0 mt-2 mt-md-0">
                                            @php
                                                $taskzId = $note['zoho_note_id'];
                                                $taskId = $note['id'];
                                                $subject = $note['note_content'];
                                            @endphp
                                            <button class="btn btn-secondary btn-sm text-nowrap" data-bs-toggle="modal" data-bs-target="#deleteModalId{{ $note['zoho_note_id'] }}">                                                    <i class="fas fa-trash-alt"></i> Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteModalId{{ $note['zoho_note_id'] }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered deleteModal">
                                    <div class="modal-content">
                                        <div class="modal-header border-0 deleteModalHeaderDiv">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body deletemodalBodyDiv">
                                            <p class="deleteModalBodyText">Please confirm you’d like to<br />delete this item.</p>
                                        </div>
                                        <div class="modal-footer deletemodalFooterDiv justify-content-evenly border-0">
                                            <div class="d-grid gap-2 col-5">
                                                <button type="button" onclick="deleteNote('{{ $note['zoho_note_id'] }}')" class="btn btn-secondary deleteModalBtn" data-bs-dismiss="modal">
                                                    <i class="fas fa-trash-alt trashIcon"></i> Yes, delete
                                                </button>
                                            </div>
                                            <div class="d-grid gap-2 col-5">
                                                <button type="button" data-bs-dismiss="modal" class="btn btn-primary goBackModalBtn">
                                                    <img src="{{ URL::asset('/images/reply.svg') }}" data-bs-dismiss="modal" alt="R">No, go back
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center">
                            <p>No recent notes found</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="dnotesBottomIcon" type="button" data-bs-toggle="modal" data-bs-target="#staticBackdropforNote">
        <div class="tooltip-wrapper">
            <img src="{{ URL::asset('/images/notesIcon.svg') }}" alt="Notes icon">
            <span class="tooltiptext">Add Notes</span>
        </div>
    </div>
    {{-- Modals --}}
    @include('common.notes.create')
@endsection
<script>
    window.deleteNote = function(id) {
        console.log("delete note called",id);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        try {
            if (id) {
                $.ajax({
                    url: "{{ route('delete.note', ['id' => ':id']) }}".replace(':id', id),
                    method: 'DELETE', // Change to DELETE method
                    contentType: 'application/json',
                    dataType: 'JSON',
                    data: {
                        'id': id,
                        '_token': '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        // Handle success response
                        window.location.reload();
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        console.error(xhr.responseText);
                    }
                })

            }
        } catch (err) {
            console.error("error", err);
        }

    }

</script>
@section('bladeScripts')
    @vite(['resources/js/dashboard.js'])
@endsection
