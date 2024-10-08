const urlParts = window.location.pathname.split("/"); // Split the URL by '/'
const contactId = urlParts.pop();
window.dealId;
console.log("datatable ContactId and uRl", contactId, urlParts);
function number_format(number, decimals, dec_point, thousands_sep) {
    // Function to format number with commas for thousands and specified decimals
    number = parseFloat(number).toFixed(decimals);
    number = number.replace(".", dec_point);
    var parts = number.toString().split(dec_point);
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousands_sep);
    return parts.join(dec_point);
}
function getColorForStage(stage) {
    switch (stage) {
        case "Potential":
            return "#dfdfdf";
        case "Active":
            return "#afafaf";
        case "Pre-Active":
            return "#cfcfcf";
        case "Under Contract":
            return "#8f8f8f";
        case "Dead-Lost To Competition":
            return "#efefef";
        default:
            return "#6f6f6f";
    }
}

function getTextColorForStage(stage) {
    switch (stage) {
        case "Under Contract":
            return "#fff";
        default:
            return "#000"; // Default text color
    }
}
function formateDate(data) {
    if (!data) return false;

    const dateObj = new Date(data);

    // Extract year, month, and day
    const year = dateObj.getFullYear();
    const month = String(dateObj.getMonth() + 1).padStart(2, "0"); // Months are 0-based
    const day = String(dateObj.getDate()).padStart(2, "0");

    // Format the date to YYYY-MM-DD
    const formattedDate = `${year}-${month}-${day}`;

    return formattedDate;
}

function fetchTasks() {
    $.ajax({
        url: "/pipline-cards",
        success: function (data) {
            $(".pipeline-cards-container").html(data);
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        },
    });
}

var dealDataForsubMittal = null;
//pipeline data table code
var table = $("#datatable_pipe_transaction").DataTable({
    paging: true,
    searching: true,
    processing: true,
    serverSide: true,
    responsive: false,
    columnDefs: [{}],
    columns: [
        {
            data: null,
            title: "Actions",
            render: function (data, type, row) {
                console.log(row, "testrow");
                let lockIcon = "";
                if (data.stage === "Under Contract") {
                    lockIcon = `<i class="fas fa-lock"></i>`;
                } else {
                    lockIcon = `<span class="lock-placeholder"></span>`;
                }
                let submittalSection = "";
                if (
                    !(
                        data.tm_preference === "Non-TM" &&
                        data.representing === "Buyer"
                    )
                ) {
                    if (row?.submittals?.length === 0) {
                        console.log(typeof row, "row is hereeeetexxttt");
                        const rowStr = encodeURIComponent(JSON.stringify(row));
                        submittalSection = `
                            <div style="color:#222;" class="ps-2" id="addSubmittal" data-row="${rowStr}" onclick="showSubmittalFormType(this)">
                                <i class="fa fa-plus fa-lg ppiplinecommonIcon" aria-hidden="true" alt="Split screen icon" title="Add Submittal"></i>
                            </div>
                        `;
                    } else {
                        submittalSection = `
                            <a href="/submittal-view/${row.submittals[0]?.submittalType}/${row?.submittals[0]?.id}" target="_blank">
                                <div style="color:#222;" class="ps-2" id="addSubmittal">
                                <i class="fa fa-eye fa-lg ppiplinecommonIcon" alt="Split screen icon"
                        title="View Submittal" aria-hidden="true"></i>
                                </div>
                            </a>
                        `;
                    }
                }
                if (
                    data.tm_preference === "Non-TM" &&
                    data.representing === "Buyer"
                ) {
                    if (row?.nontms?.length === 0) {
                        submittalSection = `
            <div style="color:#222;" class="ps-2" id="addnon-tm" onclick="addNonTmForIndex('${row?.zoho_deal_id}','${row?.deal_name}')">
                <i class="fa fa-plus fa-lg ppiplinecommonIcon" aria-hidden="true" alt="Split screen icon"
               title="Add Non-Tm"></i>
            </div>
        `;
                    } else {
                        submittalSection = `
                <a href="/nontm-view/${row?.nontms[0]?.id}" target="_blank">
                    <div style="color:#222;" class="ps-2" id="addnon-tm">
                       <i class="fa fa-eye fa-lg ppiplinecommonIcon" alt="Split screen icon"
               title="View Non-Tm" aria-hidden="true"></i>
                    </div>
                </a>
            `;
                    }
                }
                return `<div class="icon-container">
                ${lockIcon}
                <a href="/pipeline-view/${data.id}" target='_blank'>
                    <img src="/images/open.svg" alt="Open icon" class="ppiplinecommonIcon" title="Transaction Details">
                    <span class="tooltiptext"></span>
                </a>
                <img src="/images/splitscreen.svg" alt="Split screen icon"
                class="ppiplinecommonIcon"  title="Add Task" onclick="createTasksForDeal('${
                    data.id
                }','${data.zoho_deal_id}')">
                <span class="tooltiptext"></span>
                <div class="createTaskModal${data.id}"></div>
                <img src="/images/sticky_note.svg" alt="Sticky note icon"
                     class="ppiplinecommonIcon" title="Notes" data-bs-toggle="modal" data-bs-target="#"
                     onclick="fetchNotesForContact('${data.id}','${
                    data.zoho_deal_id
                }','Deals')">
                <span class="tooltiptext"></span>
                ${fetchNotesDeal(data.zoho_deal_id)}
                <img src="/images/noteBtn.svg" alt="Note icon"
                     onclick="createNotesForDeal('${data.id}','${
                    data.zoho_deal_id
                }')"
                     class="ppiplinecommonIcon" data-bs-toggle="modal"
                     data-bs-target="#staticBackdropforNote_${
                         data.id
                     }" title="Add Note">
                <span class="tooltiptext"></span>
                <div class="createNoteModal${data.id}"></div>
                ${submittalSection}
            </div>
            `;
            },
        },
        {
            data: "deal_name",
            title: "Transaction",
            render: function (data, type, row) {
                if (row.stage === "Under Contract") {
                    return `<span>${data || "N/A"}</span>`;
                }
                return `<span class="editable" data-name="deal_name" data-id="${
                    row.id
                }" title="Click to edit">${data || "N/A"}</span>
                    <br><span class="editable fs-6" data-name="address" data-id="${
                        row.id
                    }" title="Click to edit">${
                    row.address || "Address not available"
                }</span>`;
            },
        },
        {
            data: "primary_contact",
            title: "Client Name",
            render: function (data, type, row) {
                console.log("Data", data);
                let jsonString,
                    names = [];

                if (data) {
                    jsonString = data?.replace(/&quot;/g, '"');

                    // Parse the string as JSON
                    data = JSON.parse(jsonString);

                    // Extract names from the data
                    if (Array.isArray(data)) {
                        names = data
                            .filter(
                                (item) =>
                                    item.Primary_Contact &&
                                    item.Primary_Contact.name
                            )
                            .map((item) => item.Primary_Contact.name);
                    }
                }

                // Join names into a single string, separated by commas
                const namesString = names.join(", ") || "N/A";
                return `<span class="primary-contact-names">${namesString}</span>`;
            },
        },
        {
            data: "lead_agent",
            title: "Co-Listing Agent",
            render: function (data, type, row) {
                console.log(data, "sdfsdhfshd");
                return `<span data-name="leadAgent" data-id="${row.id}">${
                    data?.name || "N/A"
                }</span>`;
            },
        },
        {
            data: "stage",
            title: "Status",
            render: function (data, type, row) {
                console.log(data, "sdfsdhfshd");
                if (row.stage === "Under Contract") {
                    return `<span style="color:${getTextColorForStage(
                        data
                    )}; background-color:${getColorForStage(data)}">${
                        data || "N/A"
                    }</span>`;
                }
                return `<span class="editable" data-name="stage" data-representing='${
                    row.representing
                }' style="color:${getTextColorForStage(
                    data
                )}; background-color:${getColorForStage(data)}" data-id="${
                    row.id
                }">${data || "N/A"}</span>`;
            },
        },
        {
            data: "representing",
            title: "Representing",
            render: function (data, type, row) {
                if (row.stage === "Under Contract") {
                    return `<span>${data || "N/A"}</span>`;
                }
                return `<span class="editable" data-name="representing" data-id="${
                    row.id
                }">${data || "N/A"}</span>`;
            },
        },
        {
            data: "sale_price",
            title: "Price",
            render: function (data, type, row) {
                if (row.stage === "Under Contract") {
                    return `<span>${data || "N/A"}</span>`;
                }
                return `<span class="editable" data-name="sale_price" data-id="${
                    row.id
                }">$${number_format(data, 0, ".", ",") || "N/A"}</span>`;
            },
        },
        {
            data: "closing_date",
            title: "Close Date",
            render: function (data, type, row) {
                if (row.stage === "Under Contract") {
                    return `<span>${formateDate(data) || "N/A"}</span>`;
                }
                if (data || row.closing_date === null) {
                    // Return the formatted date
                    return `<span class="editable" data-name="closing_date" data-id="${
                        row.id
                    }">${formateDate(data) || "N/A"}</span>`;
                }
            },
        },
        {
            data: "commission",
            title: "Commission",
            render: function (data, type, row) {
                if (row.stage === "Under Contract") {
                    return `<span>${data || "N/A"}%</span>`;
                }
                return `<span class="editable" data-name="commission" data-id="${
                    row.id
                }">${data || "N/A"}%</span>`;
            },
        },
        {
            data: "potential_gci",
            title: "Potential GCI",
            render: function (data, type, row) {
                return `<span>${data || "N/A"}</span>`;
            },
        },
        {
            data: "pipeline_probability",
            title: "Probability",
            render: function (data, type, row) {
                if (row.stage === "Under Contract") {
                    return `<span>${data || "N/A"}</span>`;
                }
                return `<span>${data || "N/A"}%</span>`;
            },
        },
        {
            data: null,
            title: "Probable GCI",
            render: function (data, type, row) {
                // Calculate probable GCI
                var probableGCI =
                    (row.sale_price ?? 0) *
                    ((row.commission ?? 0) / 100) *
                    ((row.pipeline_probability ?? 0) / 100);
                return `$${number_format(probableGCI, 0, ".", ",")}`; // Format probableGCI as currency
            },
        },
    ],
    ajax: {
        url: "/pipeline_view",
        type: "GET", // or 'POST' depending on your server setup
        data: function (request) {
            request._token = "{{ csrf_token() }}";
            request.perPage = request.length;
            request.page = request.start / request.length + 1;
            var related_to_stage = $("#related_to_stage").val();
            if (related_to_stage) {
                request.stage = related_to_stage;
                request.search = "";
            }
            request.search = request.search.value;
        },
        dataSrc: function (data) {
            return data?.data; // Return the data array or object from your response
        },
    },

    initComplete: function (settings, json) {
        // Function to handle editing mode
        var currentText;

        function formatDate(dateString) {
            if (!dateString) return "";

            var date = new Date(dateString);
            var year = date.getFullYear();
            var month = ("0" + (date.getMonth() + 1)).slice(-2);
            var day = ("0" + date.getDate()).slice(-2);

            return `${year}-${month}-${day}`;
        }

        function enterEditMode(element) {
            if ($(element).hasClass("editing")) {
                return; // Do nothing if already editing
            }

            // Close any other editing inputs
            $("#datatable_pipe_transaction tbody")
                .find("input.edit-input, select.edit-input")
                .each(function () {
                    var newValue = $(this).val();
                    var dataName = $(this).data("name");
                    var dataId = $(this).data("id");
                    $(this).replaceWith(
                        `<span class="editable" data-name="${dataName}" data-id="${dataId}">${newValue}</span>`
                    );
                });

            var currentTextfilter = $(element).text(); // Get the text content of the element
            currentText = currentTextfilter.replace(/\$|%|,|.00/g, ""); // Set currentText when entering edit mode
            var dataName = $(element).data("name");
            var dataId = $(element).data("id");
            console.log("element", element);
            var representing = $(element).data("representing");

            console.log("representing", representing);

            // Replace span with input or select for editing
            if (
                dataName !== "closing_date" &&
                dataName !== "stage" &&
                dataName !== "representing"
            ) {
                $(element)
                    .replaceWith(
                        `<input type="text" class="edit-input form-control" value="${currentText}" data-name="${dataName}" data-id="${dataId}">`
                    )
                    .addClass("editing");
            } else if (dataName === "closing_date") {
                $(element)
                    .replaceWith(
                        `<input type="date" class="edit-input form-control" value="${formatDate(
                            currentText
                        )}" data-name="${dataName}" data-id="${dataId}">`
                    )
                    .addClass("editing");
            } else if (dataName === "stage") {
                // Fetch stage options from backend (example)
                var stageOptions = [
                    "Potential",
                    "Pre-Active",
                    "Under Contract",
                    "Active",
                    "Sold",
                    "Dead-Lost To Competition",
                    "Dead-Contract Terminated",
                ];
                var selectOptions = stageOptions
                    .map((option) => {
                        return `<option value="${option}" ${
                            currentText === option ? "selected" : ""
                        }>${option}</option>`;
                    })
                    .join("");

                $(element)
                    .replaceWith(
                        `<select class="edit-input form-control editable" onchange="handleStageChange(this,${dataId},'${representing}')" data-name="${dataName}" data-id="${dataId}">
                    ${selectOptions}
                </select>`
                    )
                    .addClass("editing");
            } else if (dataName === "representing") {
                // Fetch representing options from backend (example)
                var representingOptions = ["Buyer", "Seller"];
                var selectOptions = representingOptions
                    .map((option) => {
                        return `<option value="${option}" ${
                            currentText === option ? "selected" : ""
                        }>${option}</option>`;
                    })
                    .join("");

                $(element)
                    .replaceWith(
                        `<select class="edit-input form-control editable" data-name="${dataName}" data-id="${dataId}">
                    ${selectOptions}
                </select>`
                    )
                    .addClass("editing");
            }
            // Focus on the input field or select dropdown
            $("#datatable_pipe_transaction tbody")
                .find("input.edit-input, select.edit-input")
                .focus();
        }

        window.handleStageChange = function (
            selectElement,
            dataId,
            representing
        ) {
            var selectedValue = selectElement.value;
            console.log("ONCHANGE DEAL", selectElement, dataId, representing);

            if (selectedValue === "Under Contract") {
                // Open the modal when "Under Contract" is selected
                openContractModal(dataId, representing);
            }
        };

        // Function to handle exiting editing mode
        function exitEditMode(inputElement) {
            var newValue = $(inputElement).val();
            var dataName = $(inputElement).data("name");
            var dataId = $(inputElement).data("id");

            if (newValue === "Under Contract") {
                return;
            }
            // Replace input or select with span
            $(inputElement)
                .replaceWith(
                    `<span class="editable" data-name="${dataName}" data-id="${dataId}">${newValue}</span>`
                )
                .removeClass("editing");

            // Check if the value has changed
            if (newValue !== currentText) {
                $("#datatable_pipe_transaction_processing").css(
                    "display",
                    "block"
                );
                // Example AJAX call (replace with your actual endpoint and data):
                $.ajax({
                    url: "/deals/update/" + dataId,
                    type: "POST",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    data: {
                        id: dataId,
                        field: dataName,
                        value: newValue,
                    },
                    success: function (response) {
                        console.log("Updated successfully:", response);
                        $("#datatable_pipe_transaction_processing").css(
                            "display",
                            "none"
                        );
                        if (response?.message) {
                            showToast(response?.message);
                            $("#datatable_pipe_transaction")
                                .DataTable()
                                .ajax.reload();
                        }
                        fetchTasks();
                    },
                    error: function (error) {
                        console.error("Error updating:", error);
                        $("#datatable_pipe_transaction_processing").css(
                            "display",
                            "none"
                        );
                        showToastError(error?.responseJSON?.error);
                        $("#datatable_pipe_transaction")
                            .DataTable()
                            .ajax.reload();
                    },
                });
            }
        }

        // Click event to enter editing mode
        $("#datatable_pipe_transaction tbody").on(
            "click",
            "span.editable",
            function () {
                enterEditMode(this);
            }
        );

        // Keyup event to exit editing mode on Enter
        $("#datatable_pipe_transaction tbody").on(
            "keyup",
            "input.edit-input",
            function (event) {
                if (event.key === "Enter") {
                    exitEditMode(this);
                }
            }
        );
        // Handle onchange event for select
        $("#datatable_pipe_transaction tbody").on(
            "change",
            "select.edit-input",
            function () {
                exitEditMode(this); // Exit edit mode when a selection is made
            }
        );

        // Blur event to exit editing mode when clicking away
        $("#datatable_pipe_transaction tbody").on(
            "blur",
            "input.edit-input",
            function () {
                exitEditMode(this);
            }
        );
    },
});

window.openContractModal = function (dealId, representing) {
    const modalHTML = `
        <div class="modal fade" id="underContractModal${dealId}" data-bs-backdrop="static"
            data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered deleteModal">
                <div class="modal-content dtaskmodalContent">
                    <div class="modal-header border-0">
                        <p class="modal-title dHeaderText">Required Fields</p>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="underContractContainer${dealId}">
                        <div class="col-md-12">
                            <label for="validationDefault07" class="form-label nplabelText">Address</label>
                            <input type="text" class="form-control npinputinfo validate required-field" id="address">
                            <div id="addressError" class="d-none text-danger">Please fill address</div>
                        </div>
                        <div class="col-md-12">
                            <label for="validationDefault08" class="form-label nplabelText">City</label>
                            <input type="text" class="form-control npinputinfo validate required-field" id="city" required>
                            <div id="cityError" class="d-none text-danger">Please fill city</div>
                        </div>
                        <div class="col-md-12">
                            <label for="validationDefault09" class="form-label nplabelText">State</label>
                            <input type="text" class="form-control npinputinfo validate required-field" id="state" value="">
                            <div id="stateError" class="d-none text-danger">Please fill state</div>
                        </div>
                        <div class="col-md-12">
                            <label for="validationDefault10" class="form-label nplabelText">ZIP</label>
                            <input type="text" class="form-control npinputinfo validate required-field" id="zip" required>
                            <div id="zipError" class="d-none text-danger">Please fill zip</div>
                        </div>
                        <div class="col-md-12">
                            <label for="validationDefault12" class="form-label nplabelText">Property Type</label>
                            <select class="form-select npinputinfo validate required-field" id="property" required>
                                <option selected disabled value="">--None--</option>
                                <option value="Residential">Residential</option>
                                <option value="Land">Land</option>
                                <option value="Farm">Farm</option>
                                <option value="Commercial">Commercial</option>
                                <option value="Lease">Lease</option>
                            </select>
                            <div id="property_typeError" class="d-none text-danger">Please fill property type</div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" onclick="saveUnderConReqField(${dealId},'${representing}')" class="btn btn-dark float-left pt-2">
                                <i class="fas fa-save saveIcon"></i> Save
                            </button>
                            <button type="button" class="btn btn-light float-left pt-2" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Insert modal into the DOM
    document.body.insertAdjacentHTML("beforeend", modalHTML);

    // Append additional fields if representing is "Buyer"
    if (representing === "Buyer") {
        $(`#underContractContainer${dealId} .modal-footer`).before(`
            <div class="col-md-12">
                <label for="finance" class="form-label nplabelText">Financing</label>
                        <select class="form-select npinputinfo validate" id="finance" required onchange="showMoreInputs(this)">
                            <option value="" >--None--</option>
                            <option value="Cash" >Cash</option>
                            <option value="Loan" >Loan
                            </option>
                        </select>
                <div id="financeError" class="d-none text-danger">Please select finance</div>
            </div>
            <div class="col-md-12">
                <label for="validationDefault11" class="form-label nplabelText">Lender Company</label>
                <select class="form-select npinputinfo required-field" id="lender_company" required onchange="showMoreInputs(this)">
                    <option selected disabled value="">--None--</option>
                    <option value="Modern Mortgage">Modern Mortgage</option>
                    <option value="Other">Other</option>
                </select>
                <div id="lender_companyError" class="d-none text-danger">Please fill lender company</div>
            </div>
            <div class="col-md-12">
                <label for="modern_mortgage_lender" class="form-label nplabelText ">Modern Mortgage Lender</label>
                        <select class="form-select npinputinfo" id="modern_mortgage_lender" required >
                            <option value="" >--None--</option>
                            <option value="Joe Biniasz" >Joe Biniasz</option>
                            <option value="Laura Berry" >Laura Berry
                            </option>
                            <option value="Virginia Shank" >Virginia Shank
                            </option>
                        </select>
                <div id="modern_mortgage_lenderError" class="d-none text-danger">Please fill modern mortgage lender</div>
            </div>
            <div class="col-md-12 d-none" id="lender_company_name_div">
                 <label for="lender_company_name" class="form-label nplabelText">Lender Company Name</label>
                        <input type="text" class="form-control npinputinfo validate"
                            id="lender_company_name" value = "" required>
                <div id="lender_company_nameError" class="d-none text-danger">Please fill lender company name</div>
            </div>
            <div class="col-md-12 d-none" id="lender_name_div">
                <label for="lender_name" class="form-label nplabelText">Lender Name</label>
                        <input type="text" class="form-control npinputinfo validate"
                            id="lender_name" value = "" required>
                <div id="lender_nameError" class="d-none text-danger">Please fill lender name</div>
            </div>
        `);
    }

    // Show the modal using Bootstrap's modal method
    const modalElement = new bootstrap.Modal(
        document.getElementById(`underContractModal${dealId}`)
    );
    modalElement.show();

    // Add an event listener to remove the modal from DOM after it is hidden
    document
        .getElementById(`underContractModal${dealId}`)
        .addEventListener("hidden.bs.modal", function (e) {
            e.target.remove();
        });
};

window.showMoreInputs = function (selectedElement) {
    var value = selectedElement.value;
    if (value == "Loan") {
        $("#lender_company").addClass("validate");
    } else {
        $("#lender_company").removeClass("validate");
    }
    if (value == "Modern Mortgage") {
        $("#modern_mortgage_lender").addClass("validate");
        $("#lender_company_name_div").addClass("d-none");
        $("#lender_name_div").addClass("d-none");
    } else if (value == "Other") {
        $("#modern_mortgage_lender").removeClass("validate");
        $("#lender_company_name_div").removeClass("d-none");
        $("#lender_name_div").removeClass("d-none");
    }
    //     //set condtion to add validate class if value ==loan
    // $("#modern_mortgage_lender_div").removeClass("d-none");
};
window.saveUnderConReqField = function (dealId, representing) {
    console.log("call saveUnderConReqField function");

    // Perform validation
    if (!validateRequiredField()) {
        // If validation fails, exit the function
        return;
    }

    var address = $("#address").val();
    var city = $("#city").val();
    var zip = $("#zip").val();
    var property = $("#property").val();
    var state = $("#state").val();
    let formData = {
        data: [
            {
                Address: address,
                City: city,
                Zip: zip,
                Property_Type: property,
                State: state,
                Stage: "Under Contract",
            },
        ],
    };
    if (representing == "Buyer") {
        formData.data[0].Finance = $("#finance").val();
        formData.data[0].Lender_Company = $("#lender_company").val();
        formData.data[0].Modern_Mortgage_Lender = $(
            "#modern_mortgage_lender"
        ).val();
        formData.data[0].Lender_Company_Name = $("#lender_company_name").val();
        formData.data[0].Lender_Name = $("#lender_name").val();
    }

    // Proceed with saving logic if validation passes
    // Add your saving logic here, e.g., collecting form data and sending it to the server
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
                const upperCaseMessage = response.data[0].message.toUpperCase();
                showToast(upperCaseMessage);
                $("#datatable_pipe_transaction").DataTable().ajax.reload();

                // window.location.reload();
            }
        },
        error: function (xhr, status, error) {
            showToastError(xhr?.responseJSON?.error);
            $("#datatable_pipe_transaction").DataTable().ajax.reload();
        },
    });

    console.log("Form is valid. Proceed with saving...");

    // Close the modal
    const modalElement = document.querySelector(".modal.fade.show");
    const bsModal = bootstrap.Modal.getInstance(modalElement);
    bsModal.hide();
};

function validateRequiredField() {
    console.log("call validateRequiredField function");

    let isValid = true;

    // Find the modal by querying the currently opened modal
    const modalElement = document.querySelector(".modal.fade.show");
    const modalBody = modalElement.querySelector(".modal-body");

    // Clear previous error messages
    modalBody
        .querySelectorAll(".error")
        .forEach((field) => field.classList.remove("error"));

    const invalidFields = [];

    // Check required fields within the modal
    modalBody.querySelectorAll(".required-field").forEach((field) => {
        const value = field.value;
        let errorElement = document.getElementById(
            field.previousElementSibling.textContent
                .toLowerCase()
                .replace(" ", "_") + "Error"
        );
        console.log("Error Element", errorElement);

        if (
            value === "" ||
            (field.tagName === "SELECT" && value === "--None--")
        ) {
            isValid = false;

            errorElement.classList.remove("d-none");
        } else {
            errorElement.classList.add("d-none");
        }
    });

    return isValid;
}

//contact role table pipeline
var tableContactRole = $("#contact_role_table_pipeline").DataTable({
    paging: true,
    searching: true,
    processing: true,
    serverSide: true,
    columns: [
        {
            data: "name",
            title: "Name",
            render: function (data, type, row) {
                return `<a href="/contacts-view/${row?.id}" target="_blank"><span class='icon-container' >${data}</span></a>`;
            },
        },
        {
            data: "role",
            title: "Role",
            render: function (data, type, row) {
                return `<span>${data}</span>`;
            },
        },
        {
            data: "phone",
            title: "Phone",
            render: function (data, type, row) {
                return `<span>${data}</span>`;
            },
        },
        {
            data: "email",
            title: "Email",
            render: function (data, type, row) {
                return `<span>${data}</span>`;
            },
        },
    ],
    ajax: {
        url: `/get/deal/contact/role/${contactId}`, // Ensure this URL is correct
        type: "GET", // or 'POST' depending on your server setup
        data: function (request) {
            request._token = "{{ csrf_token() }}";
            request.perPage = request.length;
            request.page = request.start / request.length + 1;
            request.search = request.search.value;
        },
        dataSrc: function (data) {
            return data?.data; // Return the data array or object from your response
        },
    },
});

//submittal table pipeline

var subbmittalPipelineTable = $("#submittal_table_pipeline").DataTable({
    paging: true,
    searching: true,
    processing: true,
    serverSide: true,
    columns: [
        {
            data: "submittalName",
            title: "Submittal Name",
            render: function (data, type, row) {
                return `<span class="editable" data-name="submittalName" data-id="${
                    row.id
                }">${data || "N/A"}</span>`;
            },
        },
        {
            data: "submittalType",
            title: "Submittal Type",
            render: function (data, type, row) {
                return `<span class="editable" data-name="submittalType" data-id="${
                    row.id
                }">${data || "N/A"}</span>`;
            },
        },
        {
            data: "user_data.name",
            title: "Owner",
            render: function (data, type, row) {
                return `<span class="editable" data-name="phone" data-id="${
                    row.id
                }">${data || "N/A"}</span>`;
            },
        },
        {
            data: "created_at",
            title: "Created Time",
            render: function (data, type, row) {
                return `<span class="editable" data-name="created_at" data-id="${
                    row.id
                }">${formateDate(data) || "N/A"}</span>`;
            },
        },
    ],

    ajax: {
        url: "/submittal/" + contactId, // Ensure this URL is correct
        type: "GET", // or 'POST' depending on your server setup
        data: function (request) {
            request._token = "{{ csrf_token() }}";
            request.perPage = request.length;
            (request.stage = $("#related_to_stage").val()),
                (request.tab = "In Progress");
            request.page = request.start / request.length + 1;
            request.search = request.search.value;
        },
        dataSrc: function (data) {
            console.log(data, "data is hreeeee");
            return data?.data; // Return the data array or object from your response
        },
    },
});

//non-TM table pipeline
var subbmittalPipelineTable = $("#nonTm_table_pipeline").DataTable({
    paging: true,
    searching: true,
    processing: true,
    serverSide: true,
    columns: [
        {
            data: "name",
            title: "Number",
            render: function (data, type, row) {
                return `<span class="editable" data-name="submittalName" data-id="${row.id}">${data}</span>`;
            },
        },
        {
            data: "closed_date",
            title: "Close Date",
            render: function (data, type, row) {
                return `<span class="editable" data-name="submittalType" data-id="${row.id}">${data}</span>`;
            },
        },
        {
            data: "created_at",
            title: "Created Time",
            render: function (data, type, row) {
                console.log(data, "shdfhsdhf");
                return `<span class="editable" data-name="phone" data-id="${
                    row.id
                }">${formateDate(data) || "N/A"}</span>`;
            },
        },
        {
            data: null,
            title: "",
            render: function (data, type, row) {
                return `<a class="col-md-1" href="/nontm-view/${row.id}"><div ><img
                        src="/images/open.svg" alt="Open icon" class="ppiplinecommonIcon"
                        title="Non-TM Details"></div></a> 
                `;
            },
        },
    ],

    ajax: {
        url: "/nontms/" + contactId, // Ensure this URL is correct
        type: "GET", // or 'POST' depending on your server setup
        data: function (request) {
            request._token = "{{ csrf_token() }}";
            request.perPage = request.length;
            (request.stage = $("#related_to_stage").val()),
                (request.tab = "In Progress");
            request.page = request.start / request.length + 1;
            request.search = request.search.value;
        },
        dataSrc: function (data) {
            console.log("data", data);
            return data?.data; // Return the data array or object from your response
        },
    },
});

$("#pipelineSearch").on("keyup", function () {
    table.search(this.value).draw();
});
$("#related_to_stage").on("change", function () {
    table.search(this.value).draw();
});
$("#Reset_All").on("click", function () {
    $("#pipelineSearch").val("");
    $("#related_to_stage").val("");
    table.search("").draw();
});

window.getStageData = function () {
    // Make AJAX call
    $.ajax({
        url: "/getStage",
        method: "GET",
        dataType: "html",
        success: function (data) {
            $(".dashboard-cards").html(data);
        },
        error: function (xhr, status, error) {
            // Handle errors
            console.error("Error:", error);
        },
    });
};
window.scrollDown = function () {
    const btnBadDates = document.getElementById("btnBadDates");
    if (btnBadDates) {
        btnBadDates.addEventListener("click", function () {
            const element = document.getElementById("badDates");
            if (element) {
                const offset = 100; // Adjust this value as needed
                const elementPosition = element.getBoundingClientRect().top;
                const offsetPosition =
                    elementPosition + window.pageYOffset - offset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: "smooth",
                });
            } else {
                console.log("No bad dates element found.");
            }
        });
    } else {
        console.log("No btnBadDates element found.");
    }
};
//transaction for dashboard
var tableDashboard = $("#datatable_transaction").DataTable({
    paging: true,
    searching: true,
    processing: true,
    serverSide: true,
    responsive: false,
    columnDefs: [{}],
    columns: [
        {
            data: "deal_name",
            title: "Transaction",
            render: function (data, type, row) {
                return `<a href="/pipeline-view/${row?.id}" target="_blank"><span class='icon-container max-width-500' >${data}</span></a>`;
            },
        },
        {
            data: "primary_contact",
            title: "Client Name",
            render: function (data, type, row) {
                console.log("Data", data);
                let jsonString, name;
                if (data) {
                    jsonString = data?.replace(/&quot;/g, '"');

                    // Parse the string as JSON
                    data = JSON.parse(jsonString);
                    name =
                        (data[0] &&
                            data[0].Primary_Contact &&
                            data[0].Primary_Contact.name) ??
                        "";
                }
                return `<span >${name || "N/A"}</span>`;
            },
        },
        {
            data: "stage",
            title: "Status",
            render: function (data, type, row) {
                return `<span>${data}</span>`;
            },
        },
        {
            data: "representing",
            title: "Representing",
            render: function (data, type, row) {
                return `<span >${data}</span>`;
            },
        },
        {
            data: "sale_price",
            title: "Price",
            render: function (data, type, row) {
                console.log(data, "datattas");
                return `<span >$${number_format(data, 0, ".", ",")}</span>`;
            },
        },
        {
            data: "closing_date",
            title: "Close Date",
            render: function (data, type, row) {
                return `<span class="editable badDateInput" data-name="closing_date" data-id="${
                    row.id
                }">${formateDate(data) || "N/A"}</span>`;
            },
        },
        {
            data: "commission",
            title: "Commission",
            render: function (data, type, row) {
                return `<span >${data}%</span>`;
            },
        },
        {
            data: "potential_gci",
            title: "Potential GCI",
            render: function (data, type, row) {
                return `<span >${data}</span>`;
            },
        },
        {
            data: "pipeline_probability",
            title: "Probability",
            render: function (data, type, row) {
                return `<span >${data}%</span>`;
            },
        },
        {
            data: null,
            title: "Probable GCI",
            render: function (data, type, row) {
                // Calculate probable GCI
                var probableGCI =
                    (row.sale_price ?? 0) *
                    ((row.commission ?? 0) / 100) *
                    ((row.pipeline_probability ?? 0) / 100);
                return `$${number_format(probableGCI, 0, ".", ",")}`; // Format probableGCI as currency
            },
        },
    ],
    ajax: {
        url: "/needsNewdate", // Ensure this URL is correct
        type: "GET", // or 'POST' depending on your server setup
        data: function (request) {
            request._token = "{{ csrf_token() }}";
            request.perPage = request.length;
            (request.stage = $("#related_to_stage").val()),
                (request.page = request.start / request.length + 1);
            request.search = request.search.value;
        },
        dataSrc: function (data) {
            if (data?.data?.length >= 0) {
                $(".bad_date_count").text(data.data.length + " Bad Dates!");
            } else {
                $(".bad_date_success").html(
                    "No Bad Dates, <strong>Great Job!</strong>!"
                );
            }
            if (data?.data && data.data.length > 0) {
                $(".bad_dates_top").html(
                    "You have " +
                        data.data.length +
                        " Bad Dates! &nbsp <button onclick=scrollDown() class='btn btn-dark btn-small' id='btnBadDates'>FIX NOW</button>"
                );
            } else {
                $(".bad_dates_top").html("");
            }

            return data?.data; // Return the data array or object from your response
        },
    },
    initComplete: function () {
        // Function to handle editing mode
        var currentText;

        function formatDate(dateString) {
            if (!dateString) return "";

            var date = new Date(dateString);
            var year = date.getFullYear();
            var month = ("0" + (date.getMonth() + 1)).slice(-2);
            var day = ("0" + date.getDate()).slice(-2);

            return `${year}-${month}-${day}`;
        }

        function enterEditMode(element) {
            if ($(element).hasClass("editing")) {
                return; // Do nothing if already editing
            }

            // Close any other editing inputs
            $("#datatable_transaction tbody")
                .find("input.edit-input, select.edit-input")
                .each(function () {
                    var newValue = $(this).val();
                    var dataName = $(this).data("name");
                    var dataId = $(this).data("id");
                    $(this).replaceWith(
                        `<span class="editable" data-name="${dataName}" data-id="${dataId}">${newValue}</span>`
                    );
                });

            currentText = $(element).text(); // Set currentText when entering edit mode
            var dataName = $(element).data("name");
            var dataId = $(element).data("id");

            // Replace span with input or select for editing
            if (
                dataName !== "closing_date" &&
                dataName !== "stage" &&
                dataName !== "representing"
            ) {
                $(element)
                    .replaceWith(
                        `<input type="text" class="edit-input form-control" value="${currentText}" data-name="${dataName}" data-id="${dataId}">`
                    )
                    .addClass("editing");
            } else if (dataName === "closing_date") {
                $(element)
                    .replaceWith(
                        `<input type="date" class="edit-input form-control badDateInput" value="${formatDate(
                            currentText
                        )}" data-name="${dataName}" data-id="${dataId}">`
                    )
                    .addClass("editing");
            } else if (dataName === "stage") {
                // Fetch stage options from backend (example)
                var stageOptions = [
                    "Potential",
                    "Pre-Active",
                    "Under Contract",
                    "Active",
                ];
                var selectOptions = stageOptions
                    .map((option) => {
                        return `<option value="${option}" ${
                            currentText === option ? "selected" : ""
                        }>${option}</option>`;
                    })
                    .join("");

                $(element)
                    .replaceWith(
                        `<select class="edit-input form-control editable" data-name="${dataName}" data-id="${dataId}">
                    ${selectOptions}
                </select>`
                    )
                    .addClass("editing");
            } else if (dataName === "representing") {
                // Fetch representing options from backend (example)
                var representingOptions = ["Buyer", "Seller"];
                var selectOptions = representingOptions
                    .map((option) => {
                        return `<option value="${option}" ${
                            currentText === option ? "selected" : ""
                        }>${option}</option>`;
                    })
                    .join("");

                $(element)
                    .replaceWith(
                        `<select class="edit-input form-control editable" data-name="${dataName}" data-id="${dataId}">
                    ${selectOptions}
                </select>`
                    )
                    .addClass("editing");
            }

            // Focus on the input field or select dropdown
            $("#datatable_transaction tbody")
                .find("input.edit-input, select.edit-input")
                .focus();
        }

        // Function to handle exiting editing mode
        function exitEditMode(inputElement) {
            console.log("call exitEditMode function");

            var newValue = $(inputElement).val();
            var dataName = $(inputElement).data("name");
            var dataId = $(inputElement).data("id");

            // Replace input or select with span
            $(inputElement)
                .replaceWith(
                    `<span class="editable" data-name="${dataName}" data-id="${dataId}">${newValue}</span>`
                )
                .removeClass("editing");

            // Check if the value has changed
            if (newValue !== currentText) {
                $("#datatable_transaction_processing").css("display", "block");
                // Example AJAX call (replace with your actual endpoint and data):
                $.ajax({
                    url: "/deals/update/" + dataId,
                    type: "POST",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    data: {
                        id: dataId,
                        field: dataName,
                        value: newValue,
                    },
                    success: function (response) {
                        console.log("Updated successfully:", response);
                        if (response?.message) {
                            showToast(response?.message);
                            $("#datatable_transaction_processing").css(
                                "display",
                                "none"
                            );
                            $("#datatable_transaction")
                                .DataTable()
                                .ajax.reload();
                            getStageData();
                        }
                    },
                    error: function (error) {
                        console.error("Error updating:", error);
                        showToastError(error?.responseJSON?.error);
                        $("#datatable_transaction_processing").css(
                            "display",
                            "none"
                        );
                        $("#datatable_transaction").DataTable().ajax.reload();
                    },
                });
            }
        }

        // Click event to enter editing mode
        $("#datatable_transaction tbody").on(
            "click",
            "span.editable",
            function () {
                enterEditMode(this);
            }
        );

        // Keyup event to exit editing mode on Enter
        $("#datatable_transaction tbody").on(
            "keyup",
            "input.edit-input",
            function (event) {
                if (event.key === "Enter") {
                    exitEditMode(this);
                }
            }
        );
        // Handle onchange event for select
        $("#datatable_transaction tbody").on(
            "change",
            "select.edit-input",
            function () {
                exitEditMode(this); // Exit edit mode when a selection is made
            }
        );

        // Blur event to exit editing mode when clicking away
        $("#datatable_transaction tbody").on(
            "blur",
            "input.edit-input",
            function () {
                exitEditMode(this);
            }
        );
    },
});

var tableTasks = $("#datatable_tasks").DataTable({
    paging: true,
    searching: true,
    processing: true,
    serverSide: true,
    responsive: false,
    columns: [
        {
            data: null,
            title: '<input type="checkbox" id="checkAll" onchange="toggleCheckAll(this)" />',
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
                return `<input  type="checkbox"
                                    class="task_checkbox" id="${row.zoho_task_id}" />`;
            },
        },
        {
            data: "subject",
            title: "Subject",
            render: function (data, type, row) {
                if (row?.status === "Completed") {
                    return `<span >${data}</span>`;
                }
                return `<span class="editable" data-name="subject" data-id="${row.id}">${data}</span>`;
            },
        },
        {
            data: "detail",
            title: "Details",
            render: function (data, type, row) {
                if (row?.status === "Completed") {
                    return `<span >${data ?? "N/A"}</span>`;
                }
                return `<span class="editable" data-name="detail" data-id="${
                    row.id
                }">${data ?? "N/A"}</span>`;
            },
        },
        {
            data: "related_to",
            title: "Related To",
            render: function (data, type, row) {
                if (row?.status === "Completed") {
                    if (row.related_to === "Contacts") {
                        return `<select class="form-select">
                        <option value="${
                            row.contact_data?.zoho_contact_id
                        }" selected>
                            ${row.contact_data?.first_name ?? ""} ${
                            row.contact_data?.last_name ?? "General"
                        }
                        </option>
                    </select>`;
                    } else if (row.related_to === "Deals") {
                        return `<select class="form-select">
                        <option value="${row.dealdata.zohodealid}" selected>
                            ${row.dealdata.dealname ?? "General"}
                        </option>
                    </select>`;
                    } else {
                        return `<select class="form-select" >
                        <option value="" selected>General</option>
                    </select>`;
                    }
                } else {
                    if (row.related_to === "Contacts") {
                        return `<select class="dealTaskSelect edit-select" " data-name="related_to" data-id="${
                            row.id
                        }">
                        <option value="${
                            row.contact_data?.zoho_contact_id
                        }" selected>
                            ${row.contact_data?.first_name ?? ""} ${
                            row.contact_data?.last_name ?? "General"
                        }
                        </option>
                    </select>`;
                    } else if (row.related_to === "Deals") {
                        return `<select class="dealTaskSelect edit-select"  data-name="related_to" data-id="${
                            row.id
                        }">
                        <option value="${row.dealdata.zohodealid}" selected>
                            ${row.dealdata.dealname ?? "General"}
                        </option>
                    </select>`;
                    } else {
                        return `<select class="dealTaskSelect" data-module="General edit-select" data-name="related_to" data-id="${row.id}">
                        <option value="" selected>General</option>
                    </select>`;
                    }
                }
            },
        },
        {
            data: "due_date",
            title: "Due Date",
            render: function (data, type, row) {
                if (row?.status === "Completed") {
                    return `<span >${formateDate(data) || "N/A"}</span>`;
                }
                return `<span class="editable" data-name="due_date" data-id="${
                    row.id
                }">${formateDate(data) || "N/A"}</span>`;
            },
        },
        {
            data: null,
            title: "Options",
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
                if (row.status !== "Completed") {
                    return `<div class="d-flex btn-save-del">
                                    <div class="input-group-text dFont800 dFont11 text-white justify-content-center align-items-baseline savebtn"
                                        id="update_changes" onclick="doneTask(this)" data-name="done_task" data-id="${row.id}"
                                        >
                                        <i class="fas fa-hdd plusicon"></i>
                                        Done
                                    </div>
                                    <div class="input-group-text dFont800 dFont11 text-white justify-content-center align-items-baseline deletebtn"
                                        id="btnGroupAddon"  onclick="deleteTaskContact(this)"  data-bs-toggle="modal" data-id="${row.zoho_task_id}"
                                        data-bs-target="#deleteModalId${row.zoho_task_id}">
                                        <i class="fas fa-trash-alt plusicon"></i>
                                        Delete
                                    </div>
                                </div>`;
                }
                return "";
            },
        },
    ],
    ajax: {
        url: "/task/for/contact/" + contactId, // Ensure this URL is correct
        type: "GET", // or 'POST' depending on your server setup
        data: function (request) {
            request._token = "{{ csrf_token() }}";
            request.perPage = request.length;
            (request.stage = $("#related_to_stage").val()),
                (request.tab = "In Progress");
            request.page = request.start / request.length + 1;
            request.search = request.search.value;
            console.log(request, "skdhfkshdfkhsdkfskddfjhsk");
        },
        dataSrc: function (data) {
            console.log(data, "data is hreeeee");
            return data?.data; // Return the data array or object from your response
        },
    },
    initComplete: function () {
        // Function to handle editing mode
        var currentText;

        function formatDate(dateString) {
            if (!dateString) return "";

            var date = new Date(dateString);
            var year = date.getFullYear();
            var month = ("0" + (date.getMonth() + 1)).slice(-2);
            var day = ("0" + date.getDate()).slice(-2);

            return `${year}-${month}-${day}`;
        }

        function enterEditMode(element) {
            if ($(element).hasClass("editing")) {
                return; // Do nothing if already editing
            }

            // Close any other editing inputs
            $("#datatable_tasks tbody")
                .find("input.edit-input, select.dealTaskSelect")
                .each(function () {
                    var newValue = $(this).val();
                    var dataName = $(this).data("name");
                    var dataId = $(this).data("id");
                    if (dataName !== "related_to" && dataName !== "done_task") {
                        $(this).replaceWith(
                            `<span class="editable" data-name="${dataName}" data-id="${dataId}">${newValue}</span>`
                        );
                    }
                });

            currentText = $(element).text(); // Set currentText when entering edit mode
            var dataName = $(element).data("name");
            var dataId = $(element).data("id");

            // Replace span with input or select for editing
            if (
                dataName !== "due_date" &&
                dataName !== "related_to" &&
                dataName !== "stage" &&
                dataName !== "representing" &&
                dataName !== "done_task"
            ) {
                $(element)
                    .replaceWith(
                        `<input type="text" class="edit-input form-control" value="${currentText}" data-name="${dataName}" data-id="${dataId}">`
                    )
                    .addClass("editing");
            } else if (dataName === "due_date") {
                $(element)
                    .replaceWith(
                        `<input type="date" class="edit-input form-control" value="${formatDate(
                            currentText
                        )}" data-name="${dataName}" data-id="${dataId}">`
                    )
                    .addClass("editing");
            } else if (dataName === "related_to") {
                console.log("eyyeyeyyeyeyey");
                // Fetch stge options from backend (example)

                $(element)
                    .replaceWith(
                        `<select class="edit-input form-control editable" data-name="${dataName}" data-id="${dataId}">
                    ${selectOptions}
                </select>`
                    )
                    .addClass("editing");
            }

            // Focus on the input field or select dropdown
            $("#datatable_tasks tbody")
                .find("input.edit-input, select.edit-input")
                .focus();
        }

        window.doneTask = function (e) {
            exitEditMode(e);
        };

        window.deleteTaskContact = function (e) {
            let dataid = $(e).data("id");
            window.deleteTask(dataid);
        };

        // Function to handle exiting editing mode
        function exitEditMode(inputElement) {
            var newValue = $(inputElement).val();
            var dataName = $(inputElement).data("name");
            var dataId = $(inputElement).data("id");
            var datamodule = $(inputElement).data("module");
            console.log(datamodule, "sdhfsdfg");
            if (dataName !== "related_to" && dataName !== "done_task") {
                // Replace input or select with span
                $(inputElement)
                    .replaceWith(
                        `<span class="editable" data-name="${dataName}" data-id="${dataId}">${newValue}</span>`
                    )
                    .removeClass("editing");
            }

            // Check if the value has changed
            if (newValue !== currentText || dataName === "done_task") {
                // Example AJAX call (replace with your actual endpoint and data):
                $.ajax({
                    url: "/update-task-contact/" + dataId,
                    type: "POST",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    data: {
                        id: dataId,
                        field: dataName,
                        value: newValue,
                        module: datamodule ?? "",
                    },
                    success: function (response) {
                        console.log("Updated successfully:", response);
                        if (response?.message) {
                            showToast(response?.message);
                            $("#datatable_tasks").DataTable().ajax.reload();
                        }
                    },
                    error: function (error) {
                        console.error("Error updating:", error);
                        showToastError(error?.responseJSON?.error);
                        $("#datatable_tasks").DataTable().ajax.reload();
                    },
                });
            }
        }

        // Click event to enter editing mode
        $("#datatable_tasks tbody").on("click", "span.editable", function () {
            enterEditMode(this);
        });
        $(document).on("change", "#checkAll", function () {
            $(".task_checkbox").prop("checked", $(this).prop("checked"));
        });

        $(".taskModalSaveBtn").on("click", function () {
            console.log("testshddhfshdf");
        });

        $(".nav-link.dtabsbtn").on("click", function () {
            var tab = $(this).attr("data-tab");
            tableTasks.search(tab).draw();
        });

        // Keyup event to exit editing mode on Enter
        $("#datatable_tasks tbody").on(
            "keyup",
            "input.edit-input",
            function (event) {
                if (event.key === "Enter") {
                    exitEditMode(this);
                }
            }
        );
        // Handle onchange event for select
        $("#datatable_tasks tbody").on(
            "change",
            "select.edit-select",
            function () {
                exitEditMode(this); // Exit edit mode when a selection is made
            }
        );

        // Blur event to exit editing mode when clicking away
        $("#datatable_tasks tbody").on("blur", "input.edit-input", function () {
            exitEditMode(this);
        });
    },
});

tableTasks.on("draw.dt", function () {
    let dealTask = $(".dealTaskSelect");
    window.showDropdownForId("", dealTask);
    let selectglobal = $("#global-search");
    window.showDropdown("global-search", selectglobal);
});

var tableTaskspipe = $("#datatable_tasks1").DataTable({
    paging: true,
    searching: true,
    processing: true,
    serverSide: true,
    responsive: false,
    columns: [
        {
            data: null,
            title: '<input type="checkbox" id="checkAll" onchange="toggleCheckAll(this)" />',
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
                return `<input  type="checkbox"
                                    class="task_checkbox" id="${row.zoho_task_id}" />`;
            },
        },
        {
            data: "subject",
            title: "Subject",
            render: function (data, type, row) {
                if (row?.status === "Completed") {
                    return `<span >${data}</span>`;
                }
                return `<span class="editable" data-name="subject" data-id="${row.id}">${data}</span>`;
            },
        },
        {
            data: "detail",
            title: "Details",
            render: function (data, type, row) {
                if (row?.status === "Completed") {
                    return `<span >${data ?? "N/A"}</span>`;
                }
                return `<span class="editable" data-name="detail" data-id="${
                    row.id
                }">${data ?? "N/A"}</span>`;
            },
        },
        {
            data: "related_to",
            title: "Related To",
            render: function (data, type, row) {
                if (row?.status === "Completed") {
                    if (row.related_to === "Contacts") {
                        return `<select class="form-select">
                        <option value="${
                            row.contact_data?.zoho_contact_id
                        }" selected>
                            ${row.contact_data?.first_name ?? ""} ${
                            row.contact_data?.last_name ?? "General"
                        }
                        </option>
                    </select>`;
                    } else if (row.related_to === "Deals") {
                        return `<select class="form-select" >
                        <option value="${row.deal_data.zoho_deal_id}" selected>
                            ${row.deal_data.deal_name ?? "General"}
                        </option>
                    </select>`;
                    } else {
                        return `<select class="form-select" data-module="General">
                        <option value="" selected>General</option>
                    </select>`;
                    }
                } else {
                    if (row.related_to === "Contacts") {
                        return `<select class="dealSelectPipe edit-select_pipe" " data-name="related_to" data-id="${
                            row.id
                        }">
                        <option value="${
                            row.contact_data?.zoho_contact_id
                        }" selected>
                            ${row.contact_data?.first_name ?? ""} ${
                            row.contact_data?.last_name ?? "General"
                        }
                        </option>
                    </select>`;
                    } else if (row.related_to === "Deals") {
                        return `<select class="dealSelectPipe edit-select_pipe"  data-name="related_to" data-id="${
                            row.id
                        }">
                        <option value="${row.deal_data.zoho_deal_id}" selected>
                            ${row.deal_data.deal_name ?? "General"}
                        </option>
                    </select>`;
                    } else {
                        return `<select class="dealSelectPipe edit-select_pipe" data-module="General" data-name="related_to" data-id="${row.id}">
                        <option value="" selected>General</option>
                    </select>`;
                    }
                }
            },
        },
        {
            data: "due_date",
            title: "Due Date",
            render: function (data, type, row) {
                if (row?.status === "Completed") {
                    return `<span>${formateDate(data) || "N/A"}</span>`;
                }
                console.log(data, "shdfhsdhf");
                return `<span class="editable" data-name="due_date" data-id="${
                    row.id
                }">${formateDate(data) || "N/A"}</span>`;
            },
        },
        {
            data: null,
            title: "Options",
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
                if (row.status !== "Completed") {
                    return `<div class="d-flex btn-save-del">
                                    <div class="input-group-text dFont800 dFont11 text-white justify-content-center align-items-baseline savebtn"
                                        id="update_changes_pipe" onclick="doneTaskpipe(this)" data-name="done_task" data-id="${row.id}"
                                        >
                                        <i class="fas fa-hdd plusicon"></i>
                                        Done
                                    </div>
                                    <div class="input-group-text dFont800 dFont11 text-white justify-content-center align-items-baseline deletebtn"
                                        id="delete_task_pipe" onclick="deleteTaskpipe(this)" data-id="${row.zoho_task_id}" data-bs-toggle="modal"
                                        data-bs-target="#deleteModalId${row.zoho_task_id}">
                                        <i class="fas fa-trash-alt plusicon"></i>
                                        Delete
                                    </div>
        
                                </div>`;
                }
                return "";
            },
        },
    ],

    ajax: {
        url: "/task/for/pipe/" + contactId, // Ensure this URL is correct
        type: "GET", // or 'POST' depending on your server setup
        data: function (request) {
            request._token = "{{ csrf_token() }}";
            request.perPage = request.length;
            (request.stage = $("#related_to_stage").val()),
                (request.tab = "In Progress");
            request.page = request.start / request.length + 1;
            request.search = request.search.value;
            console.log(request, "skdhfkshdfkhsdkfskddfjhsk");
        },
        dataSrc: function (data) {
            console.log(data, "data is hreeeee");
            return data?.data; // Return the data array or object from your response
        },
    },
    initComplete: function () {
        // Function to handle editing mode
        var currentText;

        function formatDate(dateString) {
            if (!dateString) return "";

            var date = new Date(dateString);
            var year = date.getFullYear();
            var month = ("0" + (date.getMonth() + 1)).slice(-2);
            var day = ("0" + date.getDate()).slice(-2);

            return `${year}-${month}-${day}`;
        }

        window.doneTaskpipe = function (e) {
            exitEditMode(e);
        };

        window.deleteTaskpipe = function (e) {
            let dataid = $(e).data("id");
            window.deleteTask(dataid);
        };

        function enterEditMode(element) {
            if ($(element).hasClass("editing")) {
                return; // Do nothing if already editing
            }

            // Close any other editing inputs
            $("#datatable_tasks1 tbody")
                .find("input.edit-input, select.dealSelectPipe")
                .each(function () {
                    var newValue = $(this).val();
                    var dataName = $(this).data("name");
                    var dataId = $(this).data("id");
                    if (dataName !== "related_to" && dataName !== "done_task") {
                        $(this).replaceWith(
                            `<span class="editable" data-name="${dataName}" data-id="${dataId}">${newValue}</span>`
                        );
                    }
                });

            currentText = $(element).text(); // Set currentText when entering edit mode
            var dataName = $(element).data("name");
            var dataId = $(element).data("id");

            // Replace span with input or select for editing
            if (
                dataName !== "due_date" &&
                dataName !== "related_to" &&
                dataName !== "stage" &&
                dataName !== "representing" &&
                dataName !== "done_task"
            ) {
                $(element)
                    .replaceWith(
                        `<input type="text" class="edit-input form-control" value="${currentText}" data-name="${dataName}" data-id="${dataId}">`
                    )
                    .addClass("editing");
            } else if (dataName === "due_date") {
                $(element)
                    .replaceWith(
                        `<input type="date" class="edit-input form-control" value="${formatDate(
                            currentText
                        )}" data-name="${dataName}" data-id="${dataId}">`
                    )
                    .addClass("editing");
            } else if (dataName === "related_to") {
                console.log("eyyeyeyyeyeyey");
                // Fetch stge options from backend (example)

                $(element)
                    .replaceWith(
                        `<select class="edit-input form-control editable" data-name="${dataName}" data-id="${dataId}">
                    ${selectOptions}
                    </select>`
                    )
                    .addClass("editing");
            }

            // Focus on the input field or select dropdown
            $("#datatable_tasks1 tbody")
                .find("input.edit-input, select.edit-input")
                .focus();
        }

        // Function to handle exiting editing mode
        function exitEditMode(inputElement) {
            var newValue = $(inputElement).val();
            var dataName = $(inputElement).data("name");
            var dataId = $(inputElement).data("id");
            var datamodule = $(inputElement).data("module");
            console.log(datamodule, "sdhfsdfg");
            if (dataName !== "related_to" && dataName !== "done_task") {
                // Replace input or select with span
                $(inputElement)
                    .replaceWith(
                        `<span class="editable" data-name="${dataName}" data-id="${dataId}">${newValue}</span>`
                    )
                    .removeClass("editing");
            }

            // Check if the value has changed
            if (newValue !== currentText || dataName === "done_task") {
                // Example AJAX call (replace with your actual endpoint and data):
                $.ajax({
                    url: "/update-task-contact/" + dataId,
                    type: "POST",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    data: {
                        id: dataId,
                        field: dataName,
                        value: newValue,
                        module: datamodule ?? "",
                    },
                    success: function (response) {
                        console.log("Updated successfully:", response);
                        if (response?.message) {
                            showToast(response?.message);
                            $("#datatable_tasks1").DataTable().ajax.reload();
                        }
                    },
                    error: function (error) {
                        console.error("Error updating:", error);
                        showToastError(error?.responseJSON?.error);
                        $("#datatable_tasks1").DataTable().ajax.reload();
                    },
                });
            }
        }

        // Click event to enter editing mode
        $("#datatable_tasks1 tbody").on("click", "span.editable", function () {
            enterEditMode(this);
        });
        $(document).on("change", "#checkAll", function () {
            $(".task_checkbox").prop("checked", $(this).prop("checked"));
        });

        $("#update_changes_pipe").on("click", function () {
            console.log("hello testingggg");
            exitEditMode(this);
        });

        $(".taskModalSaveBtn").on("click", function () {
            console.log("testshddhfshdf");
        });

        $(".nav-link.dtabsbtn").on("click", function () {
            var tab = $(this).attr("data-tab");
            // console.log(tab, 'tabbb')
            tableTaskspipe.search(tab).draw();
        });

        // Keyup event to exit editing mode on Enter
        $("#datatable_tasks1 tbody").on(
            "keyup",
            "input.edit-input",
            function (event) {
                if (event.key === "Enter") {
                    exitEditMode(this);
                }
            }
        );
        // Handle onchange event for select
        $("#datatable_tasks1 tbody").on(
            "change",
            "select.edit-select_pipe",
            function () {
                console.log("yessdfhskdhfskdhfksjfh");
                exitEditMode(this); // Exit edit mode when a selection is made
            }
        );

        // Blur event to exit editing mode when clicking away
        $("#datatable_tasks1 tbody").on(
            "blur",
            "input.edit-input",
            function () {
                exitEditMode(this);
            }
        );
    },
});

tableTaskspipe.on("draw.dt", function () {
    let dealTask = $(".dealSelectPipe");
    window.showDropdownForId("", dealTask);
    let selectglobal = $("#global-search");
    window.showDropdown("global-search", selectglobal);
});

window.deleteTask = async function (id = "", isremoveselected = false) {
    let updateids = updateSelectedRowIds();
    console.log(updateids, "udpaiddd");

    if (updateids.length === 0 && id === "remove_selected") {
        return;
    }
    if (isremoveselected) {
        id = undefined;
    }

    if (updateids.length !== 0) {
        const shouldDelete = await saveForm();
        if (!shouldDelete) {
            return;
        }
    }
    if (id === undefined) {
        id = updateids;
    }
    let ids;
    console.log(id, "id is here");
    if (Array.isArray(id)) {
        ids = id.join(",");
    } else if (typeof id === "string") {
        ids = id;
    } else {
        // Handle unexpected cases here
        throw new Error("id should be either an array or a string.");
    }
    if (ids === "") {
        return;
    }

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    if (id) {
        $("#datatable_tasks1_processing").css("display", "block");
        $("#datatable_tasks_processing").css("display", "block");
        $.ajax({
            url: "/delete-task/" + ids,
            method: "DELETE",
            contentType: "application/json",
            dataType: "JSON",
            data: {
                id: id,
                _token: "{{ csrf_token() }}",
            },
            success: function (response) {
                showToast("Deleted successfully");
                $("#datatable_tasks1").DataTable().ajax.reload();
                $("#datatable_tasks").DataTable().ajax.reload();
                $("#datatable_tasks1_processing").css("display", "none");
                $("#datatable_tasks_processing").css("display", "none");
            },
            error: function (xhr, status, error) {
                showToastError(xhr?.responseJSON?.error);
                $("#datatable_tasks1_processing").css("display", "none");
                $("#datatable_tasks_processing").css("display", "none");
            },
        });
    }
};

window.saveForm = function () {
    return new Promise((complete, failed) => {
        $("#confirmMessage").text("Are you sure you want to do this?");

        $("#confirmYes")
            .off("click")
            .on("click", () => {
                $("#confirmModal").modal("hide");
                complete(true);
            });

        $("#confirmNo")
            .off("click")
            .on("click", () => {
                $("#confirmModal").modal("hide");
                complete(false);
            });

        $("#confirmModal").modal("show");
    });
};

window.updateSelectedRowIds = function () {
    var selectedRowIds = [];
    $(".task_checkbox:checked").each(function () {
        selectedRowIds.push($(this).attr("id"));
    });

    return selectedRowIds;
};

window.toggleCheckAll = function (checkbox) {
    var isChecked = $(checkbox).prop("checked");
    $(".task_checkbox").prop("checked", isChecked);
    let updateColor = document.getElementById("removeBtn");
    // Update color based on allChecked
    if (isChecked) {
        updateColor.style.backgroundColor = "#222";
    } else {
        updateColor.style.backgroundColor = "rgb(165, 158, 158)";
    }
};

$("#datatable_tasks tbody").on("change", ".task_checkbox", function () {
    console.log("yesssususuusuus");
    var anyChecked = false;

    $(".task_checkbox").each(function () {
        if ($(this).prop("checked")) {
            anyChecked = true;
            return false; // Exit loop early
        }
    });

    let updateColor = document.getElementById("removeBtn");
    if (anyChecked) {
        updateColor.style.backgroundColor = "#222";
    } else {
        updateColor.style.backgroundColor = "rgb(165, 158, 158)";
    }

    // Check if all checkboxes are checked to set Check All checkbox
    var allChecked = true;
    $(".task_checkbox").each(function () {
        if (!$(this).prop("checked")) {
            allChecked = false;
            return false; // Exit loop early
        }
    });

    $("#checkAll").prop("checked", allChecked);
});

$("#datatable_tasks1 tbody").on("change", ".task_checkbox", function () {
    console.log("yesssususuusuus");
    var anyChecked = false;

    $(".task_checkbox").each(function () {
        if ($(this).prop("checked")) {
            anyChecked = true;
            return false; // Exit loop early
        }
    });

    let updateColor = document.getElementById("removeBtn");
    if (anyChecked) {
        updateColor.style.backgroundColor = "#222";
    } else {
        updateColor.style.backgroundColor = "rgb(165, 158, 158)";
    }

    // Check if all checkboxes are checked to set Check All checkbox
    var allChecked = true;
    $(".task_checkbox").each(function () {
        if (!$(this).prop("checked")) {
            allChecked = false;
            return false; // Exit loop early
        }
    });

    $("#checkAll").prop("checked", allChecked);
});

function generateModalHtml(data) {
    return `
                <div class="modal fade p-5" id="newTaskModalId${
                    data.id
                }" tabindex="-1">
                    <div class="modal-dialog d-flex justify-content-center align-items-center vh-100 deleteModal">
                        <div class="modal-content p-1">
                            <div class="modal-header border-0">
                                <p class="modal-title dHeaderText">Create New Tasks</p>
                                <button type="button" class="btn-close" id="btn_closing" data-bs-dismiss="modal"
                                    onclick="resetValidationTask(${
                                        data.id
                                    })" aria-label="Close"></button>
                            </div>
                            <div class="modal-body dtaskbody">
                                <p class="ddetailsText fw-normal">Details</p>
                                <textarea name="subject" onkeyup="validateTextareaTask(${
                                    data.id
                                });" id="darea${data.id}"
                                    rows="4" class="dtextarea"></textarea>
                                <div id="subject_error${
                                    data.zoho_contact_id
                                        ? data.id
                                        : data?.zoho_deal_id
                                }" class="text-danger"></div>
                                <label class="dRelatedText mb-2">Related to...</label>
                                <div class="btn-group dmodalTaskDiv">
                                    <select class="form-select dmodaltaskSelect" id="related_to" name="related_to" aria-label="Select Transaction">
                                        <option value="${
                                            data.zoho_contact_id
                                                ? data.id
                                                : data?.zoho_deal_id
                                        }" selected>
                                            ${data.last_name ?? data.deal_name}
                                        </option>
                                    </select>
                                </div>
                                <p class="dDueText">Date due</p>
                                <input type="date" name="due_date" class="dmodalInput" />
                            </div>
                            <div class="modal-footer ">
                                <button type="button" onclick="addCommonTask('${
                                    data.zoho_contact_id
                                        ? data.zoho_contact_id
                                        : data.zoho_deal_id
                                    }','${
                                        data.zoho_contact_id ? "Contacts" : "Deals"
                                    }')"
                                    class="btn btn-secondary taskModalSaveBtn">
                                    <i class="fas fa-save saveIcon"></i> Save Changes
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
}

function fetchNotesDeal(dealId) {
    return `
                <div class="modal fade testing" onclick="event.preventDefault();"
                    id="notefetchrelatedContact${dealId}" data-bs-backdrop="static"
                    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered deleteModal">
                        <div class="modal-content dtaskmodalContent">
                            <div class="modal-header border-0">
                                <p class="modal-title dHeaderText">Notes</p>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    onclick="resetValidation()" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="notesContainer${dealId}">
        
                            </div>
                        </div>
                    </div>
                </div>
            `;
}

function enableSelect(id) {
    // Enable the select element before form submission
    document.getElementById("noteSelect" + id).removeAttribute("disabled");
    // Return true to allow form submission
    return true;
}

//contact data table code

var tableContact = $("#datatable_contact").DataTable({
    paging: true,
    searching: true,
    processing: true,
    responsive: false,
    serverSide: true,

    columnDefs: [
        {},
        {
            targets: 0,
            orderable: false,
            className: "select-checkbox1",
            defaultContent: "",
        },
        {
            targets: 1,
            orderable: false,
            className: "select_count",
            defaultContent: "",
        },
    ],
    order: [0, "desac"],
    columns: [
        {
            data: null,
            className: "select-checkbox",
            defaultContent: "",
            title: "Selected",
            orderable: false,
            render: function (data, type, row) {
                return `<input type="checkbox" id= "email-checkbox${
                    data.id
                }" class="emailCheckbox" value="${data.id}" ${
                    !row.email ? "disabled" : ""
                }/>
                `;
            },
        },
        {
            data: null,
            title: "Actions",
            render: function (data, type, row) {
                return `
            <div class="icon-container">
            <a href="/contacts-view/${data.id}" target='_blank'>
                        <img src="/images/open.svg" alt="Open icon" class="ppiplinecommonIcon" title="Transaction Details">
                        <span class="tooltiptext"></span>
                    </a>
                    <img src="/images/splitscreen.svg" alt="Split screen icon"
                            class="ppiplinecommonIcon"  title="Add Task" onclick="createTaskForContact('${
                                data.id
                            }')">
                            <span class="tooltiptext"></span>
                            <div class="createTaskModal"></div>
                    <img src="/images/sticky_note.svg" alt="Sticky note icon"
                            class="ppiplinecommonIcon" data-bs-toggle="modal" data-bs-target="#" title="Notes"
                            onclick="fetchNotesForContact('${data.id}','${
                    data.zoho_contact_id
                }','Contacts')">
                        <span class="tooltiptext"></span>
                        ${fetchNotesDeal(data.zoho_contact_id)}
                    <img src="/images/noteBtn.svg" alt="Note icon"
                            class="ppiplinecommonIcon" data-bs-toggle="modal" title="Add Note"
                            onclick="createNotesForContact('${data.id}')"
                            data-bs-target="#staticBackdropforNote_${data.id}">
                        <span class="tooltiptext"></span>
                        <div class="createNoteModal"></div>
                        `;
            },
        },
        {
            data: null,
            title: "Full name",
            render: function (data, type, row) {
                const fullName = `${row.first_name ?? ""} ${
                    row.last_name ?? ""
                }`.trim();
                return `<span>${fullName || "N/A"}</span>`;
            },
        },
        {
            data: "abcd",
            title: "ABCD",
            render: function (data, type, row) {
                return `<span>${data || "N/A"}</span>`;
            },
        },
        {
            data: "relationship_type",
            title: "Relationship Type",
            render: function (data, type, row) {
                return `<span>${data || "N/A"}</span>`;
            },
        },
        {
            data: "email",
            title: "Email",
            render: function (data, type, row) {
                return `<span class="editable" data-name="email" data-id="${
                    row.id
                }">${data || "N/A"}</span>`;
            },
        },
        {
            data: "mobile",
            title: "Mobile",
            render: function (data, type, row) {
                let cleanNumber = data && data.toString().replace(/-/g, '');
                let link = data ? `<a href ='tel: ` + cleanNumber + `' onclick = 'addCallRecord(` + row.id + `,"` + data + `")' class='mx-2 text-black'> <i class='fas fa-mobile-alt table-call-btn'></i></a>` : "";
                return `${link}<span class="editable" data-name="mobile" data-id="${row.id}">${data || "N/A"}</span>`;
            },
        },
        {
            data: "phone",
            title: "Phone",
            render: function (data, type, row) {
                let cleanNumber = data && data.toString().replace(/-/g, '');
                let link = data ? `<a href ='tel: ` + cleanNumber + `' onclick = 'addCallRecord(` + row.id + `,"` + data + `")' class='mx-2 text-black'> <i class='fas fa-phone-alt table-call-btn'></i></a>` : "";
                return `${link}<span class="editable" data-name="phone" data-id="${row.id}">${data || "N/A"}</span>`;
            },
        },
        {
            data: "salutation_s",
            title: "Envelope",
            render: function (data, type, row) {
                return `<span class="editable" data-name="salutation_s" data-id="${
                    row.id
                }">${data || "N/A"}</span>`;
            },
        },
    ],
    ajax: {
        url: "/contact_view", // Ensure this URL is correct
        type: "GET", // or 'POST' depending on your server setup
        data: function (request) {
            request._token = "{{ csrf_token() }}";
            request.perPage = request.length;
            var contactSortValue = $("#contactSort").val();
            var emailChecked = $("#filterEmail").prop("checked");
            var mobileChecked = $("#filterMobile").prop("checked");
            var abcdChecked = $("#filterABCD").prop("checked");
            if (emailChecked || mobileChecked || abcdChecked) {
                request.filterobj = {
                    email: emailChecked,
                    mobile: mobileChecked,
                    abcd: abcdChecked,
                };
            }
            if (contactSortValue) {
                request.stage = contactSortValue;
            }
            request.page = request.start / request.length + 1;
            request.search = request.search.value;
            console.log(request, "request");
        },
        dataSrc: function (data) {
            document.getElementById("close_btn").click();
            return data?.data; // Return the data array or object from your response
        },
    },
    initComplete: function () {
        // Function to handle editing mode
        var currentText;
        function enterEditMode(element) {
            if ($(element).hasClass("editing")) {
                return; // Do nothing if already editing
            }

            // Close any other editing inputs
            $("#datatable_contact tbody")
                .find("input.edit-input, select.edit-input")
                .each(function () {
                    var newValue = $(this).val();
                    var dataName = $(this).data("name");
                    var dataId = $(this).data("id");
                    $(this).replaceWith(
                        `<span class="editable" data-name="${dataName}" data-id="${dataId}">${newValue}</span>`
                    );
                });

            currentText = $(element).text(); // Set currentText when entering edit mode
            var dataName = $(element).data("name");
            var dataId = $(element).data("id");

            // Replace span with input or select for editing
            if (
                dataName !== "closing_date" &&
                dataName !== "stage" &&
                dataName !== "representing"
            ) {
                $(element)
                    .replaceWith(
                        `<input type="text" class="edit-input form-control" value="${currentText}" data-name="${dataName}" data-id="${dataId}">`
                    )
                    .addClass("editing");
            } else if (dataName === "closing_date") {
                $(element)
                    .replaceWith(
                        `<input type="date" class="edit-input form-control" value="${formatDate(
                            currentText
                        )}" data-name="${dataName}" data-id="${dataId}">`
                    )
                    .addClass("editing");
            } else if (dataName === "stage") {
                // Fetch stage options from backend (example)
                var stageOptions = [
                    "Potential",
                    "Pre-Active",
                    "Under Contract",
                    "Active",
                ];
                var selectOptions = stageOptions
                    .map((option) => {
                        return `<option value="${option}" ${
                            currentText === option ? "selected" : ""
                        }>${option}</option>`;
                    })
                    .join("");

                $(element)
                    .replaceWith(
                        `<select class="edit-input form-control editable" data-name="${dataName}" data-id="${dataId}">
                    ${selectOptions}
                </select>`
                    )
                    .addClass("editing");
            } else if (dataName === "representing") {
                // Fetch representing options from backend (example)
                var representingOptions = ["Buyer", "Seller"];
                var selectOptions = representingOptions
                    .map((option) => {
                        return `<option value="${option}" ${
                            currentText === option ? "selected" : ""
                        }>${option}</option>`;
                    })
                    .join("");

                $(element)
                    .replaceWith(
                        `<select class="edit-input form-control editable" data-name="${dataName}" data-id="${dataId}">
                    ${selectOptions}
                </select>`
                    )
                    .addClass("editing");
            }

            // Focus on the input field or select dropdown
            $("#datatable_contact tbody")
                .find("input.edit-input, select.edit-input")
                .focus();
        }

        // Function to handle exiting editing mode
        function exitEditMode(inputElement) {
            var newValue = $(inputElement).val();
            var dataName = $(inputElement).data("name");
            var conId = $(inputElement).data("id");

            // Replace input or select with span
            $(inputElement)
                .replaceWith(
                    `<span class="editable" data-name="${dataName}" data-id="${conId}">${newValue}</span>`
                )
                .removeClass("editing");

            // Check if the value has changed
            if (newValue !== currentText) {
                $("#datatable_contact_processing").css("display", "block");
                // Example AJAX call (replace with your actual endpoint and data):
                $.ajax({
                    url: "/contact/update/" + conId,
                    type: "POST",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    data: {
                        id: conId,
                        field: dataName,
                        value: newValue,
                    },
                    success: function (response) {
                        console.log("Updated successfully:", response);
                        $("#datatable_contact_processing").css(
                            "display",
                            "none"
                        );
                        if (response?.message) {
                            showToast(response?.message);
                            $("#datatable_contact").DataTable().ajax.reload();
                        }
                    },
                    error: function (error) {
                        console.error("Error updating:", error);
                        $("#datatable_contact_processing").css(
                            "display",
                            "none"
                        );
                        $("#datatable_contact").DataTable().ajax.reload();
                        showToastError(error?.responseJSON?.error);
                    },
                });
            }
        }

        // Function to update the selected count
        function updateSelectedCount() {
            var selectedCount = $(".emailCheckbox:checked").length;
            console.log($(".select_count")[0], "selectedCount");
            let selectText =
                selectedCount === 0 ? "Select" : "Selected: " + selectedCount;
            $(".select_count").eq(0).text(selectText);
        }

        // Event handler for checkbox changes
        $("#datatable_contact").on("change", ".emailCheckbox", function () {
            updateSelectedCount();
        });

        // Initial count update
        updateSelectedCount();

        // Click event to enter editing mode
        $("#datatable_contact tbody").on("click", "span.editable", function () {
            enterEditMode(this);
        });

        // Keyup event to exit editing mode on Enter
        $("#datatable_contact tbody").on(
            "keyup",
            "input.edit-input",
            function (event) {
                if (event.key === "Enter") {
                    exitEditMode(this);
                }
            }
        );
        // Handle onchange event for select
        $("#datatable_contact tbody").on(
            "change",
            "select.edit-input",
            function () {
                exitEditMode(this); // Exit edit mode when a selection is made
            }
        );

        // Blur event to exit editing mode when clicking away
        $("#datatable_contact tbody").on(
            "blur",
            "input.edit-input",
            function () {
                exitEditMode(this);
            }
        );
    },
});

$("#contactSearch").on("keyup", function () {
    tableContact.search(this.value).draw();
});

$("#contactSort").on("change", function () {
    tableContact.search("").draw();
});
$(".pfilterBtn").on("click", function () {
    tableContact.search("").draw();
});
$("#compose_email").on("click", function () {
    var checkedItems = getCheckedItems();
    console.log(checkedItems);
    openComposeModal(checkedItems);
});

$(".filterClosebtn").on("click", function () {
    $("#filterEmail").prop("checked", false);
    $("#filterMobile").prop("checked", false);
    $("#filterABCD").prop("checked", false);
    tableContact.search("").draw();
});
$("#Reset_All").on("click", function () {
    $("#contactSearch").val("");
    $("#contactSort").val("");
    $("#filterEmail").prop("checked", false);
    $("#filterMobile").prop("checked", false);
    $("#filterABCD").prop("checked", false);
    tableContact.search("").draw();
});

window.getCheckedItems = function () {
    var checkedValues = [];
    $("#datatable_contact .emailCheckbox:checked").each(function () {
        var rowId = $(this).val();
        checkedValues.push(rowId);
    });

    return checkedValues;
};

//    contacts actions
window.createNotesForContact = function (id, conId) {
    console.log("heyyy+++++");
    event.preventDefault();
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    $.ajax({
        url: "/note-create/" + id,
        method: "GET",
        success: function (response) {
            // $('#notesContainer').append('<p>New Note Content</p>');
            let noteContainer = $(".createNoteModal");
            console.log(response, "noteContainer");
            // Clear previous contents of note containe
            noteContainer.empty();
            const card = noteContainer.html(response);
            // // Show the modal after appending notes
            $("#staticBackdropforNote_" + id).modal("show");
        },
        error: function (xhr, status, error) {
            // Handle error
            showToastError(error);
            console.error("Ajax Error:", error);
        },
    });
};

window.createTaskForContact = function (id, conId) {
    console.log("heyyy+++++");
    event.preventDefault();
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    $.ajax({
        url: "/task-create/" + id,
        method: "GET",
        success: function (response) {
            // $('#notesContainer').append('<p>New Note Content</p>');
            let taskContainer = $(".createTaskModal");
            console.log(response, "taskContainer");
            // Clear previous contents of note containe
            taskContainer.empty();
            const card = taskContainer.html(response);
            // // Show the modal after appending notes
            $("#newTaskModalId" + id).modal("show");
        },
        error: function (xhr, status, error) {
            // Handle error
            showToastError(error);
            console.error("Ajax Error:", error);
        },
    });
};

window.fetchNotesForContact = function (id, conId, type) {
    event.preventDefault();
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    let urlfinal = type === "Deals" ? "/deal/note/" : "/note/";
    $.ajax({
        url: urlfinal + id,
        method: "GET",
        success: function (response) {
            // $('#notesContainer').append('<p>New Note Content</p>');
            let noteContainer = $("#notesContainer" + conId);
            console.log(response, "noteContainer");
            // Clear previous contents of note containe
            noteContainer.empty();
            const card = noteContainer.html(response);
            // Loop through each note in the response array
            // response?.forEach(function(note) {
            //     // console.log(note, 'note')
            //     // Create HTML to display note content and creation time
            //     let data = `<div class="noteCardForContact">
            //                 <p>Note Content: ${note?.contact_data?.first_name} ${note?.contact_data?.last_name}</p>
            //                 <p>Note Content: ${note?.note_content}</p>
            //             </div>`;
            //     // Append the HTML to noteContainer
            //     noteContainer.append(data);
            //     console.log("testing", noteContainer)
            // });
            // // Show the modal after appending notes
            $("#notefetchrelatedContact" + conId).modal("show");
        },
        error: function (xhr, status, error) {
            // Handle error
            showToastError(error);
            console.error("Ajax Error:", error);
        },
    });
};

//    deal actions
window.createNotesForDeal = function (id, conId) {
    event.preventDefault();
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    $.ajax({
        url: "/note-create-pipe/" + id,
        method: "GET",
        success: function (response) {
            // $('#notesContainer').append('<p>New Note Content</p>');
            let noteContainer = $(".createNoteModal" + id);
            console.log(response, "noteContainer");
            // Clear previous contents of note containe
            noteContainer.empty();
            const card = noteContainer.html(response);
            // // Show the modal after appending notes
            $("#staticBackdropforNote_" + id).modal("show");
        },
        error: function (xhr, status, error) {
            // Handle error
            showToastError(error);
            console.error("Ajax Error:", error);
        },
    });
};

window.createTasksForDeal = function (id, conId) {
    event.preventDefault();
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    $.ajax({
        url: "/task-create-pipe/" + id,
        method: "GET",
        success: function (response) {
            // $('#notesContainer').append('<p>New Note Content</p>');
            let noteContainer = $(".createTaskModal" + id);
            console.log(response, "noteContainer");
            // Clear previous contents of note containe
            noteContainer.empty();
            const card = noteContainer.html(response);
            // // Show the modal after appending notes
            $("#newTaskModalId" + id).modal("show");
        },
        error: function (xhr, status, error) {
            // Handle error
            showToastError(error);
            console.error("Ajax Error:", error);
        },
    });
};

var contactEmailTable = $("#contact-email-table").DataTable({
    paging: true,
    searching: true,
    processing: true,
    serverSide: true,
    responsive: false,
    pageLength: 5,
    lengthMenu: [5, 10, 25, 50, 75, 100],
    columns: [
        {
            data: "subject",
            title: "Subject",
            render: function (data, type, row) {
                return `<span class="editable" data-name="emailSubject" data-id="${
                    row.id
                }" onclick="viewEmail('${row.id}')" style="cursor:pointer;">
            ${data}
        </span>
        <span>${viewEmailModal(row.id)}</span>`;
            },
        },
        {
            data: "from_user_data",
            title: "Sent By",
            render: function (data, type, row) {
                console.log("FROM Data", data);
                return `<span class="editable" data-name="submittalType" data-id="${row.id}">${data.name}</span>`;
            },
        },
        {
            data: "isEmailSent",
            title: "Status",
            render: function (data, type, row) {
                return `<span class="editable" data-name="phone" data-id="${
                    row.id
                }">${(data == true ? "Sent" : "Draft") || "N/A"}</span>`;
            },
        },
        {
            data: "created_at",
            title: "Date",
            render: function (data, type, row) {
                return `<span class="editable" data-name="submittalType" data-id="${
                    row.id
                }">${formateDate(data)}</span>`;
            },
        },
    ],

    ajax: {
        url: "/contact/email/list/" + contactId, // Ensure this URL is correct
        type: "GET", // or 'POST' depending on your server setup
        data: function (request) {
            request._token = "{{ csrf_token() }}";
            request.perPage = request.length;
        },
        dataSrc: function (data) {
            console.log(data, "data is hreeeee");
            return data?.data; // Return the data array or object from your response
        },
    },
});

function viewEmailModal(id) {
    return `
                <div class="modal fade testing p-5" onclick="event.preventDefault();"
                    id="viewEmailDetailModal${id}" data-bs-backdrop="static"
                    data-bs-keyboard="false" tabindex="-1" aria-labelledby="viewEmailLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg deleteModal">
                        <div class="modal-content dtaskmodalContent">
                            <div class="modal-header border-0">
                                <p class="modal-title dHeaderText">Email</p>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="viewEmailDetails${id}">
        
                            </div>
                        </div>
                    </div>
                </div>
            `;
}

window.viewEmail = function (emailId) {
    // window.location.href = "/emails?contactId=" + contactId;
    event.preventDefault();
    $.ajax({
        url: "/get/email/modal/" + emailId,
        method: "GET",
        success: function (response) {
            let viewEmailContainer = $("#viewEmailDetails" + emailId);
            console.log(response, "viewEmailContainer");
            viewEmailContainer.empty();
            const card = viewEmailContainer.html(response);
            $("#viewEmailDetailModal" + emailId).modal("show");
        },
        error: function (xhr, status, error) {
            // Handle error
            showToastError(error);
            console.error("Ajax Error:", error);
        },
    });
};
var templateTableList = $("#template-table-list").DataTable({
    paging: true,
    searching: true,
    processing: true,
    serverSide: true,
    responsive: false,
    dom: "Bfrtip", // Integrates buttons with DataTables
    buttons: [
        {
            text: '<i class="far fa-trash-alt"></i><span class="ms-1">  Remove Selected',
            className: "btn btn-dark btn-block waves-effect waves-light",
            action: function (e, dt, node, config) {
                var selectedIds = [];
                $(".emailTemplateCheckbox:checked").each(function () {
                    selectedIds.push($(this).val());
                });

                if (selectedIds.length > 0) {
                    $.ajax({
                        url: "/delete/templates",
                        type: "POST",
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        contentType: "application/json",
                        data: JSON.stringify(selectedIds),
                        success: function (response) {
                            showToast(
                                "Selected templates deleted successfully."
                            );
                            templateTableList.ajax.reload();
                        },
                        error: function (xhr, status, error) {
                            console.error("Error details:", {
                                status: status,
                                error: error,
                                responseText: xhr.responseText,
                            });
                            showToastError(
                                "An error occurred while deleting templates. Please try again."
                            );
                        },
                    });
                } else {
                    showToastError("No templates selected.");
                }
            },
        },
    ],
    columns: [
        {
            data: null,
            className: "select-checkbox",
            defaultContent: "",
            orderable: false,
            render: function (data, type, row) {
                return `
                    <input type="checkbox" id="email-template-checkbox${
                        data.id
                    }" 
                        class="emailTemplateCheckbox" value="${data.id}" 
                        ${row.templateType === "public" ? "disabled" : ""}/>
                `;
            },
        },
        {
            data: "name",
            title: "Name",
            render: function (data, type, row) {
                return `
                    <span class="editable" data-name="templateSubject" data-id="${
                        row.id
                    }" 
                        onclick="viewTemplateDetail('${
                            row.id
                        }')" style="cursor:pointer;">
                        ${data}
                    </span>
                    ${viewTemplateModal(row.id, row.name)}
                `;
            },
        },
        {
            data: "subject",
            title: "Subject",
            render: function (data, type, row) {
                return `<span class="editable" data-name="submittalType" data-id="${row.id}">${data}</span>`;
            },
        },
        {
            data: "created_at",
            title: "Date",
            render: function (data, type, row) {
                return `<span class="editable" data-name="submittalType" data-id="${
                    row.id
                }">${formateDate(data)}</span>`;
            },
        },
    ],
    ajax: {
        url: "/get/templates/json", // Ensure this URL is correct
        type: "GET",
        data: function (request) {
            request._token = "{{ csrf_token() }}";
            request.perPage = request.length;
        },
        dataSrc: function (data) {
            console.log(data, "data is here");
            return data?.data; // Return the data array or object from your response
        },
    },
});

function viewTemplateModal(id, name) {
    return `
        <div class="modal fade p-5" id="viewTemplateModal${id}" data-bs-backdrop="static" 
            data-bs-keyboard="false" tabindex="-1" aria-labelledby="viewTemplateLabel" 
            aria-hidden="true">
            <div class="modal-dialog modal-xl deleteModal">
                <div class="modal-content dtaskmodalContent">
                    <div class="modal-header border-0">
                        <p class="modal-title dHeaderText" id="templateName${id}" onclick="editName('${id}')">${name}</p>
                        <button type="button" class="btn-close" id="templateViewClose${id}" data-bs-dismiss="modal" 
                         aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="viewTemplateData${id}">
                        <!-- Template content will be loaded here -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary btn-dark" onclick="updateTemplate('${id}')">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    `;
}

window.viewTemplateDetail = function (templateId) {
    event.preventDefault();
    $.ajax({
        url: "/read/template/detail/" + templateId,
        method: "GET",
        success: function (response) {
            let viewTemplateContainer = $("#viewTemplateData" + templateId);
            console.log(response, "viewTemplateContainer");
            viewTemplateContainer.empty();
            viewTemplateContainer.html(response);
            $("#viewTemplateModal" + templateId).modal("show");
        },
        error: function (xhr, status, error) {
            showToastError(
                "An error occurred while fetching template details."
            );
            console.error("Ajax Error:", error);
        },
    });
};

window.editName = function (templateId) {
    var element = document.getElementById("templateName" + templateId);
    console.log("ELEMENT NAME", element);

    let inputElement = document.createElement("input");
    inputElement.type = "text";
    inputElement.className = "form-control"; // Add any classes you need
    inputElement.id = "templateName" + templateId;
    inputElement.value = element.textContent.trim();
    console.log(inputElement);

    // Replace the <p> element with the <input> element
    element.replaceWith(inputElement);
};

window.updateTemplate = function (templateId) {
    event.preventDefault();
    var subject = $("#templateSubject" + templateId).val();
    var content = tinymce.get("templateContent" + templateId).getContent();
    var name = $("#templateName" + templateId).val();

    var jsonData = {};
    if (subject) {
        jsonData.subject = subject;
    }
    if (content) {
        jsonData.content = content;
    }
    if (name) {
        jsonData.name = name;
    }
    $.ajax({
        url: "/update/template/" + templateId,
        method: "PATCH",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        contentType: "application/json",
        dataType: "JSON",
        data: JSON.stringify(jsonData),
        success: function (response) {
            $("#templateViewClose" + templateId).click();
            showToast("Template update successfully");
            $("#template-table-list").DataTable().ajax.reload();
        },
        error: function (xhr, status, error) {
            showToastError(
                "An error occurred while fetching template details."
            );
            console.error("Ajax Error:", error);
        },
    });
};

var tableDashboard = $("#contact-transaction-table").DataTable({
    paging: true,
    searching: true,
    processing: true,
    serverSide: true,
    responsive: false,
    columns: [
        {
            data: "deal_name",
            title: "Transaction",
            render: function (data, type, row) {
                return `<a href="/pipeline-view/${row?.id}" target="_blank"><span class='icon-container max-width-500' >${data}</span></a>`;
            },
        },
        {
            data: "primary_contact",
            title: "Client Name",
            render: function (data, type, row) {
                console.log("Data", data);
                let jsonString, name;
                if (data) {
                    jsonString = data?.replace(/&quot;/g, '"');

                    // Parse the string as JSON
                    data = JSON.parse(jsonString);
                    name =
                        (data[0] &&
                            data[0].Primary_Contact &&
                            data[0].Primary_Contact.name) ??
                        "";
                }
                return `<span>${name || "N/A"}</span>`;
            },
        },
        {
            data: "stage",
            title: "Status",
            render: function (data, type, row) {
                return `<span>${data}</span>`;
            },
        },
        {
            data: "representing",
            title: "Representing",
            render: function (data, type, row) {
                return `<span >${data}</span>`;
            },
        },
        {
            data: "sale_price",
            title: "Price",
            render: function (data, type, row) {
                console.log(data, "datattas");
                return `<span >$${number_format(data, 0, ".", ",")}</span>`;
            },
        },
        {
            data: "closing_date",
            title: "Close Date",
            render: function (data, type, row) {
                return `<span class="editable badDateInput" data-name="closing_date" data-id="${
                    row.id
                }">${formateDate(data) || "N/A"}</span>`;
            },
        },
        {
            data: "commission",
            title: "Commission",
            render: function (data, type, row) {
                return `<span >${data}%</span>`;
            },
        },
        {
            data: "potential_gci",
            title: "Potential GCI",
            render: function (data, type, row) {
                return `<span >${data}</span>`;
            },
        },
        {
            data: "pipeline_probability",
            title: "Probability",
            render: function (data, type, row) {
                return `<span >${data}%</span>`;
            },
        },
        {
            data: null,
            title: "Probable GCI",
            render: function (data, type, row) {
                // Calculate probable GCI
                var probableGCI =
                    (row.sale_price ?? 0) *
                    ((row.commission ?? 0) / 100) *
                    ((row.pipeline_probability ?? 0) / 100);
                return `$${number_format(probableGCI, 0, ".", ",")}`; // Format probableGCI as currency
            },
        },
    ],
    ajax: {
        url: "/contacts-trasactions/" + contactId, // Ensure this URL is correct
        type: "GET", // or 'POST' depending on your server setup
        data: function (request) {
            request._token = "{{ csrf_token() }}";
            request.perPage = request.length;
            (request.stage = $("#related_to_stage").val()),
                (request.page = request.start / request.length + 1);
            request.search = request.search.value;
        },
        dataSrc: function (data) {
            return data?.data; // Return the data array or object from your response
        },
    },
});

var callRecordBoard = $("#call-record-table").DataTable({
    paging: true,
    searching: true,
    processing: true,
    serverSide: true,
    responsive: false,
    columns: [
        {
            data: "phone_number",
            title: "Phone Number",
            render: function (data, type, row) {
                let cleanNumber = data && data.toString().replace(/-/g, '');
                let link = data ? `<a href ='tel: ` + cleanNumber + `' onclick = 'addCallRecord(` + row.contact_id + `,"` + data + `")' class='mx-2 text-black'> <i class='fas fa-phone-alt table-call-btn'></i></a>` : "";
                return `${link}</i></a><span class="editable" data-name="phone" data-id="${row.contact_id}">${data || "N/A"}</span>`;
            },
        },
        {
            data: "created_at",
            title: "Start Time",
            render: function (data, type, row) {
                return `<span>${formatDateTime(data)}</span>`;
            },
        }
    ],
    ajax: {
        url: route('call.records.list', { contactId: contactId }),
        type: "GET",
        data: function (request) {
            request._token = "{{ csrf_token() }}";
            request.perPage = request.length;
            (request.stage = $("#related_to_stage").val()),
                (request.page = request.start / request.length + 1);
            request.search = request.search.value;
        },
        dataSrc: function (data) {
            return data?.data; // Return the data array or object from your response
        },
    },
});

window.addCallRecord = function(id, phone_number) {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    $.ajax({
        url: route('call.records.create'),
        method: "POST",
        data: {contact_id: id, phone_number: phone_number},
        success: function (response) {
        },
        error: function (xhr, status, error) {
            showToastError(
                "An error occurred while adding call record."
            );
            console.error("Ajax Error:", error);
        },
    });
}
