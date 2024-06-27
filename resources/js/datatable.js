
        function number_format(number, decimals, dec_point, thousands_sep) {
            // Function to format number with commas for thousands and specified decimals
            number = parseFloat(number).toFixed(decimals);
            number = number.replace('.', dec_point);
            var parts = number.toString().split(dec_point);
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousands_sep);
            return parts.join(dec_point);
        }
        function getColorForStage(stage) {
            switch (stage) {
                case 'Potential':
                    return '#dfdfdf';
                case 'Active':
                    return '#afafaf';
                case 'Pre-Active':
                    return '#cfcfcf';
                case 'Under Contract':
                    return '#8f8f8f';
                case 'Dead-Lost To Competition':
                    return '#efefef';
                default:
                    return '#6f6f6f';
            }
        }
        
        function getTextColorForStage(stage) {
            switch (stage) {
                case 'Under Contract':
                    return '#fff';
                default:
                    return '#000'; // Default text color
            }
        }
        function formateDate(data){
            const dateObj = new Date(data);
                
            // Format the date to display in YYYY-MM-DD format
            const formattedDate = dateObj.toLocaleDateString('en-US', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit'
            });
            return formattedDate;
        }
      //pipeline data table code
        var table = $('#datatable_pipe_transaction').DataTable({
            paging: true,
            searching: true,
            "processing": true,
            serverSide: true,
            columns: [{
                    data: null,
                    title: "Actions",
                    render: function(data, type, row) {
                        let icon="";
                        if(data.stage==="Under Contract") icon = `<i class="fas fa-lock"></i>`;
                        return ` ${icon}
                        <a href="/pipeline-view/${data.id}" target='_blank'>
                        <img src="/images/open.svg" alt="Open icon" class="ppiplinecommonIcon" title="Transaction Details">
                        <span class="tooltiptext"></span>
                    </a>
                    <img src="/images/splitscreen.svg" alt="Split screen icon"
                            class="ppiplinecommonIcon" data-bs-toggle="modal"
                            data-bs-target="#newTaskModalId${data.id}" title="Add Task">
                        <span class="tooltiptext"></span>
                         ${generateModalHtml(data)}
                    <img src="/images/sticky_note.svg" alt="Sticky note icon"
                            class="ppiplinecommonIcon" data-bs-toggle="modal" data-bs-target="#"
                            onclick="fetchNotesForContact('${data.id}','${data.zoho_deal_id}','Deals')">
                        <span class="tooltiptext"></span>
                         ${fetchNotesDeal(data.zoho_deal_id)}
                    <img src="/images/noteBtn.svg" alt="Note icon"
                    onclick="createNotesForDeal('${data.id}','${data.zoho_deal_id}')"
                            class="ppiplinecommonIcon"  data-bs-toggle="modal"
                            data-bs-target="#staticBackdropforNote_${data.id}">
                        <span class="tooltiptext"></span>
                        <div class="createNoteModal"></div>
                        `;
                    }
                },
                {
                    data: 'deal_name',
                    title: "Transaction",
                    render: function(data, type, row) {
                        if (row.stage === "Under Contract") {
                            return `<span>${data || "N/A"}</span>`;
                        }
                        return `<span class="editable" data-name="deal_name" data-id="${row.id}" title="Click to edit">${data || "N/A"}</span>
                    <br><span class="editable fs-6" data-name="address" data-id="${row.id}" title="Click to edit">${row.address || "Address not available"}</span>`;
                    }
                },
                {
                    data: 'client_name_primary',
                    title: "Client Name",
                    render: function(data, type, row) {
                        return `<span>${data || "N/A"}</span>`;
                    }
                },
                {
                    data: 'stage',
                    title: "Status",
                    render: function(data, type, row) {
                        console.log(data,'sdfsdhfshd')
                        if (row.stage === "Under Contract") {
                            return `<span style="color:${getTextColorForStage(data)}; background-color:${getColorForStage(data)}">${data || "N/A"}</span>`;
                        } 
                        return `<span class="editable" data-name="stage" style="color:${getTextColorForStage(data)}; background-color:${getColorForStage(data)}" data-id="${row.id}">${data || "N/A"}</span>`;
                    }
                },
                {
                    data: 'representing',
                    title: "Representing",
                    render: function(data, type, row) {
                        if (row.stage === "Under Contract") {
                            return `<span>${data || "N/A"}</span>`;
                        } 
                        return `<span class="editable" data-name="representing" data-id="${row.id}">${data || "N/A"}</span>`;
                    }
                },
                {
                    data: 'sale_price',
                    title: "Price",
                    render: function(data, type, row) {
                        if (row.stage === "Under Contract") {
                            return `<span>${data || "N/A"}</span>`;
                        } 
                        return `<span class="editable" data-name="sale_price" data-id="${row.id}">$${number_format(data, 0, '.', ',') || "N/A"}</span>`;
                    }
                },
                {
                    data: 'closing_date',
                    title: "Close Date",
                    render: function(data, type, row) {
                        if (row.stage === "Under Contract") {
                            return `<span>${formateDate(data) || "N/A"}</span>`;
                        } 
                        if (data) {
                            // Create a Date object from the ISO 8601 format string
                          
                
                            // Return the formatted date
                            return `<span class="editable" data-name="closing_date" data-id="${row.id}">${formateDate(data) || "N/A"}</span>`;
                        }
                    }
                },
                {
                    data: 'commission',
                    title: "Commission",
                    render: function(data, type, row) {
                        if (row.stage === "Under Contract") {
                            return `<span>${data || "N/A"}</span>`;
                        } 
                        return `<span class="editable" data-name="commission" data-id="${row.id}">${data || "N/A"}%</span>`;
                    }
                },
                {
                    data: 'potential_gci',
                    title: "Potential GCI",
                    render: function(data, type, row) {
                        return `<span>${data || "N/A"}</span>`;
                    }
                },
                {
                    data: 'pipeline_probability',
                    title: "Probability",
                    render: function(data, type, row) {
                        if (row.stage === "Under Contract") {
                            return `<span>${data || "N/A"}</span>`;
                        } 
                        return `<span class="editable" data-name="pipeline_probability" data-id="${row.id}">${data || "N/A"}%</span>`;
                    }
                },
                {
                    data: null,
                    title: "Probable GCI",
                    render: function(data, type, row) {
                        // Calculate probable GCI
                        var probableGCI = (row.sale_price ?? 0) * ((row.commission ?? 0) / 100) * ((row
                            .pipeline_probability ?? 0) / 100);
                        return `$${number_format(probableGCI, 0, '.', ',')}`; // Format probableGCI as currency
                    }
                }
            ],
            ajax: {
                url: '/pipeline_view', // Ensure this URL is correct
                type: 'GET', // or 'POST' depending on your server setup
                "data": function(request) {
                    request._token = "{{ csrf_token() }}";
                    request.perPage = request.length; 
                    request.page = (request.start / request.length) + 1;
                    request.search = request.search.value;
                },
                dataSrc: function(data) {
                    return data?.data; // Return the data array or object from your response
                }
            },
            initComplete: function() {
                // Function to handle editing mode
                var currentText;

                function formatDate(dateString) {
                    if (!dateString) return '';

                    var date = new Date(dateString);
                    var year = date.getFullYear();
                    var month = ('0' + (date.getMonth() + 1)).slice(-2);
                    var day = ('0' + date.getDate()).slice(-2);

                    return `${year}-${month}-${day}`;
                }

                function enterEditMode(element) {
                    if ($(element).hasClass('editing')) {
                        return; // Do nothing if already editing
                    }

                    // Close any other editing inputs
                    $('#datatable_pipe_transaction tbody').find('input.edit-input, select.edit-input').each(
                        function() {
                            var newValue = $(this).val();
                            var dataName = $(this).data('name');
                            var dataId = $(this).data('id');
                            $(this).replaceWith(
                                `<span class="editable" data-name="${dataName}" data-id="${dataId}">${newValue}</span>`
                            );
                        });

                    currentText = $(element).text(); // Set currentText when entering edit mode
                    var dataName = $(element).data('name');
                    var dataId = $(element).data('id');

                    // Replace span with input or select for editing
                    if (dataName !== "closing_date" && dataName !== "stage" && dataName !== "representing") {
                        $(element).replaceWith(
                            `<input type="text" class="edit-input form-control" value="${currentText}" data-name="${dataName}" data-id="${dataId}">`
                        ).addClass('editing');
                    } else if (dataName === "closing_date") {
                        $(element).replaceWith(
                            `<input type="date" class="edit-input form-control" value="${formatDate(currentText)}" data-name="${dataName}" data-id="${dataId}">`
                        ).addClass('editing');
                    } else if (dataName === "stage") {
                        // Fetch stage options from backend (example)
                        var stageOptions = ['Potential', 'Pre-Active', 'Under Contract', 'Active','Sold','Dead-Lost To Competition','Dead-Contract Terminated'];
                        var selectOptions = stageOptions.map(option => {
                            return `<option value="${option}" ${currentText === option ? 'selected' : ''}>${option}</option>`;
                        }).join('');

                        $(element).replaceWith(
                            `<select class="edit-input form-control editable" data-name="${dataName}" data-id="${dataId}">
                    ${selectOptions}
                </select>`
                        ).addClass('editing');
                    } else if (dataName === "representing") {
                        // Fetch representing options from backend (example)
                        var representingOptions = ['Buyer', 'Seller'];
                        var selectOptions = representingOptions.map(option => {
                            return `<option value="${option}" ${currentText === option ? 'selected' : ''}>${option}</option>`;
                        }).join('');

                        $(element).replaceWith(
                            `<select class="edit-input form-control editable" data-name="${dataName}" data-id="${dataId}">
                    ${selectOptions}
                </select>`
                        ).addClass('editing');
                    }
                    // Focus on the input field or select dropdown
                    $('#datatable_pipe_transaction tbody').find('input.edit-input, select.edit-input').focus();
                }


                // Function to handle exiting editing mode
                function exitEditMode(inputElement) {
                    var newValue = $(inputElement).val();
                    var dataName = $(inputElement).data('name');
                    var dataId = $(inputElement).data('id');

                    // Replace input or select with span
                    $(inputElement).replaceWith(
                        `<span class="editable" data-name="${dataName}" data-id="${dataId}">${newValue}</span>`
                    ).removeClass('editing');

                    // Check if the value has changed
                    if (newValue !== currentText) {
                        $("#datatable_pipe_transaction_processing").css("display", "block");
                        // Example AJAX call (replace with your actual endpoint and data):
                        $.ajax({
                            url: '/deals/update/' + dataId,
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                id: dataId,
                                field: dataName,
                                value: newValue
                            },
                            success: function(response) {
                                console.log('Updated successfully:', response);
                                $("#datatable_pipe_transaction_processing").css("display", "none");
                                if (response?.message) {
                                    showToast(response?.message);
                                    $('#datatable_pipe_transaction').DataTable().ajax.reload();
                                }
                            },
                            error: function(error) {
                                console.error('Error updating:', error);
                                $("#datatable_pipe_transaction_processing").css("display", "none");
                                showToastError(error?.responseJSON?.error);
                                $('#datatable_pipe_transaction').DataTable().ajax.reload();
                            }
                        });
                    }
                }

                // Click event to enter editing mode
                $('#datatable_pipe_transaction tbody').on('click', 'span.editable', function() {
                    enterEditMode(this);
                });

                // Keyup event to exit editing mode on Enter
                $('#datatable_pipe_transaction tbody').on('keyup', 'input.edit-input', function(event) {
                    if (event.key === "Enter") {
                        exitEditMode(this);
                    }
                });
                // Handle onchange event for select
                $('#datatable_pipe_transaction tbody').on('change', 'select.edit-input', function() {
                    exitEditMode(this); // Exit edit mode when a selection is made
                });

                // Blur event to exit editing mode when clicking away
                $('#datatable_pipe_transaction tbody').on('blur', 'input.edit-input', function() {
                    exitEditMode(this);
                });
            }
        });
        

        $('#pipelineSearch').on('keyup', function() {
            table.search(this.value).draw();
        });
        $('#related_to_stage').on('change', function() {
            table.search(this.value).draw();
        });
        $('#Reset_All').on('click', function() {
            $('#pipelineSearch').val("");
            $('#related_to_stage').val("");
            table.search("").draw();
        });


        //transaction for dashboard
        var tableDashboard = $('#datatable_transaction').DataTable({
            paging: true,
            searching: true,
            "processing": true,
            serverSide: true,
            columns: [
                {
                    data: 'deal_name',
                    title: "Transaction",
                    render: function(data, type, row) {
                        return `<span >${data}</span>`;
                    }
                },
                {
                    data: 'client_name_primary',
                    title: "Client Name",
                    render: function(data, type, row) {
                        return `<span >${data}</span>`;
                    }
                },
                {
                    data: 'stage',
                    title: "Status",
                    render: function(data, type, row) {
                        return `<span>${data}</span>`;
                    }
                },
                {
                    data: 'representing',
                    title: "Representing",
                    render: function(data, type, row) {
                        return `<span >${data}</span>`;
                    }
                },
                {
                    data: 'sale_price',
                    title: "Price",
                    render: function(data, type, row) {
                        console.log(data, 'datattas')
                        return `<span >$${number_format(data, 0, '.', ',')}</span>`;
                    }
                },
                {
                    data: 'closing_date',
                    title: "Close Date",
                    render: function(data, type, row) {
                        return `<span class="editable badDateInput" data-name="closing_date" data-id="${row.id}">${data}</span>`;
                    }
                },
                {
                    data: 'commission',
                    title: "Commission",
                    render: function(data, type, row) {
                        return `<span >${data}%</span>`;
                    }
                },
                {
                    data: 'potential_gci',
                    title: "Potential GCI",
                    render: function(data, type, row) {
                        return `<span >${data}</span>`;
                    }
                },
                {
                    data: 'pipeline_probability',
                    title: "Probability",
                    render: function(data, type, row) {
                        return `<span >${data}%</span>`;
                    }
                },
                {
                    data: null,
                    title: "Probable GCI",
                    render: function(data, type, row) {
                        // Calculate probable GCI
                        var probableGCI = (row.sale_price ?? 0) * ((row.commission ?? 0) / 100) * ((row
                            .pipeline_probability ?? 0) / 100);
                        return `$${number_format(probableGCI, 0, '.', ',')}`; // Format probableGCI as currency
                    }
                }
            ],
            ajax: {
                url: '/needsNewdate', // Ensure this URL is correct
                type: 'GET', // or 'POST' depending on your server setup
                "data": function(request) {
                    request._token = "{{ csrf_token() }}";
                    request.perPage = request.length;
                    request.stage = $('#related_to_stage').val(),
                    request.page = (request.start / request.length) + 1;
                    request.search = request.search.value;

                },
                dataSrc: function(data) {
                    return data?.data; // Return the data array or object from your response
                }
            },
            initComplete: function() {
                // Function to handle editing mode
                var currentText;

                function formatDate(dateString) {
                    if (!dateString) return '';

                    var date = new Date(dateString);
                    var year = date.getFullYear();
                    var month = ('0' + (date.getMonth() + 1)).slice(-2);
                    var day = ('0' + date.getDate()).slice(-2);

                    return `${year}-${month}-${day}`;
                }

                function enterEditMode(element) {
                    if ($(element).hasClass('editing')) {
                        return; // Do nothing if already editing
                    }

                    // Close any other editing inputs
                    $('#datatable_transaction tbody').find('input.edit-input, select.edit-input').each(
                        function() {
                            var newValue = $(this).val();
                            var dataName = $(this).data('name');
                            var dataId = $(this).data('id');
                            $(this).replaceWith(
                                `<span class="editable" data-name="${dataName}" data-id="${dataId}">${newValue}</span>`
                            );
                        });

                    currentText = $(element).text(); // Set currentText when entering edit mode
                    var dataName = $(element).data('name');
                    var dataId = $(element).data('id');

                    // Replace span with input or select for editing
                    if (dataName !== "closing_date" && dataName !== "stage" && dataName !== "representing") {
                        $(element).replaceWith(
                            `<input type="text" class="edit-input form-control" value="${currentText}" data-name="${dataName}" data-id="${dataId}">`
                        ).addClass('editing');
                    } else if (dataName === "closing_date") {
                        $(element).replaceWith(
                            `<input type="date" class="edit-input form-control badDateInput" value="${formatDate(currentText)}" data-name="${dataName}" data-id="${dataId}">`
                        ).addClass('editing');
                    } else if (dataName === "stage") {
                        // Fetch stage options from backend (example)
                        var stageOptions = ['Potential', 'Pre-Active', 'Under Contract', 'Active'];
                        var selectOptions = stageOptions.map(option => {
                            return `<option value="${option}" ${currentText === option ? 'selected' : ''}>${option}</option>`;
                        }).join('');

                        $(element).replaceWith(
                            `<select class="edit-input form-control editable" data-name="${dataName}" data-id="${dataId}">
                    ${selectOptions}
                </select>`
                        ).addClass('editing');
                    } else if (dataName === "representing") {
                        // Fetch representing options from backend (example)
                        var representingOptions = ['Buyer', 'Seller'];
                        var selectOptions = representingOptions.map(option => {
                            return `<option value="${option}" ${currentText === option ? 'selected' : ''}>${option}</option>`;
                        }).join('');

                        $(element).replaceWith(
                            `<select class="edit-input form-control editable" data-name="${dataName}" data-id="${dataId}">
                    ${selectOptions}
                </select>`
                        ).addClass('editing');
                    }

                    // Focus on the input field or select dropdown
                    $('#datatable_transaction tbody').find('input.edit-input, select.edit-input').focus();
                }


                // Function to handle exiting editing mode
                function exitEditMode(inputElement) {
                    var newValue = $(inputElement).val();
                    var dataName = $(inputElement).data('name');
                    var dataId = $(inputElement).data('id');

                    // Replace input or select with span
                    $(inputElement).replaceWith(
                        `<span class="editable" data-name="${dataName}" data-id="${dataId}">${newValue}</span>`
                    ).removeClass('editing');

                    // Check if the value has changed
                    if (newValue !== currentText) {

                        // Example AJAX call (replace with your actual endpoint and data):
                        $.ajax({
                            url: '/deals/update/' + dataId,
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                id: dataId,
                                field: dataName,
                                value: newValue
                            },
                            success: function(response) {
                                console.log('Updated successfully:', response);
                                if (response?.message) {
                                    showToast(response?.message);
                                }
                            },
                            error: function(error) {
                                console.error('Error updating:', error);
                                showToastError(error?.responseJSON?.error);
                            }
                        });
                    }
                }

                // Click event to enter editing mode
                $('#datatable_transaction tbody').on('click', 'span.editable', function() {
                    enterEditMode(this);
                });

                // Keyup event to exit editing mode on Enter
                $('#datatable_transaction tbody').on('keyup', 'input.edit-input', function(event) {
                    if (event.key === "Enter") {
                        exitEditMode(this);
                    }
                });
                // Handle onchange event for select
                $('#datatable_transaction tbody').on('change', 'select.edit-input', function() {
                    exitEditMode(this); // Exit edit mode when a selection is made
                });

                // Blur event to exit editing mode when clicking away
                $('#datatable_transaction tbody').on('blur', 'input.edit-input', function() {
                    exitEditMode(this);
                });
            }
        });


        var tableTasks = $('#datatable_tasks').DataTable({
            paging: true,
            searching: true,
            "processing": true,
            serverSide: true,
            columns: [
                {
                    data: null,
                    title: '<input type="checkbox" id="checkAll" />',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `<input onchange="triggerCheckbox('${row.zoho_task_id}')" type="checkbox"
                                    class="task_checkbox" id="${row.zoho_task_id}" />`;
                    }
                },
                {
                    data: 'subject',
                    title: 'Subject',
                    render: function(data, type, row) {
                        return `<span class="editable" data-name="deal_name" data-id="${row.id}">${data}</span>`;
                    }
                },
                {
                    data: 'related_to',
                    title: 'Related To',
                    render: function(data, type, row) {
                        return `<span class="editable" data-name="client_name_primary" data-id="${row.id}">${data}</span>`;
                    }
                },
                {
                    data: 'due_date',
                    title: 'Due Date',
                    render: function(data, type, row) {
                        return  `<span class="editable" data-name="closing_date" data-id="${row.id}">${data}</span>`;
                    }
                },
                {
                    data: null,
                    title: 'Options',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `<div class="d-flex btn-save-del">
                                    <div class="input-group-text dFont800 dFont11 text-white justify-content-center align-items-baseline savebtn"
                                        id="update_changes" data-bs-toggle="modal"
                                        onclick="updateTask('${row.zoho_task_id}','${row.id}')">
                                        <i class="fas fa-hdd plusicon"></i>
                                        Done
                                    </div>
                                    <div class="input-group-text dFont800 dFont11 text-white justify-content-center align-items-baseline deletebtn"
                                        id="btnGroupAddon" data-bs-toggle="modal"
                                        data-bs-target="#deleteModalId${row.zoho_task_id}">
                                        <i class="fas fa-trash-alt plusicon"></i>
                                        Delete
                                    </div>
                                </div>`;
                    }
                }
            ],
            ajax: {
                url: '/dashboard-tasks', // Ensure this URL is correct
                type: 'GET', // or 'POST' depending on your server setup
                "data": function(request) {
                    request._token = "{{ csrf_token() }}";
                    request.perPage = request.length;
                    request.stage = $('#related_to_stage').val(),
                    request.page = (request.start / request.length) + 1;
                    request.search = request.search.value;

                },
                dataSrc: function(data) {
                    console.log(data,'data is hreeeee')
                    return data?.data; // Return the data array or object from your response
                }
            },
            initComplete: function() {
                // Function to handle editing mode
                var currentText;

                function formatDate(dateString) {
                    if (!dateString) return '';

                    var date = new Date(dateString);
                    var year = date.getFullYear();
                    var month = ('0' + (date.getMonth() + 1)).slice(-2);
                    var day = ('0' + date.getDate()).slice(-2);

                    return `${year}-${month}-${day}`;
                }

                function enterEditMode(element) {
                    if ($(element).hasClass('editing')) {
                        return; // Do nothing if already editing
                    }

                    // Close any other editing inputs
                    $('#datatable_tasks tbody').find('input.edit-input, select.edit-input').each(
                        function() {
                            var newValue = $(this).val();
                            var dataName = $(this).data('name');
                            var dataId = $(this).data('id');
                            $(this).replaceWith(
                                `<span class="editable" data-name="${dataName}" data-id="${dataId}">${newValue}</span>`
                            );
                        });

                    currentText = $(element).text(); // Set currentText when entering edit mode
                    var dataName = $(element).data('name');
                    var dataId = $(element).data('id');

                    // Replace span with input or select for editing
                    if (dataName !== "closing_date" && dataName !== "stage" && dataName !== "representing") {
                        $(element).replaceWith(
                            `<input type="text" class="edit-input form-control" value="${currentText}" data-name="${dataName}" data-id="${dataId}">`
                        ).addClass('editing');
                    } else if (dataName === "closing_date") {
                        $(element).replaceWith(
                            `<input type="date" class="edit-input form-control" value="${formatDate(currentText)}" data-name="${dataName}" data-id="${dataId}">`
                        ).addClass('editing');
                    } else if (dataName === "stage") {
                        // Fetch stage options from backend (example)
                        var stageOptions = ['Potential', 'Pre-Active', 'Under Contract', 'Active'];
                        var selectOptions = stageOptions.map(option => {
                            return `<option value="${option}" ${currentText === option ? 'selected' : ''}>${option}</option>`;
                        }).join('');

                        $(element).replaceWith(
                            `<select class="edit-input form-control editable" data-name="${dataName}" data-id="${dataId}">
                    ${selectOptions}
                </select>`
                        ).addClass('editing');
                    } else if (dataName === "representing") {
                        // Fetch representing options from backend (example)
                        var representingOptions = ['Buyer', 'Seller'];
                        var selectOptions = representingOptions.map(option => {
                            return `<option value="${option}" ${currentText === option ? 'selected' : ''}>${option}</option>`;
                        }).join('');

                        $(element).replaceWith(
                            `<select class="edit-input form-control editable" data-name="${dataName}" data-id="${dataId}">
                    ${selectOptions}
                </select>`
                        ).addClass('editing');
                    }

                    // Focus on the input field or select dropdown
                    $('#datatable_tasks tbody').find('input.edit-input, select.edit-input').focus();
                }


                // Function to handle exiting editing mode
                function exitEditMode(inputElement) {
                    var newValue = $(inputElement).val();
                    var dataName = $(inputElement).data('name');
                    var dataId = $(inputElement).data('id');

                    // Replace input or select with span
                    $(inputElement).replaceWith(
                        `<span class="editable" data-name="${dataName}" data-id="${dataId}">${newValue}</span>`
                    ).removeClass('editing');

                    // Check if the value has changed
                    if (newValue !== currentText) {

                        // Example AJAX call (replace with your actual endpoint and data):
                        $.ajax({
                            url: '/deals/update/' + dataId,
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                id: dataId,
                                field: dataName,
                                value: newValue
                            },
                            success: function(response) {
                                console.log('Updated successfully:', response);
                                if (response?.message) {
                                    showToast(response?.message);
                                }
                            },
                            error: function(error) {
                                console.error('Error updating:', error);
                                showToastError(error?.responseJSON?.error);
                            }
                        });
                    }
                }

                // Click event to enter editing mode
                $('#datatable_tasks tbody').on('click', 'span.editable', function() {
                    enterEditMode(this);
                });

                // Keyup event to exit editing mode on Enter
                $('#datatable_tasks tbody').on('keyup', 'input.edit-input', function(event) {
                    if (event.key === "Enter") {
                        exitEditMode(this);
                    }
                });
                // Handle onchange event for select
                $('#datatable_tasks tbody').on('change', 'select.edit-input', function() {
                    exitEditMode(this); // Exit edit mode when a selection is made
                });

                // Blur event to exit editing mode when clicking away
                $('#datatable_tasks tbody').on('blur', 'input.edit-input', function() {
                    exitEditMode(this);
                });
            }
        });


        function generateModalHtml(data) {
            return `
                <div class="modal fade" id="newTaskModalId${data.id}" tabindex="-1">
                    <div class="modal-dialog d-flex justify-content-center align-items-center vh-100 deleteModal">
                        <div class="modal-content p-1">
                            <div class="modal-header border-0">
                                <p class="modal-title dHeaderText">Create New Tasks</p>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    onclick="resetValidationTask(${data.id})" aria-label="Close"></button>
                            </div>
                            <div class="modal-body dtaskbody">
                                <p class="ddetailsText fw-normal">Details</p>
                                <textarea name="subject" onkeyup="validateTextareaTask(${data.id});" id="darea${data.id}"
                                    rows="4" class="dtextarea"></textarea>
                                <div id="subject_error${data.id}" class="text-danger"></div>
                                <label class="dRelatedText mb-2">Related to...</label>
                                <div class="btn-group dmodalTaskDiv">
                                    <select class="form-select dmodaltaskSelect" id="related_to" name="related_to" aria-label="Select Transaction">
                                        <option value="${data.zoho_contact_id ? data.id : data?.zoho_deal_id}" selected>
                                            ${data.last_name ?? data.deal_name}
                                        </option>
                                    </select>
                                </div>
                                <p class="dDueText">Date due</p>
                                <input type="date" name="due_date" class="dmodalInput" />
                            </div>
                            <div class="modal-footer ">
                                <button type="button" onclick="addCommonTask('${data.zoho_contact_id ? data.zoho_contact_id : data.zoho_deal_id}','${data.zoho_contact_id ? 'Contacts' : 'Deals'}')"
                                    class="btn btn-secondary taskModalSaveBtn">
                                    <i class="fas fa-save saveIcon"></i> Save Changes
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }


        function fetchNotesDeal(dealId) {
            return `
                <div class="modal fade testing" onclick="event.preventDefault();"
                    id="notefetchrelatedContact${dealId}" data-bs-backdrop="static"
                    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered deleteModal">
                        <div class="modal-content dtaskmodalContent">
                            <div class="modal-header border-0">
                                <p class="modal-title dHeaderText">Notes</p>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    onclick="resetValidation()" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="notesContainer${dealId}">
        
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }
        

        window.validateNoteDash=function(id = null) {
            console.log(window.groupLabel, window.whoid, 'checkouttttttt');
            let noteText, relatedTo, changeButton
            if (id) {
                noteText = document.getElementById("note_text" + id).value;
                relatedTo = document.getElementById("related_to" + id)?.value;
                changeButton = document.getElementById('validate-button' + id);
                let isValid = true;
    
                // Reset errors
                document.getElementById("note_text_error" + id).innerText = "";
                document.getElementById("related_to_error" + id).innerText = "";
    
                /* // Validate note text length
                if (noteText.trim().length > 10) {
                    document.getElementById("note_text_error"+id).innerText = "Note text must be 10 characters or less";
                    isValid = false;
                } */
                // Validate note text
                if (noteText.trim() === "") {
                    document.getElementById("note_text_error" + id).innerText = "Note text is required";
                    isValid = false;
                }
    
                // Validate related to
                if (relatedTo === "") {
                    document.getElementById("related_to_error" + id).innerText = "Related to is required";
                    document.getElementById("noteSelect" + id).style.display = "none";
                    isValid = false;
                }
                if (isValid) {
                    changeButton.type = "submit";
                    document.getElementById("staticBackdropforNote_" + id).removeAttribute("onclick");
                }
                return isValid;
            } else {
                noteText = document.getElementById("note_text").value;
                relatedTo = document.getElementById("related_to_note").value;
                changeButton = document.getElementById('validate-button');
                let isValid = true;
    
                // Reset errors
                document.getElementById("note_text_error").innerText = "";
                document.getElementById("related_to_error").innerText = "";
                let mergerdata = document.getElementById('merged_data');
                console.log(mergerdata, 'emrefkdjklfsd');
                // Validate note text
                if (noteText.trim() === "") {
                    document.getElementById("note_text_error").innerText = "Note text is required";
                    isValid = false;
                } else {
                    document.getElementById("note_text_error").innerText = "";
                }
    
                // Validate related to
                if (relatedTo === "") {
                    document.getElementById("related_to_error").innerText = "Related to is required";
                    isValid = false;
                } else {
                    document.getElementById("related_to_error").innerText = "";
                }
                if (isValid) {
                    const mergedData = {
                        groupLabel: window.groupLabel,
                        whoid: window.whoid,
                        relatedTo: window.relatedTo,
                        moduleId: window.moduelID
                    };
                    // Serialize the array to a JSON string
                    const mergedDataJson = JSON.stringify(mergedData);
                    mergerdata.value = mergedDataJson;
                }
                return isValid;
            }
    
    
        }

        function enableSelect(id) {
            // Enable the select element before form submission
            document.getElementById('noteSelect'+id).removeAttribute('disabled');
            // Return true to allow form submission
            return true;
        }


 //contact data table code

        var tableContact = $('#datatable_contact').DataTable({
            paging: true,
            searching: true,
            processing: true,
            responsive: true,
            serverSide: true,
            order: [0, 'desac'],
            columns: [{
                    data: null,
                    title: "Actions",
                    render: function(data, type, row) {
                        return `<a href="/contacts-view/${data.id}" target='_blank'>
                        <img src="/images/open.svg" alt="Open icon" class="ppiplinecommonIcon" title="Transaction Details">
                        <span class="tooltiptext"></span>
                    </a>
                    <img src="/images/splitscreen.svg" alt="Split screen icon"
                            class="ppiplinecommonIcon" data-bs-toggle="modal"
                            data-bs-target="#newTaskModalId${data.id}" title="Add Task">
                        <span class="tooltiptext"></span>
                        ${generateModalHtml(data)}
                    <img src="/images/sticky_note.svg" alt="Sticky note icon"
                            class="ppiplinecommonIcon" data-bs-toggle="modal" data-bs-target="#"
                            onclick="fetchNotesForContact('${data.id}','${data.zoho_contact_id}','Contacts')">
                        <span class="tooltiptext"></span>
                        ${fetchNotesDeal(data.zoho_contact_id)}
                    <img src="/images/noteBtn.svg" alt="Note icon"
                            class="ppiplinecommonIcon" data-bs-toggle="modal"
                            onclick="createNotesForContact('${data.id}','${data.zoho_contact_id}')"
                            data-bs-target="#staticBackdropforNote_${data.id}">
                        <span class="tooltiptext"></span>
                        <div class="createNoteModal"></div>
                        `;
                    }
                },
                {
                    data: 'last_name',
                    title: "Full name",
                    render: function(data, type, row) {
                        return `<span>${data || 'N/A'}</span>`;
                    }
                },
                {
                    data: 'abcd',
                    title: "ABCD",
                    render: function(data, type, row) {
                        return `<span>${data || 'N/A'}</span>`;
                    }
                },
                {
                    data: 'relationship_type',
                    title: "Relationship Type",
                    render: function(data, type, row) {
                        return `<span>${data || 'N/A'}</span>`;
                    }
                },
                {
                    data: 'email',
                    title: "Email",
                    render: function(data, type, row) {
                        return `<span class="editable" data-name="email" data-id="${row.id}">${data || 'N/A'}</span>`;
                    }
                },
                {
                    data: 'mobile',
                    title: "Mobile",
                    render: function(data, type, row) {
                        return `<span class="editable" data-name="mobile" data-id="${row.id}">${data || 'N/A'}</span>`;
                    }
                },
                {
                    data: 'phone',
                    title: "Phone",
                    render: function(data, type, row) {
                        return `<span class="editable" data-name="phone" data-id="${row.id}">${data || 'N/A'}</span>`;
                    }
                },
                {
                    data: 'envelope_salutation',
                    title: "Envelope",
                    render: function(data, type, row) {
                        return `<span class="editable" data-name="envelope_salutation" data-id="${row.id}">${data || 'N/A'}</span>`;
                    }
                },
            ],
            ajax: {
                url: '/contact_view', // Ensure this URL is correct
                type: 'GET', // or 'POST' depending on your server setup
                "data": function(request) {
                    request._token = "{{ csrf_token() }}";
                    request.perPage = request.length;
                    var contactSortValue = $('#contactSort').val();
                    var emailChecked = $('#filterEmail').prop('checked');
                    var mobileChecked = $('#filterMobile').prop('checked');
                    var abcdChecked = $('#filterABCD').prop('checked');
                    if(emailChecked || mobileChecked || abcdChecked){
                        request.filterobj = {
                            email:emailChecked,
                            mobile:mobileChecked,
                            abcd:abcdChecked
                        }
                    }
                    if (contactSortValue) {
                        request.stage = contactSortValue;
                    }
                    request.page = (request.start / request.length) + 1;
                    request.search = request.search.value;
                    console.log(request,'request')

                },
                dataSrc: function(data) {
                    document.getElementById('close_btn').click();
                    return data?.data; // Return the data array or object from your response
                }
            },
            initComplete: function() {
                // Function to handle editing mode
                var currentText;
                function enterEditMode(element) {
                    if ($(element).hasClass('editing')) {
                        return; // Do nothing if already editing
                    }

                    // Close any other editing inputs
                    $('#datatable_contact tbody').find('input.edit-input, select.edit-input').each(
                        function() {
                            var newValue = $(this).val();
                            var dataName = $(this).data('name');
                            var dataId = $(this).data('id');
                            $(this).replaceWith(
                                `<span class="editable" data-name="${dataName}" data-id="${dataId}">${newValue}</span>`
                            );
                        });

                    currentText = $(element).text(); // Set currentText when entering edit mode
                    var dataName = $(element).data('name');
                    var dataId = $(element).data('id');

                    // Replace span with input or select for editing
                    if (dataName !== "closing_date" && dataName !== "stage" && dataName !== "representing") {
                        $(element).replaceWith(
                            `<input type="text" class="edit-input form-control" value="${currentText}" data-name="${dataName}" data-id="${dataId}">`
                        ).addClass('editing');
                    } else if (dataName === "closing_date") {
                        $(element).replaceWith(
                            `<input type="date" class="edit-input form-control" value="${formatDate(currentText)}" data-name="${dataName}" data-id="${dataId}">`
                        ).addClass('editing');
                    } else if (dataName === "stage") {
                        // Fetch stage options from backend (example)
                        var stageOptions = ['Potential', 'Pre-Active', 'Under Contract', 'Active'];
                        var selectOptions = stageOptions.map(option => {
                            return `<option value="${option}" ${currentText === option ? 'selected' : ''}>${option}</option>`;
                        }).join('');

                        $(element).replaceWith(
                            `<select class="edit-input form-control editable" data-name="${dataName}" data-id="${dataId}">
                    ${selectOptions}
                </select>`
                        ).addClass('editing');
                    } else if (dataName === "representing") {
                        // Fetch representing options from backend (example)
                        var representingOptions = ['Buyer', 'Seller'];
                        var selectOptions = representingOptions.map(option => {
                            return `<option value="${option}" ${currentText === option ? 'selected' : ''}>${option}</option>`;
                        }).join('');

                        $(element).replaceWith(
                            `<select class="edit-input form-control editable" data-name="${dataName}" data-id="${dataId}">
                    ${selectOptions}
                </select>`
                        ).addClass('editing');
                    }

                    // Focus on the input field or select dropdown
                    $('#datatable_contact tbody').find('input.edit-input, select.edit-input').focus();
                }


                // Function to handle exiting editing mode
                function exitEditMode(inputElement) {
                    var newValue = $(inputElement).val();
                    var dataName = $(inputElement).data('name');
                    var conId = $(inputElement).data('id');

                    // Replace input or select with span
                    $(inputElement).replaceWith(
                        `<span class="editable" data-name="${dataName}" data-id="${conId}">${newValue}</span>`
                    ).removeClass('editing');

                    // Check if the value has changed
                    if (newValue !== currentText) {
                        $("#datatable_contact_processing").css("display", "block");
                        // Example AJAX call (replace with your actual endpoint and data):
                        $.ajax({
                            url: '/contact/update/' + conId,
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                id: conId,
                                field: dataName,
                                value: newValue
                            },
                            success: function(response) {
                                console.log('Updated successfully:', response);
                                $("#datatable_contact_processing").css("display", "none");
                                if (response?.message) {
                                    showToast(response?.message);
                                    $('#datatable_contact').DataTable().ajax.reload();
                                }
                            },
                            error: function(error) {
                                console.error('Error updating:', error);
                                $("#datatable_contact_processing").css("display", "none");
                                $('#datatable_contact').DataTable().ajax.reload();
                                showToastError(error?.responseJSON?.error);
                            }
                        });
                    }
                }

                // Click event to enter editing mode
                $('#datatable_contact tbody').on('click', 'span.editable', function() {
                    enterEditMode(this);
                });

                // Keyup event to exit editing mode on Enter
                $('#datatable_contact tbody').on('keyup', 'input.edit-input', function(event) {
                    if (event.key === "Enter") {
                        exitEditMode(this);
                    }
                });
                // Handle onchange event for select
                $('#datatable_contact tbody').on('change', 'select.edit-input', function() {
                    exitEditMode(this); // Exit edit mode when a selection is made
                });

                // Blur event to exit editing mode when clicking away
                $('#datatable_contact tbody').on('blur', 'input.edit-input', function() {
                    exitEditMode(this);
                });
            }
        });

        $('#contactSearch').on('keyup', function() {
            tableContact.search(this.value).draw();
        });

        $('#contactSort').on('change', function() {
            tableContact.search("").draw();
        });
        $('.pfilterBtn').on('click', function() {
            tableContact.search("").draw();
        });
         
        $('.filterClosebtn').on('click', function() {
            $('#filterEmail').prop('checked',false);
            $('#filterMobile').prop('checked',false);
            $('#filterABCD').prop('checked',false);
            tableContact.search("").draw();
        });
        $('#Reset_All').on('click', function() {
            $('#contactSearch').val("");
            $('#contactSort').val("");  
            $('#filterEmail').prop('checked',false);
            $('#filterMobile').prop('checked',false);
            $('#filterABCD').prop('checked',false);
            tableContact.search("").draw();
        });

//    contacts actions
        window.createNotesForContact = function(id, conId){

            event.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "/note-create/"+id,
                method: "GET",
                success: function(response) {
                    // $('#notesContainer').append('<p>New Note Content</p>');
                    let noteContainer = $(".createNoteModal");
                    console.log(response, 'noteContainer')
                    // Clear previous contents of note containe
                    noteContainer.empty();
                    const card = noteContainer.html(response);
                    // // Show the modal after appending notes
                    $("#staticBackdropforNote_" + id).modal('show');
                },
                error: function(xhr, status, error) {
                    // Handle error
                    showToastError(error);
                    console.error("Ajax Error:", error);
                }
            });

        }

        window.fetchNotesForContact=function(id, conId,type) {
            event.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            let urlfinal =type==="Deals" ?"/deal/note/" :"/note/";
            $.ajax({
                url: urlfinal+id,
                method: "GET",
                success: function(response) {
                    // $('#notesContainer').append('<p>New Note Content</p>');
                    let noteContainer = $("#notesContainer" + conId);
                    console.log(response, 'noteContainer')
                    // Clear previous contents of note containe
                    noteContainer.empty();
                    const card = noteContainer.html(response);
                    // Loop through each note in the response array
                    // response?.forEach(function(note) {
                    //     // console.log(note, 'note')
                    //     // Create HTML to display note content and creation time
                    //     let data = `<div class="noteCardForContact">
                    //                 <p>Note Content: ${note?.contact_data?.first_name} ${note?.contact_data?.last_name}</p>
                    //                 <p>Note Content: ${note?.note_content}</p>
                    //             </div>`;
                    //     // Append the HTML to noteContainer
                    //     noteContainer.append(data);
                    //     console.log("testing", noteContainer)
                    // });
                    // // Show the modal after appending notes
                    $("#notefetchrelatedContact" + conId).modal('show');
                },
                error: function(xhr, status, error) {
                    // Handle error
                    showToastError(error);
                    console.error("Ajax Error:", error);
                }
            });
    
        }

        //    deal actions
        window.createNotesForDeal = function(id, conId){

            event.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "/note-create-pipe/"+id,
                method: "GET",
                success: function(response) {
                    // $('#notesContainer').append('<p>New Note Content</p>');
                    let noteContainer = $(".createNoteModal");
                    console.log(response, 'noteContainer')
                    // Clear previous contents of note containe
                    noteContainer.empty();
                    const card = noteContainer.html(response);
                    // // Show the modal after appending notes
                    $("#staticBackdropforNote_" + id).modal('show');
                },
                error: function(xhr, status, error) {
                    // Handle error
                    showToastError(error);
                    console.error("Ajax Error:", error);
                }
            });

        }