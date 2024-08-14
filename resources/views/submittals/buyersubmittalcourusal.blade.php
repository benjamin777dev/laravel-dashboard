{{-- @component('components.breadcrumb')
@slot('li_1') Form @endslot
@slot('title') Buyer Submittal @endslot
@endcomponent --}}

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">CHR -TM</h4>

                <div id="basic-example-buyer">
                    <!-- Seller Details -->
                    <h3>Basic Information</h3>
                    <section>
                        <form>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label>Transaction Name <svg
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
                                        <select class="form-select required-field" id="relatedTransactionShow" required disabled>
                                            @foreach($deals as $currDeal)
                                            <option value="{{$currDeal}}" {{ $currDeal['zoho_deal_id'] == $submittal['dealData']['zoho_deal_id'] ? 'selected' : '' }}>
                                                {{$currDeal['deal_name']}}
                                            </option>
                                            @endforeach
                                        </select>
                                        <select class="form-select validate" id="relatedTransaction" required hidden>
                                            @foreach($deals as $currDeal)
                                            <option value="{{$currDeal}}" {{ $currDeal['zoho_deal_id'] == $submittal['dealData']['zoho_deal_id'] ? 'selected' : '' }}>>
                                                {{$currDeal['deal_name']}}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="basicpill-lastname-input">Buyer Package <svg
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
                                        <select class="form-select required-field" name="related_transaction" id="buyerPackage" class="nontm-select validate_err">
                                            <option value="">--None--</option>
                                            <option value="Standard" {{ $submittal['buyerPackage']=='Standard'? 'selected' : '' }}>Standard</option>
                                            <option value="New Construction" {{ $submittal['buyerPackage']=='New Construction'? 'selected' : '' }}>New Construction</option>
                                         
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="basicpill-phoneno-input">Additional Email for Confirmation</label>
                                        <input type="email" class="form-control" value="{{$submittal['additionalEmail']}}" id="additionalEmailBuyer" placeholder="Enter Your Email.">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="basicpill-email-input">Closing Date <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18" fill="none">
                                            <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="19" height="18">
                                                <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                            </mask>
                                            <g mask="url(#mask0_2151_10662)">
                                                <path d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z" fill="#AC5353" />
                                            </g>
                                        </svg></label>
                                        <input type="date" class="form-control required-field" value="{{$submittal['buyerClosingDate']?$submittal['buyerClosingDate']:$submittal['dealData']['closing_date']}}" id="buyerClosingDate" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label for="buyerTmName">TM name <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18" fill="none">
                                            <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="19" height="18">
                                                <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                            </mask>
                                            <g mask="url(#mask0_2151_10662)">
                                                <path d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z" fill="#AC5353" />
                                            </g>
                                        </svg></label>
                                        <input type="text" value="{{$submittal['dealData']['tmName']['name']}}" id="buyerTmName" class="form-control required-field" rows="2" placeholder="Enter Your Address">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </section>

                    <!-- Company Document -->
                    <h3>Basic Information</h3>
                    <section>
                        <form>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="basicpill-pancard-input">Mailout Needed
                                            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18" fill="none">
                                                <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="19" height="18">
                                                    <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                                </mask>
                                                <g mask="url(#mask0_2151_10662)">
                                                    <path d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z" fill="#AC5353" />
                                                </g>
                                            </svg></label>
                                            <div class="d-flex gap-2">
                                                <div class="mb-3">
                                                    <input type="radio" id="buyerMailoutNeeded" name="buyerMailoutNeeded" value = "Yes" {{ $submittal['mailoutNeeded']=='Yes'? 'checked' : '' }}>
                                                    <label class="form-check-label" for="mailoutNeeded_yes">
                                                        Yes
                                                    </label>
                                                </div>
                                                <div class="mb-3">
                                                    <input type="radio" id="buyerMailoutNeeded" name="buyerMailoutNeeded" value = "No" {{ $submittal['mailoutNeeded']=='No'? 'checked' : '' }}>
                                                    <label class="form-check-label" for="mailoutNeeded_no">
                                                        No
                                                    </label>
                                                </div>
                                            </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="basicpill-vatno-input">Power of Attny Needed?
                                            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18" fill="none">
                                                <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="19" height="18">
                                                    <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                                </mask>
                                                <g mask="url(#mask0_2151_10662)">
                                                    <path d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z" fill="#AC5353" />
                                                </g>
                                            </svg></label>
                                            <div class="d-flex gap-2">
                                                <div class="mb-3">
                                                    <input type="radio" id="buyerPowerAttny" name="buyerPowerAttny" value = "Yes" {{ $submittal['powerOfAttnyNeeded'] == 'Yes' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="powerOfAttnyNeeded_yes">
                                                        Yes
                                                    </label>
                                                </div>
                                                <div class="mb-3">
                                                    <input type="radio" id="buyerPowerAttny" name="buyerPowerAttny" value = "No" {{ $submittal['powerOfAttnyNeeded'] == 'No' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="powerOfAttnyNeeded_no">
                                                        No
                                                    </label>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="basicpill-cstno-input">Include Insights in Intro?
                                            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18" fill="none">
                                                <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="19" height="18">
                                                    <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                                </mask>
                                                <g mask="url(#mask0_2151_10662)">
                                                    <path d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z" fill="#AC5353" />
                                                </g>
                                            </svg></label>
                                            <div class="d-flex gap-2">
                                                <div class="mb-3">
                                                    <input type="radio" id="buyerincludeInsight" name="buyerincludeInsight" value = "Yes" {{ $submittal['includeInsights']=='Yes'? 'checked' : '' }}>
                                                    <label class="form-check-label" for="mailoutNeeded_yes">
                                                        Yes
                                                    </label>
                                                </div>
                                                <div class="mb-3">
                                                    <input type="radio" id="buyerincludeInsight" name="buyerincludeInsight" value = "No"{{ $submittal['includeInsights']=='No'? 'checked' : '' }}>
                                                    <label class="form-check-label" for="mailoutNeeded_no">
                                                        No
                                                    </label>
                                                </div>
                                            </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="basicpill-servicetax-input">Referral to Pay
                                            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18" fill="none">
                                                <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="19" height="18">
                                                    <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                                </mask>
                                                <g mask="url(#mask0_2151_10662)">
                                                    <path d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z" fill="#AC5353" />
                                                </g>
                                            </svg></label>
                                            <div class="d-flex gap-2">
                                                <div class="mb-3">
                                                    <input type="radio" id="buyerRefrralPay" name="buyerRefrralPay" value = "Yes" {{ $submittal['referralToPay'] == 'Yes' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="buyerRefrralPay_yes">
                                                        Yes
                                                    </label>
                                                </div>
                                                <div class="mb-3">
                                                    <input type="radio" id="buyerRefrralPay" name="buyerRefrralPay" value = "No"{{ $submittal['referralToPay'] == 'No' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="buyerRefrralPay_no">
                                                        No
                                                    </label>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="buyerLenderEmail">Lender Email</label>
                                        <input type="email" class="form-control" id="buyerLenderEmail" value="{{$submittal['buyerLenderEmail']}}" placeholder="Enter Your Email">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="basicpill-declaration-input">Lender Phone</label>
                                        <input type="text" class="form-control" value="{{$submittal['buyerLenderPhone']}}" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" id="buyerLenderPhone" placeholder="Lender Phone">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </section>

                    <!-- Bank Details -->
                    <h3>Basic Information</h3>
                    <section>
                        <div>
                            <form>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="buyerFeesCharged">Fees Charged to Buyer at Closing</label>
                                            <input type="text" class="form-control" placeholder="$" id= "buyerFeesCharged" value="{{ $submittal['buyerFeesCharged'] }}">
                                        </div>
                                    </div>
 
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="buyerAmountChr">Amount to CHR Gives</label>
                                            <input type="text" class="form-control" placeholder="$" value="{{ $submittal['amountToCHR'] }}" id="buyerAmountChr">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="buyerRefrealDetails">Referral Details</label>
                                            <input type="text" name="Referral" class="form-control" value="{{ $submittal['referralDetails'] }}" id="buyerRefrealDetails"
                                             placeholder="Enter Details">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="buyerOtherNotes">Other important Notes</label>
                                            <textarea id="buyerOtherNotes" class="form-control" rows="2" placeholder="Enter Your Address">{{ $submittal['marketingNotes'] }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </section>

                      <!-- Confirm Details -->
                      <h3 class="contruction-item-default">Construction Detials</h3>
                      <section >
                        <div>
                            <form>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="buyerBuilderrepresent">Builder Representative <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18" fill="none">
                                                <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="19" height="18">
                                                    <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                                </mask>
                                                <g mask="url(#mask0_2151_10662)">
                                                    <path d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z" fill="#AC5353" />
                                                </g>
                                            </svg></label>
                                            <input type="text" placeholder="Enter Details" value="{{$submittal['buyerBuilderrepresent']}}"
                                            class="form-control" id="buyerBuilderrepresent">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="BuyerTitleCompany">Title Company/Closer Info <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18" fill="none">
                                                <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="19" height="18">
                                                    <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                                </mask>
                                                <g mask="url(#mask0_2151_10662)">
                                                    <path d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z" fill="#AC5353" />
                                                </g>
                                            </svg></label>
                                            <input type="text" class="form-control" value="{{$submittal['titleCompany']}}" id="BuyerTitleCompany"
                                         placeholder="Enter Details" >
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="builderCommisionPercent">Builder Commission (% and/or flat fee) <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18" fill="none">
                                                <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="19" height="18">
                                                    <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                                </mask>
                                                <g mask="url(#mask0_2151_10662)">
                                                    <path d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z" fill="#AC5353" />
                                                </g>
                                            </svg></label>
                                            <input type="text" placeholder="Enter Details" value="{{$submittal['builderCommisionPercent']}}" 
                                            class="form-control" id="builderCommisionPercent">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="buyerOtherNotes">Builder Commission Based On <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18" fill="none">
                                                <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="19" height="18">
                                                    <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                                </mask>
                                                <g mask="url(#mask0_2151_10662)">
                                                    <path d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z" fill="#AC5353" />
                                                </g>
                                            </svg></label>
                                            <select name="related_transaction" id="builderCommision" class="form-select validate_err">
                                                <option value="">--None--</option>
                                                    <option value="Base Price" {{ $submittal['builderCommision']=='Base Price'? 'selected' : '' }}>Base Price</option>
                                                    <option value="Flat Fee" {{ $submittal['builderCommision']=='Flat Fee'? 'selected' : '' }}>Flat Fee</option>
                                                    <option value="Other" {{ $submittal['builderCommision']=='Other'? 'selected' : '' }}>Other</option>
                                             
                                                </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="builderCommisionPercent">Builder Contract Fully Executed <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18" fill="none">
                                                <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="19" height="18">
                                                    <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                                </mask>
                                                <g mask="url(#mask0_2151_10662)">
                                                    <path d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z" fill="#AC5353" />
                                                </g>
                                            </svg></label>
                                            <div class="d-flex gap-2">
                                                <div class="mb-3">
                                                    <input type="radio" id="contractExecuted" name="contractExecuted" value="Yes" {{ $submittal['contractExecuted']=='Yes'? 'checked' : '' }}>
                                                    <label class="form-check-label" for="mailoutNeeded_yes">
                                                        Yes
                                                    </label>
                                                </div>
                                                <div class="mb-3">
                                                    <input type="radio" id="contractExecuted" name="contractExecuted" value="No" {{ $submittal['contractExecuted']=='No'? 'checked' : '' }}>
                                                    <label class="form-check-label" for="mailoutNeeded_no">
                                                        No
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="buyerOtherNotes">Buyer Agency Executed <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18" fill="none">
                                                <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="19" height="18">
                                                    <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                                </mask>
                                                <g mask="url(#mask0_2151_10662)">
                                                    <path d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z" fill="#AC5353" />
                                                </g>
                                            </svg></label>
                                            <div class="d-flex gap-2">
                                                <div class="mb-3">
                                                    <input type="radio" id="buyerAgency_yes" name="buyerAgency" value="Yes" {{ $submittal['buyerAgency']=='Yes'? 'checked' : '' }}>
                                                    <label class="form-check-label" for="buyerAgency">
                                                        Yes
                                                    </label>
                                                </div>
                                                <div class="mb-3">
                                                    <input type="radio" id="buyerAgency_no" name="buyerAgency" value="No" {{ $submittal['buyerAgency']=='No'? 'checked' : '' }}>
                                                    <label class="form-check-label" for="buyerAgency">
                                                        No
                                                    </label>
                                                </div>
                                            </div>
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
<!-- end row -->
<!-- jquery step -->
<script defer src="{{ URL::asset('build/libs/jquery-steps/build/jquery.steps.min.js') }}"></script>

<!-- form wizard init -->
<script src="{{ URL::asset('build/js/pages/form-wizard.init.js') }}"></script>

<script>
      

     $(document).ready(function() {
        const $stepsContainer = $('#basic-example-buyer');

        function initializeSteps() {
            $stepsContainer.steps({
                headerTag: "h3",
                bodyTag: "section",
                transitionEffect: "slide",
                onStepChanging: function(event, currentIndex, newIndex) {
                    // Perform validation before allowing step change
                    const isValid = validateStep(currentIndex);
                    return isValid;
                },
                onFinished: function(event, currentIndex) {
                // Validate the current step
                const isValid = validateStep(currentIndex);

                if (isValid) {
                    // If valid, proceed with the API call
                    window.validateSubmittal(true);
                    // Optionally, you might want to return true explicitly here
                    return true;
                } else {
                    // Return false to indicate that the form submission should not proceed
                    return false;
                }
            }

            });
        }

        initializeSteps();

             // Event listeners and other logic
             let dropdown = document.querySelector('select[name="related_transaction"]');

            // Get the selected value
            let selectedValue = dropdown?.value;
        showAndDisableValues(selectedValue);

        document.addEventListener('change', event => {
            if (event.target.matches('select[name="related_transaction"]')) {
                const radioButton = event.target;
                const value = radioButton.value;
                showAndDisableValues(value);
            }
        });

        var contruction_detail = `<h3 class="contruction-item">Construction Detials</h3>
         <section >
                        <div>
                            <form>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="buyerBuilderrepresent">Builder Representative <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18" fill="none">
                                                <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="19" height="18">
                                                    <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                                </mask>
                                                <g mask="url(#mask0_2151_10662)">
                                                    <path d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z" fill="#AC5353" />
                                                </g>
                                            </svg></label>
                                            <input type="text" placeholder="Enter Details" value="{{$submittal['buyerBuilderrepresent']}}"
                                            class="form-control" id="buyerBuilderrepresent">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="BuyerTitleCompany">Title Company/Closer Info <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18" fill="none">
                                                <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="19" height="18">
                                                    <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                                </mask>
                                                <g mask="url(#mask0_2151_10662)">
                                                    <path d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z" fill="#AC5353" />
                                                </g>
                                            </svg></label>
                                            <input type="text" class="form-control" value="{{$submittal['titleCompany']}}" id="BuyerTitleCompany"
                                         placeholder="Enter Details" >
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="builderCommisionPercent">Builder Commission (% and/or flat fee) <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18" fill="none">
                                                <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="19" height="18">
                                                    <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                                </mask>
                                                <g mask="url(#mask0_2151_10662)">
                                                    <path d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z" fill="#AC5353" />
                                                </g>
                                            </svg></label>
                                            <input type="text" placeholder="Enter Details" value="{{$submittal['builderCommisionPercent']}}" 
                                            class="form-control" id="builderCommisionPercent">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="buyerOtherNotes">Builder Commission Based On <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18" fill="none">
                                                <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="19" height="18">
                                                    <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                                </mask>
                                                <g mask="url(#mask0_2151_10662)">
                                                    <path d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z" fill="#AC5353" />
                                                </g>
                                            </svg></label>
                                            <select name="related_transaction" id="builderCommision" class="form-select validate_err">
                                                <option value="">--None--</option>
                                                    <option value="Base Price" {{ $submittal['builderCommision']=='Base Price'? 'selected' : '' }}>Base Price</option>
                                                    <option value="Flat Fee" {{ $submittal['builderCommision']=='Flat Fee'? 'selected' : '' }}>Flat Fee</option>
                                                    <option value="Other" {{ $submittal['builderCommision']=='Other'? 'selected' : '' }}>Other</option>
                                             
                                                </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="builderCommisionPercent">Builder Contract Fully Executed <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18" fill="none">
                                                <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="19" height="18">
                                                    <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                                </mask>
                                                <g mask="url(#mask0_2151_10662)">
                                                    <path d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z" fill="#AC5353" />
                                                </g>
                                            </svg></label>
                                            <div class="d-flex gap-2">
                                                <div class="mb-3">
                                                    <input type="radio" id="contractExecuted" name="contractExecuted" value="Yes" {{ $submittal['contractExecuted']=='Yes'? 'checked' : '' }}>
                                                    <label class="form-check-label" for="mailoutNeeded_yes">
                                                        Yes
                                                    </label>
                                                </div>
                                                <div class="mb-3">
                                                    <input type="radio" id="contractExecuted" name="contractExecuted" value="No" {{ $submittal['contractExecuted']=='No'? 'checked' : '' }}>
                                                    <label class="form-check-label" for="mailoutNeeded_no">
                                                        No
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="buyerOtherNotes">Buyer Agency Executed <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18" fill="none">
                                                <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="19" height="18">
                                                    <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                                </mask>
                                                <g mask="url(#mask0_2151_10662)">
                                                    <path d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z" fill="#AC5353" />
                                                </g>
                                            </svg></label>
                                            <div class="d-flex gap-2">
                                                <div class="mb-3">
                                                    <input type="radio" id="buyerAgency_yes" name="buyerAgency" value="Yes" {{ $submittal['buyerAgency']=='Yes'? 'checked' : '' }}>
                                                    <label class="form-check-label" for="buyerAgency">
                                                        Yes
                                                    </label>
                                                </div>
                                                <div class="mb-3">
                                                    <input type="radio" id="buyerAgency_no" name="buyerAgency" value="No" {{ $submittal['buyerAgency']=='No'? 'checked' : '' }}>
                                                    <label class="form-check-label" for="buyerAgency">
                                                        No
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    
     </section>`;
     // Confirm Details -->
     var confirm_detail = `<h3>Confirm Detail</h3>
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
      </section>`;

        function showAndDisableValues(value) {
            console.log(value,'value is hre')
            if (value === "Standard" && value !== undefined) {
                  removeStep(3)

            } else if (value === "New Construction") {
                if ($(".contruction-item").length === 0 && $(".contruction-item-default").length===0) {
                addStepChr("Construction Detials",contruction_detail);
                }
            } else {
                
                removeStep(3)
            }
        }

        function validateStep(stepIndex) {
            let isValid = true;
            const $currentSection = $stepsContainer.find(`section:eq(${stepIndex})`);

            // Check required fields in this section
           
            let reqValmsg;
            $currentSection.find('.required-field').each(function() {
                reqValmsg = this?.name;
                if (!$(this).val()) {
                    isValid = false;
                    $(this).addClass('error'); // Add error class for styling
                } else {
                    $(this).removeClass('error'); // Remove error class if valid
                }
            });

            // Check radio buttons
            $currentSection.find('input[type="radio"]').each(function() {
                const name = $(this).attr('name');

                if (name !== "conciergeListing") {
                    const $radioGroup = $currentSection.find(`input[name="${name}"]`);
                    if ($radioGroup.filter(':checked').length === 0) {
                        isValid = false;
                        $radioGroup.addClass('error'); // Add error class for styling
                    } else {
                        $radioGroup.removeClass('error'); // Remove error class if valid
                    }
                }
            });

            // Optionally, display a message or highlight errors if invalid
            if (!isValid) {
                showToastError(reqValmsg ?`Please fill out ${reqValmsg} Detail required field.`:"Please fill out all required fields.");
            }

            return isValid;
        }

        function addStepChr(title, content) {
            $stepsContainer.steps('add', {
                headerTag: "h3",
                bodyTag: "section",
                title: title,
                content: content
            });
        }

        function removeStep(index) {
             let totalSteps = 7;
            if (index >= 0 && index < totalSteps) {
                $stepsContainer.steps('remove', index);
            } else {
                console.warn('Index out of range:', index);
            }
            
        }
   
       // Select all radio buttons and the additional field
        const referralRadioButtons = document.querySelectorAll('input[name="buyerRefrralPay"]');
        const additionalField = document.getElementById('buyerRefrealDetails');

        // Function to update the additional field requirement
        function updateAdditionalFieldRequirement() {
            const isYesSelected = document.querySelector('input[name="buyerRefrralPay"]:checked')?.value === 'Yes';
            if (isYesSelected) {
              additionalField.classList.add('required-field', 'validate');
            } else {
                additionalField.classList.remove('required-field', 'validate');
            }
        }

        // Add event listeners to all radio buttons
        referralRadioButtons.forEach(radio => {
            radio.addEventListener('change', updateAdditionalFieldRequirement);
        });

        // Initial check in case the page is loaded with a pre-selected value
        updateAdditionalFieldRequirement();


        });

          

           
           
</script>
