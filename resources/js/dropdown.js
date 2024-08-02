window.showDropdown = function (showDropdown, selectElement) {
    console.log("showDropdown", showDropdown);
    selectElement.empty();
    selectElement.each(function () {
        const options = {
            theme: "bootstrap",
            dropdownParent: $(this).parent(),
            ajax: {
                url: "/task/get-Modules",
                dataType: "json",
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term, // search term
                        page: params.page || 1,
                        limit: 5, // number of records to fetch initially
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.items,
                    };
                },
                cache: true,
            },
            templateResult: function (state) {
                if (!state.id) {
                    return state.text;
                }

                if (state.children) {
                    return $(
                        '<span id="' +
                            state.text +
                            '">' +
                            state.text +
                            "</span>"
                    );
                }

                if (state.first_name || state.last_name) {
                    return $(
                        '<span data-module="' +
                            state.zoho_module_id +
                            '" id="' +
                            state.zoho_contact_id +
                            '">' +
                            (state.first_name ?? "") +
                            " " +
                            (state.last_name ?? "") +
                            "</span>"
                    );
                }

                return $(
                    '<span id="' +
                        state.zoho_deal_id +
                        '">' +
                        state.deal_name +
                        "</span>"
                );
            },
            templateSelection: function (state) {
                var NoRecord = "No Records Found";
                if (
                    (state?.children?.length === 0 &&
                        state.text === "Contacts") ||
                    (state?.children?.length === 0 && state.text === "Deals")
                ) {
                    return $(
                        '<span id="' +
                            state.text +
                            '">' +
                            state.text +
                            "</span>" +
                            '<br><span class="no-records-found">' +
                            NoRecord +
                            "</span>"
                    );
                }
                if (!state.id) {
                    return state.text;
                }

                if (state.first_name || state.last_name) {
                    return (
                        (state.first_name ?? "") + " " + (state.last_name ?? "")
                    );
                }

                return state.deal_name;
            },
        };

        if (showDropdown === "global-search") {
            options.allowClear = true;
            options.multiple = true;
            options.maximumSelectionLength = 1; // Use `maximumSelectionLength` instead of `maximumSelectionSize`
            options.placeholder = "Search...";
        } else if (showDropdown === "staticBackdropforNote") {
            options.placeholder = "Search...";
            options.width = "resolve";
        } else {
            options.placeholder = "General";
            options.width = "resolve";
        }

        $(this)
            .select2(options)
            .on("select2:select", function (e) {
                var selectedData = e.params.data;
                console.log("Selected Data:", selectedData);

                var selectedText;
                if (selectedData.first_name || selectedData.last_name) {
                    selectedText =
                        (selectedData.first_name ?? "") +
                        " " +
                        (selectedData.last_name ?? "");
                    console.log("Selected Text:", selectedText);
                    console.log("zoho_module_id:", selectedData.zoho_module_id);
                    console.log(
                        "zoho_contact_id:",
                        selectedData.zoho_contact_id
                    );
                    window.groupLabel = "Contacts";
                    window.moduelID = selectedData.zoho_module_id;
                    window.relatedTo = selectedData.zoho_contact_id;
                    const url = new URL(
                        `/contacts-view/${selectedData.id}`,
                        window.location.origin
                    );
                    if (showDropdown === "global-search") {
                        window.location.href = url;
                    }
                } else {
                    selectedText = selectedData.deal_name;
                    console.log("zoho_module_id:", selectedData.zoho_module_id);
                    console.log("zoho_deal_id:", selectedData.zoho_deal_id);
                    window.groupLabel = "Deals";
                    window.moduelID = selectedData.zoho_module_id;
                    window.relatedTo = selectedData.zoho_deal_id;
                    const url = new URL(
                        `/pipeline-view/${selectedData.id}`,
                        window.location.origin
                    );
                    if (showDropdown === "global-search") {
                        window.location.href = url;
                    }
                }
            });
    });
};

window.showDropdownForId = function (modalID, selectElement) {
    var selectedval = selectElement.val();
    var selectedText1 = selectElement.find("option:selected").text();
    console.log(selectedval, "selectedText1");
    console.log(selectedText1, "selectedText1");
    selectElement.each(function () {
        $(this)
            .select2({
                theme: "bootstrap-5",
                width: "resolve",
                ajax: {
                    url: "/task/get-Modules",
                    dataType: "json",
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page || 1,
                            limit: 5, // number of records to fetch initially
                        };
                    },
                    processResults: function (data, params) {
                        console.log(data.items.length, "showdropidddd");
                        params.page = params.page || 1;
                        return {
                            results: data.items,
                        };
                    },
                    cache: true,
                },
                templateResult: function (state) {
                    var NoRecord = "No Records Found";
                    if (
                        (state?.children?.length === 0 &&
                            state.text === "Contacts") ||
                        (state?.children?.length === 0 &&
                            state.text === "Deals")
                    ) {
                        return $(
                            '<span id="' +
                                state.text +
                                '">' +
                                state.text +
                                "</span>" +
                                '<br><span class="no-records-found">' +
                                NoRecord +
                                "</span>"
                        );
                    }
                    if (!state.id) {
                        return state.text;
                    }
                    if (state.children) {
                        return $(
                            '<span id="' +
                                state.text +
                                '">' +
                                state.text +
                                "</span>"
                        );
                    }

                    if (state.first_name || state.last_name) {
                        return $(
                            '<span data-module="' +
                                state.zoho_module_id +
                                '" id="' +
                                state.zoho_contact_id +
                                '">' +
                                (state.first_name ?? "") +
                                " " +
                                (state.last_name ?? "") +
                                "</span>"
                        );
                    }

                    return $(
                        '<span id="' +
                            state.zoho_deal_id +
                            '">' +
                            state.deal_name +
                            "</span>"
                    );
                },
                templateSelection: function (state) {
                    if (!state.id) {
                        return state.text;
                    }

                    if (state.first_name || state.last_name) {
                        return (
                            (state.first_name ?? "") +
                            " " +
                            (state.last_name ?? "")
                        );
                    }

                    return state.deal_name;
                },
            })
            .on("select2:select", function (e) {
                var selectedData = e.params.data;
                console.log("Selected Data:", selectedData);

                var selectedText;
                if (selectedData?.first_name || selectedData?.last_name) {
                    selectedText =
                        selectedData.first_name + " " + selectedData.last_name;
                    console.log(
                        "zoho_module_idddd:contact",
                        selectedData.zoho_module_id
                    );
                    console.log(
                        "zoho_contact_id:",
                        selectedData.zoho_contact_id
                    );
                    window.groupLabel = "Contacts";
                    window.moduelID = selectedData.zoho_module_id;
                    window.relatedTo = selectedData.zoho_contact_id;
                    updateText("", groupLabel, modalID, "", window.relatedTo);
                } else {
                    selectedText = selectedData.deal_name;
                    console.log(
                        "zoho_module_idddddd:daealslsls",
                        selectedData.zoho_module_id
                    );
                    console.log("zoho_deal_id:", selectedData.zoho_deal_id);
                    window.groupLabel = "Deals";
                    window.moduelID = selectedData.zoho_module_id;
                    window.relatedTo = selectedData.zoho_deal_id;
                    updateText("", groupLabel, modalID, window.relatedTo, "");
                }
            });
    });
    let select2data = document.getElementsByClassName(
        "select2-selection__rendered"
    );
    Array.from(select2data).forEach((element) => {
        element.innerHTML = element.title;
    });
};

window.updateText = function (
    newText,
    textfield,
    id,
    WhatSelectoneid = "",
    whoID = ""
) {
    let dateLocal;
    if (textfield === "date") {
        dateLocal = document.getElementById("date_local" + id);
        newText = newText?.substring(0, 10);
    }

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    var formData = {
        data: [
            {
                Subject: textfield === "subject" ? newText : undefined,
                Due_Date: textfield === "date" ? newText : undefined,
                What_Id: WhatSelectoneid ? { id: WhatSelectoneid } : undefined,
                Who_Id: whoID ? { id: whoID } : undefined,
                $se_module:
                    textfield === "Deals" || textfield === "Contacts"
                        ? textfield
                        : undefined,
            },
        ],
    };

    formData.data[0] = Object.fromEntries(
        Object.entries(formData.data[0]).filter(
            ([_, value]) => value !== undefined
        )
    );

    $.ajax({
        url: "/update-task/" + id,
        method: "PUT",
        contentType: "application/json",
        dataType: "json",
        data: JSON.stringify(formData),
        success: function (response) {
            showToast(response?.data[0]?.message.toUpperCase());
        },
        error: function (xhr, status, error) {
            showToastError(xhr.responseJSON.error);
            console.error(xhr.responseText);
        },
    });
};

window.resetValidationTask = function (id) {
    if (id) {
        document.getElementById("subject_error" + id).innerHTML = "";
        document.getElementById("darea" + id).value = "";
    } else {
        document.getElementById("subject_error").innerHTML = "";
        document.getElementById("darea").value = "";
    }
};

window.validateTextareaTask = function (id) {
    if (id) {
        var textarea = document.getElementById("sarea" + id);
        var textareaValue = textarea.value.trim();
        // Check if textarea value is empty
        if (textareaValue === "") {
            // Show error message or perform validation logic
            document.getElementById("subject_error" + id).innerHTML =
                "Please enter subject";
        } else {
            document.getElementById("subject_error" + id).innerHTML = "";
        }
    } else {
        var textarea = document.getElementById("sarea");
        var textareaValue = textarea.value.trim();
        // Check if textarea value is empty
        if (textareaValue === "") {
            // Show error message or perform validation logic
            document.getElementById("subject_error").innerHTML =
                "Please enter subject";
        } else {
            document.getElementById("subject_error").innerHTML = "";
        }
    }
};

window.addCommonTask = function (id = "", type = "") {
    var selectionId;
    if (window?.groupLabel === "Contacts") {
        type = window.groupLabel;
        selectionId = window.relatedTo;
    }
    if (window?.groupLabel === "Deals") {
        type = window.groupLabel;
        selectionId = window.relatedTo;
    }
    var resetId;
    if (id) {
        var subject = document.getElementsByName("subject")[0].value;
        var detail = document.getElementsByName("detail")[0].value;
        if (subject.trim() === "") {
            document.getElementById("subject_error" + id).innerHTML =
                "Please enter subject";
            return;
        }

        // var whoSelectoneid = document.getElementsByName("who_id")[0].value;
        // var whoId = window.selectedTransation
        // if (whoId === undefined) {
        //     whoId = whoSelectoneid
        // }
        var formData;
        var dueDate = document.getElementsByName("due_date")[0].value;
        if (type == "Contacts") {
            var formData = {
                data: [
                    {
                        Subject: subject,
                        Detail: detail,
                        // "Who_Id": {
                        //     "id": whoId
                        // },
                        Status: "Not Started",
                        Due_Date: dueDate ?? undefined,
                        // "Created_Time":new Date()
                        Priority: "High",
                        Who_Id: {
                            id: id,
                        },
                        $se_module: type,
                    },
                ],
            };
        } else if (type == "Deals") {
            var related_to = document.getElementById("related_to").value;
            WhatSelectoneid = related_to;
            formData = {
                data: [
                    {
                        Subject: subject !== "" ? subject : undefined,
                        Detail: detail !== "" ? detail : undefined,
                        // "Who_Id": {
                        //     "id": whoId
                        // },
                        Status: "Not Started",
                        Due_Date: dueDate !== "" ? dueDate : undefined,
                        // "Created_Time":new Date()
                        Priority: "High",
                        What_Id: WhatSelectoneid
                            ? {
                                  id: WhatSelectoneid,
                              }
                            : undefined,
                        $se_module: type !== "" ? type : undefined,
                    },
                ],
            };
        }
        resetId = id;
    } else {
        var subject = document.getElementsByName("subject")[0].value;
        var detail = document.getElementsByName("detail")[0].value;
        if (subject.trim() === "") {
            document.getElementById("subject_error").innerHTML =
                "Please enter subject";
            return;
        }

        var seModule = type;
        var WhatSelectoneid = selectionId;
        console.log("WHAT ID", WhatSelectoneid);
        var dueDate = document.getElementsByName("due_date")[0].value;
        if (seModule == "Deals") {
            formData = {
                data: [
                    {
                        Subject: subject !== "" ? subject : undefined,
                        Detail: detail !== "" ? detail : undefined,
                        // "Who_Id": {
                        //     "id": whoId
                        // },
                        Status: "Not Started",
                        Due_Date: dueDate !== "" ? dueDate : undefined,
                        // "Created_Time":new Date()
                        Priority: "High",
                        What_Id: WhatSelectoneid
                            ? {
                                  id: WhatSelectoneid,
                              }
                            : undefined,
                        $se_module: seModule !== "" ? seModule : undefined,
                    },
                ],
            };
        } else {
            formData = {
                data: [
                    {
                        Subject: subject !== "" ? subject : undefined,
                        Detail: detail !== "" ? detail : undefined,
                        // "Who_Id": {
                        //     "id": whoId
                        // },
                        Status: "Not Started",
                        Due_Date: dueDate !== "" ? dueDate : undefined,
                        // "Created_Time":new Date()
                        Priority: "High",
                        Who_Id: WhatSelectoneid
                            ? {
                                  id: WhatSelectoneid,
                              }
                            : undefined,
                        $se_module: seModule !== "" ? seModule : undefined,
                    },
                ],
            };
        }
        resetId = null;
    }

    formData.data[0] = Object.fromEntries(
        Object.entries(formData.data[0]).filter(
            ([_, value]) => value !== undefined
        )
    );

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    $.ajax({
        url: "/create-task",
        type: "POST",
        contentType: "application/json",
        dataType: "json",
        data: JSON.stringify(formData),
        success: function (response) {
            console.log(response, "responsejdhjkghsdugh");
            if (response?.data && response.data[0]?.message) {
                // Convert message to uppercase and then display
                const upperCaseMessage = response.data[0].message.toUpperCase();
                showToast(upperCaseMessage);
                var modalElement = document.getElementById('closeModal');
                if(modalElement){
                    modalElement?.click();
                }
                var closing_btnnnnn = document.getElementById('closing_btnnnnn');
                if(closing_btnnnnn){
                    closing_btnnnnn?.click();
                }
                var pathname = window.location.pathname;
                if(pathname==="/task"){
                    window.location.reload();
                    return;
                }
                window.fetchData();
                resetTaskForm(resetId);
                $("#datatable_tasks1")?.DataTable().ajax.reload();
                $("#datatable_tasks")?.DataTable().ajax.reload();
                formData = "";
                // window.location.reload();
            } else {
                showToastError("Response or message not found");
            }
        },
        error: function (xhr, status, error) {
            // Handle error response
            console.log(xhr);
            showToastError(error);
            resetTaskForm(resetId);
            $("#datatable_tasks").DataTable().ajax.reload();
            $("#datatable_tasks1").DataTable().ajax.reload();
        },
    });
};

window.resetTaskForm = function (id = null) {
    console.log("ResetForm", id);
    if (id) {
        const modalId = "#newTaskModalId" + id;
        document.getElementById("sarea" + id).value = "";
        document.getElementById("darea" + id).value = "";

        document.querySelector('input[name="due_date"]').value = "";
        document.getElementById("subject_error" + id).innerHTML = "";
        document.getElementById("detail_error" + id).innerHTML = "";
    } else {
        const modalId = "#newTaskModalId";
        document.getElementById("sarea").value = "";
        document.getElementById("darea").value = "";

        document.querySelector('input[name="due_date"]').value = "";
        document.getElementById("subject_error").innerHTML = "";
        document.getElementById("detail_error").innerHTML = "";
    }
};

window.fetchData = function() {
    $('#spinner').show();
   let loadtask = true;
    // Make AJAX call
    $.ajax({
        url: '/upcomming-task',
        method: 'GET',
        dataType: 'html',
        success: function(data) {
            $('#spinner').hide();
            loadtask = false;
            $('.upcomming-task').html(data);

        },
        error: function(xhr, status, error) {
            // Handle errors
            loadtask = false;
            console.error('Error:', error);
        }
    });

}
