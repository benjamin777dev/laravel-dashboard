<script>
    

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
