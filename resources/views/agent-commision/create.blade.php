<div class="modal fade p-5" id="contactCommission" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h4 class="modal-title">Create Agent Commission Income</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">


                <form id="myForm" class="needs-validation" novalidate>
                    <div class="agent-info">
                        <h4 class="text-decoration-underline">Agent Commission Income Information
                        </h4>
                    </div>
                    <div class="container">
                        <div class="form-group row">
                            <label for="id" class="col-sm-2 col-form-label">Agent Name</label>
                            <div class="col-sm-10">
                                <select type="text" class="form-control" id="Agent_Name" name="AgentName"
                                    placeholder="Enter your Agent Name">
                                    <option value="{{ $deal['userData']['root_user_id'] ?? '' }}" selected>
                                        {{ $deal['userData']['name'] ?? '' }}</option>
                                </select>
                                <div class="invalid-feedback">
                                    Please choose a Agent Name
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="id" class="col-sm-2 col-form-label">Agent Portion of Commission that gets
                                split</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="Agent_Portion" name="Agent_Portion"
                                    placeholder="$" required />
                                <div class="invalid-feedback">
                                    Please choose a Agent Portion.
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="id" class="col-sm-2 col-form-label">Portion of Total %</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="Portion_percent" name="Portion_percent"
                                    placeholder="$" required />
                                <div class="invalid-feedback">
                                    Please choose a Portion percent
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="id" class="col-sm-2 col-form-label">Less Split to CHR</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="less_split" name="less_split"
                                    placeholder="$" required />
                                <div style="color:red;" id="less_split_error">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="id" class="col-sm-2 col-form-label">After Splits</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="after_split" name="after_split"
                                    placeholder="$" required />
                                <div class="invalid-feedback">
                                    Please choose a After Splits
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="id" class="col-sm-2 col-form-label">Sale Price</label>
                            <div class="col-sm-10">
                                <input type="text" value="{{ $deal['sale_price'] }}"
                                    class="form-control cursor_not_allowed" id="sale_price" name="sale_price"
                                    placeholder="$" readonly />
                                <div class="invalid-feedback">
                                    Please choose a Sale Price
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="agent-info">
                        <h4 class="text-decoration-underline">Expenses
                        </h4>
                    </div>
                    <div class="container">
                        <div class="form-group row">
                            <label for="id" class="col-sm-2 col-form-label">CHR Gives due to CHR</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="chr_due" name="chr_due" placeholder="$"
                                    required />
                                <div class="invalid-feedback">
                                    Please choose a CHR Gives due to CHR
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="id" class="col-sm-2 col-form-label">TM Fees due to CHR</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="due_tm_fee" name="due_tm_fee"
                                    placeholder="$" />
                                <div style="color: red;" id="due_tm_fee_error">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="id" class="col-sm-2 col-form-label">Agent Contribution to Client
                                Transaction Costs</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="t_cost" name="t_cost"
                                    placeholder="$" required />
                                <div class="invalid-feedback">
                                    Please choose a Transaction Costs
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="id" class="col-sm-2 col-form-label">Home Warranty</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="h_warranty" name="h_warranty"
                                    placeholder="$" required />
                                <div class="invalid-feedback">
                                    Please choose a Home Warranty
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="id" class="col-sm-2 col-form-label">eCommission</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="e_comm" name="e_comm"
                                    placeholder="$" required />
                                <div class="invalid-feedback">
                                    Please choose a e_comm
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="id" class="col-sm-2 col-form-label">Past Due Amount to CHR</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="due_ammount" name="due_ammount"
                                    placeholder="$" required />
                                <div class="invalid-feedback">
                                    Please choose a Past Due Amount to CHR
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="agent-info">
                        <h4 class="text-decoration-underline">Sub-Total
                        </h4>
                    </div>
                    <div class="container">
                        <div class="form-group row">
                            <label for="id" class="col-sm-2 col-form-label">Admin Fee Income</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="admin_fee_income" name="chr_due"
                                    placeholder="$" required />
                                <div class="invalid-feedback">
                                    Please choose Admin Fee Income
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="agent-info">
                        <h4 class="text-decoration-underline">Payouts
                        </h4>
                    </div>
                    <div class="container">
                        <div class="form-group row">
                            <label for="id" class="col-sm-2 col-form-label">Agent Check Amount</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="agent_check" name="agent_check"
                                    placeholder="$" required />
                                <div id="agent_check_error" style="color: red;">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="id" class="col-sm-2 col-form-label">Colorado Home Realty</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="chr" name="chr"
                                    placeholder="$" required />
                                <div class="invalid-feedback">
                                    Please choose a tm fee
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="id" class="col-sm-2 col-form-label">CHR Gives</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="chr_gives" name="chr_gives"
                                    placeholder="$" required />
                                <div class="invalid-feedback">
                                    Please choose a CHR Gives
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="id" class="col-sm-2 col-form-label">Credit to Client</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="c_client" name="c_client"
                                    placeholder="$" required />
                                <div class="invalid-feedback">
                                    Please choose a Credit to Client
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="id" class="col-sm-2 col-form-label">Home Warranty Payout</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="home_w_payout" name="home_w_payout"
                                    placeholder="$" required />
                                <div class="invalid-feedback">
                                    Please choose a Home Warranty Payout
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="id" class="col-sm-2 col-form-label">eCommission Payout</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="e_comm_payout" name="e_comm_payout"
                                    placeholder="$" required />
                                <div class="invalid-feedback">
                                    Please choose a eCommission Payout
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="id" class="col-sm-2 col-form-label">IRS Reported 1099 Income For This
                                Transaction</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="irs_rep" name="irs_rep"
                                    placeholder="$" required />
                                <div id="irs_rep_error" style="color: red;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="agent-info">
                        <h4 class="text-decoration-underline">Meta
                        </h4>
                    </div>
                    <div class="container">
                        <div class="form-group row">
                            <label for="id" class="col-sm-2 col-form-label">Currency</label>
                            <div class="col-sm-10">
                                <input type="text" value="USD" class="form-control" id="Currency"
                                    name="Currency" placeholder="$" required />
                                <div class="invalid-feedback">
                                    Please choose a Currency
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="id" class="col-sm-2 col-form-label">Exchange Rate</label>
                            <div class="col-sm-10">
                                <input type="text" value="{{ $deal['exchange_rate'] ?? 0 }}"
                                    class="form-control cursor_not_allowed" id="exchange_rate" name="exchange_rate"
                                    placeholder="" readonly />
                                <div class="invalid-feedback">
                                    Please choose a Exchange Rate
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="id" class="col-sm-2 col-form-label">Transaction</label>
                            <div class="col-sm-10">
                                <select type="text"
                                    class="form-control" id="Transaction" name="Transaction" placeholder="$"
                                     >
                                     <option value="{{ $deal['zoho_deal_id']}}">{{ $deal['deal_name'] == 'Untitled' ? '' : $deal['deal_name'] }}</option>
                                     </select>
                                <div class="invalid-feedback">
                                    Please choose a Transaction
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="id" class="col-sm-2 col-form-label">Agent User
                            </label>
                            <div class="col-sm-10">
                                <select type="text" class="form-control" id="agent_user" name="agent_user" placeholder="$">
                                    <option value="{{ trim($deal['contactName']['zoho_contact_id']) }}">
                                        {{ trim($deal['contactName']['first_name'] ?? '') }} {{ trim($deal['contactName']['last_name'] ?? '') }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="id" class="col-sm-2 col-form-label">Representing</label>
                            <div class="col-sm-10">
                                <input type="text" value="{{ $deal['representing'] ?? '' }}"
                                    class="form-control {{ isset($deal['representing']) ? 'cursor_not_allowed' : '' }}"
                                    id="Representing" name="Representing" placeholder=""
                                    {{ isset($deal['representing']) ? 'readonly' : '' }} />
                                <div class="invalid-feedback">
                                    Please choose a Representing
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="id" class="col-sm-2 col-form-label">Closing Date
                            </label>
                            <div class="col-sm-10">
                                <input type="date"
                                    value="{{ $deal['closing_date'] ? \Carbon\Carbon::parse($deal['closing_date'])->format('Y-m-d') : '' }}"
                                    class="form-control cursor_not_allowed" id="closing_date" name="closing_date"
                                    placeholder=""/>
                                <div class="invalid-feedback">
                                    Please choose a eCommission Payout
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="id" class="col-sm-2 col-form-label">Stage</label>
                            <div class="col-sm-10">
                                <input type="text" value="{{ $deal['stage'] }}"
                                    class="form-control cursor_not_allowed" id="stage" name="irs_rep"
                                    placeholder="" readonly />
                                <div class="invalid-feedback">
                                    Please choose a Stage
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="id" class="col-sm-2 col-form-label">Agent Commission Income
                                Owner</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="agent_owner"
                                    value="{{ $contact['userData']['name'] ?? '' }}" name="agent_owner"
                                    placeholder="$" required />
                                <div class="invalid-feedback">
                                    Please choose a Agent Owner
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="id" class="col-sm-2 col-form-label">Commission Notes</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="comm_notes" name="comm_notes"
                                    placeholder="" required />
                                <div class="invalid-feedback">
                                    Please choose a Commission Notes
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="id" class="col-sm-2 col-form-label">Mentee Amount Paid</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="ammount_paid" name="ammount_paid"
                                    placeholder="$" required />
                                <div class="invalid-feedback">
                                    Please choose a Mentee Amount Paid
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="id" class="col-sm-2 col-form-label">Import Batch ID
                            </label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="imp_batch_id" name="imp_batch_id"
                                    placeholder="Enter your Import Batch ID" required />
                                <div class="invalid-feedback">
                                    Please choose a Import Batch ID
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="id" class="col-sm-2 col-form-label">Total Gross Commission
                            </label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="total_gross_comm"
                                    name="total_gross_comm" placeholder="Enter your Total Gross Commission"
                                    required />
                                <div class="invalid-feedback">
                                    Please choose a Total Gross Commission

                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="id" class="col-sm-2 col-form-label">Personal Transaction
                            </label>
                            <div class="col-sm-10">
                                <input type="checkbox" id="p_transaction" name="p_transaction validate"
                                    placeholder="Enter your Transacton" required />
                                <div class="invalid-feedback">
                                    Please choose a Personal Transaction
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="id" class="col-sm-2 col-form-label">Double Ended
                            </label>
                            <div class="col-sm-10">
                                <input type="checkbox" id="double_ended" name="double_ended"
                                    placeholder="Enter your Portion percent" required />
                                <div class="invalid-feedback">
                                    Please choose a Double Ended
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="id" class="col-sm-2 col-form-label">Sides
                            </label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="Sides" name="Sides"
                                    placeholder="" required />
                                <div class="invalid-feedback">
                                    Please choose a Sides
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="id" class="col-sm-2 col-form-label">Commission Percent
                            </label>
                            <div class="col-sm-10">
                                <input type="text" value="{{ $deal['commission'] }}"
                                    class="form-control {{ isset($deal['commission']) ? 'cursor_not_allowed' : '' }}"
                                    id="comm_percent" name="comm_percent" placeholder=""
                                    {{ isset($deal['commission']) ? 'readonly' : '' }} />

                                <div class="text-danger" id="comm_percent_error">
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary taskModalSaveBtn"
                        onclick="submitAgentCommission('{{ $deal['id'] }}')">
                        <i class="fas fa-save saveIcon"></i> Save Changes
                    </button>
                </form>
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let lessSplit = document.getElementById("less_split");
        let due_tm_fee = document.getElementById("due_tm_fee");
        let agentCheck = document.getElementById("agent_check");
        let irsRep = document.getElementById("irs_rep");
        let commPercent = document.getElementById("comm_percent");
        lessSplit.addEventListener("keyup", validateCommision);
        due_tm_fee.addEventListener("keyup", validateCommision);
        agentCheck.addEventListener("keyup", validateCommision);
        irsRep.addEventListener("keyup", validateCommision);
        commPercent.addEventListener("keyup", validateCommision);
    });

    function validateCommision() {
        let lessSplit = document.getElementById("less_split");
        let dueFee = document.getElementById("due_tm_fee");
        let agentCheck = document.getElementById("agent_check");
        let irsRep = document.getElementById("irs_rep");
        let commPercent = document.getElementById("comm_percent");



        let lessSplitError = document.getElementById("less_split_error");
        let dueFeeError = document.getElementById("due_tm_fee_error");
        let agentCheckError = document.getElementById("agent_check_error");
        let irsRepError = document.getElementById("irs_rep_error");
        let commPercentError = document.getElementById("comm_percent_error");

        let isValid = true;

        // Validate lessSplit
        if (lessSplit.value.trim() === "") {
            lessSplitError.textContent = "Less Split to CHR cannot be empty.";
            isValid = false;
        } else if (isNaN(lessSplit.value.trim())) {
            lessSplitError.textContent = "Less Split to CHR must be a number.";
            isValid = false;
        } else {
            lessSplitError.textContent = "";
        }

        // Validate dueFee
        if (dueFee.value.trim() === "") {
            console.log(dueFee, 'dueFee');
            dueFeeError.textContent = "TM Fees due to CHR cannot be empty.";
            isValid = false;
        } else if (isNaN(dueFee.value.trim())) {
            dueFeeError.textContent = "TM Fees due to must be a number.";
            isValid = false;
        } else {
            dueFeeError.textContent = "";
        }

        // validate agent
        if (agentCheck.value.trim() === "") {
            agentCheckError.textContent = "TM Fees due to CHR cannot be empty.";
            isValid = false;
        } else if (isNaN(agentCheck.value.trim())) {
            agentCheckError.textContent = "TM Fees due to must be a number.";
            isValid = false;
        } else {
            agentCheckError.textContent = "";
        }
        // validate irsp
        if (irsRep.value.trim() === "") {
            irsRepError.textContent = "IRS Reported 1099 Income For This Transaction cannot be empty.";
            isValid = false;
        } else if (isNaN(irsRep.value.trim())) {
            irsRepError.textContent = "IRS Reported 1099 Income For This Transaction must be a number.";
            isValid = false;
        } else {
            irsRepError.textContent = "";
        }

        if (commPercent.value.trim() === "") {
            commPercentError.textContent = "Commission Percent cannot be empty.";
            isValid = false;
        } else if (isNaN(commPercent.value.trim())) {
            commPercentError.textContent = "Commission Percent must be a number.";
            isValid = false;
        } else {
            commPercentError.textContent = "";
        }



        return isValid;
    }

    function submitAgentCommission(id) {
        if (!validateCommision()) {
            return;
        }
        let Agent_Name = document.getElementById("Agent_Name");
        let Agent_Portion = document.getElementById("Agent_Portion");
        let Portion_percent = document.getElementById("Portion_percent");
        let less_split = document.getElementById("less_split");
        let after_split = document.getElementById("after_split");
        let sale_price = document.getElementById("sale_price");
        let chr_due = document.getElementById("chr_due");
        let due_tm_fee = document.getElementById("due_tm_fee");
        let t_cost = document.getElementById("t_cost");
        let h_warranty = document.getElementById("h_warranty");
        let e_comm = document.getElementById("e_comm");
        let due_ammount = document.getElementById("due_ammount");
        let admin_fee_income = document.getElementById("admin_fee_income");
        let comm_percent = document.getElementById("comm_percent");
        let Sides = document.getElementById("Sides");
        let double_ended = document.getElementById("double_ended");
        let p_transaction = document.getElementById("p_transaction");
        let total_gross_comm = document.getElementById("total_gross_comm");
        let imp_batch_id = document.getElementById("imp_batch_id");
        let ammount_paid = document.getElementById("ammount_paid");
        let comm_notes = document.getElementById("comm_notes");
        let agent_owner = document.getElementById("agent_owner");
        let stage = document.getElementById("stage");
        let irsRep = document.getElementById("irs_rep");
        let e_comm_payout = document.getElementById("e_comm_payout");
        let closing_date = document.getElementById("closing_date");
        let Representing = document.getElementById("Representing");
        let agent_user = document.getElementById("agent_user");
        let agentCheck = document.getElementById("agent_check");
        let Transaction = document.getElementById("Transaction");
        let exchange_rate = document.getElementById("exchange_rate");
        let Currency = document.getElementById("Currency");
        let chr = document.getElementById("chr");
        let chr_gives = document.getElementById("chr_gives");
        let c_client = document.getElementById("c_client");
        let home_w_payout = document.getElementById("home_w_payout");

        let formData = {
            "data": [{
                "Exchange_Rate": exchange_rate?.value.trim(),
                "Owner": {
                    "id": "{{$user->root_user_id}}",
                    "full_name": "{{$user->name}}"
                },
                "Personal_Transaction": false,
                "Double_Ended": false,
                "Currency": Currency?.value.trim(),
                "Transaction": {
                    "id": Transaction.value.trim(),
                    "name": Transaction.textContent.trim(),
                },
                "Name": Agent_Name.textContent.trim(),
                "Agent_Portion_of_Commission_that_gets_split": Agent_Portion.value.trim(),
                "CHR_Agent": {
                    "id": agent_user.value.trim(),
                    "name": agent_user.textContent.trim(),
                },
                "Stage": stage.value.trim(),
                "Portion_of_Total": Portion_percent.value,
                "Sides": Sides.value,
                "TM_Fees_due_to_CHR": due_tm_fee.value,
                "Total_Gross_Commission": total_gross_comm.value,
                "Colorado_Home_Realty": chr.value,
                "Less_Split_to_CHR": less_split.value,
                "Sale_Price": sale_price.value,
                "Agent_Check_Amount": agentCheck.value,
                "IRS_Reported_1099_Income_For_This_Transaction": irsRep.value,
                "Representing": Representing.value,
                "Commission_Percent": comm_percent.value,
                "Closing_Date": closing_date.value,
                "zia_suggested_users": {}
            }],
            "skip_mandatory": false
        }
        $.ajax({
            url: '/aci_create',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
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
</script>
