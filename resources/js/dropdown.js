function showDropdown(showDropdown,selectElement, taskArr) {
    selectElement.empty();
    console.log(selectElement,'selectElement')
    // Add default "Please select" option
    selectElement.append($('<option>', {
        value: "",
        text: "Please select"
    }));

    taskArr.forEach(function (state) {
        var optgroup = selectElement.find('optgroup[label="' + state.label + '"]');
        if (optgroup.length === 0) {
            optgroup = $('<optgroup>', {
                label: state.label
            });
            selectElement.append(optgroup);
        }

        var count = 0; // Counter to track the number of records appended for each label

        if (state.label === "Contacts") {
            state.data.forEach(function (contact) {
                if (count < 5) { // Limit the number of records appended to 5
                    optgroup.append($('<option>', {
                        value: contact.zoho_contact_id,
                        text: (contact.first_name) + " " + (
                            contact.last_name ?? "")
                    }).attr('data-module', contact.zoho_module_id));

                    count++;
                }
            });
        }

        if (state.label === "Deals") {
            state.data.forEach(function (deal) {
                if (count < 5) { // Limit the number of records appended to 5
                    optgroup.append($('<option>', {
                        value: deal.zoho_deal_id,
                        text: deal.deal_name,
                    }).attr('data-module', deal.zoho_module_id));
                    count++;
                }
            });
        }
    });


    selectElement.each(function() {
        $(this).select2({
            theme: 'bootstrap-5',
            dropdownParent: $(this).parent(),
        });
    });

    $(document).on('customAjaxResponseTask', function (event, selectElement, response) {
        // Handle the response here, you can also pass it to another function if needed
        console.log("Custom event triggered with response:", response);
        updateSelectOptionsTask(selectElement, response);

    });

    $(document).on('customSendData', function (event, optgroupLabel, WhatSelectoneID, WhoID) {
        // Handle the response here, you can also pass it to another function if needed
        console.log("Custom event triggered with response:", optgroupLabel, WhatSelectoneID, WhoID);
        window.groupLabel = optgroupLabel;
        window.WhatiD = WhatSelectoneID;
        window.whoid = WhoID;

    });

    selectElement.next(".select2-container").addClass("form-select");
    $(selectElement).on("change", function () {
        console.log(this, 'vthisthisthisthisthis')
        var selectedValue = $(this).val();
        var selectedText = $(this).find(':selected').text();
        var moduleId = $(this).find(':selected').data('module');
        var optgroupLabel = $(this).find(':selected').closest('optgroup').attr('label');
        console.log("Selected value:", selectedValue);
        console.log("Selected text:", selectedText);
        console.log("Optgroup label:", optgroupLabel);
        var WhoID;
        var WhatSelectoneID;
        if (optgroupLabel === "Contacts") {
            WhoID = selectedValue;
        }
        if (optgroupLabel === "Deals") {
            WhatSelectoneID = selectedValue;
        }
        window.moduelID = moduleId;
        $(document).trigger('customSendData', [optgroupLabel, WhatSelectoneID, WhoID]);

    });
    $(selectElement).on("select2:open", function () {
        let timer; // Variable to hold the timer

        $(this).data('select2').$dropdown.find('.select2-search__field').on('input',
            function (e) {
                // This function will be triggered when the user types into the Select2 input
                clearTimeout(timer); // Clear the previous timer
                let search = $(this).val();
                timer = setTimeout(() => {
                    console.log("User has finished typing:", $(this).val());
                    updateTaskArrTask(selectElement, search);
                    // Perform any actions you need here
                }, 250); // Set timer to execute after 250ms
            });
    });

}

function updateSelectOptionsTask(selectElement, taskArr) {
    // Clear existing options
    selectElement.empty();

    taskArr.forEach(function (state) {
        var optgroup = $('<optgroup>', {
            label: state.label
        });
        selectElement.append(optgroup);

        var count = 0; // Counter to track the number of records appended for each label

        if (state.label === "Contacts") {
            state.data.forEach(function (contact) {
                if (count < 5) {

                    var option = $('<option>', {
                        value: contact.zoho_contact_id,
                        text: (contact.first_name || "") + " " + (contact.last_name || "")
                    });
                    optgroup.append(option);

                    count++;
                }
            });
        }
        if (state.label === "Deals") {
            state.data.forEach(function (deal) {
                if (count < 5) {
                    var option = $('<option>', {
                        value: deal.zoho_deal_id,
                        text: deal.deal_name,
                    });
                    optgroup.append(option);

                    count++;
                }
            });
        }
    });

    // Reinitialize Select2 after updating options
    selectElement.trigger('select2:updated');
    // var search = $("#inputhidden input.select2-input");
    // search.trigger("input");
}

function updateTaskArrTask(selectElement, search) {

    // Populate select with new options
    $.ajax({
        url: '/task/get-Modules?search=' + search,
        method: "GET",
        dataType: "json",
        success: function (response) {
            $(document).trigger('customAjaxResponseTask', [selectElement, response]);
        },
        error: function (xhr, status, error) {
            // Handle error
            console.error("Ajax Error:", error);
        }
    });
}

window.addCommonTask = function (id, type) {
    var selectionId;
    if (window.groupLabel === "Contacts") {
        type = window.groupLabel;
        selectionId = window.whoid;
    }
    if (window.groupLabel === "Deals") {
        type = window.groupLabel;
        selectionId = window.WhatiD;
    }

    if (id) {
        var subject = document.getElementsByName("subject")[0].value;
        if (subject.trim() === "") {
            document.getElementById("subject_error" + id).innerHTML = "Please enter details";
            return;
        }
        // var whoSelectoneid = document.getElementsByName("who_id")[0].value;
        // var whoId = window.selectedTransation
        // if (whoId === undefined) {
        //     whoId = whoSelectoneid
        // }
        var dueDate = document.getElementsByName("due_date")[0].value;
        if (type == "Contacts") {
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
                    "Who_Id": {
                        "id": id
                    },
                    "$se_module": type
                }],
            };
        } else if (type == "Deals") {
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
                        "id": id
                    },
                    "$se_module": type
                }],
            };
        }
        console.log("formData", formData);
    } else {
        var subject = document.getElementsByName("subject")[0].value;
        if (subject.trim() === "") {
            document.getElementById("subject_error").innerHTML = "Please enter details";
            return;
        }
        var seModule = type;
        var WhatSelectoneid = selectionId;
        console.log("WHAT ID", WhatSelectoneid);
        var dueDate = document.getElementsByName("due_date")[0].value;
        if (seModule == "Deals") {
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
                        "id": WhatSelectoneid
                    },
                    "$se_module": seModule
                }],
            };
        } else {
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
                    "Who_Id": {
                        "id": WhatSelectoneid
                    },
                    "$se_module": seModule
                }],
            };
        }
        console.log("formData", formData);
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: '/create-task',
        type: 'POST',
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify(formData),
        success: function (response) {
            if (response?.data && response.data[0]?.message) {
                // Convert message to uppercase and then display
                const upperCaseMessage = response.data[0].message.toUpperCase();
                showToast(upperCaseMessage);
                window.location.reload();
            } else {
                showToastError("Response or message not found");
            }
        },
        error: function (xhr, status, error) {
            // Handle error response
            console.log(xhr);
            showToastError(error);
        }
    })
}

