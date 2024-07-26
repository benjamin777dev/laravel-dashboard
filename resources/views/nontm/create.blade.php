@extends('layouts.master')

@section('title', 'Agent Commander | NonTm')

@section('content')
<div class="nontm-header">
    <div class="non-title-div">
        <p>NON-TM CHECK REQUEST WIZARD</p>
    </div>
    <div class="non-btns">
        <a href="{{ url('/pipeline-view/' . $dealData['dealData']['id']) }}">
            <div class="input-group-text text-white justify-content-center ppipeBtn"  >
                <i class="fas fa-times">
                </i>
                Cancel
            </div>
        </a>
        <div class="ppipeBtn" onclick="updateNonTm({{ $dealData }},true)">
            <i class="fas fa-save saveIcon"></i> Save 
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Non-TM Check Request Information</h4>

                <div id="basic-example-nontm-view">
                    <!-- Seller Details -->
                    <h3>Basic Information</h3>
                    <section>
                        <form>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label>Related Transaction <svg
                                            xmlns="http://www.w3.org/2000/svg" width="19" height="18"
                                            viewBox="0 0 19 18" fill="none">
                                            <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse"
                                                x="0" y="0" width="19" height="18">
                                                <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                            </mask>
                                            <g mask="url(#mask0_2151_10662)">
                                                <path
                                                    d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z"
                                                    fill="#AC5353" />
                                            </g>
                                        </svg></label>
                                        <select name="related_transaction" id="related_transaction" class="form-select validate_err" disabled>
                                            @foreach ($deals as $deal)
                                               <option value="{{ $deal->zoho_deal_id }}"
                                                   {{ $deal->zoho_deal_id == $dealData->dealId ? 'selected' : '' }}>
                                                   {{ $deal->deal_name }}
                                               </option>
                                           @endforeach
                                       </select>
                                    </div>
                                    <div id="related_transaction_error" class="text-danger">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="basicpill-lastname-input">Additional Email for Confirmation </label>
                                        <input type="email" value="{{ isset($dealData['email']) ? $dealData['email'] : '' }}"
                                        class="form-control" placeholder="Enter email" id="add_email">
                                        <div class="add_email_error text-danger" id="add_email_error">
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="basicpill-email-input">Close Date <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18" fill="none">
                                            <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="19" height="18">
                                                <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                            </mask>
                                            <g mask="url(#mask0_2151_10662)">
                                                <path d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z" fill="#AC5353" />
                                            </g>
                                        </svg></label>
                                        <input type="date" class="form-control" value="{{ isset($dealData['dealData']['closing_date']) ? \Carbon\Carbon::parse($dealData['dealData']['closing_date'])->format('Y-m-d') : '' }}" id="close_date" >
                                        <div id="close_date_error" class="text-danger">
                
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="basicpill-phoneno-input">Commission %<svg
                                            xmlns="http://www.w3.org/2000/svg" width="19" height="18"
                                            viewBox="0 0 19 18" fill="none">
                                            <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse"
                                                x="0" y="0" width="19" height="18">
                                                <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                            </mask>
                                            <g mask="url(#mask0_2151_10662)">
                                                <path
                                                    d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z"
                                                    fill="#AC5353" />
                                            </g>
                                        </svg></label>
                                        <input type="text" value="{{ isset($dealData['dealData']['commission']) ? $dealData['dealData']['commission'] : '' }}"
                                        class="form-control" id="commission">
                                        <div id="commission_error" class="text-danger">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </section>
                    <h3>Basic Information</h3>
                    <section>
                        <form>
                            <div class="accordion" id="accordionExample">
                                <!-- Referral Fee Paid Out -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseReferral" aria-expanded="false" aria-controls="collapseReferral">
                                            Referral Fee Paid Out?
                                            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18" fill="none">
                                                <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="19" height="18">
                                                    <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                                </mask>
                                                <g mask="url(#mask0_2151_10662)">
                                                    <path d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z" fill="#AC5353" />
                                                </g>
                                            </svg>
                                        </button>
                                    </h2>
                                    <div id="collapseReferral" class="accordion-collapse collapse" aria-labelledby="headingOne" >
                                        <div class="accordion-body">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="referralFee" value="yes" id="referralYes" {{ isset($dealData['referral_fee_paid_out']) && $dealData['referral_fee_paid_out'] === "YES" ? 'checked' : '' }}>
                                                <label class="form-check-label" for="referralYes">Yes</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="referralFee" value="no" id="referralNo" {{ isset($dealData['referral_fee_paid_out']) && $dealData['referral_fee_paid_out'] === "NO" ? 'checked' : '' }}>
                                                <label class="form-check-label" for="referralNo">No</label>
                                            </div>
                                            <div class="referralCustomFields label-div-mb" style="margin-top:10px;display:none;">
                                                <div class="referralFeeAmount label-div-mb">
                                                    <label for="referralFeeAmount" class="common-label">Referral Fee Amount</label>
                                                    <input type="text" value="{{ isset($dealData['referralFeeAmount']) ? $dealData['referralFeeAmount'] : '' }}" class="form-control" id="referralFeeAmount">
                                                    <div class="referralFeeAmount_error text-danger" id="referralFeeAmount_error"></div>
                                                </div>
                                                <div class="referralFeeBrokerage label-div-mb">
                                                    <label for="referralFeeBrokerage" class="common-label">Referral Fee Brokerage Name</label>
                                                    <input type="text" value="{{ isset($dealData['referralFeeBrokerage']) ? $dealData['referralFeeBrokerage'] : '' }}" class="form-control" id="referralFeeBrokerage">
                                                    <div class="referralFeeBrokerage_error text-danger" id="referralFeeBrokerage_error"></div>
                                                </div>
                                                <div class="referralAgreement label-div-mb">
                                                    <label for="referralAgreement" class="common-label">Referral Fee Agreement Executed</label>
                                                    <input type="text" value="{{ isset($dealData['referralAgreement']) ? $dealData['referralAgreement'] : '' }}" class="form-control" id="referralAgreement">
                                                    <div class="referralAgreement_error text-danger" id="referralAgreement_error"></div>
                                                </div>
                                                <div class="hasW9Provided label-div-mb">
                                                    <label for="hasW9Provided" class="common-label">Has the W-9 been provided</label>
                                                    <input type="text" value="{{ isset($dealData['hasW9Provided']) ? $dealData['hasW9Provided'] : '' }}" class="form-control" id="hasW9Provided">
                                                    <div class="hasW9Provided_error text-danger" id="hasW9Provided_error"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Home Warranty Paid Out Agent -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingTwo">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseHomeWarranty1" aria-expanded="false" aria-controls="collapseHomeWarranty1">
                                            Home Warranty Paid Out Agent?
                                            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18" fill="none">
                                                <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="19" height="18">
                                                    <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                                </mask>
                                                <g mask="url(#mask0_2151_10662)">
                                                    <path d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z" fill="#AC5353" />
                                                </g>
                                            </svg>
                                        </button>
                                    </h2>
                                    <div id="collapseHomeWarranty1" class="accordion-collapse collapse" aria-labelledby="headingTwo" >
                                        <div class="accordion-body">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="homeWarranty" value="yes" id="homeWarrantyYes" {{ isset($dealData['home_warranty_paid_out_agent']) && $dealData['home_warranty_paid_out_agent'] === "YES" ? 'checked' : '' }}>
                                                <label class="form-check-label" for="homeWarrantyYes">Yes</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="homeWarranty" value="no" id="homeWarrantyNo" {{ isset($dealData['home_warranty_paid_out_agent']) && $dealData['home_warranty_paid_out_agent'] === "NO" ? 'checked' : '' }}>
                                                <label class="form-check-label" for="homeWarrantyNo">No</label>
                                            </div>
                                            <div class="homeWarrentyFields label-div-mb" style="margin-top:10px;display:none;">
                                                <div class="homeWarrentyAmount label-div-mb">
                                                    <label for="homeWarrentyAmount" class="common-label">Home Warranty Amount</label>
                                                    <input type="text" value="{{ isset($dealData['homeWarrentyAmount']) ? $dealData['homeWarrentyAmount'] : '' }}" class="form-control" id="homeWarrentyAmount">
                                                    <div class="homeWarrentyAmount_error text-danger" id="homeWarrentyAmount_error"></div>
                                                </div>
                                                <div class="homeWarrentyDescription label-div-mb">
                                                    <label for="homeWarrentyDescription" class="common-label">Home Warranty Description</label>
                                                    <input type="text" value="{{ isset($dealData['homeWarrentyDescription']) ? $dealData['homeWarrentyDescription'] : '' }}" class="form-control" id="homeWarrentyDescription">
                                                    <div class="homeWarrentyDescription_error text-danger" id="homeWarrentyDescription_error"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Additional Fees Charged -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingThree">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAdditionalFees" aria-expanded="false" aria-controls="collapseAdditionalFees">
                                            Any Additional Fees Charged?
                                            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18" fill="none">
                                                <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="19" height="18">
                                                    <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                                </mask>
                                                <g mask="url(#mask0_2151_10662)">
                                                    <path d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z" fill="#AC5353" />
                                                </g>
                                            </svg>
                                        </button>
                                    </h2>
                                    <div id="collapseAdditionalFees" class="accordion-collapse collapse" aria-labelledby="headingThree" >
                                        <div class="accordion-body">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="additionalFees" value="yes" id="additionalFeesYes" {{ isset($dealData['any_additional_fees_charged']) && $dealData['any_additional_fees_charged'] === "YES" ? 'checked' : '' }}>
                                                <label class="form-check-label" for="additionalFeesYes">Yes</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="additionalFees" value="no" id="additionalFeesNo" {{ isset($dealData['any_additional_fees_charged']) && $dealData['any_additional_fees_charged'] === "NO" ? 'checked' : '' }}>
                                                <label class="form-check-label" for="additionalFeesNo">No</label>
                                            </div>
                                            <div class="additionalFeesFields label-div-mb" style="margin-top:10px;display:none;">
                                                <div class="additionalFeeAmount label-div-mb">
                                                    <label for="additionalFeeAmount" class="common-label">Additional Fee Amount</label>
                                                    <input type="text" value="{{ isset($dealData['additionalFeesAmount']) ? $dealData['additionalFeesAmount'] : '' }}" class="form-control" id="additionalFeeAmount">
                                                    <div class="additionalFeeAmount_error text-danger" id="additionalFeeAmount_error"></div>
                                                </div>
                                                <div class="additionalFeeDescription label-div-mb">
                                                    <label for="additionalFeeDescription" class="common-label">Additional Fee Description</label>
                                                    <input type="text" value="{{ isset($dealData['additionalFeesDescription']) ? $dealData['additionalFeesDescription'] : '' }}" class="form-control" id="additionalFeeDescription">
                                                    <div class="additionalFeeDescription_error text-danger" id="additionalFeeDescription_error"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </section>

                    <h3>Basic Information</h3>
                    <section>
                        <form>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label>Final Purchase Price <svg
                                            xmlns="http://www.w3.org/2000/svg" width="19" height="18"
                                            viewBox="0 0 19 18" fill="none">
                                            <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse"
                                                x="0" y="0" width="19" height="18">
                                                <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                            </mask>
                                            <g mask="url(#mask0_2151_10662)">
                                                <path
                                                    d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z"
                                                    fill="#AC5353" />
                                            </g>
                                        </svg></label>
                                        <input type="text"
                                        value="{{ isset($dealData['final_purchase_price']) ? $dealData['final_purchase_price'] : '' }}"
                                        placeholder="$" class="form-control" id="final_purchase">
                                        <div id="final_purchase_error" class="text-danger">
                
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="basicpill-lastname-input">Amount to CHR GIves </label>
                                        <input type="text" value="{{ isset($dealData['amount_to_chr_gives']) ? $dealData['amount_to_chr_gives'] : '' }}"
                                        placeholder="$" class="form-control nontm-input" id="amount_chr">
                                        <div id="amount_chr_error" class="text-danger">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="basicpill-email-input">Checks Payable to</label>
                                        <select name="additional_charge" id="additonal_fee"
                                        class="form-select second-step-common-select select-mb24" id="">
                                        <option value="" selected>{{ $deal->userData->name }}</option>
                                    </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="basicpill-phoneno-input">Agent Comments/Remarks/Instructions<svg
                                            xmlns="http://www.w3.org/2000/svg" width="19" height="18"
                                            viewBox="0 0 19 18" fill="none">
                                            <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse"
                                                x="0" y="0" width="19" height="18">
                                                <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                            </mask>
                                            <g mask="url(#mask0_2151_10662)">
                                                <path
                                                    d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z"
                                                    fill="#AC5353" />
                                            </g>
                                        </svg></label>
                                        <textarea placeholder="Add Copy" id="agent_comments" class="form-control" rows="2" placeholder="Enter Your Address">{{ isset($dealData['agent_comments']) ? $dealData['agent_comments'] : '' }}</textarea>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="basicpill-phoneno-input">Other Commission Notes</label>
                                        <textarea placeholder="Add Copy" id="other_comm_notes" class="form-control" rows="2" placeholder="Enter Your Address">{{ isset($dealData['other_commission_notes']) ? $dealData['other_commission_notes'] : '' }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </section>
                    <!-- Confirm Details -->
                    <h3>Confirm Detail</h3>
                    <section>
                        <div class="row justify-content-center">
                            <div class="col-lg-6">
                                <div class="text-center">
                                    <div class="mb-4">
                                        <i class="mdi mdi-check-circle-outline text-success display-4"></i>
                                    </div>
                                    <div>
                                        <h5>Confirm Detail</h5>
                                        <p class="text-muted"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

            </div>
            <!-- end card body -->
        </div>
        <!-- end card -->
    </div>
    <!-- end col -->
</div>
    @endsection
            <!-- jquery step -->
            <script defer src="{{ URL::asset('build/libs/jquery-steps/build/jquery.steps.min.js') }}"></script>

            <!-- form wizard init -->
            <script src="{{ URL::asset('build/js/pages/form-wizard.init.js') }}"></script>

            <script>
        window.onload = function(){
                    $(function () {
                $("#basic-example-nontm").steps({
                    headerTag: "h3",
                    bodyTag: "section",
                    transitionEffect: "slide"
                    });
                });

                let related_transaction = document.getElementById("related_transaction");
                let add_email = document.getElementById("add_email");
                let close_date = document.getElementById("close_date");
                let commission = document.getElementById("commission");
                let final_purchase = document.getElementById("final_purchase");
                let amount_chr = document.getElementById("amount_chr");
        


        // Select all radio buttons
                const radioButtons = document.querySelectorAll('input[type="radio"]');

                // Initialize an object to store the values
                window.values = {};

                radioButtons.forEach(radioButton => {
                    if (radioButton.checked) {
                        const question = radioButton.closest('.accordion-item').querySelector('button').textContent.trim();
                        const value = radioButton.value;
                        window.values[question] = value;
                        openNewFields();
                    }
                
                });

                // Add event listener to each radio button
                document.addEventListener('change', event => {
                if (event.target.matches('input[type="radio"]')) {
                    const radioButton = event.target;
                    const question = radioButton.closest('.accordion-item').querySelector('button').textContent.trim();
                    const value = radioButton.value;
                    window.values[question] = value;
                        openNewFields();
                    }
                });

                // Get all accordion buttons
                const accordionButtons = document.querySelectorAll('.accordion-button');

                // Add event listener to each button
                accordionButtons.forEach((button) => {
                    // Get the accordion item
                    const accordionItem = button.closest('.accordion-item');

                    // Get the collapse element
                    const collapseElement = accordionItem.querySelector('.accordion-collapse');

                    // Toggle the collapse element
                    collapseElement.classList.toggle('show');


                    // Update the aria-expanded attribute
                    if (collapseElement.classList.contains('show')) {
                        button.classList.remove('collapsed');
                        button.setAttribute('aria-expanded', 'true');
                    } else {
                        button.classList.add('collapsed');
                        button.setAttribute('aria-expanded', 'false');
                    }
                });
     }

 window.openNewFields=function(field){
        let referralData =window.values['Referral Fee Paid Out?']
        let homeWarrentyData =window.values['Home Warranty Paid Out Agent?']
        let additionalFees =window.values['Any Additional Fees Charged?']
        console.log("Referral Fee Paid Out?",);
        if(referralData==="yes"){
            $(".referralCustomFields").show();
        }else{
            $(".referralCustomFields").hide();
        } 

        if(homeWarrentyData==="yes"){
            $(".homeWarrentyFields").show();
        }else{
            $(".homeWarrentyFields").hide();
        } 

        if(additionalFees==="yes"){
            $(".additionalFeesFields").show();
        }else{
            $(".additionalFeesFields").hide();
        } 
    }
    function isValidUrl(url) {
        var regex = new RegExp(
            '^(https?:\\/\\/)' + // protocol
            '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.?)+[a-z]{2,}|' + // domain name
            '((\\d{1,3}\\.){3}\\d{1,3})|' + // OR ip (v4) address
            '\\[?[a-f\\d]*:[a-f\\d:]+\\]?)' + // OR ip (v6) address
            '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // port and path
            '(\\?[;&a-z\\d%_.~+=-]*)?' + // query string
            '(\\#[-a-z\\d_]*)?$', 'i' // fragment locator
        );
        return regex.test(url);
    }

    function validateNonTm() {
        let related_transaction = document.getElementById("related_transaction");
        let add_email = document.getElementById("add_email");
        let close_date = document.getElementById("close_date");
        let commission = document.getElementById("commission");
        let final_purchase = document.getElementById("final_purchase");
        let amount_chr = document.getElementById("amount_chr");

        let related_transactionError = document.getElementById("related_transaction_error");
        let add_emailError = document.getElementById("add_email_error");
        let close_dateError = document.getElementById("close_date_error");
        let commissionError = document.getElementById("commission_error");
        let final_purchaseError = document.getElementById("final_purchase_error");
        let amount_chrError = document.getElementById("amount_chr_error");
        /* let additionalCharges_error = document.getElementById("amount_chr_error");*/

        let referralData =window.values['Referral Fee Paid Out?']
        let homeWarrentyData =window.values['Home Warranty Paid Out Agent?']
        let additionalFees =window.values['Any Additional Fees Charged?']
        

        let isValid = true;

        if(referralData==''||referralData == null){
            isValid = false;
            showToastError('Referral Fee Paid Out? is required')
        }

        if(homeWarrentyData==''||homeWarrentyData == null){
            isValid = false;
            showToastError('Home Warranty Paid Out Agent?')
        }
        if(additionalFees==''||additionalFees == null){
            isValid = false;
            showToastError('Any Additional Fees Charged?')
        }


        if (document.querySelector('input[name="referralFee"]:checked').value === "yes") {
            if (document.getElementById('referralFeeAmount').value.trim() === "") {
                isValid = false;
                document.getElementById('referralFeeAmount_error').innerText = "Referral Fee Amount is required.";
            } else {
                document.getElementById('referralFeeAmount_error').innerText = "";
            }

            if (document.getElementById('referralFeeBrokerage').value.trim() === "") {
                isValid = false;
                document.getElementById('referralFeeBrokerage_error').innerText = "Referral Fee Brokerage Name is required.";
            } else {
                document.getElementById('referralFeeBrokerage_error').innerText = "";
            }

            if (document.getElementById('referralAgreement').value.trim() === "") {
                isValid = false;
                document.getElementById('referralAgreement_error').innerText = "Referral Fee Agreement Executed is required.";
            } else {
                document.getElementById('referralAgreement_error').innerText = "";
            }

            if (document.getElementById('hasW9Provided').value.trim() === "") {
                isValid = false;
                document.getElementById('hasW9Provided_error').innerText = "W-9 provision status is required.";
            } else {
                document.getElementById('hasW9Provided_error').innerText = "";
            }
        }

        // Home Warranty Validation
        if (document.querySelector('input[name="homeWarranty"]:checked').value === "yes") {
            if (document.getElementById('homeWarrentyAmount').value.trim() === "") {
                isValid = false;
                document.getElementById('homeWarrentyAmount_error').innerText = "Home Warranty Amount is required.";
            } else {
                document.getElementById('homeWarrentyAmount_error').innerText = "";
            }

            if (document.getElementById('homeWarrentyDescription').value.trim() === "") {
                isValid = false;
                document.getElementById('homeWarrentyDescription_error').innerText = "Home Warranty Description is required.";
            } else {
                document.getElementById('homeWarrentyDescription_error').innerText = "";
            }
        }

        // Additional Fees Validation
        if (document.querySelector('input[name="additionalFees"]:checked').value === "yes") {
            if (document.getElementById('additionalFeeAmount').value.trim() === "") {
                isValid = false;
                document.getElementById('additionalFeeAmount_error').innerText = "Additional Fees Amount is required.";
            } else {
                document.getElementById('additionalFeeAmount_error').innerText = "";
            }

            if (document.getElementById('additionalFeeDescription').value.trim() === "") {
                isValid = false;
                document.getElementById('additionalFeeDescription_error').innerText = "Additional Fees Description is required.";
            } else {
                document.getElementById('additionalFeeDescription_error').innerText = "";
            }
        }

        // Validate related_transaction
        if (related_transaction.value.trim() === "" ) {
            related_transactionError.textContent = "Transaction Related cannot be empty.";
            isValid = false;
        }
        let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
       if (add_email.value.trim() === "" ) {
            console.log(add_email.value, "ADD EMAIL"); // Logging the value for debugging purposes
           add_emailError.textContent = "Please Enter Email.";
            // Regular expression to match a standard email format
            // Checking if the input value matches the email regex
        }

          if (!emailRegex.test(add_email.value) && add_email.value.trim() !=="") {
               console.log('yesfhksdhfksdfug')
                add_emailError.textContent = "Additional Email for confirmation should be in email format.";
                isValid = false;
        }
        
        // else if (isNaN(related_transaction.value.trim())) {
        //     related_transactionError.textContent = "Transaction Related must be a number.";
        //     isValid = false;
        // }
        else {
            related_transactionError.textContent = "";
            add_emailError.textContent = "";
        }

        // Validate close_date
        if (close_date.value.trim() === "") {
            console.log(close_date, 'close_date');
            close_dateError.textContent = "Close Date cannot be empty.";
            isValid = false;
        }
        //  else if (isNaN(close_date.value.trim())) {
        //     close_dateError.textContent = "TM Fees due to must be a number.";
        //     isValid = false;
        // } 
        else {
            close_dateError.textContent = "";
        }

        // validate commissionError
        if (commission.value.trim() === "") {
            commissionError.textContent = "Commission cannot be empty.";
            isValid = false;
        } else if (isNaN(commission.value.trim())) {
            commissionError.textContent = "Commission must be a number.";
            isValid = false;
        } else if (commission.value.trim().split('.')[0]&&commission.value.trim().length>4) {
            commissionError.textContent = "Commission length must be exactly 4 characters.";
            isValid = false;
        }else {
            commissionError.textContent = "";
        }

        // validate commissionError
        final_purchase.value = final_purchase.value.trim().split('.')[0]
        if (final_purchase.value.trim() === "") {
            final_purchaseError.textContent = "Final Purchase cannot be empty.";
            isValid = false;
        } else if (isNaN(final_purchase.value.trim())) {
            final_purchaseError.textContent = "Final Purchase must be a number.";
            isValid = false;
        } else if (final_purchase.value.trim().length <= 0) {
            final_purchaseError.textContent = "Final Purchase length must be greater than 0 characters.";
            isValid = false;
        }else {
            final_purchaseError.textContent = "";
        }
        
        if(amount_chr){
            amount_chr.value = amount_chr.value.trim().split('.')[0]
            if (isNaN(amount_chr.value.trim())) {
                amount_chrError.textContent = "Amount to CHR must be a number.";
                isValid = false;
            } else if (amount_chr.value.trim().length<=0) {
                amount_chrError.textContent = "Amount to CHR length must be greater than 0 characters.";
                isValid = false;
            }else {
                amount_chrError.textContent = "";
            }
        }
        return isValid;
    }

    window.updateNonTm = function(dealData, status) {
        // dealData = JSON.parse(dealData)
        console.log(dealData);
        id=dealData.id
        if (!validateNonTm()) {
            return;
        }

        let related_transaction = document.getElementById("related_transaction");
        let add_email = document.getElementById("add_email");
        let close_date = document.getElementById("close_date");
        let commission = document.getElementById("commission");
        let final_purchase = document.getElementById("final_purchase");
        let amount_chr = document.getElementById("amount_chr");
        // let additonal_fee = document.getElementById("additonal_fee");
        let other_comm_notes = document.getElementById("other_comm_notes");
        let agent_comments = document.getElementById("agent_comments");
        let referralFeeAmount = document.getElementById("referralFeeAmount");
        let referralFeeBrokerage = document.getElementById("referralFeeBrokerage");
        let referralAgreement = document.getElementById("referralAgreement");
        let hasW9Provided = document.getElementById("hasW9Provided");
        let homeWarrentyAmount = document.getElementById("homeWarrentyAmount");
        let homeWarrentyDescription = document.getElementById("homeWarrentyDescription");
        let additionalFeeAmount = document.getElementById("additionalFeeAmount");
        let additionalFeeDescription = document.getElementById("additionalFeeDescription");
        var selectedOption = related_transaction.options[related_transaction.selectedIndex];
        // Get the value and text of the selected option
        var selectedValue = selectedOption.value;
        var selectedText = selectedOption.textContent;
        let formData = {
            "data": [{
                "Commission":commission?.value?.trim().split('.')[0] ?? undefined,
                "Final_Purchase_Price": final_purchase? final_purchase.value.trim():undefined,
                "Any_Additional_Fees_Charged":window?.values["Any Additional Fees Charged?"]? window?.values["Any Additional Fees Charged?"]
                    ?.toUpperCase():undefined,
                "Additonal_Fees_Amount":additionalFeeAmount? additionalFeeAmount.value.trim()
                    :undefined,
                "Additional_Fees_Description":additionalFeeDescription? additionalFeeDescription.value.trim()
                    :undefined,
                "Additional_Email_for_Confirmation":add_email? add_email.value.trim():undefined,
                "Referral_Fee_Paid_Out":window.values['Referral Fee Paid Out?']? window.values['Referral Fee Paid Out?']?.toUpperCase():undefined,
                "Referral_Fee_Amount":referralFeeAmount? referralFeeAmount.value.trim():undefined,
                "Referral_Fee_Brokerage_Name":referralFeeBrokerage? referralFeeBrokerage.value.trim():undefined,
                "Referral_Fee_Agreement_Executed":referralAgreement? referralAgreement.value.trim():undefined,
                "Has_the_W-9_been_provided":hasW9Provided? hasW9Provided.value.trim():undefined,
                "Close_Date": close_date?close_date.value.trim():undefined,
                "Home_Warranty_Paid_by_Agent":window.values['Home Warranty Paid Out Agent?']? window.values['Home Warranty Paid Out Agent?']?.toUpperCase():undefined,
                "Home_Warranty_Amount":homeWarrentyAmount? homeWarrentyAmount.value.trim():undefined,
                "Home_Warranty_Description":homeWarrentyDescription? homeWarrentyDescription.value.trim():undefined,
                "CHR_Gives_Amount_to_Give":amount_chr? amount_chr.value.trim():undefined,
                "Other_Commission_Notes":other_comm_notes.value? other_comm_notes.value.trim():undefined,
                "Agent_Comments_Remarks_Instructions":agent_comments.value ? agent_comments.value.trim():undefined,
                "Related_Transaction":selectedValue ? {
                    "id": selectedValue.trim(),
                    "name": selectedText.trim(),
                }:undefined,
            }]
        }

        formData.data[0] = Object.fromEntries(
            Object.entries(formData.data[0]).filter(([_, value]) => value !== undefined)
        );
        
        $.ajax({
            url: '/nontm-update/' + id + '?status=' + status,
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
                    window.location.href = "/pipeline-view/" + dealData.deal_data.id;
                }
            },
            error: function(xhr, status, error) {
                // Handle error response
                console.error(xhr.responseText);
            }
        })

    }
           
            </script>


