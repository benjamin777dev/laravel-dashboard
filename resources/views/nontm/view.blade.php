@extends('layouts.master')

@section('title', 'Agent Commander | NonTm')

@section('content')
    <div class="nontm-header">
        <div class="non-title-div">
            <p>NON-TM CHECK REQUEST WIZARD</p>
        </div>
        <div class="non-btns">
           
            <div class="nontm-save-btn" onclick="updateNonTm({{ $dealData }},true)" >
                <button>Update</button>

            </div>
        </div>
    </div>
    <div class="col-lg-12 main-carousel">
        <div id="carouselExampleControls" class="carousel slide " data-bs-ride="carousel"
            style="display: flex;justify-content:space-between">
            <div class="corausal-req-mb48">
                <p class="corausal-req-text">Non-TM Check Request Information</p>
            </div>
            <div class="carousel-indicators">
                <div class="prev_btn">
                    <a href=""><span class="prev">
                            < Previous</span></a>
                </div>
                <div class="bullets">
                    <button type="button" data-bs-target="#carouselExampleControls" data-bs-slide-to="0" class="active"
                        aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#carouselExampleControls" data-bs-slide-to="1"
                        aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#carouselExampleControls" data-bs-slide-to="2"
                        aria-label="Slide 3"></button>
                    <button type="button" data-bs-target="#carouselExampleControls" data-bs-slide-to="3"
                        aria-label="Slide 4"></button>
                </div>
                <div class="next_btn">
                    <a href=""><span class="next">Next ></span></a>
                </div>
            </div>
            <div class="carousel-inner">

                <div class="carousel-item active">
                    <div class="main_form_div">
                        <div class="related_trxn label-div-mb">
                            <label for="relatedto" class="common-label">Related Transaction <svg
                                    xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18"
                                    fill="none">
                                    <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0"
                                        y="0" width="19" height="18">
                                        <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                    </mask>
                                    <g mask="url(#mask0_2151_10662)">
                                        <path
                                            d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z"
                                            fill="#AC5353" />
                                    </g>
                                </svg></label>
                            <div class="nontm-select-div">
                                <select name="related_transaction" id="related_transaction" class="nontm-select">
                                    @foreach ($deals as $deal)
                                        <option value="{{ $deal->zoho_deal_id }}"
                                            {{ $deal->zoho_deal_id == $dealData->dealId ? 'selected' : '' }}>
                                            {{ $deal->deal_name }}
                                        </option>
                                    @endforeach
                                </select>

                                <img src="{{ URL::asset('/images/domain_add.svg') }}" alt="">
                            </div>
                            <div id="related_transaction_error" class="text-danger">
                            </div>
                        </div>
                        <div class="additional_email label-div-mb">
                            <label for="add_email" class="common-label">Additional Email for Confirmation</label>
                            <input type="email" value="{{ isset($dealData['email']) ? $dealData['email'] : '' }}"
                                class="form-control" placeholder="Enter email" id="add_email">
                            <div class="add_email_error text-danger" id="add_email_error">
                            </div>
                        </div>
                        <div class="close-date-comm">
                            <div class="close-date-nontm">
                                <label for="close_date" class="common-label">Close Date <svg
                                        xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18"
                                        fill="none">
                                        <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0"
                                            y="0" width="19" height="18">
                                            <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                        </mask>
                                        <g mask="url(#mask0_2151_10662)">
                                            <path
                                                d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z"
                                                fill="#AC5353" />
                                        </g>
                                    </svg></label>
                                <input type="date"
                                    value="{{ isset($dealData['closed_date']) ? $dealData['closed_date'] : '' }}"
                                    class="form-control nontm-input" id="close_date">
                                <div id="close_date_error" class="text-danger">

                                </div>
                            </div>
                            <div class="col-6 commission-nontm">
                                <label for="commission" class="common-label">Commission % <svg
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
                                    value="{{ isset($dealData['Commission']) ? $dealData['Commission'] : '' }}"
                                    class="form-control nontm-input" id="commission">
                                <div id="commission_error" class="text-danger">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">

                    <div class="main_form_div">
                        <div class="accordion" id="accordionExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseReferral" aria-expanded="true"
                                        aria-controls="collapseReferral" >
                                        Referral Fee Paid Out?
                                    </button>
                                </h2>
                                <div id="collapseReferral" class="accordion-collapse collapse"
                                    aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="referralFee"
                                                value="yes" id="referralYes" {{ isset($dealData['referral_fee_paid_out']) && $dealData['referral_fee_paid_out'] === "YES" ? 'checked' : '' }}>

                                            <label class="form-check-label" for="referralYes" >
                                                Yes
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="referralFee"
                                                value="no" id="referralNo" {{ isset($dealData['referral_fee_paid_out']) && $dealData['referral_fee_paid_out'] === "NO" ? 'checked' : '' }}>
                                            <label class="form-check-label" for="referralNo">
                                                No
                                            </label>
                                        </div>
                                        <div class="referralCustomFields label-div-mb" style="margin-top:10px;display:none;">
                                            <div class="referralFeeAmount label-div-mb">
                                                <label for="referralFeeAmount" class="common-label">Referral Fee Amount</label>
                                                <input type="text" value="{{ isset($dealData['referralFeeAmount']) ? $dealData['referralFeeAmount'] : '' }}"
                                                    class="form-control" id="referralFeeAmount">
                                                <div class="referralFeeAmount_error text-danger" id="referralFeeAmount_error">
                                                </div>
                                            </div>
                                            <div class="referralFeeBrokerage label-div-mb">
                                                <label for="referralFeeBrokerage" class="common-label">Referral Fee Brokerage Name</label>
                                                <input type="text" value="{{ isset($dealData['referralFeeBrokerage']) ? $dealData['referralFeeBrokerage'] : '' }}"
                                                    class="form-control" id="referralFeeBrokerage">
                                                <div class="referralFeeBrokerage_error text-danger" id="referralFeeBrokerage_error">
                                                </div>
                                            </div>
                                            <div class="referralAgreement label-div-mb">
                                                <label for="referralAgreement" class="common-label">Referral Fee Agreement Executed</label>
                                                <input type="text" value="{{ isset($dealData['referralAgreement']) ? $dealData['referralAgreement'] : '' }}"
                                                    class="form-control" id="referralAgreement">
                                                <div class="referralAgreement_error text-danger" id="referralAgreement_error">
                                                </div>
                                            </div>
                                            <div class="hasW9Provided label-div-mb">
                                                <label for="hasW9Provided" class="common-label">Has the W-9 been provided</label>
                                                <input type="text" value="{{ isset($dealData['hasW9Provided']) ? $dealData['hasW9Provided'] : '' }}"
                                                    class="form-control" id="hasW9Provided">
                                                <div class="hasW9Provided_error text-danger" id="hasW9Provided_error">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseHomeWarranty1" aria-expanded="false"
                                        aria-controls="collapseHomeWarranty1">
                                        Home Warranty Paid Out Agent?
                                    </button>
                                </h2>
                                <div id="collapseHomeWarranty1" class="accordion-collapse collapse"
                                    aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="homeWarranty"
                                                value="yes" id="homeWarrantyYes" {{ isset($dealData['home_warranty_paid_out_agent']) && $dealData['home_warranty_paid_out_agent'] === "YES" ? 'checked' : '' }}>
                                            <label class="form-check-label" for="homeWarrantyYes">
                                                Yes
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="homeWarranty"
                                                value="no" id="homeWarrantyNo" {{ isset($dealData['home_warranty_paid_out_agent']) && $dealData['home_warranty_paid_out_agent'] === "NO" ? 'checked' : '' }}>
                                            <label class="form-check-label" for="homeWarrantyNo">
                                                No
                                            </label>
                                        </div>
                                        <div class="homeWarrentyFields label-div-mb" style="margin-top:10px;display:none;">
                                            <div class="homeWarrentyAmount label-div-mb">
                                                <label for="homeWarrentyAmount" class="common-label"> Home Warranty Amount</label>
                                                <input type="text" value="{{ isset($dealData['homeWarrentyAmount']) ? $dealData['homeWarrentyAmount'] : '' }}"
                                                    class="form-control" id="homeWarrentyAmount">
                                                <div class="homeWarrentyAmount_error text-danger" id="homeWarrentyAmount_error">
                                                </div>
                                            </div>
                                            <div class="homeWarrentyDescription label-div-mb">
                                                <label for="homeWarrentyDescription" class="common-label">Home Warranty Description</label>
                                                <input type="text" value="{{ isset($dealData['homeWarrentyDescription']) ? $dealData['homeWarrentyDescription'] : '' }}"
                                                    class="form-control" id="homeWarrentyDescription">
                                                <div class="homeWarrentyDescription_error text-danger" id="homeWarrentyDescription_error">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseAdditionalFee2" aria-expanded="false"
                                        aria-controls="collapseAdditionalFee2">
                                        Any Additional Fees Charged?
                                    </button>
                                </h2>
                                <div id="collapseAdditionalFee2" class="accordion-collapse collapse"
                                    aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="additionalFee"
                                                value="yes" id="additionalFeeYes" {{ isset($dealData['any_additional_fees_charged']) && $dealData['any_additional_fees_charged'] === "YES" ? 'checked' : '' }}>
                                            <label class="form-check-label" for="additionalFeeYes">
                                                Yes
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="additionalFee"
                                                value="no" id="additionalFeeNo" {{ isset($dealData['any_additional_fees_charged']) && $dealData['any_additional_fees_charged'] === "NO" ? 'checked' : '' }}>
                                            <label class="form-check-label" for="additionalFeeNo">
                                                No
                                            </label>
                                        </div>
                                        <div class="additionalFeesFields label-div-mb" style="margin-top:10px;display:none;">
                                            <div class="additionalFeesAmount label-div-mb">
                                                <label for="additionalFeesAmount" class="common-label">Additional Fees Amount?</label>
                                                <input type="text" value="{{ isset($dealData['additionalFeesAmount']) ? $dealData['additionalFeesAmount'] : '' }}"
                                                    class="form-control" id="additionalFeesAmount">
                                                <div class="additionalFeesAmount_error text-danger" id="additionalFeesAmount_error">
                                                </div>
                                            </div>
                                            <div class="additionalFeesDescription label-div-mb">
                                                <label for="additionalFeesDescription" class="common-label">Additional Fees Description</label>
                                                <input type="text" value="{{ isset($dealData['additionalFeesDescription']) ? $dealData['additionalFeesDescription'] : '' }}"
                                                    class="form-control" id="additionalFeesDescription">
                                                <div class="additionalFeesDescription_error text-danger" id="additionalFeesDescription_error">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="carousel-item main_form_div">
                    <div class="close-date-comm select-mb24">
                        <div class="close-date-nontm">
                            <label for="close_date" class="common-label">Final Purchase Price <svg
                                    xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18"
                                    fill="none">
                                    <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0"
                                        y="0" width="19" height="18">
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
                                placeholder="$" class="form-control nontm-input" id="final_purchase">
                            <div id="final_purchase_error" class="text-danger">

                            </div>
                        </div>
                        <div class="col-6 commission-nontm">
                            <label for="commission" class="common-label">Amount to CHR GIves</label>
                            <input type="text"
                                value="{{ isset($dealData['amount_to_chr_gives']) ? $dealData['amount_to_chr_gives'] : '' }}"
                                placeholder="$" class="form-control nontm-input" id="amount_chr">
                                <div id="amount_chr_error" class="text-danger">

                            </div>

                        </div>
                    </div>
                    <div>
                        <label for="payable" class="common-label">Checks Payable to</label>
                        <select name="additional_charge" id="additonal_fee"
                            class="form-select second-step-common-select select-mb24" id="">
                            <option value="" selected>{{ $deal->userData->name }}</option>
                        </select>
                    </div>

                </div>
                <div class="carousel-item">
                    <div class="main_form_div">
                        <div class="commission-nontm select-mb24">
                            <label for="commission" class="common-label">Agent Comments/Remarks/Instructions</label>
                            <input type="textarea"
                                value="{{ isset($dealData['agent_comments']) ? $dealData['agent_comments'] : '' }}"
                                placeholder="Add Copy" class="form-control nontm-input-textarea" id="agent_comments">
                        </div>
                        <div class="commission-nontm">
                            <label for="commission" class="common-label">Other Commission Notes</label>
                            <input type="textarea"
                                value="{{ isset($dealData['other_commission_notes']) ? $dealData['other_commission_notes'] : '' }}"
                                placeholder="Add Copy" class="form-control nontm-input-textarea" id="other_comm_notes">
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>

@endsection

<script type="text/javascript">
    window.onload = function() {
        $(".main-carousel a span.prev").click(function(e) {
            e.preventDefault();
            $(".main-carousel .carousel-control-prev").trigger("click");
        });
        $(".main-carousel a span.next").click(function(e) {
            e.preventDefault();
            $(".main-carousel .carousel-control-next").trigger("click");
        });

        $('.nontm-select').select2();

        let related_transaction = document.getElementById("related_transaction");
        let add_email = document.getElementById("add_email");
        let close_date = document.getElementById("close_date");
        let commission = document.getElementById("commission");
        let final_purchase = document.getElementById("final_purchase");
        let amount_chr = document.getElementById("amount_chr");
        related_transaction.addEventListener("keyup", validateNonTm);
        add_email.addEventListener("keyup",validateNonTm);
        close_date.addEventListener("change", validateNonTm);
        commission.addEventListener("keyup", validateNonTm);
        final_purchase.addEventListener("keyup", validateNonTm);
        amount_chr.addEventListener("keyup", validateNonTm);

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
        radioButtons.forEach(radioButton => {
            radioButton.addEventListener('change', event => {
                const question = radioButton.closest('.accordion-item').querySelector('button')
                    .textContent.trim();
                const value = event.target.value;
                window.values[question] = value;
                openNewFields();
                console.log(values, 'valuesis hreeee')
            });
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


        let isValid = true;

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
        let additionalFeesAmount = document.getElementById("additionalFeesAmount");
        let additionalFeesDescription = document.getElementById("additionalFeesDescription");
        var selectedOption = related_transaction.options[related_transaction.selectedIndex];
        // Get the value and text of the selected option
        var selectedValue = selectedOption.value;
        var selectedText = selectedOption.textContent;
        let formData = {
            "data": [{
                "Commission":commission? commission.value.trim():undefined,
                "Final_Purchase_Price": final_purchase? final_purchase.value.trim():undefined,
                "Any_Additional_Fees_Charged":window.values["Any Additional Fees Charged?"]? window.values["Any Additional Fees Charged?"]
                    ?.toUpperCase():undefined,
                "Additional_Fees_Amount":additionalFeesAmount.value.trim()? additionalFeesAmount.value.trim()
                    :undefined,
                "Additional_Fees_Description":additionalFeesDescription.value.trim()? additionalFeesDescription.value.trim()
                    :undefined,
                "Additional_Email_for_Confirmation":add_email? add_email.value.trim():undefined,
                "Referral_Fee_Paid_Out":window.values['Referral Fee Paid Out?']? window.values['Referral Fee Paid Out?']?.toUpperCase():undefined,
                "Referral_Fee_Amount":referralFeeAmount.value.trim()? referralFeeAmount.value.trim():undefined,
                "Referral_Fee_Brokerage_Name":referralFeeBrokerage.value.trim()? referralFeeBrokerage.value.trim():undefined,
                "Referral_Fee_Agreement_Executed":referralAgreement.value.trim()? referralAgreement.value.trim():undefined,
                "Has_the_W-9_been_provided":hasW9Provided.value.trim()? hasW9Provided.value.trim():undefined,
                "Close_Date": close_date?close_date.value.trim():undefined,
                "Home_Warranty_Paid_by_Agent":window.values['Home Warranty Paid Out Agent?']? window.values['Home Warranty Paid Out Agent?']?.toUpperCase():undefined,
                "Home_Warranty_Amount":homeWarrentyAmount.value.trim()? homeWarrentyAmount.value.trim():undefined,
                "Home_Warranty_Description":homeWarrentyDescription.value.trim()? homeWarrentyDescription.value.trim():undefined,
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
