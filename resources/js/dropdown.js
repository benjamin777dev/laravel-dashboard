window.showDropdown = function (showDropdown, selectElement) {
    selectElement.empty();

    selectElement.each(function () {
        $(this).select2({
            theme: 'bootstrap-5',
            dropdownParent: $(this).parent(),
            placeholder: 'Please select',
            width: 'resolve',
            ajax: {
                url: '/task/get-Modules',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term, // search term
                        page: params.page || 1,
                        limit: 5 // number of records to fetch initially
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.items,
                    };
                },
                cache: true
            },
            templateResult: function (state) {
                if (!state.id) {
                    return state.text;
                }

                if (state.children) {
                    return $('<span id="' + state.text + '">' + state.text + '</span>');
                }

                if (state.first_name || state.last_name) {
                    return $('<span data-module="' + state?.zoho_module_id + '" id="' + state?.zoho_contact_id + '">' + state?.first_name + ' ' + state?.last_name + '</span>');
                }

                return $('<span id="' + state?.zoho_deal_id + '">' + state?.deal_name + '</span>');
            },
            templateSelection: function (state) {
                if (!state.id) {
                    return state.text;
                }

                if (state.first_name || state.last_name) {
                    return state.first_name + ' ' + state.last_name;
                }

                return state.deal_name;
            }
        }).on('select2:select', function (e) {
            var selectedData = e.params.data;
            console.log('Selected Data:', selectedData);

            var selectedText;
            if (selectedData.first_name && selectedData.last_name) {
                selectedText = selectedData.first_name + ' ' + selectedData.last_name;
                console.log('zoho_module_id:', selectedData.zoho_module_id);
                console.log('zoho_contact_id:', selectedData.zoho_contact_id);
                window.groupLabel = "Contacts";
                window.moduelID = selectedData.zoho_module_id;
                window.relatedTo = selectedData.zoho_contact_id;
            } else {
                selectedText = selectedData.deal_name;
                console.log('zoho_module_id:', selectedData.zoho_module_id);
                console.log('zoho_deal_id:', selectedData.zoho_deal_id);
                window.groupLabel = "Deals";
                window.moduelID = selectedData.zoho_module_id;
                window.relatedTo = selectedData.zoho_deal_id;
            }
        });
    });

}

window.showDropdownForId = function (modalID, selectElement) {
    var selectedval = selectElement.val();
    var selectedText1 = selectElement.find('option:selected').text();
    console.log(selectedval, 'selectedText1');
    console.log(selectedText1, 'selectedText1');
    selectElement.each(function () {
        $(this).select2({
            theme: 'bootstrap-5',
            width: 'resolve',
            ajax: {
                url: '/task/get-Modules',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term, // search term
                        page: params.page || 1,
                        limit: 5 // number of records to fetch initially
                    };
                },
                processResults: function (data, params) {
                    console.log(data, 'data is here')
                    params.page = params.page || 1;

                    return {
                        results: data.items,
                    };
                },
                cache: true
            },
            templateResult: function (state) {
                if (!state.id) {
                    return state.text;
                }

                if (state.children) {
                    return $('<span id="' + state.text + '">' + state.text + '</span>');
                }

                if (state.first_name && state.last_name) {
                    return $('<span data-module="' + state.zoho_module_id + '" id="' + state.zoho_contact_id + '">' + state.first_name + ' ' + state.last_name + '</span>');
                }

                return $('<span id="' + state.zoho_deal_id + '">' + state.deal_name + '</span>');
            },
            templateSelection: function (state) {
                if (!state.id) {
                    return state.text;
                }

                if (state.first_name && state.last_name) {
                    return state.first_name + ' ' + state.last_name;
                }

                return state.deal_name;
            }

        }).on('select2:select', function (e) {
            var selectedData = e.params.data;
            console.log('Selected Data:', selectedData);

            var selectedText;
            if (selectedData.first_name && selectedData.last_name) {
                selectedText = selectedData.first_name + ' ' + selectedData.last_name;
                console.log('zoho_module_idddd:', selectedData.zoho_module_id);
                console.log('zoho_contact_id:', selectedData.zoho_contact_id);
                window.groupLabel = "Contacts";
                window.moduelID = selectedData.zoho_module_id;
                window.relatedTo = selectedData.zoho_contact_id;
                updateText(newText = "", groupLabel, modalID, "", window.relatedTo);
            } else {
                selectedText = selectedData.deal_name;
                console.log('zoho_module_idddddd:', selectedData.zoho_module_id);
                console.log('zoho_deal_id:', selectedData.zoho_deal_id);
                window.groupLabel = "Deals";
                window.moduelID = selectedData.zoho_module_id;
                window.relatedTo = selectedData.zoho_deal_id;
                updateText(newText = "", groupLabel, modalID, window.relatedTo, "");
            }
        });
    });
    let select2data = document.getElementsByClassName("select2-selection__rendered");
    Array.from(select2data).forEach(element => {
        element.innerHTML = element.title;
    });


}

window.updateSelectOptionsTask = function (selectElement, taskArr) {
    // Clear existing options
    selectElement.empty();

    taskArr.forEach(function (state) {
        var optgroup = $('<optgroup>', {
            label: state.text
        });
        selectElement.append(optgroup);

        var count = 0; // Counter to track the number of records appended for each label

        if (state.text === "Contacts") {
            state.children.forEach(function (contact) {
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
        if (state.text === "Deals") {
            state.children.forEach(function (deal) {
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

window.updateTaskArrTask = function (selectElement, search) {

    // Populate select with new options
    $.ajax({
        url: '/task/get-Modules?search=' + search,
        method: "GET",
        dataType: "json",
        success: function (response) {
            console.log(response, 'response')
            // $(document).trigger('customAjaxResponseTask', [selectElement, response]);
        },
        error: function (xhr, status, error) {
            // Handle error
            console.error("Ajax Error:", error);
        }
    });
}

window.addCommonTask = function (id = "", type = "") {
    // console.log(window.groupLabel, type,id, 'selction type is here');
    var selectionId;
    if (window?.groupLabel === "Contacts") {
        type = window.groupLabel;
        selectionId = window.relatedTo;
    }
    if (window?.groupLabel === "Deals") {
        type = window.groupLabel;
        selectionId = window.relatedTo;
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


