<div class="table-responsive dresponsivetable">
    <table class="table dtableresp">
        <thead>
            <tr class="dFont700 dFont10">
                <th scope="col"><input type="checkbox" onclick="toggleAllCheckboxes()" id="checkbox_all"
                        id="checkbox_task" /></th>
                <th scope="col">Subject</th>
                <th scope="col">Transaction Related</th>
                <th scope="col">Task Date</th>
                <th scope="col">Options</th>
            </tr>
        </thead>
        <tbody>

            @if (count($tasks) > 0)
                @foreach ($tasks as $task)
                    <tr class="dresponsivetableTr">
                        <td><input onchange="triggerCheckbox('{{ $task['zoho_task_id'] }}')" type="checkbox"
                                class="task_checkbox" id="{{ $task['zoho_task_id'] }}" /></td>
                        <td>
                            <p class="dFont900 dFont14 d-flex justify-content-between dMt16 dSubjectText"
                                id="editableText{{ $task['id'] }}">
                                {{ $task['subject'] ?? 'N/A' }}
                                <i class="fas fa-pencil-alt pencilIcon"
                                    onclick="makeEditable('{{ $task['id'] }}','subject','{{ $task['zoho_task_id'] }}')"></i>
                            </p>
                        </td>
                        <td>
                            <div class="btn-group">
                                <select class="form-select" id="related_to_rem{{ $task['id'] }}"
                                    onclick="getModule('{{ $task['id'] }}')" name="related_to_rem{{ $task['id'] }}">
                                    @if ($task['related_to'] == 'Contacts')
                                        <option value="" {{ empty($task['contactData']) ? 'selected' : '' }}>
                                            {{ $task['contactData']['first_name'] ?? '' }}
                                            {{ $task['contactData']['last_name'] ?? 'please select One' }}
                                        </option>
                                    @elseif ($task['related_to'] == 'Deals')
                                        <option value="" {{ empty($task['dealData']) ? 'selected' : '' }}>
                                            {{ $task['dealData']['deal_name'] ?? 'Please select One' }}
                                        </option>
                                    @else
                                        <option value="" selected>Please select One</option>
                                    @endif
                                </select>
                                <select class="form-select dmodaltaskSelect" id="taskSelect{{ $task['id'] }}"
                                    onchange="testFun('{{ $task['id'] }}','deals','{{ $task['zoho_task_id'] }}')"
                                    name="related_to_parent{{ $task['id'] }}" aria-label="Select Transaction" style="display: none;">
                                    <option value="">Please Select one</option>
                                </select>
                            </div>
                        </td>
                        <td>
                            <input type="datetime-local" id="date_local{{ $task['id'] }}"
                                onchange="makeEditable('{{ $task['id'] }}','date','{{ $task['zoho_task_id'] }}')"
                                id="date_val{{ $task['zoho_task_id'] }}"
                                value="{{ \Carbon\Carbon::parse($task['due_date'])->format('Y-m-d\TH:i') }}" />
                        </td>
                        <td>
                            <div class="d-flex ">
                                <div class="input-group-text dFont800 dFont11 text-white justify-content-center align-items-baseline savebtn"
                                    id="btnGroupAddon" data-bs-toggle="modal"
                                    onclick="updateTask('{{ $task['zoho_task_id'] }}','{{ $task['id'] }}')">
                                    <i class="fas fa-hdd plusicon"></i>
                                    Save
                                </div>
                                <div class="input-group-text dFont800 dFont11 text-white justify-content-center align-items-baseline deletebtn"
                                    id="btnGroupAddon" data-bs-toggle="modal"
                                    data-bs-target="#deleteModalId{{ $task['zoho_task_id'] }}">
                                    <i class="fas fa-trash-alt plusicon"></i>
                                    Delete
                                </div>
                            </div>
                            {{-- delete Modal --}}
                            {{-- <div class="modal fade" id="deleteModalId{{$task['zoho_task_id']}}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered deleteModal">
                                        <div class="modal-content">
                                            <div class="modal-header border-0">
                                                {{-- <h5 class="modal-title">Modal title</h5> --}}
                            {{-- <button type="button" class="btn-close"
                                                    data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="deleteModalBodyText">Please confirm you’d
                                                    like to<br />
                                                    delete this item.</p>
                                            </div>
                                            <div class="modal-footer justify-content-evenly border-0">
                                                <div class="d-grid gap-2 col-5">
                                                    <button onclick="deleteTask('{{$task['zoho_task_id']}}')" type="button"
                                                        class="btn btn-secondary deleteModalBtn"
                                                        data-bs-dismiss="">
                                                        <i class="fas fa-trash-alt trashIcon"></i> Yes,
                                                        delete
                                                    </button>
                                                </div>
                                                <div class="d-grid gap-2 col-5">
                                                    <button type="button"
                                                        class="btn btn-primary goBackModalBtn">
                                                        <i class="fas fa-arrow-left goBackIcon"></i>
                                                        No, go back
                                                    </button>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div> --}}
                            {{-- </div>  --}}
                            {{-- delete Modal --}}
                            <div class="modal fade" id="deleteModalId{{ $task['zoho_task_id'] }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered deleteModal">
                                    <div class="modal-content">
                                        <div class="modal-header border-0 deleteModalHeaderDiv">
                                            {{-- <h5 class="modal-title">Modal title</h5> --}}
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body deletemodalBodyDiv">
                                            <p class="deleteModalBodyText">Please confirm you’d
                                                like
                                                to<br />
                                                delete this item.</p>
                                        </div>
                                        <div class="modal-footer deletemodalFooterDiv justify-content-evenly border-0">
                                            <div class="d-grid gap-2 col-5">
                                                <button type="button"
                                                    onclick="deleteTask('{{ $task['zoho_task_id'] }}')"
                                                    class="btn btn-secondary deleteModalBtn" data-bs-dismiss="modal">
                                                    <i class="fas fa-trash-alt trashIcon"></i> Yes,
                                                    delete
                                                </button>
                                            </div>
                                            <div class="d-grid gap-2 col-5">
                                                <button type="button" data-bs-dismiss="modal"
                                                    class="btn btn-primary goBackModalBtn">
                                                    <img src="{{ URL::asset('/images/reply.svg') }}"
                                                        data-bs-dismiss="modal" alt="R">No,
                                                    go
                                                    back
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="saveModalId{{ $task['zoho_task_id'] }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered deleteModal">
                                    <div class="modal-content">
                                        <div class="modal-header saveModalHeaderDiv border-0">
                                            {{-- <h5 class="modal-title">Modal title</h5> --}}
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body saveModalBodyDiv">
                                            <p class="saveModalBodyText" id="updated_message">Changes
                                                have been saved</p>
                                        </div>
                                        <div class="modal-footer saveModalFooterDiv justify-content-evenly border-0">
                                            <div class="d-grid col-12">
                                                <button type="button" class="btn btn-secondary saveModalBtn"
                                                    data-bs-dismiss="modal">
                                                    <i class="fas fa-check trashIcon"></i>
                                                    Understood
                                                </button>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="savemakeModalId{{ $task['zoho_task_id'] }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered deleteModal">
                                    <div class="modal-content">
                                        <div class="modal-header saveModalHeaderDiv border-0">
                                            {{-- <h5 class="modal-title">Modal title</h5> --}}
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body saveModalBodyDiv">
                                            <p class="saveModalBodyText" id="updated_message_make">
                                                Changes have been saved</p>
                                        </div>
                                        <div class="modal-footer saveModalFooterDiv justify-content-evenly border-0">
                                            <div class="d-grid col-12">
                                                <button type="button" class="btn btn-secondary saveModalBtn"
                                                    data-bs-dismiss="modal">
                                                    <i class="fas fa-check trashIcon"></i>
                                                    Understood
                                                </button>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </td>

                    </tr>
                @endforeach
            @else
                <tr>
                    <td class="text-center" colspan="12">No records found</td>
                </tr>
            @endif

        </tbody>

    </table>
    <div class="dprogressCards">
        @if (count($tasks) > 0)
            @foreach ($tasks as $task)
                <div class="dcardscheckbox">
                    <input type="checkbox" />
                </div>
                <div class="dcardssubjectdiv">
                    <p class="dcardSubject" id="editableTextCard{{ $task['id'] }}"
                        onclick="makeEditable('{{ $task['id'] }}')">
                        {{ $task['subject'] ?? 'N/A' }}
                        {{-- <i class="fas fa-pencil-alt pencilIcon "></i> --}}
                    </p>
                    <div class="btn-group dcardsselectdiv">
                        <p class="dcardsTransactionText">Transaction Related</p>
                        @if ($task['related_to'] == 'Contacts')
                            <input
                                value="{{ $task['contactData']['first_name'] ?? '' }} {{ $task['contactData']['last_name'] ?? '' }}">
                        @elseif ($task['related_to'] == 'Deals')
                            <input value="{{ $task['dealData']['deal_name'] ?? '' }}">
                        @else
                            <input value="Global">
                        @endif

                        </select>
                    </div>
                    <div class="dcardsdateinput">
                        <p class="dcardsTaskText">Task Date</p>
                        <input type="datetime-local"
                            value="{{ \Carbon\Carbon::parse($task['due_date'])->format('Y-m-d\TH:i') }}" />
                    </div>
                </div>
                <div class="dcardsbtnsDiv">
                    <div id="update_changes" class="input-group-text dcardssavebtn" id="btnGroupAddon"
                        data-bs-toggle="modal"
                        onclick="updateTask('{{ $task['zoho_task_id'] }}','{{ $task['id'] }}')"
                        data-bs-target="#saveModalId">
                        <i class="fas fa-hdd plusicon"></i>
                        Save
                    </div>
                    <div class="input-group-text dcardsdeletebtn" onclick="deleteTask('{{ $task['zoho_task_id'] }}')"
                        id="btnGroupAddon" data-bs-toggle="modal" data-bs-target="#deleteModalId">
                        <i class="fas fa-trash-alt plusicon"></i>

                        Delete
                    </div>
                </div>
            @endforeach
        @else
            <div>
                <div class="text-center">No records found</div>
            </div>
        @endif
    </div>
    @if (count($tasks) > 0)
        <div class="dpagination">
            <div onclick="deleteTask('{{ $task['zoho_task_id'] }}',true)"
                class="input-group-text text-white justify-content-center removebtn dFont400 dFont13" id="removeBtn">
                <i class="fas fa-trash-alt plusicon"></i>
                Remove Selected
            </div>
            <nav aria-label="..." class="dpaginationNav">
                <ul class="pagination ppipelinepage d-flex justify-content-end">
                    <!-- Previous Page Link -->
                    @if ($tasks->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link">Previous</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link"
                                href="{{ $tasks->previousPageUrl() }}&tab={{ request()->query('tab') }}"
                                rel="prev">Previous</a>
                        </li>
                    @endif

                    <!-- Pagination Elements -->
                    @php
                        $currentPage = $tasks->currentPage();
                        $lastPage = $tasks->lastPage();
                        $startPage = max($currentPage - 1, 1);
                        $endPage = min($currentPage + 1, $lastPage);
                    @endphp

                    {{-- @if ($startPage > 1)
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    @endif --}}

                    @for ($page = $startPage; $page <= $endPage; $page++)
                        <li class="page-item {{ $tasks->currentPage() == $page ? 'active' : '' }}">
                            <a class="page-link"
                                href="{{ $tasks->url($page) }}&tab={{ request()->query('tab') }}">{{ $page }}</a>
                        </li>
                    @endfor

                    {{-- @if ($endPage < $lastPage)
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    @endif --}}

                    <!-- Next Page Link -->
                    @if ($tasks->hasMorePages())
                        <li class="page-item">
                            <a class="page-link"
                                href="{{ $tasks->nextPageUrl() }}&tab={{ request()->query('tab') }}"
                                rel="next">Next</a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span class="page-link">Next</span>
                        </li>
                    @endif
                </ul>
            </nav>


        </div>
    @endif

    {{-- <div class="dpagination">
        <div onclick="removeAllSelected()"
            class="input-group-text text-white justify-content-center removebtn dFont400 dFont13"> <i
                class="fas fa-trash-alt plusicon"></i>
            Remove Selected
        </div>
        <nav aria-label="..." class="dpaginationNav">
            <ul class="pagination d-flex justify-content-end">
                <li class="page-item disabled">
                    <a class="page-link">Previous</a>
                </li>
                <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item active" aria-current="page">
                    <a class="page-link" href="#">2</a>
                </li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                    <a class="page-link" href="#">Next</a>
                </li>
            </ul>
        </nav>
    </div> --}}
</div>
<script>


    function getModule(id) {
        console.log('yes triggerwed')
        // Get the select element
        var selectElement = document.getElementById("related_to_rem" + id);

        // Check if it's the first click
            // Remove the existing options
            selectElement.innerHTML = "";

            // Add a default option
            var option1 = document.createElement("option");
            option1.value = "";
            option1.text = "Please select Module";
            selectElement.appendChild(option1);
            // Populate select with new options
            @if (!empty($retrieveModuleData))
                @foreach ($retrieveModuleData as $item)
                    @if (!empty($item['api_name']) && in_array($item['api_name'], ['Deals', 'Contacts']))
                        var option = document.createElement("option");
                        option.id = "{{ $item['zoho_module_id'] }}";
                        option.value = "{{ $item['api_name'] }}";
                        option.text = "{{ $item['api_name'] }}";
                        selectElement.appendChild(option);
                    @endif
                @endforeach
            @endif


            // Change the flag to indicate that it's no longer the first click
            isFirstClick = false;
            selectElement.addEventListener('change', function() {
                // Remove the onclick attribute
                selectElement.removeAttribute("onclick");

                // Set the onchange attribute to call moduleSelected function passing this as a parameter
                selectElement.setAttribute("onchange", `moduleSelected(this,${id})`);
            });
        
    }

    function testFun(id, textfield, zohoID) {
        if (textfield === "deals") {
            var related_to_rem = document.getElementsByName("related_to_rem"+id)[0].value;
            var WhatSelectoneid = document.getElementsByName("related_to_parent"+id)[0].value;
            updateText(related_to_rem, textfield, zohoID, WhatSelectoneid);
        }
    }
</script>
