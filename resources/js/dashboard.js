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

window.updateDeal = function(zohoDealId, dealId, parentElement) {
    event.preventDefault();
   
    let dealData = {};

    let closingDateInput = document.getElementById(`closing_date${zohoDealId}`);
    let closingDate = closingDateInput ? closingDateInput.value : null;

    if (closingDate === null) {
        console.log("Closing date not found");
        return;
    }

    dealData['closing_date'] = closingDate;

    let formData = {
        "data": [{
            "Deal_Name": dealData.deal_name? dealData.deal_name:undefined,
            "Client_Name_Primary": dealData.client_name_primary?dealData.client_name_primary:undefined,
            "Stage": dealData.stage?dealData.stage:undefined,
            "Representing": dealData.representing ? dealData.representing : undefined,
            "Sale_Price": dealData.sale_price ? dealData.sale_price : undefined,
            "Closing_Date": dealData.closing_date ? dealData.closing_date : undefined,
            "Commission": dealData.commission ? dealData.commission : undefined,
            "Pipeline_Probability": dealData.pipeline_probability ? dealData.pipeline_probability: undefined,
        }],
        "skip_mandatory": true
    };

    formData.data[0] = Object.fromEntries(
        Object.entries(formData.data[0]).filter(([_, value]) => value !== undefined)
    );

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

