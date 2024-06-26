@if (count($notesInfo) === 0)
    <p class="text-center">No notes found.</p>
@else
    <ul class="list-group dnotesUl">
        @foreach ($notesInfo as $note)
            <li class="list-group-item border-0 mb-4 d-flex justify-content-between align-items-start dashboard-notes-list">
                <div class="text-wrap" onclick="handleDeleteCheckbox('{{ $note['id'] }}')"
                    class="form-check-input checkbox{{ $note['id'] }}"
                    id="editButton{{ $note['id'] }}" class="btn btn-primary dnotesBottomIcon"
                    type="button" data-bs-toggle="modal"
                    data-bs-target="#staticBackdropnoteview{{ $note['id'] }}">
                    <span class="dFont800 dFont13">Related to:</span>
                    @if(isset($deal))
                        {{ $note->dealData->deal_name ?? '' }}
                    @elseif(isset($contact))
                        {{ $note->contactData->first_name ?? '' }}
                        {{ $note->contactData->last_name ?? '' }}
                    @else
                        Global
                    @endif
                    <br />
                    <p class="dFont400 fs-4 mb-0">
                        {{ $note['note_content'] }}
                    </p>
                </div>
            </li>
        @endforeach
    </ul>
@endif


<script>
    // Function to open the confirmation modal
    function openConfirmationModal(id) {
        var modal = document.getElementById(id);
        modal.style.display = 'block';
    }
    // Function to close the confirmation modal
    function closeConfirmationModal(id) {
        var modal = document.getElementById(id);
        modal.style.display = 'none';
    }

    // Function to handle deletion
    function deleteNoteItem(id) {
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

     function handleDeleteCheckbox(id) {
        // Get all checkboxes
        const checkboxes = document.querySelectorAll('.checkbox' + id);
        // Get delete button
        const deleteButton = document.getElementById('deleteButton' + id);
        const editButton = document.getElementById('editButton' + id);
        console.log(checkboxes, 'checkboxes')
        // Add event listener to checkboxes
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                // Check if any checkbox is checked
                const anyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
                // Toggle delete button visibility
                editButton.style.display = anyChecked ? 'block' : 'none';
                // if (deleteButton.style.display === 'block') {
                //     selectedNoteIds.push(id)
                // }
            });
        });

    }
    
</script>