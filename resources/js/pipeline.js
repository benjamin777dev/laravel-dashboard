window.checkValidate = function () {
    var stage = document.getElementById('validationDefault04');
    var address = document.getElementById('validationDefault07');
    var city = document.getElementById('validationDefault08');
    var state = document.getElementById('validationDefault09');
    var zip = document.getElementById('validationDefault10');
    var property_type = document.getElementById('validationDefault12');
    if (stage.value === 'Under Contract') {
        address.classList.add('validate');
        city.classList.add('validate');
        state.classList.add('validate');
        zip.classList.add('validate');
        property_type.classList.add('validate');
    } else {
        address.classList.remove('validate');
        city.classList.remove('validate');
        state.classList.remove('validate');
        zip.classList.remove('validate');
        property_type.classList.remove('validate');
    }
}

window.updateDataDeal = function (dealId) {
    let isValid = true
    console.log(dealId);
    // Retrieve values from form fields
    var client_name_primary = $('#validationDefault01').val();
    client_name_primary = JSON.parse(client_name_primary)
    var representing = $('#validationDefault02').val();
    var deal_name = $('#validationDefault03').val();
    var stage = $('#validationDefault04').val();
    var sale_price = $('#validationDefault05').val();
    var closing_date = $('#validationDefault06').val();
    var address = $('#validationDefault07').val();
    var city = $('#validationDefault08').val();
    var state = $('#validationDefault09').val();
    var zip = $('#validationDefault10').val();
    var commission = $('#validationDefault11').val();
    var commission_flat_free = $('#commissionflat').val();
    var property_type = $('#validationDefault12').val();
    var ownership_type = $('#validationDefault13').val();
    var potential_gci = $('#validationDefault14').val();
    var pipeline_probability = $('#validationDefault15').val();
    var probable_gci = $('#validationDefault16').val();
    var personal_transaction = $('#flexCheckChecked01').prop('checked');
    var double_ended = $('#flexCheckChecked02').prop('checked');
    var review_gen_opt_out = $('#flexCheckChecked03').prop('checked');


    if (client_name_primary === '') {
        isValid = false
    }
    if (representing === '') {
        isValid = false
    }
    if (deal_name === '') {
        isValid = false
    }
    if (stage === '') {
        isValid = false
    }
    if (sale_price === '') {
        isValid = false
    }
    if (closing_date === '') {
        isValid = false
    }
    if (commission === '') {
        isValid = false
    }
    if (stage === 'Under Contract') {
        if (address === '') {
            isValid = false
        }
        if (city === '') {
            isValid = false
        }
        if (state === '') {
            isValid = false
        }
        if (zip === '') {
            isValid = false
        }
        if (property_type === '') {
            isValid = false
        }
    }
    if (isValid == true) {
        // Create formData object
        var formData = {
            "data": [{
                "Client_Name_Primary": (client_name_primary.first_name || "") + " " + (client_name_primary.last_name || ""),
                "Client_Name_Only": (client_name_primary.first_name || "") + " " + (client_name_primary.last_name || "") + " || " + client_name_primary.zoho_contact_id,
                "Representing": representing,
                "Deal_Name": deal_name,
                "Stage": stage,
                "Sale_Price": sale_price,
                "Closing_Date": closing_date,
                "Address": address,
                "City": city,
                "State": state,
                "Zip": zip,
                "Commission": parseInt(commission),
                "Property_Type": property_type,
                "Ownership_Type": ownership_type,
                "Potential_GCI": potential_gci,
                "Pipeline_Probability": pipeline_probability,
                "Pipeline1": probable_gci,
                "Personal_Transaction": personal_transaction,
                "Double_Ended": double_ended,
                "Contact": {
                    "Name": (client_name_primary.first_name || "") + " " + (client_name_primary.last_name || ""),
                    "id": client_name_primary.zoho_contact_id
                },
                "Double_Ended": double_ended,
                "Review_Gen_Opt_Out": review_gen_opt_out,
                "Commission_Flat_Free": commission_flat_free
            }],
        };

        console.log("formData", formData, dealId);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // Send AJAX request
        $.ajax({
            url: "/pipeline/update/" + dealId,
            type: 'PUT',
            contentType: 'application/json',
            dataType: 'json',
            data: JSON.stringify(formData),
            success: function (response) {
                if (response?.data && response.data[0]?.message) {
                    // Convert message to uppercase and then display
                    const upperCaseMessage = response.data[0].message.toUpperCase();
                    showToast(upperCaseMessage);
                    // window.location.reload();
                }
            },
            error: function (xhr, status, error) {
                // Handle error response
                console.error(xhr.responseText);
            }
        })
    }
}

window.formatDate = function (date) {
    const d = new Date(date);
    const year = d.getFullYear();
    const month = String(d.getMonth() + 1).padStart(2, '0');
    const day = String(d.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}
window.updateDeal = function (dealID, field, Id, card, date) {
    event.preventDefault();
    let updateElement
    if (card) {
        updateElement = document.getElementById("card_" + field + dealID);
    } else {
        updateElement = document.getElementById(field + dealID);

    }

    if (!updateElement) {
        console.log("not found");
        return;
    }

    // Check if the input element already exists
    if (updateElement.querySelector('input')) {
        return; // Exit if input is already present
    }

    // Extract and clean the text value
    var text = updateElement.textContent.trim();
    text = text.replace(/\$\s*/, '').trim();  // Remove dollar sign and extra spaces


    if (field == "closing_date") {
        // Assuming date is defined and in a valid format
        const formattedDate = formatDate(date); // Format date if needed
        updateElement.innerHTML =
            `<input type="date" class="form-control npinputinfo" id="edit${field}${dealID}" required value="${formattedDate}">`;
    } else {
        updateElement.innerHTML =
            '<input type="text" class="inputDesign" onclick="event.preventDefault();" id="edit' + field + dealID +
            '" value="' + text + '" />';
    }

    let inputElementmake = document.getElementById('edit' + field + dealID);
    inputElementmake.focus();
    inputElementmake.addEventListener('keydown', function (event) {
        // Check if the key pressed is Enter
        if (event.key === 'Enter') {
            // Prevent the default form submission behavior
            event.preventDefault();
            // Call the function to update the element
            updateDealData(field, Id, dealID);
        }
    });

    let dateInput = document.getElementById('edit' + field + dealID);
    if (field == "closing_date") {
        // Create a date input element
        // Add event listener to update the deal data when the date input loses focus
        dateInput.addEventListener('blur', function () {
            updateDealData(field, Id, dealID);
        });

        dateInput.focus(); // Set focus to the new date input
    } else {

        dateInput.focus(); // Set focus to the new text input

        dateInput.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                updateDealData(field, Id, dealID);
            }
        });

        dateInput.addEventListener('change', function () {
            updateElement.innerHTML = '<div class="commonTextEllipsis" id="' + field + dealID + '">' + dateInput.value + '</div>';
            updateDealData(field, Id, dealID);
        });
    }


    // Prevent default action when clicking on container
    let container = document.getElementById("contactlist");
    container?.addEventListener("click", function (event) {
        event.preventDefault();
    });
}


window.updateDealData = function (field, id, dealID, value = null) {
    let elementId = document.getElementById(field + dealID);
    console.log(field, dealID, elementId?.textContent ? elementId?.textContent : elementId.value, 'eleme');
    let formData = {
        "data": [{
            "Deal_Name": field == "deal_name" ? elementId?.textContent : undefined,
            "Client_Name_Primary": field == "client_name_primary" ? elementId?.textContent : undefined,
            "Stage": field == "stage" ? value : undefined,
            // "ABCD": "",
            "Representing": field == "representing" ? value : undefined,
            "Sale_Price": field == "sale_price" ? elementId?.textContent : undefined,
            "Closing_Date": field == "closing_date" ? (elementId?.textContent ? elementId?.textContent : elementId.value) : undefined,
            "Commission": field == "commission" ? elementId?.textContent : undefined,
            "Pipeline_Probability": field == "pipeline_probability" ? elementId?.textContent : undefined
        }],
        "skip_mandatory": true
    }
    // Iterate through the data array
    formData?.data?.forEach(obj => {
        // Iterate through the keys of each object
        Object.keys(obj).forEach(key => {
            // Check if the value is undefined and delete the key
            if (obj[key] === undefined) {
                delete obj[key];
            }
        });
    });
    //ajax call hitting here
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: `/pipeline/update/${dealID}`,
        method: 'PUT',
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify(formData),
        success: function (response) {
            console.log("LJHJLDKGFLHDSGFKDHSGF", response)
            // Handle success response
            if (response?.data[0]?.status == "success") {
                if (!document.getElementById('savemakeModalId' + dealID).classList.contains('show')) {
                    var modalTarget = document.getElementById('savemakeModalId' + dealID);
                    var update_message = document.getElementById('updated_message_make');
                    update_message.textContent = response?.data[0]?.message;
                    // Show the modal
                    $(modalTarget).modal('show');
                    window.location.reload();
                }

            }
        },
        error: function (xhr, status, error) {
            // Handle error response
            console.error(xhr.responseText, 'errrorroororooro');


        }
    })



}

