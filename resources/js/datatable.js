const urlParts = window.location.pathname.split("/"); // Split the URL by '/'
const contactId = urlParts.pop();
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
    const dateObj = new Date(data);
    if (!data) return false;
    // Format the date to display in YYYY-MM-DD format
    const formattedDate = dateObj.toLocaleDateString("en-US", {
        year: "numeric",
        month: "2-digit",
        day: "2-digit",
    });
    return formattedDate;
}
//pipeline data table code
var table = $("#datatable_pipe_transaction").DataTable({
    paging: true,
    searching: true,
    processing: true,
    serverSide: true,
    responsive: true,
    columnDefs: [{ responsivePriority: 2, targets: -9 }],
    columns: [
        {
            className: "dt-control",
            orderable: false,
            data: null,
            defaultContent: "",
        },
        {
            data: null,
            title: "Actions",
            render: function (data, type, row) {
                let lockIcon = "";
                if (data.stage === "Under Contract") {
                    lockIcon = `<i class="fas fa-lock"></i>`;
                } else {
                    lockIcon = `<span class="lock-placeholder"></span>`;
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
            </div>`;
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
                console.log(data, "sdfsdhfshd");
                if (row.stage === "Under Contract") {
                    return `<span style="color:${getTextColorForStage(
                        data
                    )}; background-color:${getColorForStage(data)}">${
                        data || "N/A"
                    }</span>`;
                }
                return `<span class="editable" data-name="stage" style="color:${getTextColorForStage(
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
                    return `<span>${data || "N/A"}</span>`;
                }
                return `<span class="editable" data-name="commission" data-id="${
                    row.id
                }">${data || "N/A"}</span>`;
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
                return `<span class="editable" data-name="pipeline_probability" data-id="${
                    row.id
                }">${data || "N/A"}%</span>`;
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
        url: "/pipeline_view", // Ensure this URL is correct
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
            $("#datatable_pipe_transaction tbody")
                .find("input.edit-input, select.edit-input")
                .focus();
        }

        // Function to handle exiting editing mode
        function exitEditMode(inputElement) {
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

//contact role table pipeline
var tableContactRole = $("#contact_role_table_pipeline").DataTable({
    paging: true,
    searching: true,
    processing: true,
    serverSide: true,
    columns: [
        // {
        //     className: 'dt-control',
        //     orderable: false,
        //     data: null,
        //     defaultContent: ''
        // },

        {
            data: "name",
            title: "Name",
            render: function (data, type, row) {
                return `<span class='icon-container' >${data}</span>`;
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
                return `<span class="editable" data-name="submittalName" data-id="${row.id}">${data}</span>`;
            },
        },
        {
            data: "submittalType",
            title: "Submittal Type",
            render: function (data, type, row) {
                return `<span class="editable" data-name="submittalType" data-id="${row.id}">${data}</span>`;
            },
        },
        {
            data: "user_data.name",
            title: "Owner",
            render: function (data, type, row) {
                console.log(data, "shdfhsdhf");
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

//transaction for dashboard
var tableDashboard = $("#datatable_transaction").DataTable({
    paging: true,
    searching: true,
    processing: true,
    serverSide: true,
    columns: [
        {
            className: "dt-control",
            orderable: false,
            data: null,
            defaultContent: "",
        },
        {
            data: "deal_name",
            title: "Transaction",
            render: function (data, type, row) {
                return `<span class='icon-container' >${data}</span>`;
            },
        },
        {
            data: "client_name_primary",
            title: "Client Name",
            render: function (data, type, row) {
                return `<span >${data}</span>`;
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
                }">${formateDate(data)}</span>`;
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
                        }
                    },
                    error: function (error) {
                        console.error("Error updating:", error);
                        showToastError(error?.responseJSON?.error);
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
});

var tableTaskspipe = $("#datatable_tasks1").DataTable({
    paging: true,
    searching: true,
    processing: true,
    serverSide: true,
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
                showToastError(xhr.responseText);
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
                <div class="modal fade" id="newTaskModalId${
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
    responsive: true,
    serverSide: true,
    responsive: true,
    columnDefs: [
        { responsivePriority: 1, targets: 0 },
        { responsivePriority: 10001, targets: 4 },
        { responsivePriority: 2, targets: -7 },
    ],
    order: [0, "desac"],
    columns: [
        {
            className: "dt-control",
            orderable: false,
            data: null,
            defaultContent: "",
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
            data: "last_name",
            title: "Full name",
            render: function (data, type, row) {
                return `<span>${data || "N/A"}</span>`;
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
                return `<span class="editable" data-name="mobile" data-id="${
                    row.id
                }">${data || "N/A"}</span>`;
            },
        },
        {
            data: "phone",
            title: "Phone",
            render: function (data, type, row) {
                return `<span class="editable" data-name="phone" data-id="${
                    row.id
                }">${data || "N/A"}</span>`;
            },
        },
        {
            data: "envelope_salutation",
            title: "Envelope",
            render: function (data, type, row) {
                return `<span class="editable" data-name="envelope_salutation" data-id="${
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
