
        function number_format(number, decimals, dec_point, thousands_sep) {
            // Function to format number with commas for thousands and specified decimals
            number = parseFloat(number).toFixed(decimals);
            number = number.replace('.', dec_point);
            var parts = number.toString().split(dec_point);
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousands_sep);
            return parts.join(dec_point);
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
                        return `<a href="/pipeline-view/${data.id}" target='_blank'>
                        <img src="/images/open.svg" alt="Open icon" class="ppiplinecommonIcon" title="Transaction Details">
                        <span class="tooltiptext"></span>
                    </a>
                    <img src="/images/splitscreen.svg" alt="Split screen icon"
                            class="ppiplinecommonIcon" data-bs-toggle="modal"
                            data-bs-target="#newTaskModalId${data.id}" title="Add Task">
                        <span class="tooltiptext"></span>
                    <img src="/images/sticky_note.svg" alt="Sticky note icon"
                            class="ppiplinecommonIcon" data-bs-toggle="modal" data-bs-target="#"
                            onclick="fetchNotesForDeal(${data.id},${data.zoho_deal_id})">
                        <span class="tooltiptext"></span>
                    <img src="/images/noteBtn.svg" alt="Note icon"
                            class="ppiplinecommonIcon" data-bs-toggle="modal"
                            data-bs-target="#staticBackdropforNote_${data.id}">
                        <span class="tooltiptext"></span>
                        `;
                    }
                },
                {
                    data: 'deal_name',
                    title: "Transaction",
                    render: function(data, type, row) {
                        return `<span class="editable" data-name="deal_name" data-id="${row.id}">${data}</span>`;
                    }
                },
                {
                    data: 'client_name_primary',
                    title: "Client Name",
                    render: function(data, type, row) {
                        return `<span class="editable" data-name="client_name_primary" data-id="${row.id}">${data}</span>`;
                    }
                },
                {
                    data: 'stage',
                    title: "Status",
                    render: function(data, type, row) {
                        return `<span class="editable" data-name="stage" data-id="${row.id}">${data}</span>`;
                    }
                },
                {
                    data: 'representing',
                    title: "Representing",
                    render: function(data, type, row) {
                        return `<span class="editable" data-name="representing" data-id="${row.id}">${data}</span>`;
                    }
                },
                {
                    data: 'sale_price',
                    title: "Price",
                    render: function(data, type, row) {
                        console.log(data, 'datattas')
                        return `<span class="editable" data-name="sale_price" data-id="${row.id}">$${number_format(data, 0, '.', ',')}</span>`;
                    }
                },
                {
                    data: 'closing_date',
                    title: "Close Date",
                    render: function(data, type, row) {
                        return `<span class="editable" data-name="closing_date" data-id="${row.id}">${data}</span>`;
                    }
                },
                {
                    data: 'commission',
                    title: "Commission",
                    render: function(data, type, row) {
                        return `<span class="editable" data-name="commission" data-id="${row.id}">${data}%</span>`;
                    }
                },
                {
                    data: 'potential_gci',
                    title: "Potential GCI",
                    render: function(data, type, row) {
                        return `<span class="editable" data-name="potential_gci" data-id="${row.id}">${data}</span>`;
                    }
                },
                {
                    data: 'pipeline_probability',
                    title: "Probability",
                    render: function(data, type, row) {
                        return `<span class="editable" data-name="pipeline_probability" data-id="${row.id}">${data}%</span>`;
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
            table.search("").draw();
        });


 //contact data table code

 var tableContact = $('#datatable_contact').DataTable({
    paging: true,
    searching: true,
    "processing": true,
    serverSide: true,
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
            <img src="/images/sticky_note.svg" alt="Sticky note icon"
                    class="ppiplinecommonIcon" data-bs-toggle="modal" data-bs-target="#"
                    onclick="fetchNotesForDeal(${data.id},${data.zoho_deal_id})">
                <span class="tooltiptext"></span>
            <img src="/images/noteBtn.svg" alt="Note icon"
                    class="ppiplinecommonIcon" data-bs-toggle="modal"
                    data-bs-target="#staticBackdropforNote_${data.id}">
                <span class="tooltiptext"></span>
                `;
            }
        },
        {
            data: 'last_name',
            title: "Full name",
            render: function(data, type, row) {
                return `<span class="editable" data-name="last_name" data-id="${row.id}">${data}</span>`;
            }
        },
        {
            data: 'relationship_type',
            title: "Relationship Type",
            render: function(data, type, row) {
                return `<span class="editable" data-name="relationship_type" data-id="${row.id}">${data}</span>`;
            }
        },
        {
            data: 'email',
            title: "Email",
            render: function(data, type, row) {
                return `<span class="editable" data-name="email" data-id="${row.id}">${data}</span>`;
            }
        },
        {
            data: 'mobile',
            title: "Mobile",
            render: function(data, type, row) {
                return `<span class="editable" data-name="mobile" data-id="${row.id}">${data}</span>`;
            }
        },
        {
            data: 'mailing_address',
            title: "Address",
            render: function(data, type, row) {
                console.log(data, 'datattas')
                return `<span class="editable" data-name="mailing_address" data-id="${row.id}">${data}</span>`;
            }
        },
    ],
    ajax: {
        url: '/contact_view', // Ensure this URL is correct
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
            tableContact.search(this.value).draw();
        });
        
        $('#Reset_All').on('click', function() {
            $('#contactSearch').val("");
            $('#contactSort').val("");  
            tableContact.search("").draw();
        });