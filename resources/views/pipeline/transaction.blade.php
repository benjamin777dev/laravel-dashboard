<div class="table-responsive">
    <div class="container-fluid">
        <div class="col-md-12">
          <table id="example" class="table bg-grey table-bordered nowrap" cellspacing="0" width="100%">
              <thead class="thead_con_design">
                  <tr>
                    <th>
                        <div></div>
                    </th>
                    <th class="sorting sorting_asc">
                        <div onclick="toggleSort('deal_name',this)" class="commonFlex">
                        <p class="mb-0">Transaction</p>
                            <div class="d-flex flex-column">
                                <i class="bx bx-caret-up up-arrow"></i>
                                <i class="bx bx-caret-down down-arrow"></i>
                            </div>
                        </div>
                    </th>
                    <th>
                        <div onclick="toggleSort('client_name_primary',this)" class="commonFlex">
                        <p class="mb-0">Client Name</p>
                            <div class="d-flex flex-column">
                                <i class="bx bx-caret-up up-arrow"></i>
                                <i class="bx bx-caret-down down-arrow"></i>
                            </div>
                        </div>
                    </th>
    
                    <th>
                        <div onclick="toggleSort('stage',this)" class="commonFlex">
                        <p class="mb-0">Status&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                        <div class="d-flex flex-column">
                            <i class="bx bx-caret-up up-arrow"></i>
                            <i class="bx bx-caret-down down-arrow"></i>
                        </div>
                        </div>
                    </th>
    
                    <th>
                        <div onclick="toggleSort('representing',this)" class="commonFlex">
                        <p class="mb-0">Representing</p>
                        <div class="d-flex flex-column">
                            <i class="bx bx-caret-up up-arrow"></i>
                            <i class="bx bx-caret-down down-arrow"></i>
                        </div>
                        </div>
                    </th>
    
    
                    <th>
                        <div onclick="toggleSort('sale_price',this)" class="commonFlex">
                        <p class="mb-0">Price</p>
                        <div class="d-flex flex-column">
                            <i class="bx bx-caret-up up-arrow"></i>
                            <i class="bx bx-caret-down down-arrow"></i>
                        </div>
                        </div>
                    </th>
    
                    <th>
                        <div onclick="toggleSort('closing_date',this)" class="commonFlex">
                        <p class="mb-0">Close Date</p>
                        <div class="d-flex flex-column">
                            <i class="bx bx-caret-up up-arrow"></i>
                            <i class="bx bx-caret-down down-arrow"></i>
                        </div>
                            </div>
                    </th>
                    <th>
                        <div onclick="toggleSort('closing_date',this)" class="commonFlex">
                        <p class="mb-0">Commission</p>
                        <div class="d-flex flex-column">
                            <i class="bx bx-caret-up up-arrow"></i>
                            <i class="bx bx-caret-down down-arrow"></i>
                        </div>
                        </div>
                    </th>
                    <th>
                        <div onclick="toggleSort('closing_date',this)" class="commonFlex">
                        <p class="mb-0">Potential GCI</p>
                        <div class="d-flex flex-column">
                            <i class="bx bx-caret-up up-arrow"></i>
                            <i class="bx bx-caret-down down-arrow"></i>
                        </div>
                            </div>
                    </th>
                    <th>
                        <div onclick="toggleSort('closing_date',this)" class="commonFlex">
                        <p class="mb-0">Probability</p>
                        <div class="d-flex flex-column">
                            <i class="bx bx-caret-up up-arrow"></i>
                            <i class="bx bx-caret-down down-arrow"></i>
                        </div>
                            </div>
    
                    </th>
                    <th>
                        <div onclick="toggleSort('closing_date',this)" class="commonFlex">
                        <p class="mb-0">Probable GCI</p>
                        <div class="d-flex flex-column">
                            <i class="bx bx-caret-up up-arrow"></i>
                            <i class="bx bx-caret-down down-arrow"></i>
                        </div>
                        </div>
                    </th>
                  </tr>
              </thead>
                <tbody class="table_pipeline">
                    @include('pipeline.pipelineload', ['module' => $deals])
                    <tr class="spinner" style="display: none;">
                        <td colspan="7" class="text-center">
                            <!-- Add your spinner HTML here -->
                            <!-- For example, you can use Font Awesome spinner -->
                            <i class="fas fa-spinner fa-spin"></i> Loading...
                        </td>
                    </tr>
                </tbody>
          </table>
        </div>
      </div>
</div>
<div class="ptableCardDiv">
    @if (count($deals) > 0)
        @foreach ($deals as $deal)
            <div class="pTableCard" onclick="viewPipeline('{{ $deal }}')">
                <p class="pTableTransText">Transaction</p>
                <p class="pTableNameText"
                    onclick="updateDeal('{{ $deal['zoho_deal_id'] }}','deal_name','{{ $deal['id'] }}','card')"
                    id="card_deal_name{{ $deal['zoho_deal_id'] }}">{{ $deal['deal_name'] ?? 'N/A' }}</p>
                <p class="pTableNameText"
                    onclick="updateDeal('{{ $deal['zoho_deal_id'] }}','address','{{ $deal['id'] }}','card')"
                    id="card_address{{ $deal['zoho_deal_id'] }}">{{ $deal['address'] ?? 'N/A' }}</p>
                <div class="d-flex justify-content-between">
                    <div class="pTableSelect pipelinestatusdiv">
                        <select class="form-select pstatusText"
                            style="background-color: {{ $deal['stage'] === 'Potential' ? '#dfdfdf' : ($deal['stage'] === 'Active' ? '#afafaf' : ($deal['stage'] === 'Pre-Active' ? '#cfcfcf' : ($deal['stage'] === 'Under Contract' ? '#8f8f8f;color=#fff;' : ($deal['stage'] === 'Dead-Lost To Competition' ? '#efefef' : '#6f6f6f;color=#fff;')))) }}"
                            id="stage{{ $deal['zoho_deal_id'] }}" required
                            onchange="updateDealData('stage','{{ $deal['id'] }}','{{ $deal['zoho_deal_id'] }}',this.value)">
                            @foreach ($allstages as $stage)
                                <option value="{{ $stage }}" {{ $deal['stage'] == $stage ? 'selected' : '' }}>
                                    {{ $stage }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div onclick="updateDeal('{{ $deal['zoho_deal_id'] }}','closing_date','{{ $deal['id'] }}','card','{{ \Carbon\Carbon::parse($deal['closing_date'])->format('Y-m-d') ?? 'N/A' }}')"
                        id="card_closing_date{{ $deal['zoho_deal_id'] }}">
                        {{ \Carbon\Carbon::parse($deal['closing_date'])->format('m/d/Y') ?? 'N/A' }}
                    </div>
                </div>
                <div class="" style="width: 75px;">
                    <select class="form-select npinputinfo" id="card_representing{{ $deal['zoho_deal_id'] }}"
                        required
                        onchange="updateDealData('representing','{{ $deal['id'] }}','{{ $deal['zoho_deal_id'] }}',this.value)">
                        <option value="Buyer" {{ $deal['representing'] == 'Buyer' ? 'selected' : '' }}>Buyer</option>
                        <option value="Seller" {{ $deal['representing'] == 'Seller' ? 'selected' : '' }}>Seller
                        </option>
                    </select>
                </div>
                <div class="d-flex justify-content-between psellDiv">
                    <div onclick="updateDeal('{{ $deal['zoho_deal_id'] }}','client_name_primary','{{ $deal['id'] }}','card')"
                        id="card_client_name_primary{{ $deal['zoho_deal_id'] }}"><img
                            src="{{ URL::asset('/images/account_box.svg') }}" alt="A">
                        {{ $deal->client_name_primary ?? 'N/A' }}
                        {{-- {{ $deal->contactName->last_name ?? '' }} --}}
                        {{-- {{ $deal['Primary_Contact'] ?? 'N/A' }} --}}
                    </div>
                    <div onclick="updateDeal('{{ $deal['zoho_deal_id'] }}','sale_price','{{ $deal['id'] }}','card')"
                        id="card_sale_price{{ $deal['zoho_deal_id'] }}">
                        <img src="{{ URL::asset('/images/sell.svg') }}" alt="A">
                        $ {{ $deal['sale_price'] ?? 'N/A' }}
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <div onclick="updateDeal('{{ $deal['zoho_deal_id'] }}','commission','{{ $deal['id'] }}','card')"
                        id="card_commission{{ $deal['zoho_deal_id'] }}">
                        {{ number_format($deal['commission'] ?? '0', 2) }}%
                    </div>
                    <div>
                        ${{ number_format($deal['potential_gci'] ?? '0', 0, '.', ',') }}</div>
                </div>

                <div class="d-flex justify-content-between">
                    <div onclick="updateDeal('{{ $deal['zoho_deal_id'] }}','pipeline_probability','{{ $deal['id'] }}','card')"
                        id="card_pipeline_probability{{ $deal['zoho_deal_id'] }}">
                        {{ number_format($deal['pipeline_probability'] ?? '0', 2) }}%
                    </div>
                    <div>
                        ${{ number_format(($deal->sale_price ?? 0) * (($deal->commission ?? 0) / 100) * (($deal->pipeline_probability ?? 0) / 100), 0, '.', ',') }}
                    </div>
                </div>
                <div class="pCardFooter">
                    <div class="pfootericondiv">
                        <img src="{{ URL::asset('/images/splitscreen.svg') }}" alt="Open icon"
                            class="pdiversityicon" data-bs-toggle="modal"
                            data-bs-target="#newTaskModalId{{ $deal['id'] }}">
                        <img src="{{ URL::asset('/images/sticky_note.svg') }}"
                            onclick="fetchNotesForDeal('{{ $deal['id'] }}','{{ $deal['zoho_deal_id'] }}')"
                            alt="" class="pdiversityicon">
                    </div>
                    <div>
                        <img src="{{ URL::asset('/images/noteBtn.svg') }}" alt="Note icon" class="pdiversityicon"
                            data-bs-toggle="modal" data-bs-target="#staticBackdropforNote_{{ $deal['id'] }}">
                    </div>
                </div>
            </div>
        @endforeach
    
    @endif
</div>
<div class="datapagination d-none">
    @include('common.pagination', ['module' => $deals])
</div>

</div>
<script>
    $(document).ready(function() {
        let isLoading = false;
        let currentPage = 1;
        const baseUrl = '{{ url('/pipeline') }}';
        
        $(window).scroll(function() {
            if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
                if (!isLoading) {
                    loadMorePosts();
                }
            }
        });

        function loadMorePosts() {
            isLoading = true;
            $('.spinner').show();
            currentPage++;
            const nextPageUrl = `${baseUrl}?page=${currentPage}`;
            
            $.ajax({
                url: nextPageUrl,
                type: 'get',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    $('.spinner').hide();
                    const newRows = data;

                    if (newRows.trim().indexOf("No records found") !== -1) {
                        $(window).off('scroll');
                    } else {
                        $('.table_pipeline').append(newRows);
                    }
                 

                    // If "No records found" is present in new rows, stop further pagination
                    
                    isLoading = false;
                },
                error: function(xhr, status, error) {
                    console.error("Error loading more posts:", error);
                    $('.spinner').hide();
                    isLoading = false;
                }
            });
        }
    });
    window.fetchNotesForDeal=function(id, dealId) {
        event.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{ route('notes.fetch.deal', ['dealId' => ':dealId']) }}".replace(':dealId', id),
            method: "GET",
            success: function(response) {
                console.log(response);
                // $('#notesContainer').append('<p>New Note Content</p>');
                let noteContainer = $("#notesContainer"+dealId);
                const card = noteContainer.html(response);
                console.log(card, 'card')
                $("#notefetchrelatedDeal" + dealId).modal('show');
            },
            error: function(xhr, status, error) {
                // Handle error
                showToastError(error);
                console.error("Ajax Error:", error);
            }
        });

    }

    window.moduleSelected = function(selectedModule, deal) {
        console.log("dealId", deal);
        deal = JSON.parse(deal)
        var selectedOption = selectedModule.options[selectedModule.selectedIndex];
        var selectedText = selectedOption.text;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/task/get-' + selectedText + '?dealId=' + deal.zoho_deal_id, // Fixed the concatenation
            method: "GET",
            dataType: "json",
            success: function(response) {
                var tasks = response;
                var taskSelect = $('#taskSelect_' + deal.id);
                taskSelect.empty();
                $.each(tasks, function(index, task) {
                    if (selectedText === "Tasks") {
                        taskSelect.append($('<option>', {
                            value: task?.zoho_task_id,
                            text: task?.subject
                        }));
                    }
                    if (selectedText === "Deals") {
                        taskSelect.append($('<option>', {
                            value: task?.zoho_deal_id,
                            text: task?.deal_name
                        }));
                    }
                    if (selectedText === "Contacts") {
                        taskSelect.append($('<option>', {
                            value: task?.contactData?.zoho_contact_id,
                            text: task?.contactData?.first_name + ' ' + task
                                ?.contactData?.last_name
                        }));
                    }
                });
                taskSelect.show();
            },
            error: function(xhr, status, error) {
                console.error("Ajax Error:", error);
            }
        });
    }

    window.viewPipeline = function(deal) {
        deal = JSON.parse(deal);
        window.location.href = `/pipeline-view/${deal.id}`;
    }
    window.resetFormAndHideSelect = function(dealId) {
        $('#noteForm_' + dealId).get(0).reset(); // Changed to jQuery method
        $('#taskSelect_' + _dealId).hide();
        clearValidationMessages(dealId);
    }

    window.clearValidationMessages = function(dealId) {
        $("#note_text_error_" + dealId).text("");
        $("#related_to_error_" + dealId).text("");
    }

    window.validateForm = function(dealId) {
        let noteText = $("#note_text_" + dealId).val().trim();
        let relatedTo = $("#related_to_notes_" + dealId).val();
        let isValid = true;

        // Reset errors
        clearValidationMessages(dealId);

        // Validate note text length
        if (noteText.length > 100) {
            $("#note_text_error_" + dealId).text("Note text must be 100 characters or less");
            isValid = false;
        }
        // Validate note text
        if (noteText === "") {
            $("#note_text_error_" + dealId).text("Note text is required");
            isValid = false;
        }

        // Validate related to
        if (relatedTo === "") {
            $("#related_to_error_" + dealId).text("Related to is required");
            $('#taskSelect_' + dealId).hide();
            isValid = false;
        }
        if (isValid) {
            let changeButton = $('#validate-button_' + dealId);
            changeButton.prop("type", "submit"); // Changed to jQuery method
        }
        return isValid;
    }

    window.addTask = function(deal) {
        var subject = document.getElementsByName("subject")[0].value;
        if (subject.trim() === "") {
            document.getElementById("subject_error").innerHTML = "Please enter details";
            return;
        }
        
        var dueDate = document.getElementsByName("due_date")[0].value;

        var formData = {
            "data": [{
                "Subject": subject,
                // "Who_Id": {
                //     "id": whoId
                // },
                "Status": "Not Started",
                "Due_Date": dueDate,
                // "Created_Time":new Date()
                "Priority": "High",
                "What_Id": {
                    "id": deal
                },
                "$se_module": "Deals"
            }],
            "_token": '{{ csrf_token() }}'
        };
        console.log("formData", formData);
        $.ajax({
            url: '{{ route('create.task') }}',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            contentType: 'application/json',
            dataType: 'json',
            data: JSON.stringify(formData),
            success: function(response) {
                if (response?.data && response.data[0]?.message) {
                    // Convert message to uppercase and then display
                    const upperCaseMessage = response.data[0].message.toUpperCase();
                    showToast(upperCaseMessage);
                    // window.location.reload();
                }
            },
            error: function(xhr, status, error) {
                // Handle error response
                console.error(xhr.responseText);
            }
        })
    }


    // Initial sorting direction
    
    let sortDirectionPipeline = 'desc';
    // Function to toggle sort
    window.toggleSort = function(sortField,clickedColumn) {
        // Toggle the sort direction
        sortDirectionPipeline = (sortDirectionPipeline === 'desc') ? 'asc' : 'desc';
        // Call fetchDeal with the sortField parameter
        fetchDeal(sortField, sortDirectionPipeline,"",clickedColumn);
    };

    function validateTextarea() {
        var textarea = document.getElementById('darea');
        var textareaValue = textarea.value.trim();
        // Check if textarea value is empty
        if (textareaValue === '') {
            // Show error message or perform validation logic
            document.getElementById("subject_error").innerHTML = "please enter details";
        } else {
            document.getElementById("subject_error").innerHTML = "";
        }
    }

</script>
