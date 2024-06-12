
<script src="https://code.jquery.com/jquery-3.6.0.min.js">
</script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
            var getLeadAgent = $('#leadAgent');
            getLeadAgent.select2({
                placeholder: 'Search...',
            });
            var getClientName = $('#validationDefault01');
            getClientName.select2({
                placeholder: 'Search...',
            });

            var representing = document.getElementById('validationDefault02');
            var stage = document.getElementById('validationDefault04');
            if (representing?.value == 'Buyer' && stage?.value == "Under Contract") {
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
        })

        window.updateDeal = function(dealId) {
            console.log(dealId);
            // Retrieve values from form fields
            var client_name_primary = $('#validationDefault01').val();
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
            var property_type = $('#validationDefault12').val();
            var ownership_type = $('#validationDefault13').val();
            var potential_gci = $('#validationDefault14').val();
            var pipeline_probability = $('#validationDefault15').val();
            var probable_gci = $('#validationDefault16').val();
            var personal_transaction = $('#flexCheckChecked01').prop('checked');
            var double_ended = $('#flexCheckChecked02').prop('checked');

            // Create formData object
            var formData = {
                "data": [{
                    "Client_Name_Primary": (client_name_primary.first_name || "") + " " + (
                        client_name_primary.last_name || ""),
                    "Client_Name_Only": (client_name_primary.first_name || "") + " " + (client_name_primary
                        .last_name || "") + " || " + client_name_primary.zoho_contact_id,
                    "Representing": representing,
                    "Deal_Name": deal_name,
                    "Stage": stage,
                    "Sale_Price": sale_price,
                    "Closing_Date": closing_date,
                    "Address": address,
                    "City": city,
                    "State": state,
                    "Zip": zip,
                    "Commission": commission,
                    "Property_Type": property_type,
                    "Ownership_Type": ownership_type,
                    "Potential_GCI": potential_gci,
                    "Pipeline_Probability": pipeline_probability,
                    "Pipeline1": probable_gci,
                    "Personal_Transaction": personal_transaction,
                    "Double_Ended": double_ended,
                    "Contact": {
                        "Name": (client_name_primary.first_name || "") + " " + (client_name_primary
                            .last_name || ""),
                        "id": client_name_primary.zoho_contact_id
                    }
                }],
                "_token": '{{ csrf_token() }}'
            };
            console.log("formData", formData, dealId);

            // Send AJAX request
            $.ajax({
                url: "{{ route('pipeline.update', ['dealId' => ':id']) }}".replace(':id', dealId),
                type: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                contentType: 'application/json',
                dataType: 'json',
                data: JSON.stringify(formData),
                success: function(response) {
                    if (response?.data && response.data[0]?.message) {
                        // Convert message to uppercase and then display
                        const upperCaseMessage = response.data[0].message.toUpperCase();
                        showToast(upperCaseMessage);
                        // window.location.reload();
                    }
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.error(xhr.responseText);
                }
            })
        }

    </script>