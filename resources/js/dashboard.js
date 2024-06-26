window.moduleSelected = function (selectedModule, id = "") {
    // console.log(accessToken,'accessToken')
    var selectedOption = selectedModule.options[selectedModule.selectedIndex];
    var selectedText = selectedOption.text;
    console.log(selectedText, "selectedText");
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: '/task/get-' + selectedText,
        method: "GET",
        dataType: "json",
        success: function (response) {
            // Handle successful response
            var notes = response;
            // Assuming you have another select element with id 'taskSelect'
            var noteSelect = $('#noteSelect');
            // Clear existing options
            noteSelect.empty();
            // Populate select options with tasks
            $.each(notes, function (index, note) {
                if (selectedText === "Tasks") {
                    noteSelect.append($('<option>', {
                        value: note?.zoho_task_id,
                        text: note?.subject
                    }));
                }
                if (selectedText === "Deals") {
                    noteSelect.append($('<option>', {
                        value: note?.zoho_deal_id,
                        text: note?.deal_name
                    }));
                }
                if (selectedText === "Contacts") {
                    noteSelect.append($('<option>', {
                        value: note?.zoho_contact_id,
                        text: (note?.first_name ?? '') + ' ' + (note?.last_name ?? '')
                    }));
                }
            });
            noteSelect.show();
            // Do whatever you want with the response data here
        },
        error: function (xhr, status, error) {
            // Handle error
            console.error("Ajax Error:", error);
        }
    });
}

window.closeTask = function(id, indexid, date) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var formData = {
        "data": [{
             "Subject": elementValue,
             "Status":"Completed"
        }]
    };

    // console.log("ys check ot")
    $.ajax({
        url: "/update-task/:id".replace(':id', id),
        method: 'PUT',
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify(formData),
        success: function(response) {
            // Handle success response

            if (response?.data[0]?.status == "success") {
                window.location.reload();
            }
        },
        error: function(xhr, status, error) {
            // Handle error response
            showToastError("Something went wrong");
            console.error(xhr.responseText, 'errrorroororooro');
        }
    })
}


window.updateDeal = function(zohoDealId, dealId, parentElement) {
    event.preventDefault();

    let dealData = {};
    parentElement.querySelectorAll('[data-type]').forEach(element => {
        let type = element.getAttribute('data-type');
        let value = element.getAttribute('data-value');
        dealData[type] = value;
    });

    let closingDateInput = document.getElementById(`closing_date${zohoDealId}`);
    let closingDate = closingDateInput ? closingDateInput.value : null;

    if (closingDate === null) {
        console.log("Closing date not found");
        return;
    }

    dealData['closing_date'] = closingDate;

    let formData = {
        "data": [{
            "Deal_Name": dealData.deal_name,
            "Client_Name_Primary": dealData.client_name_primary,
            "Stage": dealData.stage,
            "Representing": dealData.representing,
            "Sale_Price": dealData.sale_price,
            "Closing_Date": dealData.closing_date,
            "Commission": dealData.commission,
            "Pipeline_Probability": dealData.pipeline_probability,
        }],
        "skip_mandatory": true
    };

    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    $.ajax({
        url: `/pipeline/update/${zohoDealId}`,
        method: 'PUT',
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify(formData),
        success: function(response) {
            document.getElementById("loaderOverlay").style.display = "none";
            document.getElementById('loaderfor').style.display = "none";
            if (response?.data[0]?.status == "success") {
                const upperCaseMessage = response.data[0].message.toUpperCase();
                showToast(upperCaseMessage);
                window.location.reload();
            }
        },
        error: function(xhr, status, error) {
            document.getElementById("loaderOverlay").style.display = "none";
            document.getElementById('loaderfor').style.display = "none";
            console.error(xhr.responseText, 'error');
        }
    });
};

