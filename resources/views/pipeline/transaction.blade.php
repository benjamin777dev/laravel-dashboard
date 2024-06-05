<div class="table-responsive">
    <div class="container-fluid">
        <div class="col-md-12">
          <table id="example" class="table bg-grey table-bordered nowrap" cellspacing="0" width="100%">
              <thead class="thead_con_design">
                  <tr>
                    <th>
                        <div class="commonFlex">
                        <p class="mb-0">Transaction</p>
                        <img src="{{ URL::asset('/images/swap_vert.svg') }}" class="ppiplineSwapIcon" alt="Transaction icon"
                            id="pipelineSort" onclick="toggleSort('deal_name')">
                        </div>
                    </th>
                    <th>
                        <div class="commonFlex">
                        <p class="mb-0">Client Name</p>
                        <img src="{{ URL::asset('/images/swap_vert.svg') }}" alt="Client icon" class="ppiplineSwapIcon"
                            id="pipelineSort" onclick="toggleSort('client_name_primary')">
                        </div>
                    </th>
    
                    <th>
                        <div class="commonFlex">
                        <p class="mb-0">Status&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                        <img src="{{ URL::asset('/images/swap_vert.svg') }}" alt="Status icon" class="ppiplineSwapIcon"
                            id="pipelineSort" onclick="toggleSort('stage')">
                        </div>
                    </th>
    
                    <th>
                        <div class="commonFlex">
                        <p class="mb-0">Representing</p>
                        <img src="{{ URL::asset('/images/swap_vert.svg') }}" alt="Rep icon" class="ppiplineSwapIcon"
                            id="pipelineSort" onclick="toggleSort('representing')">
                        </div>
                    </th>
    
    
                    <th>
                        <div class="commonFlex">
                        <p class="mb-0">Price</p>
                        <img src="{{ URL::asset('/images/swap_vert.svg') }}" alt="Price icon" class="ppiplineSwapIcon"
                            id="pipelineSort" onclick="toggleSort('sale_price')">
                        </div>
                    </th>
    
                    <th>
                        <div class="commonFlex">
                        <p class="mb-0">Close Date</p>
                        <img src="{{ URL::asset('/images/swap_vert.svg') }}" alt="Close icon" class="ppiplineSwapIcon"
                            id="pipelineSort" onclick="toggleSort('closing_date')">
                            </div>
                    </th>
                    <th>
                        <div class="commonFlex">
                        <p class="mb-0">Commission</p>
                        <img src="{{ URL::asset('/images/swap_vert.svg') }}" alt="Commission" class="ppiplineSwapIcon"
                            id="pipelineSort" onclick="toggleSort('closing_date')">
                        </div>
                    </th>
                    <th>
                        <div class="commonFlex">
                        <p class="mb-0">Potential GCI</p>
                        <img src="{{ URL::asset('/images/swap_vert.svg') }}" alt="Potential GCI" class="ppiplineSwapIcon"
                            id="pipelineSort" onclick="toggleSort('closing_date')">
                            </div>
                    </th>
                    <th>
                        <div class="commonFlex">
                        <p class="mb-0">Probability</p>
                        <img src="{{ URL::asset('/images/swap_vert.svg') }}" alt="Probability" class="ppiplineSwapIcon"
                            id="pipelineSort" onclick="toggleSort('closing_date')">
                            </div>
    
                    </th>
                    <th>
                        <div class="commonFlex">
                        <p class="mb-0">Probable GCI</p>
                        <img src="{{ URL::asset('/images/swap_vert.svg') }}" alt="Probable GCI" class="ppiplineSwapIcon"
                            id="pipelineSort" onclick="toggleSort('closing_date')">
                        </div>
                    </th>
                    <th>
                        <div></div>
                    </th>
                  </tr>
              </thead>
                <tbody class="table_pipeline">
                    @include('pipeline.pipelineload', ['module' => $deals])
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
    // window.onload = function() {
    //     $(document).on('click', '.datapagination a', function(e) {
    //         console.log(e, 'eeeeeee')
    //         e.preventDefault();
    //         let page = $(this).attr('href').split('page=')[1]
    //         record(page)
    //     })

    //     function record(page) {
    //         $.ajax({
    //             url: "/pipeline?page=" + page,
    //             success: function(res) {
    //                 $('.transaction-container').html(res);
    //             }
    //         })
    //     }
    // }
    $(document).ready(function() {
        let nextPageUrl = '{{ $deals->nextPageUrl() }}';
        $(window).scroll(function() {
            if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
                if (nextPageUrl) {
                    loadMorePosts();
                }
            }
        });

        function loadMorePosts() {
            $.ajax({
                url: nextPageUrl,
                type: 'get',
                beforeSend: function() {
                    nextPageUrl = '';
                },
                success: function(data) {
                    console.log(data, 'datatatata')
                    nextPageUrl = data.nextPageUrl;
                    $('.table_pipeline').append(data);
                },
                error: function(xhr, status, error) {
                    console.error("Error loading more posts:", error);
                }
            });
        }
    });

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
        // var whoSelectoneid = document.getElementsByName("who_id")[0].value;
        // var whoId = window.selectedTransation
        // if (whoId === undefined) {
        //     whoId = whoSelectoneid
        // }
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
    let sortDirection = 'desc';

    // Function to toggle sort
    window.toggleSort = function(sortField) {
        // Toggle the sort direction
        sortDirection = (sortDirection === 'desc') ? 'asc' : 'desc';
        // Call fetchDeal with the sortField parameter
        fetchDeal(sortField, sortDirection);
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

    // Add an event listener to send search term as request
    function fetchData(sortValue, sortType, filter = null, searchInput, ppipelineTableBody, ptableCardDiv) {
        // console.log("filter",filter);
        const searchValue = searchInput.val().trim();
        $.ajax({
            url: '{{ url('/pipeline/deals') }}',
            method: 'GET',
            data: {
                search: encodeURIComponent(searchValue),
                sort: sortValue || "",
                sortType: sortType || "",
                filter: filter
            },

            success: function(data) {
                const card = $('.transaction-container').html(data);
                // ppipelineTableBody.empty();
                // ptableCardDiv.empty();
                // const isMobile = window.innerWidth < 767;
                // if (isMobile) {
                //     if (data.length === 0) {
                //         // If no data found, display a message
                //         ptableCardDiv.append('<div class="pnofound"><p class="text-center">No records found</p></div>');
                //         return;
                //     }
                // }else{
                //     if (data.length === 0) {
                //         // If no data found, display a message
                //         ppipelineTableBody.append('<div class="pnofound"><p class="text-center">No records found</p></div>');
                //         return;
                //     }
                // }
                // $.each(data, function(index, item) {
                //     if (isMobile) {
                //         // Render data in card format
                //         const card = $('<div class="pTableCard">').html(`
                //             <p class="pTableTransText">Transaction</p>
                //                     <p class="pTableNameText">${item.deal_name || 'N/A'}</p>
                //                     <div class="d-flex justify-content-between">
                //                         <div class="pTableSelect pipelinestatusdiv">
                //                             <p style="background-color: ${item.stage === 'Potential'
                //                 ? '#dfdfdf'
                //                 : (item.stage === 'Active'
                //                     ? '#afafaf'
                //                     : (item.stage === 'Pre-Active'
                //                         ? '#cfcfcf'
                //                         : (item.stage === 'Under Contract'
                //                             ? '#8f8f8f;color=#fff;'
                //                             : (item.stage === 'Dead-Lost To Competition'
                //                                 ? '#efefef'
                //                                 : '#6f6f6f;color=#fff;'))))}"
                //                                 class="pstatusText">${item.stage || 'N/A'}</p>
                //                             <i class="fas fa-angle-down"></i>
                //                         </div>
                //                         ${item.closing_date || 'N/A'}
                //                     </div>
                //                     <div class="d-flex justify-content-between psellDiv">
                //                         <div><img src="{{ URL::asset('/images/account_box.svg') }}" alt="A"> ${item.client_name_primary?? 'N/A'}
                //                         </div>
                //                         <div>
                //                             <img src="{{ URL::asset('/images/sell.svg') }}" alt="A">$
                //                             ${item.sale_price || 'N/A'}
                //                         </div>
                //                     </div>
                //                     <div class="pCardFooter">
                //                         <div class="pfootericondiv">
                //                             <img src="{{ URL::asset('/images/Frame 99.svg') }}" alt=""
                //                                 class="pdiversityicon">
                //                             <img src="{{ URL::asset('/images/sticky_note.svg') }}" alt=""
                //                                 class="pdiversityicon">
                //                         </div>
                //                         <div>
                //                             <img src="{{ URL::asset('/images/noteBtn.svg') }}" alt=""
                //                                 class="pdiversityicon">
                //                         </div>
                //                     </div>
                //                 </div>
                //         `);
                //         ptableCardDiv.append(card);
                //     } else {
                //         deal = item
                //         // Render data in table format
                //         const row = $('<div class="psearchandsort">').html(Item);
                //         ppipelineTableBody.append(row);
                //     }
                // });
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }


    window.fetchDeal = function(sortField, sortDirection) {
        const searchInput = $('#pipelineSearch');
        const sortInput = $('#pipelineSort');
        const ppipelineTableBody = $('.psearchandsort');
        const ptableCardDiv = $('.ptableCardDiv');
        var selectedModule = $('#related_to_stage');
        var selectedText = selectedModule.val();
        // Call fetchData with the updated parameters
        fetchData(sortField, sortDirection, selectedText, searchInput, ppipelineTableBody, ptableCardDiv);
    }
</script>
