@extends('layouts.master')

@section('title', 'zPortal | NonTm')

@section('content')
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
                                            <label>Related Transaction <svg xmlns="http://www.w3.org/2000/svg" width="19"
                                                    height="18" viewBox="0 0 19 18" fill="none">
                                                    <mask id="mask0_2151_10662" style="mask-type:alpha"
                                                        maskUnits="userSpaceOnUse" x="0" y="0" width="19"
                                                        height="18">
                                                        <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                                    </mask>
                                                    <g mask="url(#mask0_2151_10662)">
                                                        <path
                                                            d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z"
                                                            fill="#AC5353" />
                                                    </g>
                                                </svg></label>
                                            <select name="related_transaction" id="related_transaction"
                                                class="form-select validate_err" disabled>
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
                                            <input type="email"
                                                value="{{ isset($dealData['email']) ? $dealData['email'] : '' }}"
                                                class="form-control" placeholder="Enter email" id="add_email">
                                            <div class="add_email_error text-danger" id="add_email_error">
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="basicpill-email-input">Close Date <svg
                                                    xmlns="http://www.w3.org/2000/svg" width="19" height="18"
                                                    viewBox="0 0 19 18" fill="none">
                                                    <mask id="mask0_2151_10662" style="mask-type:alpha"
                                                        maskUnits="userSpaceOnUse" x="0" y="0" width="19"
                                                        height="18">
                                                        <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                                    </mask>
                                                    <g mask="url(#mask0_2151_10662)">
                                                        <path
                                                            d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z"
                                                            fill="#AC5353" />
                                                    </g>
                                                </svg></label>
                                            <input type="date" class="form-control required-field validate"
                                                value="{{ isset($dealData['dealData']['closing_date']) ? \Carbon\Carbon::parse($dealData['dealData']['closing_date'])->format('Y-m-d') : '' }}"
                                                id="close_date">
                                            <div id="close_date_error" class="text-danger">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="basicpill-phoneno-input">Commission %<svg
                                                    xmlns="http://www.w3.org/2000/svg" width="19" height="18"
                                                    viewBox="0 0 19 18" fill="none">
                                                    <mask id="mask0_2151_10662" style="mask-type:alpha"
                                                        maskUnits="userSpaceOnUse" x="0" y="0" width="19"
                                                        height="18">
                                                        <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                                    </mask>
                                                    <g mask="url(#mask0_2151_10662)">
                                                        <path
                                                            d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z"
                                                            fill="#AC5353" />
                                                    </g>
                                                </svg></label>
                                            <input type="text"
                                                value="{{ isset($dealData['dealData']['commission']) ? $dealData['dealData']['commission'] : '' }}"
                                                class="form-control required-field validate integer-field" id="commission">
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
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapseReferral"
                                                aria-expanded="false" aria-controls="collapseReferral">
                                                Referral Fee Paid Out?
                                                <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18"
                                                    viewBox="0 0 19 18" fill="none">
                                                    <mask id="mask0_2151_10662" style="mask-type:alpha"
                                                        maskUnits="userSpaceOnUse" x="0" y="0" width="19"
                                                        height="18">
                                                        <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                                    </mask>
                                                    <g mask="url(#mask0_2151_10662)">
                                                        <path
                                                            d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z"
                                                            fill="#AC5353" />
                                                    </g>
                                                </svg>
                                            </button>
                                        </h2>
                                        <div id="collapseReferral" class="accordion-collapse collapse"
                                            aria-labelledby="headingOne">
                                            <div class="accordion-body">
                                                <div class="form-check">
                                                    <input class="form-check-input ignore-validation" type="radio"
                                                        name="referralFee" value="yes" id="referralYes"
                                                        {{ isset($dealData['referral_fee_paid_out']) && $dealData['referral_fee_paid_out'] === 'YES' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="referralYes">Yes</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input ignore-validation" type="radio"
                                                        name="referralFee" value="no" id="referralNo"
                                                        {{ isset($dealData['referral_fee_paid_out']) && $dealData['referral_fee_paid_out'] === 'NO' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="referralNo">No</label>
                                                </div>
                                                <div class="referralCustomFields label-div-mb"
                                                    style="margin-top:10px;display:none;">
                                                    <div class="referralFeeAmount label-div-mb">
                                                        <label for="referralFeeAmount" class="common-label">Referral Fee
                                                            Amount</label><svg xmlns="http://www.w3.org/2000/svg"
                                                            width="19" height="18" viewBox="0 0 19 18"
                                                            fill="none">
                                                            <mask id="mask0_2151_10662" style="mask-type:alpha"
                                                                maskUnits="userSpaceOnUse" x="0" y="0" width="19"
                                                                height="18">
                                                                <rect x="0.5" width="18" height="18"
                                                                    fill="#D9D9D9" />
                                                            </mask>
                                                            <g mask="url(#mask0_2151_10662)">
                                                                <path
                                                                    d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z"
                                                                    fill="#AC5353" />
                                                            </g>
                                                        </svg>
                                                        <input type="text"
                                                            value="{{ isset($dealData['referralFeeAmount']) ? $dealData['referralFeeAmount'] : '' }}"
                                                            class="form-control required-field validate"
                                                            id="referralFeeAmount">
                                                        <div class="referralFeeAmount_error text-danger"
                                                            id="referralFeeAmount_error"></div>
                                                    </div>
                                                    <div class="referralFeeBrokerage label-div-mb">
                                                        <label for="referralFeeBrokerage" class="common-label">Referral
                                                            Fee Brokerage Name</label><svg
                                                            xmlns="http://www.w3.org/2000/svg" width="19"
                                                            height="18" viewBox="0 0 19 18" fill="none">
                                                            <mask id="mask0_2151_10662" style="mask-type:alpha"
                                                                maskUnits="userSpaceOnUse" x="0" y="0" width="19"
                                                                height="18">
                                                                <rect x="0.5" width="18" height="18"
                                                                    fill="#D9D9D9" />
                                                            </mask>
                                                            <g mask="url(#mask0_2151_10662)">
                                                                <path
                                                                    d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z"
                                                                    fill="#AC5353" />
                                                            </g>
                                                        </svg>
                                                        <input type="text"
                                                            value="{{ isset($dealData['referralFeeBrokerage']) ? $dealData['referralFeeBrokerage'] : '' }}"
                                                            class="form-control" id="referralFeeBrokerage">
                                                        <div class="referralFeeBrokerage_error text-danger"
                                                            id="referralFeeBrokerage_error"></div>
                                                    </div>
                                                    <div class="referralFeeBrokerage label-div-mb">
                                                        <label for="referralFeeBrokerage" class="common-label">Referral
                                                            Fee Agreement Executed</label><svg
                                                            xmlns="http://www.w3.org/2000/svg" width="19"
                                                            height="18" viewBox="0 0 19 18" fill="none">
                                                            <mask id="mask0_2151_10662" style="mask-type:alpha"
                                                                maskUnits="userSpaceOnUse" x="0" y="0" width="19"
                                                                height="18">
                                                                <rect x="0.5" width="18" height="18"
                                                                    fill="#D9D9D9" />
                                                            </mask>
                                                            <g mask="url(#mask0_2151_10662)">
                                                                <path
                                                                    d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z"
                                                                    fill="#AC5353" />
                                                            </g>
                                                        </svg>
                                                        <div class="d-flex gap-2">
                                                            <div class="mb-3">
                                                                <input type="radio" id="referralAgreementYes"
                                                                    {{ isset($dealData['referralAgreement']) && $dealData['referralAgreement'] == 'yes' ? 'checked' : '' }}
                                                                    name="referralAgreement" value="yes"
                                                                    class="form-check-input">
                                                                <label for="referralAgreementYes">
                                                                    Yes
                                                                </label>
                                                            </div>
                                                            <div class="mb-3">
                                                                <input type="radio" id="referralAgreementNo"
                                                                    {{ isset($dealData['referralAgreement']) && $dealData['referralAgreement'] == 'no' ? 'checked' : '' }}
                                                                    name="referralAgreement" value="no"
                                                                    class="form-check-input">
                                                                <label for="referralAgreementNo">
                                                                    No
                                                                </label>
                                                            </div>
                                                            <div class="referralAgreement_error text-danger"
                                                                id="referralAgreement_error"></div>
                                                        </div>
                                                    </div>


                                                    <div class="hasW9Provided label-div-mb">
                                                        <label class="common-label">Has the W-9 been
                                                            provided</label><svg xmlns="http://www.w3.org/2000/svg"
                                                            width="19" height="18" viewBox="0 0 19 18"
                                                            fill="none">
                                                            <mask id="mask0_2151_10662" style="mask-type:alpha"
                                                                maskUnits="userSpaceOnUse" x="0" y="0" width="19"
                                                                height="18">
                                                                <rect x="0.5" width="18" height="18"
                                                                    fill="#D9D9D9" />
                                                            </mask>
                                                            <g mask="url(#mask0_2151_10662)">
                                                                <path
                                                                    d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z"
                                                                    fill="#AC5353" />
                                                            </g>
                                                        </svg>
                                                        <div class="d-flex gap-2">
                                                            <div class="mb-3">
                                                                <input type="radio" id="hasW9ProvidedYes"
                                                                    {{ isset($dealData['hasW9Provided']) && $dealData['hasW9Provided'] == 'yes' ? 'checked' : '' }}
                                                                    name="hasW9Provided" value="yes"
                                                                    class="form-check-input">
                                                                <label for="hasW9ProvidedYes">
                                                                    Yes
                                                                </label>
                                                            </div>
                                                            <div class="mb-3">
                                                                <input type="radio" id="hasW9ProvidedNo"
                                                                    {{ isset($dealData['hasW9Provided']) && $dealData['hasW9Provided'] == 'no' ? 'checked' : '' }}
                                                                    name="hasW9Provided" value="no"
                                                                    class="form-check-input">
                                                                <label for="hasW9ProvidedNo">
                                                                    No
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="hasW9Provided_error text-danger"
                                                            id="hasW9Provided_error"></div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Home Warranty Paid Out Agent -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingTwo">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapseHomeWarranty1"
                                                aria-expanded="false" aria-controls="collapseHomeWarranty1">
                                                Home Warranty Paid Out Agent?
                                                <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18"
                                                    viewBox="0 0 19 18" fill="none">
                                                    <mask id="mask0_2151_10662" style="mask-type:alpha"
                                                        maskUnits="userSpaceOnUse" x="0" y="0" width="19"
                                                        height="18">
                                                        <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                                    </mask>
                                                    <g mask="url(#mask0_2151_10662)">
                                                        <path
                                                            d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z"
                                                            fill="#AC5353" />
                                                    </g>
                                                </svg>
                                            </button>
                                        </h2>
                                        <div id="collapseHomeWarranty1" class="accordion-collapse collapse"
                                            aria-labelledby="headingTwo">
                                            <div class="accordion-body">
                                                <div class="form-check">
                                                    <input class="form-check-input ignore-validation" type="radio"
                                                        name="homeWarranty" value="yes" id="homeWarrantyYes"
                                                        {{ isset($dealData['home_warranty_paid_out_agent']) && $dealData['home_warranty_paid_out_agent'] === 'YES' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="homeWarrantyYes">Yes</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input ignore-validation" type="radio"
                                                        name="homeWarranty" value="no" id="homeWarrantyNo"
                                                        {{ isset($dealData['home_warranty_paid_out_agent']) && $dealData['home_warranty_paid_out_agent'] === 'NO' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="homeWarrantyNo">No</label>
                                                </div>
                                                <div class="homeWarrentyFields label-div-mb"
                                                    style="margin-top:10px;display:none;">
                                                    <div class="homeWarrentyAmount label-div-mb">
                                                        <label for="homeWarrentyAmount" class="common-label">Home Warranty
                                                            Amount</label><svg xmlns="http://www.w3.org/2000/svg"
                                                            width="19" height="18" viewBox="0 0 19 18"
                                                            fill="none">
                                                            <mask id="mask0_2151_10662" style="mask-type:alpha"
                                                                maskUnits="userSpaceOnUse" x="0" y="0" width="19"
                                                                height="18">
                                                                <rect x="0.5" width="18" height="18"
                                                                    fill="#D9D9D9" />
                                                            </mask>
                                                            <g mask="url(#mask0_2151_10662)">
                                                                <path
                                                                    d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z"
                                                                    fill="#AC5353" />
                                                            </g>
                                                        </svg>
                                                        <input type="text"
                                                            value="{{ isset($dealData['homeWarrentyAmount']) ? $dealData['homeWarrentyAmount'] : '' }}"
                                                            class="form-control" id="homeWarrentyAmount">
                                                        <div class="homeWarrentyAmount_error text-danger"
                                                            id="homeWarrentyAmount_error"></div>
                                                    </div>
                                                    <div class="homeWarrentyDescription label-div-mb">
                                                        <label for="homeWarrentyDescription" class="common-label">Home
                                                            Warranty Description</label><svg
                                                            xmlns="http://www.w3.org/2000/svg" width="19"
                                                            height="18" viewBox="0 0 19 18" fill="none">
                                                            <mask id="mask0_2151_10662" style="mask-type:alpha"
                                                                maskUnits="userSpaceOnUse" x="0" y="0" width="19"
                                                                height="18">
                                                                <rect x="0.5" width="18" height="18"
                                                                    fill="#D9D9D9" />
                                                            </mask>
                                                            <g mask="url(#mask0_2151_10662)">
                                                                <path
                                                                    d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z"
                                                                    fill="#AC5353" />
                                                            </g>
                                                        </svg>
                                                        <input type="text"
                                                            value="{{ isset($dealData['homeWarrentyDescription']) ? $dealData['homeWarrentyDescription'] : '' }}"
                                                            class="form-control" id="homeWarrentyDescription">
                                                        <div class="homeWarrentyDescription_error text-danger"
                                                            id="homeWarrentyDescription_error"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Additional Fees Charged -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingThree">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapseAdditionalFees"
                                                aria-expanded="false" aria-controls="collapseAdditionalFees">
                                                Any Additional Fees Charged?
                                                <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18"
                                                    viewBox="0 0 19 18" fill="none">
                                                    <mask id="mask0_2151_10662" style="mask-type:alpha"
                                                        maskUnits="userSpaceOnUse" x="0" y="0" width="19"
                                                        height="18">
                                                        <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                                    </mask>
                                                    <g mask="url(#mask0_2151_10662)">
                                                        <path
                                                            d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z"
                                                            fill="#AC5353" />
                                                    </g>
                                                </svg>
                                            </button>
                                        </h2>
                                        <div id="collapseAdditionalFees" class="accordion-collapse collapse"
                                            aria-labelledby="headingThree">
                                            <div class="accordion-body">
                                                <div class="form-check">
                                                    <input class="form-check-input ignore-validation" type="radio"
                                                        name="additionalFees" value="yes" id="additionalFeesYes"
                                                        {{ isset($dealData['any_additional_fees_charged']) && $dealData['any_additional_fees_charged'] === 'YES' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="additionalFeesYes">Yes</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input ignore-validation" type="radio"
                                                        name="additionalFees" value="no" id="additionalFeesNo"
                                                        {{ isset($dealData['any_additional_fees_charged']) && $dealData['any_additional_fees_charged'] === 'NO' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="additionalFeesNo">No</label>
                                                </div>
                                                <div class="additionalFeesFields label-div-mb"
                                                    style="margin-top:10px;display:none;">
                                                    <div class="additionalFeeAmount label-div-mb">
                                                        <label for="additionalFeeAmount" class="common-label">Additional
                                                            Fee Amount</label><svg xmlns="http://www.w3.org/2000/svg"
                                                            width="19" height="18" viewBox="0 0 19 18"
                                                            fill="none">
                                                            <mask id="mask0_2151_10662" style="mask-type:alpha"
                                                                maskUnits="userSpaceOnUse" x="0" y="0" width="19"
                                                                height="18">
                                                                <rect x="0.5" width="18" height="18"
                                                                    fill="#D9D9D9" />
                                                            </mask>
                                                            <g mask="url(#mask0_2151_10662)">
                                                                <path
                                                                    d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z"
                                                                    fill="#AC5353" />
                                                            </g>
                                                        </svg>
                                                        <input type="text"
                                                            value="{{ isset($dealData['additionalFeesAmount']) ? $dealData['additionalFeesAmount'] : '' }}"
                                                            class="form-control" id="additionalFeeAmount">
                                                        <div class="additionalFeeAmount_error text-danger"
                                                            id="additionalFeeAmount_error"></div>
                                                    </div>
                                                    <div class="additionalFeeDescription label-div-mb">
                                                        <label for="additionalFeeDescription"
                                                            class="common-label">Additional Fee Description</label><svg
                                                            xmlns="http://www.w3.org/2000/svg" width="19"
                                                            height="18" viewBox="0 0 19 18" fill="none">
                                                            <mask id="mask0_2151_10662" style="mask-type:alpha"
                                                                maskUnits="userSpaceOnUse" x="0" y="0" width="19"
                                                                height="18">
                                                                <rect x="0.5" width="18" height="18"
                                                                    fill="#D9D9D9" />
                                                            </mask>
                                                            <g mask="url(#mask0_2151_10662)">
                                                                <path
                                                                    d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z"
                                                                    fill="#AC5353" />
                                                            </g>
                                                        </svg>
                                                        <input type="text"
                                                            value="{{ isset($dealData['additionalFeesDescription']) ? $dealData['additionalFeesDescription'] : '' }}"
                                                            class="form-control" id="additionalFeeDescription">
                                                        <div class="additionalFeeDescription_error text-danger"
                                                            id="additionalFeeDescription_error"></div>
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
                                            <label>Final Purchase Price <svg xmlns="http://www.w3.org/2000/svg"
                                                    width="19" height="18" viewBox="0 0 19 18" fill="none">
                                                    <mask id="mask0_2151_10662" style="mask-type:alpha"
                                                        maskUnits="userSpaceOnUse" x="0" y="0" width="19"
                                                        height="18">
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
                                                placeholder="$" class="form-control required-field validate integer-field"
                                                id="final_purchase">
                                            <div id="final_purchase_error" class="text-danger">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="basicpill-lastname-input">Amount to CHR GIves </label>
                                            <input type="text"
                                                value="{{ isset($dealData['amount_to_chr_gives']) ? $dealData['amount_to_chr_gives'] : '' }}"
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
                                                    class="form-select second-step-common-select select-mb24"
                                                    id="">
                                                    <option value="" selected>{{ $deal->userData->name }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="basicpill-phoneno-input">Agent
                                                    Comments/Remarks/Instructions</label>
                                                <textarea placeholder="Add Copy" id="agent_comments" class="form-control" rows="2"
                                                    placeholder="Enter Your Address">{{ isset($dealData['agent_comments']) ? $dealData['agent_comments'] : '' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="basicpill-phoneno-input">Other Commission Notes</label>
                                                <textarea placeholder="Add Copy" id="other_comm_notes" class="form-control" rows="2"
                                                    placeholder="Enter Your Address">{{ isset($dealData['other_commission_notes']) ? $dealData['other_commission_notes'] : '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                            </form>
                        </section>

                        <h3>Resubmittal Information</h3>
                        <section>
                            <div>
                                <form>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="resubmitting_why_list_all_changes">Resubmitting? Why? --LIST
                                                    ALL CHANGES--</label>
                                                <textarea class="form-control" id="resubmitting_why_list_all_changes" aria-label="With textarea">{{ $dealData['resubmitting_why_list_all_changes'] }}</textarea>
                                            </div>
                                        </div>

                                    </div>
                                </form>
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

<script>
    window.onload = function() {
        var dealData = @json($dealData);
        const $stepsContainer = $('#basic-example-nontm-view');

        function initializeSteps() {
            $stepsContainer.steps({
                headerTag: "h3",
                enableAllSteps: true,
                bodyTag: "section",
                transitionEffect: "slide",

                onStepChanging: function(event, currentIndex, newIndex) {
                    // Perform validation before allowing step change
                    return validateStep(currentIndex);
                },
                onFinished: function(event, currentIndex) {
                    // API call here
                    window.updateNonTm(dealData, true);
                }
            });
        }

        initializeSteps();

        // Select all radio buttons
        const radioButtons = document.querySelectorAll('input[type="radio"]');

        // Initialize an object to store the values
        window.values = {};

        const fieldUpdates = {
            'Referral Fee Paid Out?': [{
                    id: 'referralFeeAmount',
                    required: true,
                    integer: 'integer-field'
                },
                {
                    id: 'referralFeeBrokerage',
                    required: true,
                    integer: ''
                },
                {
                    id: 'referralAgreement',
                    required: true,
                    integer: ''
                },
                {
                    id: 'hasW9Provided',
                    required: true,
                    integer: ''
                }
            ],
            'Home Warranty Paid Out Agent?': [{
                    id: 'homeWarrentyAmount',
                    required: true,
                    integer: 'integer-field'
                },
                {
                    id: 'homeWarrentyDescription',
                    required: true,
                    integer: ''
                }
            ],
            'Any Additional Fees Charged?': [{
                    id: 'additionalFeeAmount',
                    required: true,
                    integer: 'integer-field'
                },
                {
                    id: 'additionalFeeDescription',
                    required: true,
                    integer: ''
                }
            ]
        };

        radioButtons.forEach(radioButton => {

            if (radioButton.checked) {
                const question = radioButton.closest('.accordion-item').querySelector('button').textContent
                    .trim();
                const value = radioButton.value;
                window.values[question] = value;
                openNewFields();
            }

        });



        // Add event listener to each radio button
        document.addEventListener('change', event => {
            if (event.target.matches('input[type="radio"]')) {
                const radioButton = event.target;
                const validIds = [
                    "referralYes", "referralNo",
                    "homeWarrantyYes", "homeWarrantyNo",
                    "additionalFeesYes", "additionalFeesNo"
                ];

                if (validIds.includes(radioButton.id)) {
                    const question = radioButton.closest('.accordion-item').querySelector('button')
                        .textContent.trim();
                    const value = radioButton.value;

                    // Initialize window.values if not already defined
                    window.values = window.values || {};
                    window.values[question] = value;

                    openNewFields(); // Ensure this function is defined elsewhere
                    // Call the updateFields function with the question and value
                    updateFields(question, value);
                }
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

        function updateFields(question, value) {
            const updates = fieldUpdates[question];
            if (updates) {
                updates.forEach(({
                    id,
                    required,
                    integer
                }) => {
                    const element = document.getElementById(id);
                    if (element) {
                        console.log(element, 'test');
                        if (element.type === 'radio') {
                            // For radio buttons, update their state based on the value
                            if (value === 'yes') {
                                if (integer) {
                                    element.classList.add('required-field', 'validate', integer);
                                } else {
                                    element.classList.add('required-field', 'validate');
                                }
                            } else {
                                element.classList.remove('required-field', 'validate');
                                // Remove the integer class only if it exists
                                if (integer) {
                                    element.classList.remove(integer);
                                }
                            }
                        } else {
                            // For text inputs and other fields, add/remove classes based on required status
                            if (required) {
                                if (value === 'yes') {
                                    if (integer) {
                                        element.classList.add('required-field', 'validate', integer);
                                    } else {
                                        element.classList.add('required-field', 'validate');
                                    }
                                } else {
                                    element.classList.remove('required-field', 'validate');
                                    // Remove the integer class only if it exists
                                    if (integer) {
                                        element.classList.remove(integer);
                                    }
                                }
                            }
                        }
                    } else {
                        console.warn(`Element with ID ${id} not found.`);
                    }
                });
            } else {
                console.warn(`No updates found for question: ${question}`);
            }
        };



        function openNewFields() {
            let referralData = window.values['Referral Fee Paid Out?'];
            let homeWarrentyData = window.values['Home Warranty Paid Out Agent?'];
            let additionalFees = window.values['Any Additional Fees Charged?'];

            console.log("Referral Fee Paid Out?", referralData);

            $(".referralCustomFields").toggle(referralData === "yes");
            $(".homeWarrentyFields").toggle(homeWarrentyData === "yes");
            $(".additionalFeesFields").toggle(additionalFees === "yes");
            // Ensure fields are updated with validation classes
            if (referralData === "yes" || homeWarrentyData === "yes" || additionalFees === "yes") {
                updateFields('Referral Fee Paid Out?', referralData);
                updateFields('Home Warranty Paid Out Agent?', homeWarrentyData);
                updateFields('Any Additional Fees Charged?', additionalFees);
            }
        };


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

        function validateStep(stepIndex) {
            let isValid = true;
            const $currentSection = $stepsContainer.find(`section:eq(${stepIndex})`);

            // Clear previous error messages
            $currentSection.find('.error').removeClass('error');
            $currentSection.find('.error-message').text('');

            const invalidFields = [];

            // Check required fields
            $currentSection.find('.required-field').each(function() {
                const $field = $(this);
                const value = $field.val();
                if (value === "-None-" || !value) {
                    isValid = false;
                    $field.addClass('error');
                } else {
                    $field.removeClass('error');
                }
            });

            // Check radio button groups
            $currentSection.find('input[type="radio"]').each(function() {
                const $radioGroup = $currentSection.find(`input[name="${$(this).attr('name')}"]`);
                console.log($radioGroup, 'radioGroup');

                // Check if the radio group does not have a specific class (e.g., 'ignore-validation')
                const hasIgnoreValidationClass = $radioGroup.hasClass('ignore-validation');

                // Check if at least one radio button is selected
                const hasCheckedRadio = $radioGroup.filter(':checked').length > 0;

                if (!hasIgnoreValidationClass || !hasCheckedRadio) {
                    if (!hasCheckedRadio) {
                        isValid = false;
                        $radioGroup.addClass('error');
                    } else {
                        $radioGroup.removeClass('error');
                    }
                } else {
                    console.log("yes hee comesss")
                    isValid = true;
                    // If 'ignore-validation' class is present and at least one radio button is selected
                    $radioGroup.removeClass('error');
                }
            });

            // Check integer fields
            $currentSection.find('.integer-field').each(function() {
                const $field = $(this);
                const value = $field.val();
                const $label = $field.siblings('label');
                const labelText = $label.text().trim();
                const numberStr = value.toString();
                if (/^\d+(\.\d+)?$/.test(numberStr)) { // Check if the value is an integer
                    console.log('yes heree')
                    $field.removeClass('error');
                    $field.siblings('.error-message').text('');
                } else {
                    isValid = false;
                    $field.addClass('error');
                    $field.siblings('.error-message').text('Please enter a valid integer.');
                    if (labelText) invalidFields.push(labelText);
                }

            });

            // Check specific radio button groups from fieldUpdates
            Object.keys(fieldUpdates).forEach(question => {
                const updates = fieldUpdates[question];
                updates.forEach(({
                    id
                }) => {
                    const element = document.getElementById(id);
                    if (element && element.type === 'radio') {
                        const $radioGroup = $currentSection.find(`input[name="${element.name}"]`);
                        if ($radioGroup.filter(':checked').length === 0) {
                            isValid = false;
                            $radioGroup.addClass('error');
                        } else {
                            $radioGroup.removeClass('error');
                        }
                    }
                });
            });

            // Handle invalid fields and overall message
            if (invalidFields.length > 0) {
                showToastError('Please correct the following fields: ' + invalidFields.join(', '));
            } else if (!isValid) {
                showToastError('Please fill out all required fields.');
            }
            console.log(isValid,'isvalid')

            return isValid;
        }



        window.updateNonTm = function(dealData, status) {
            // dealData = JSON.parse(dealData)
            console.log(dealData);
            id = dealData.id
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
            let referralAgreement = document.querySelector('input[name="referralAgreement"]:checked')?.value;
            let hasW9Provided = document.querySelector('input[name="hasW9Provided"]:checked')?.value;
            let homeWarrentyAmount = document.getElementById("homeWarrentyAmount");
            let homeWarrentyDescription = document.getElementById("homeWarrentyDescription");
            let additionalFeeAmount = document.getElementById("additionalFeeAmount");
            let resubmitting_why_list_all_changes = document.getElementById(
                "resubmitting_why_list_all_changes");
            
            let additionalFeeDescription = document.getElementById("additionalFeeDescription");
            var selectedOption = related_transaction.options[related_transaction.selectedIndex];
            // Get the value and text of the selected option
            var selectedValue = selectedOption.value;
            var selectedText = selectedOption.textContent;
            let formData = {
                "data": [{
                    "Commission": commission?.value?.trim().split('.')[0] ?? undefined,
                    "Final_Purchase_Price": final_purchase ? final_purchase.value.trim() :
                        undefined,
                    "Any_Additional_Fees_Charged": window?.values["Any Additional Fees Charged?"] ?
                        window?.values["Any Additional Fees Charged?"]
                        ?.toUpperCase() : undefined,
                    "Additonal_Fees_Amount": additionalFeeAmount ? additionalFeeAmount.value
                        .trim() : undefined,
                    "Additional_Fees_Description": additionalFeeDescription ?
                        additionalFeeDescription.value.trim() : undefined,
                    "Additional_Email_for_Confirmation": add_email ? add_email.value.trim() :
                        undefined,
                    "Referral_Fee_Paid_Out": window.values['Referral Fee Paid Out?'] ? window
                        .values['Referral Fee Paid Out?']?.toUpperCase() : undefined,
                    "Referral_Fee_Amount": referralFeeAmount ? referralFeeAmount.value.trim() :
                        undefined,
                    "Referral_Fee_Brokerage_Name": referralFeeBrokerage ? referralFeeBrokerage.value
                        .trim() : undefined,
                    "Referral_Fee_Agreement_Executed": referralAgreement ? referralAgreement
                        .trim() : undefined,
                    "Has_the_W-9_been_provided": hasW9Provided ? hasW9Provided.trim() : undefined,
                    "Close_Date": close_date ? close_date.value.trim() : undefined,
                    "Home_Warranty_Paid_by_Agent": window.values['Home Warranty Paid Out Agent?'] ?
                        window.values['Home Warranty Paid Out Agent?']?.toUpperCase() : undefined,
                    "Home_Warranty_Amount": homeWarrentyAmount ? homeWarrentyAmount.value.trim() :
                        undefined,
                    "Home_Warranty_Description": homeWarrentyDescription ? homeWarrentyDescription
                        .value.trim() : undefined,
                    "CHR_Gives_Amount_to_Give": amount_chr ? amount_chr.value.trim() : undefined,
                    "Other_Commission_Notes": other_comm_notes.value ? other_comm_notes.value
                        .trim() : undefined,
                    "Agent_Comments_Remarks_Instructions": agent_comments.value ? agent_comments
                        .value.trim() : undefined,
                    "Resubmitting_Why_LIST_ALL_CHANGES": resubmitting_why_list_all_changes.value ?
                        resubmitting_why_list_all_changes.value.trim() : undefined,
                    "resubmit_text": true,
                    "Related_Transaction": selectedValue ? {
                        "id": selectedValue.trim(),
                        "name": selectedText.trim(),
                    } : undefined,
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
    }
</script>
