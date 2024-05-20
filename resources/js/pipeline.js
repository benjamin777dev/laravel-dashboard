
window.updateDeal = function (dealID, field, Id, card, date) {
    console.log(dealID, field);
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
        updateElement.innerHTML =
            `<input type="date" class="form-control npinputinfo" id="edit${field}${dealID}" required="" value="${date}">`;
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

    if (field == "closing_date") {
        inputElementmake.addEventListener('click', function () {
            // Create a date input element
            var dateInput = document.createElement('input');
            dateInput.type = 'text'; // Change type to text for Bootstrap Datepicker compatibility
            dateInput.classList.add('form-control', 'npinputinfo');
            dateInput.id = 'edit' + field + dealID;
            dateInput.required = true;

            // Set the value of the date input element
            if (inputElementmake.value) {
                dateInput.value = inputElementmake.value;
            }

            // Replace the update element with the date input element
            updateElement.innerHTML = '';
            updateElement.appendChild(dateInput);

            // Initialize Bootstrap Datepicker
            $(dateInput).datepicker();

            // Add event listener to update the deal data when the date input loses focus
            dateInput.addEventListener('blur', function () {
                updateDealData(field, Id, dealID);
            });
        });
    }
    else {
        inputElementmake.addEventListener('change', function () {
            // Update the update element with the input element's value
            updateElement.innerHTML = '<div class="commonTextEllipsis" id="' + field + dealID + '">' + inputElementmake.value + '</div>';

            // Update the deal data
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


