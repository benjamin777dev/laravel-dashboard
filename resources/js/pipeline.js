
window.updateDeal = function (dealID, field, Id, card, date) {
    event.preventDefault();
    let updateElement;
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
    text = text.replace(/\$\s*/, '').trim(); // Remove dollar sign and extra spaces

    if (field === "closing_date") {
        // Assuming date is defined and in a valid format
       let dateInput =  document.getElementById(field+dealID);
       console.log(dateInput,'dateInputksdjfklhs')

        dateInput.addEventListener('blur', function () {
                updateDealData(field, Id, dealID, dateInput.value);
        });
    } else {
        var inputElement = document.createElement('input');
        inputElement.type = 'text';
        inputElement.className = 'inputDesign';
        inputElement.value = text;
        inputElement.id = 'edit' + field + dealID;
        updateElement.innerHTML = '';
        updateElement.appendChild(inputElement);
        inputElement.focus();

        inputElement.addEventListener('blur', function () {
            if (inputElement.value !== text) {
                updateDealData(field, Id, dealID, inputElement.value);
                updateElement.innerHTML = inputElement.value;
            } else {
                updateElement.innerHTML = text;
            }
        });

        inputElement.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                inputElement.blur(); // Trigger blur event
            }
        });
    }

    // Prevent default action when clicking on container
    let container = document.getElementById("contactlist");
    container?.addEventListener("click", function (event) {
        event.preventDefault();
    });
}

window.addEventListener('DOMContentLoaded', function() {
    window.updateDealData =async function (field, id, dealID, value = null) {
        document.getElementById("loaderOverlay").style.display = "block";
        document.getElementById('loaderfor').style.display = "block";
        let elementId = await document.getElementById(field + dealID);
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
                document.getElementById("loaderOverlay").style.display = "none";
                document.getElementById('loaderfor').style.display = "none";
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
                document.getElementById("loaderOverlay").style.display = "none";
                document.getElementById('loaderfor').style.display = "none";
                console.error(xhr.responseText, 'errrorroororooro');
    
    
            }
        })
    
    
    
    }
});



