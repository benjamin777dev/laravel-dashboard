// Function to add or remove validation class
window.toggleValidation = function (element, addValidation) {
    console.log(element, addValidation, "Toggle");
    if (addValidation) {
        element.classList.add("validate");
    } else {
        element.classList.remove("validate");
    }
};
window.checkValidate = function (deal) {
    console.log(deal);

    var representing = document.getElementById("validationDefault02");
    var stage = document.getElementById("validationDefault04");
    if (representing?.value == "Buyer" && stage?.value == "Under Contract") {
        let disabledText = "disabled";

        $("#additionalFields").append(`
                    <div class="col-md-6 additional-field ">
                        <label for="finance" class="form-label nplabelText">Financing</label>
                        <select class="form-select npinputinfo" id="finance" ${disabledText} required onchange='checkAdditionalValidation(${JSON.stringify(
                            deal
                        )})'>
                            <option value="" ${
                                !deal["financing"] ? "selected" : ""
                            }>--None--</option>
                            <option value="Cash" ${
                                deal["financing"] == "Cash" ? "selected" : ""
                            }>Cash</option>
                            <option value="Loan" ${
                                deal["financing"] == "Loan" ? "selected" : ""
                            }>Loan
                            </option>
                        </select>
                    </div>
                    <div class="col-md-6 additional-field">
                        <label for="lender_company" class="form-label nplabelText">Lender Company</label>
                        <select class="form-select npinputinfo" id="lender_company" ${disabledText} required onchange='checkAdditionalValidation(${JSON.stringify(
                            deal
                        )})'>
                            <option value="" ${
                                !deal["lender_company"] ? "selected" : ""
                            }>--None--</option>
                            <option value="Modern Mortgage" ${
                                deal["lender_company"] == "Modern Mortgage"
                                    ? "selected"
                                    : ""
                            }>Modern Mortgage</option>
                            <option value="Other" ${
                                deal["lender_company"] == "Other"
                                    ? "selected"
                                    : ""
                            }>Other
                            </option>
                        </select>
                    </div>
                    <div class="col-md-6 additional-field">
                        <label for="modern_mortgage_lender" class="form-label nplabelText">Modern Mortgage Lender</label>
                        <select class="form-select npinputinfo" ${disabledText} id="modern_mortgage_lender" required >
                            <option value="" ${
                                !deal["modern_mortgage_lender"]
                                    ? "selected"
                                    : ""
                            }>--None--</option>
                            <option value="Joe Biniasz" ${
                                deal["modern_mortgage_lender"] == "Joe Biniasz"
                                    ? "selected"
                                    : ""
                            }>Joe Biniasz</option>
                            <option value="Laura Berry" ${
                                deal["modern_mortgage_lender"] == "Laura Berry"
                                    ? "selected"
                                    : ""
                            }>Laura Berry
                            </option>
                            <option value="Virginia Shank" ${
                                deal["modern_mortgage_lender"] ==
                                "Virginia Shank"
                                    ? "selected"
                                    : ""
                            }>Virginia Shank
                            </option>
                        </select>
                    </div>
                `);
    } else {
        // If representing is not buyer, remove the additional fields
        $("#additionalFields").find(".additional-field").remove();
    }

    var probability = document.getElementById("validationDefault15");
    if (stage.value == "Active") {
        probability.value = "40";
    } else if (stage.value == "Potential") {
        probability.value = "5";
    } else if (stage.value == "Pre-Active") {
        probability.value = "20";
    } else if (stage.value == "Under Contract") {
        probability.value = "60";
    } else if (stage.value == "Sold") {
        probability.value = "100";
    } else if (stage.value == "Dead-Contract Terminated") {
        probability.value = "0";
    } else if (stage.value == "Dead-Lost To Competition") {
        probability.value = "0";
    }
    var address = document.getElementById("validationDefault07");
    var city = document.getElementById("validationDefault08");
    var state = document.getElementById("validationDefault09");
    var zip = document.getElementById("validationDefault10");
    var property_type = document.getElementById("validationDefault12");
    var tm_preference = document.getElementById("tmPreference");
    var finance = document.getElementById("finance");
    console.log("FINANCE", finance);
    var contact_name = document.getElementById("contactName");

    // Check representing value
    if (stage.value === "Under Contract" && representing.value === "Seller") {
        toggleValidation(address, true);
        toggleValidation(city, true);
        toggleValidation(state, true);
        toggleValidation(zip, true);
        toggleValidation(tm_preference, true);
        toggleValidation(property_type, true);
    } else if (
        stage.value === "Under Contract" &&
        representing.value === "Buyer"
    ) {
        toggleValidation(address, true);
        toggleValidation(city, true);
        toggleValidation(state, true);
        toggleValidation(zip, true);
        toggleValidation(tm_preference, true);
        toggleValidation(property_type, true);
        if (finance) {
            toggleValidation(finance, true);
        }
    } else if (stage.value === "Under Contract") {
        toggleValidation(address, true);
        toggleValidation(city, true);
        toggleValidation(state, true);
        toggleValidation(zip, true);
        toggleValidation(property_type, true);
        toggleValidation(tm_preference, true);
    } else {
        toggleValidation(address, false);
        toggleValidation(city, false);
        toggleValidation(state, false);
        toggleValidation(zip, false);
        toggleValidation(tm_preference, false);
        toggleValidation(property_type, false);
        if (finance) {
            toggleValidation(finance, false);
        }
    }

    if (finance && finance.value == "Loan") {
        var lender_company = document.getElementById("lender_company");
        toggleValidation(lender_company, true);
        if (lender_company && lender_company.value == "Modern Mortgage") {
            var modern_mortgage_lender = document.getElementById(
                "modern_mortgage_lender"
            );
            toggleValidation(modern_mortgage_lender, true);
        } else if (lender_company && lender_company.value == "Other") {
            toggleValidation(lender_company, true);
        }
    }
};

window.checkAdditionalValidation = function (deal) {
    console.log("CheckValidation", deal);
    var finance = document.getElementById("finance");
    var lender_company = document.getElementById("lender_company");
    var modern_mortgage_lender = document.getElementById(
        "modern_mortgage_lender"
    );
    if (finance && finance.value == "Loan") {
        toggleValidation(lender_company, true);
        if (lender_company && lender_company.value == "Modern Mortgage") {
            console.log(modern_mortgage_lender);
            toggleValidation(modern_mortgage_lender, true);
            $("#additionalFields").find(".additional-field-lender").remove();
        } else if (lender_company && lender_company.value == "Other") {
            var stage = document.getElementById("validationDefault04");
            let disabledText =  stage?.value == "Under Contract" ? "disabled" : "";
            $("#additionalFields").append(`
                    <div class="col-md-6 additional-field-lender ">
                        <label for="lender_company_name" class="form-label nplabelText">Lender Company Name</label>
                        <input type="text" class="form-control npinputinfo ${disabledText} validate"
                            id="lender_company_name" value = "${
                                deal["lender_company_name"]
                                    ? deal["lender_company_name"]
                                    : ""
                            }" required>
                    </div>
                    <div class="col-md-6 additional-field-lender ">
                        <label for="lender_name" class="form-label nplabelText">Lender Name</label>
                        <input type="text" class="form-control ${disabledText} npinputinfo validate"
                            id="lender_name" value = "${
                                deal["lender_name"] ? deal["lender_name"] : ""
                            }" required>
                    </div>
                `);
            toggleValidation(modern_mortgage_lender, false);
        } else {
            $("#additionalFields").find(".additional-field-lender").remove();
        }
    } else {
        toggleValidation(lender_company, false);
        $("#additionalFields").find(".additional-field-lender").remove();
    }
};

window.updateDataDeal = function (dealId, dbDealId) {
    let isValid = true;
    console.log(dealId);
    // Retrieve values from form fields
    var client_name_primary = $("#validationDefault01").val();
    client_name_primary = JSON.parse(client_name_primary);
    var representing = $("#validationDefault02").val();
    var deal_name = $("#validationDefault03").val();
    var stage = $("#validationDefault04").val();
    var sale_price = $("#validationDefault05").val();
    var closing_date = $("#validationDefault06").val();
    var address = $("#validationDefault07").val();
    var city = $("#validationDefault08").val();
    var state = $("#validationDefault09").val();
    var zip = $("#validationDefault10").val();
    var commission = $("#validationDefault11").val();
    var commission_flat_free = $("#commissionflat").val();
    var property_type = $("#validationDefault12").val();
    var ownership_type = $("#validationDefault13").val();
    var potential_gci = $("#validationDefault14").val();
    var pipeline_probability = $("#validationDefault15").val();
    var probable_gci = $("#validationDefault16").val();
    var personal_transaction = $("#flexCheckChecked01").prop("checked");
    var double_ended = $("#flexCheckChecked02").prop("checked");
    var review_gen_opt_out = $("#flexCheckChecked03").prop("checked");
    var deadline_em_opt_out = $("#flexCheckChecked05").prop("checked");
    var status_rpt_opt_out = $("#flexCheckChecked04").prop("checked");
    var tm_preference = $("#tmPreference").val();
    var tm_name = $("#tmName").val();
    tm_name = JSON.parse(tm_name);
    var contact_name = $("#contactNameObject").val();
    contact_name = JSON.parse(contact_name);
    var transaction_owner = $("#transactionOwner").val();
    var lead_agent = $("#leadAgent").val();
    lead_agent = JSON.parse(lead_agent);
    var finance = $("#finance").val();
    var lender_company = $("#lender_company").val();
    var modern_mortgage_lender = $("#modern_mortgage_lender").val();

    if (client_name_primary === "") {
        showToastError("Client Name Primary is required");
        isValid = false;
    }
    if (representing === "") {
        showToastError("Representing is required");
        isValid = false;
    }
    if (deal_name === "") {
        showToastError("Deal Name is required");
        isValid = false;
    }
    if (stage === "") {
        showToastError("Stage is required");
        isValid = false;
    }
    if (sale_price === "") {
        showToastError("Sale Price is required");
        isValid = false;
    }
    if (closing_date === "") {
        showToastError("Closing Date is required");
        isValid = false;
    }
    if (commission === "") {
        showToastError("Commission is required");
        isValid = false;
    }
    if (stage === "Under Contract") {
        if (address === "") {
            showToastError("Address is required for Under Contract stage");
            isValid = false;
        }
        if (city === "") {
            showToastError("City is required for Under Contract stage");
            isValid = false;
        }
        if (state === "") {
            showToastError("State is required for Under Contract stage");
            isValid = false;
        }
        if (zip === "") {
            showToastError("ZIP Code is required for Under Contract stage");
            isValid = false;
        }
        if (property_type === "") {
            showToastError(
                "Property Type is required for Under Contract stage"
            );
            isValid = false;
        }
    }

    if (isValid == true) {
        // Create formData object
        var formData = {
            data: [
                {
                    Client_Name_Primary:
                        (client_name_primary.first_name || "") +
                        " " +
                        (client_name_primary.last_name || ""),
                    Client_Name_Only:
                        (client_name_primary.first_name || "") +
                        " " +
                        (client_name_primary.last_name || "") +
                        " || " +
                        client_name_primary.zoho_contact_id,
                    Representing: representing,
                    Deal_Name: deal_name,
                    Stage: stage,
                    Sale_Price: sale_price,
                    Closing_Date: closing_date,
                    Address: address,
                    City: city,
                    State: state,
                    Zip: zip,
                    Commission: parseInt(commission),
                    Property_Type: property_type,
                    Ownership_Type: ownership_type,
                    Potential_GCI: potential_gci,
                    Pipeline_Probability: pipeline_probability,
                    Pipeline1: probable_gci,
                    Personal_Transaction: personal_transaction,
                    Double_Ended: double_ended,
                    Contact_Name: {
                        Name:
                            (contact_name.first_name ?? "") +
                            " " +
                            (contact_name.last_name ?? ""),
                        id: contact_name.zoho_contact_id,
                    },
                    Review_Gen_Opt_Out: review_gen_opt_out,
                    Commission_Flat_Free: commission_flat_free,
                    TM_Preference: tm_preference,
                    Transaction_Owner: transaction_owner,
                    Contact: {
                        Name:
                            (contact_name.first_name ?? "") +
                            " " +
                            (contact_name.last_name ?? ""),
                        id: contact_name.zoho_contact_id,
                    },
                    Status_pt_out_out: status_rpt_opt_out,
                    Deadline_Emails: deadline_em_opt_out,
                    Financing: finance,
                    Lender_Company: lender_company,
                    Modern_Mortgage_Lender: modern_mortgage_lender,
                },
            ],
        };

        // Add Lead_Agent if lead_agent is defined
        if (lead_agent) {
            formData.data[0].Lead_Agent = {
                id: lead_agent.root_user_id ?? "",
                full_name: lead_agent.name ?? "",
            };
        }

        // Add TM_Name if tm_name is defined
        if (tm_name) {
            formData.data[0].TM_Name = {
                full_name: tm_name.name ?? "",
                id: `${tm_name.root_user_id}` ?? "",
            };
        }

        console.log("formData", formData, dealId);
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
        // Send AJAX request
        $.ajax({
            url: "/pipeline/update/" + dealId,
            type: "PUT",
            contentType: "application/json",
            dataType: "json",
            data: JSON.stringify(formData),
            success: function (response) {
                if (response?.data && response.data[0]?.message) {
                    // Convert message to uppercase and then display
                    const upperCaseMessage =
                        response.data[0].message.toUpperCase();
                    showToast(upperCaseMessage);
                    updateDealInformation(dbDealId);
                    // window.location.reload();
                }
            },
            error: function (xhr, status, error) {
                // Handle error response
                console.error(xhr.responseText);
            },
        });
    }
};

window.getSubmittals = function (dealId) {
    $.ajax({
        url: "/submittal/" + dealId,
        type: "Get",
        success: function (response) {
            // if (response?.data && response.data[0]?.message) {
            //     // Convert message to uppercase and then display
            //     const upperCaseMessage = response.data[0].message.toUpperCase();
            //     showToast(upperCaseMessage);
            //     updateDealInformation(response.data[0])
            //     // window.location.reload();
            // }
            $(".showsubmittal").html(response);
        },
        error: function (xhr, status, error) {
            // Handle error response
            console.error(xhr.responseText);
        },
    });
};

window.formatDate = function (date) {
    const d = new Date(date);
    const year = d.getFullYear();
    const month = String(d.getMonth() + 1).padStart(2, "0");
    const day = String(d.getDate()).padStart(2, "0");
    return `${year}-${month}-${day}`;
};
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
    if (updateElement.querySelector("input")) {
        return; // Exit if input is already present
    }

    // Extract and clean the text value
    var text = updateElement.textContent.trim();
    text = text.replace(/\$\s*/, "").trim(); // Remove dollar sign and extra spaces

    if (field === "closing_date") {
        // Assuming date is defined and in a valid format
        let dateInput = document.getElementById(field + dealID);
        console.log(dateInput, "dateInputksdjfklhs");

        dateInput.addEventListener("blur", function () {
            updateDealData(field, Id, dealID, dateInput.value);
        });
    } else {
        var inputElement = document.createElement("input");
        inputElement.type = "text";
        inputElement.className = "inputDesign";
        inputElement.value = text;
        inputElement.id = "edit" + field + dealID;
        updateElement.innerHTML = "";
        updateElement.appendChild(inputElement);
        inputElement.focus();

        inputElement.addEventListener("blur", function () {
            if (inputElement.value !== text) {
                updateDealData(field, Id, dealID, inputElement.value);
                updateElement.innerHTML = inputElement.value;
            } else {
                updateElement.innerHTML = text;
            }
        });

        inputElement.addEventListener("keydown", function (event) {
            if (event.key === "Enter") {
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
};

window.addEventListener("DOMContentLoaded", function () {
    window.updateDealData = async function (field, id, dealID, value = null) {
        let elementId = await document.getElementById(field + dealID);
        let pipelineprobability = await document.getElementById(
            "pipeline_probability" + dealID
        );
        let probabilityData = {
            Potential: 5,
            "Pre-Active": 20,
            Active: 40,
            "Under Contract": 60,
            "Dead-Lost To Competition": 100,
        };

        if (field === "stage") {
            let stageValue = elementId.value;
            if (probabilityData.hasOwnProperty(stageValue)) {
                let probabilityValue = probabilityData[stageValue];
                pipelineprobability.textContent =
                    probabilityValue.toFixed(2) + "%";
            }
        }
        document.getElementById("loaderOverlay").style.display = "block";
        document.getElementById("loaderfor").style.display = "block";
        let formData = {
            data: [
                {
                    Deal_Name:
                        field == "deal_name"
                            ? elementId?.textContent
                            : undefined,
                    Client_Name_Primary:
                        field == "client_name_primary"
                            ? elementId?.textContent
                            : undefined,
                    Stage: field == "stage" ? value : undefined,
                    // "ABCD": "",
                    Representing: field == "representing" ? value : undefined,
                    Sale_Price:
                        field == "sale_price"
                            ? elementId?.textContent
                            : undefined,
                    Closing_Date:
                        field == "closing_date"
                            ? elementId?.textContent
                                ? elementId?.textContent
                                : elementId.value
                            : undefined,
                    Commission:
                        field == "commission"
                            ? elementId?.textContent
                            : undefined,
                    Pipeline_Probability:
                        field == "pipeline_probability"
                            ? elementId?.textContent ??
                              pipelineprobability?.textContent
                            : undefined,
                },
            ],
            skip_mandatory: true,
        };
        // Iterate through the data array
        formData?.data?.forEach((obj) => {
            // Iterate through the keys of each object
            Object.keys(obj).forEach((key) => {
                // Check if the value is undefined and delete the key
                if (obj[key] === undefined) {
                    delete obj[key];
                }
            });
        });
        //ajax call hitting here
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        $.ajax({
            url: `/pipeline/update/${dealID}`,
            method: "PUT",
            contentType: "application/json",
            dataType: "json",
            data: JSON.stringify(formData),
            success: function (response) {
                console.log("LJHJLDKGFLHDSGFKDHSGF", response);
                document.getElementById("loaderOverlay").style.display = "none";
                document.getElementById("loaderfor").style.display = "none";
                // Handle success response
                if (response?.data[0]?.status == "success") {
                    if (
                        !document
                            .getElementById("savemakeModalId" + dealID)
                            .classList.contains("show")
                    ) {
                        var modalTarget = document.getElementById(
                            "savemakeModalId" + dealID
                        );
                        var update_message = document.getElementById(
                            "updated_message_make"
                        );
                        update_message.textContent = response?.data[0]?.message;
                        // Show the modal
                        $(modalTarget).modal("show");
                        window.location.reload();
                    }
                }
            },
            error: function (xhr, status, error) {
                // Handle error response
                document.getElementById("loaderOverlay").style.display = "none";
                document.getElementById("loaderfor").style.display = "none";
                console.error(xhr.responseText, "errrorroororooro");
            },
        });
    };
});
