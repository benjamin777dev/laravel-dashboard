// Function to add or remove validation class
window.toggleValidation = function (element, addValidation) {
    console.log(element, addValidation, "Toggle");
    if (addValidation) {
        element.classList.add('validate');
    } else {
        element.classList.remove('validate');
    }
}
window.checkValidate = function (deal) {
    console.log(deal);
    deal = JSON.parse(JSON.stringify(deal))
    var representing = document.getElementById('validationDefault02');
    var stage = document.getElementById('validationDefault04');
    if (representing.value == 'Buyer' && stage.value == "Under Contract") {
        $('#additionalFields').append(`
                    <div class="col-md-6 additional-field ">
                        <label for="finance" class="form-label nplabelText">Financing</label>
                        <select class="form-select npinputinfo" id="finance" required onchange='checkAdditionalValidation(${deal})'>
                            <option value="" ${!(deal['financing']) ? 'selected' : ''}>--None--</option>
                            <option value="Cash" ${deal['financing'] == 'Cash' ? 'selected' : ''}>Cash</option>
                            <option value="Loan" ${deal['financing'] == 'Loan' ? 'selected' : ''}>Loan
                            </option>
                        </select>
                    </div>
                    <div class="col-md-6 additional-field">
                        <label for="lender_company" class="form-label nplabelText">Lender Company</label>
                        <select class="form-select npinputinfo" id="lender_company" required onchange='checkAdditionalValidation(${deal})'>
                            <option value="" ${!(deal['lender_company']) ? 'selected' : ''}>--None--</option>
                            <option value="Modern Mortgage" ${deal['lender_company'] == 'Modern Mortgage' ? 'selected' : ''}>Modern Mortgage</option>
                            <option value="Other" ${deal['lender_company'] == 'Other' ? 'selected' : ''}>Other
                            </option>
                        </select>
                    </div>
                    <div class="col-md-6 additional-field">
                        <label for="modern_mortgage_lender" class="form-label nplabelText">Modern Mortgage Lender</label>
                        <select class="form-select npinputinfo" id="modern_mortgage_lender" required >
                            <option value="" ${!(deal['modern_mortgage_lender']) ? 'selected' : ''}>--None--</option>
                            <option value="Joe Biniasz" ${deal['modern_mortgage_lender'] == 'Joe Biniasz' ? 'selected' : ''}>Joe Biniasz</option>
                            <option value="Laura Berry" ${deal['modern_mortgage_lender'] == 'Laura Berry' ? 'selected' : ''}>Laura Berry
                            </option>
                            <option value="Virginia Shank" ${deal['modern_mortgage_lender'] == 'Virginia Shank' ? 'selected' : ''}>Virginia Shank
                            </option>
                        </select>
                    </div>
                `);
    } else {
        // If representing is not buyer, remove the additional fields
        $('#additionalFields').find('.additional-field').remove();
    }


    var probability = document.getElementById('validationDefault15');
    if (stage.value == 'Active') {
        probability.value = "40";
    } else if (stage.value == 'Potential') {
        probability.value = "5";
    } else if (stage.value == 'Pre-Active') {
        probability.value = "20";
    } else if (stage.value == 'Under Contract') {
        probability.value = "60";
    } else if (stage.value == 'Dead-Lost To Competition') {
        probability.value = "100";
    }
    var address = document.getElementById('validationDefault07');
    var city = document.getElementById('validationDefault08');
    var state = document.getElementById('validationDefault09');
    var zip = document.getElementById('validationDefault10');
    var property_type = document.getElementById('validationDefault12');
    var tm_preference = document.getElementById('tmPreference');
    var finance = document.getElementById('finance');
    console.log("FINANCE", finance);
    var contact_name = document.getElementById('contactName');



    // Check representing value
    if (stage.value === 'Under Contract' && representing.value === 'Seller') {
        toggleValidation(address, true);
        toggleValidation(city, true);
        toggleValidation(state, true);
        toggleValidation(zip, true);
        toggleValidation(tm_preference, true);
        toggleValidation(contact_name, true);
        toggleValidation(property_type, true);
    } else if (stage.value === 'Under Contract' && representing.value === 'Buyer') {
        toggleValidation(address, true);
        toggleValidation(city, true);
        toggleValidation(state, true);
        toggleValidation(zip, true);
        toggleValidation(tm_preference, true);
        toggleValidation(contact_name, true);
        toggleValidation(property_type, true);
        if (finance) {
            toggleValidation(finance, true);
        }
    } else if (stage.value === 'Under Contract') {
        toggleValidation(address, true);
        toggleValidation(city, true);
        toggleValidation(state, true);
        toggleValidation(zip, true);
        toggleValidation(property_type, true);
    } else {
        toggleValidation(address, false);
        toggleValidation(city, false);
        toggleValidation(state, false);
        toggleValidation(zip, false);
        toggleValidation(tm_preference, false);
        toggleValidation(contact_name, false);
        toggleValidation(property_type, false);
        if (finance) {
            toggleValidation(finance, false);
        }
    }

    if (finance && finance.value == "Loan") {
        var lender_company = document.getElementById('lender_company');
        if (lender_company && lender_company.value == "Modern Mortgage") {
            var modern_mortgage_lender = document.getElementById('modern_mortgage_lender');
            toggleValidation(modern_mortgage_lender, true);
        } else if (lender_company && lender_company.value == "Other") {
            toggleValidation(lender_company, true);
        }
    }


}

window.checkAdditionalValidation = function (deal) {
    var finance = document.getElementById('finance');
    var lender_company = document.getElementById('lender_company');
    var modern_mortgage_lender = document.getElementById('modern_mortgage_lender');
    if (finance && finance.value == "Loan") {
        toggleValidation(lender_company, true);
        if (lender_company && lender_company.value == "Modern Mortgage") {
            console.log(modern_mortgage_lender);
            toggleValidation(modern_mortgage_lender, true);
            $('#additionalFields').find('.additional-field-lender').remove();
        } else if (lender_company && lender_company.value == "Other") {
            $('#additionalFields').append(`
                    <div class="col-md-6 additional-field-lender ">
                        <label for="lender_company_name" class="form-label nplabelText">Lender Company Name</label>
                        <input type="text" class="form-control npinputinfo validate"
                            id="lender_company_name" value = "${deal['lender_company_name'] ? deal['lender_company_name'] : ''}" required>
                    </div>
                    <div class="col-md-6 additional-field-lender ">
                        <label for="lender_name" class="form-label nplabelText">Lender Name</label>
                        <input type="text" class="form-control npinputinfo validate"
                            id="lender_name" value = "${deal['lender_name'] ? deal['lender_name'] : ''}" required>
                    </div>
                `);
            toggleValidation(modern_mortgage_lender, false);
        }
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
    var deadline_em_opt_out = $('#flexCheckChecked05').prop('checked');
    var status_rpt_opt_out = $('#flexCheckChecked04').prop('checked');
    var tm_preference = $('#tmPreference').val();
    var tm_name = $('#tmName').val();
    tm_name = JSON.parse(tm_name)
    var contact_name = $('#contactName').val();
    var transaction_owner = $('#transactionOwner').val();
    var lead_agent = $('#leadAgent').val();
    lead_agent = JSON.parse(lead_agent)
    var finance = $('#finance').val();
    var lender_company = $('#lender_company').val();
    var modern_mortgage_lender = $('#modern_mortgage_lender').val();
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
    console.log("Lead Agent", lead_agent.zoho_contact_id, lead_agent.first_name, lead_agent.last_name);
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
                "Contact_Name": {
                    "Name": (client_name_primary.first_name || "") + " " + (client_name_primary.last_name || ""),
                    "id": client_name_primary.zoho_contact_id
                },
                "Double_Ended": double_ended,
                "Review_Gen_Opt_Out": review_gen_opt_out,
                "Commission_Flat_Free": commission_flat_free,
                "TM_Preference": tm_preference,
                "TM_Name": {
                    "id": tm_name.zoho_contact_id,
                    "full_name": (tm_name.first_name || "") + " " + (tm_name.last_name || ""),
                },
                "Transaction_Owner": transaction_owner,
                "Contact": contact_name,
                "Status_pt_out_out": status_rpt_opt_out,
                "Deadline_Emails": deadline_em_opt_out,
                "Lead_Agent": {
                    "id": lead_agent.zoho_contact_id,
                    "full_name": (lead_agent.first_name || "") + " " + (lead_agent.last_name || ""),
                },
                'Financing': finance,
                'Lender_Company': lender_company,
                'Modern_Mortgage_Lender': modern_mortgage_lender,
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
        let dateInput = document.getElementById(field + dealID);
        console.log(dateInput, 'dateInputksdjfklhs')

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

window.addEventListener('DOMContentLoaded', function () {
    window.updateDealData = async function (field, id, dealID, value = null) {
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



