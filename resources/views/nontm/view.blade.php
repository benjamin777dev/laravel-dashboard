@extends('layouts.master')

@section('title', 'Agent Commander | NonTm')

@section('content')
    <div class="nontm-header">
        <div class="non-title-div">
            <p>NON-TM CHECK REQUEST WIZARD</p>
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
                                        <option value="{{ $dealData->zoho_deal_id }}">
                                            {{ $dealData->dealData->deal_name }}
                                        </option>
                                </select>

                                <img src="{{ URL::asset('/images/domain_add.svg') }}" alt="">
                            </div>
                            <div id="related_transaction_error" class="text-danger">
                            </div>
                        </div>
                        <div class="additional_email label-div-mb">
                            <label for="add_email" class="common-label">Additional Email for Confirmation</label>
                            <input type="email" value="{{isset($dealData['email']) ? $dealData['email'] : ''}}" class="form-control" placeholder="Enter email" id="add_email" readonly>
                            <div class="add_email_error">
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
                                <input type="date"  value="{{isset($dealData['closed_date']) ? $dealData['closed_date'] : ''}}" class="form-control nontm-input" id="close_date" readonly>
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
                                <input type="text" value="{{isset($dealData['Commission']) ? $dealData['Commission'] : ''}}" class="form-control nontm-input" id="commission" readonly>
                                <div id="commission_error" class="text-danger" >

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
                                        aria-controls="collapseReferral">
                                        Referral Fee Paid Out?
                                    </button>
                                </h2>
                                <div id="collapseReferral" class="accordion-collapse collapse"
                                    aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="referralFee"
                                            value="yes" {{ (isset($dealData['referral_fee_paid_out']) && $dealData['referral_fee_paid_out'] === 'YES') ? 'checked' : '' }} id="referralYes" readonly>                                     
                                            <label class="form-check-label" for="referralYes">
                                                Yes
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="referralFee"
                                                value="no" {{ (isset($dealData['referral_fee_paid_out']) && $dealData['referral_fee_paid_out'] === 'NO') ? 'checked' : '' }} id="referralNo" readonly>
                                            <label class="form-check-label" for="referralNo">
                                                No
                                            </label>
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
                                                value="yes" {{ (isset($dealData['home_warranty_paid_out_agent']) && $dealData['home_warranty_paid_out_agent'] === 'YES') ? 'checked' : '' }} id="homeWarrantyYes" readonly>
                                            <label class="form-check-label" for="homeWarrantyYes">
                                                Yes
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input {{ (isset($dealData['home_warranty_paid_out_agent']) && $dealData['home_warranty_paid_out_agent'] === 'NO') ? 'checked' : '' }} class="form-check-input" type="radio" name="homeWarranty"
                                                value="no" id="homeWarrantyNo" readonly>
                                            <label class="form-check-label" for="homeWarrantyNo">
                                                No
                                            </label>
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
                                                value="yes" {{ (isset($dealData['any_additional_fees_charged']) && $dealData['any_additional_fees_charged'] === 'NO') ? 'checked' : '' }} id="additionalFeeYes" readonly>
                                            <label class="form-check-label" for="additionalFeeYes">
                                                Yes
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="additionalFee"
                                                value="no" {{ (isset($dealData['any_additional_fees_charged']) && $dealData['any_additional_fees_charged'] === 'NO') ? 'checked' : '' }} id="additionalFeeNo" readonly>
                                            <label class="form-check-label" for="additionalFeeNo">
                                                No
                                            </label>
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
                            <input type="text" value="{{isset($dealData['final_purchase_price']) ? $dealData['final_purchase_price'] : ''}}" placeholder="$" class="form-control nontm-input" id="final_purchase" readonly>
                            <div id="final_purchase_error" class="text-danger">

                            </div>
                        </div>
                        <div class="col-6 commission-nontm">
                            <label for="commission" class="common-label">Amount to CHR GIves</label>
                            <input type="text" value="{{isset($dealData['amount_to_chr_gives']) ? $dealData['amount_to_chr_gives'] : ''}}" placeholder="$" class="form-control nontm-input" id="amount_chr" readonly>

                        </div>
                    </div>
                    <div>
                        <label for="payable" class="common-label">Checks Payable to</label>
                        <select name="additional_charge" id="additonal_fee"
                            class="form-select second-step-common-select select-mb24" id="">
                            <option value="" selected>{{ $dealData->userData->name }}</option>
                        </select>
                    </div>

                </div>
                <div class="carousel-item">
                    <div class="main_form_div">
                        <div class="commission-nontm select-mb24">
                            <label for="commission" class="common-label">Agent Comments/Remarks/Instructions</label>
                            <input type="textarea" value="{{isset($dealData['agent_comments']) ? $dealData['agent_comments'] : ''}}" placeholder="Add Copy" class="form-control nontm-input-textarea"
                                id="agent_comments" readonly>
                        </div>
                        <div class="commission-nontm">
                            <label for="commission" class="common-label">Other Commission Notes</label>
                            <input type="textarea" value="{{isset($dealData['other_commission_notes']) ? $dealData['other_commission_notes'] : ''}}" placeholder="Add Copy" class="form-control nontm-input-textarea"
                                id="other_comm_notes" readonly>
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

    }
</script>
