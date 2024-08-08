{{-- @component('components.breadcrumb')
@slot('li_1') Form @endslot
@slot('title') Buyer Submittal @endslot
@endcomponent --}}

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">CHR -TM</h4>

                <div id="basic-example-seller">
                    <!-- Seller Details -->
                    <h3>Basic Information</h3>
                    <section>
                        <form>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label>Transaction Name <svg xmlns="http://www.w3.org/2000/svg" width="19"
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
                                        <select name="related_transaction" id="transactionName"
                                            class="form-select validate_err required-field" disabled>
                                            @foreach ($deals as $currDeal)
                                                <option value="{{ $currDeal }}"
                                                    {{ $currDeal['zoho_deal_id'] == $submittal['dealData']['zoho_deal_id'] ? 'selected' : '' }}>
                                                    {{ $currDeal['deal_name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="basicpill-phoneno-input">Additional Email for Confirmation</label>
                                        <input type="email" class="form-control"
                                            value="{{ $submittal['additionalEmail'] }}" id="additionalEmail"
                                            placeholder="Enter Your Email.">
                                    </div>
                                </div>
                            </div>


                            <div class="row">

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="basicpill-lastname-input">Agent Name on Material <svg
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
                                        <input type="text" class="validate_err form-control required-field"
                                            value="{{ $submittal['agentName'] }}" placeholder="Enter agent Name"
                                            id="agentName" />
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="basicpill-email-input">Coming Soon? <svg
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
                                        <div class="d-flex gap-2">
                                            <div class="mb-3">
                                                <input type="radio" id="commingSoon_yes"
                                                    {{ $submittal['commingSoon'] == 'on' ? 'checked' : '' }}
                                                    name="commingSoon">
                                                <label class="" for="commingSoon_yes">
                                                    Yes
                                                </label>
                                            </div>
                                            <div class="mb-3">
                                                <input type="radio" id="commingSoon_no"
                                                    {{ $submittal['commingSoon'] == 'off' ? 'checked' : '' }}
                                                    name="commingSoon">
                                                <label class="" for="commingSoon_no">
                                                    No
                                                </label>
                                            </div>
                                        </div>
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
                                        <label for="comingSoonDate">Coming Soon MLS date </label>
                                        <input type="date" value="{{ $submittal['comingSoonDate'] }}"
                                            class="form-control" id="comingSoonDate">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="basicpill-lastname-input">Tm Name <svg
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
                                        <input type="text" value="{{ $submittal['dealData']['tmName']['name'] }}"
                                            class="form-control validate_err required-field" placeholder=""
                                            id="tmName">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="basicpill-cstno-input">Active Date
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
                                            </svg></label>
                                        <input type="date" id="activeDate" value="{{ $submittal['activeDate'] }}"
                                            class="form-control validate_err required-field" id="activeDate">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="basicpill-servicetax-input">Listing Agreement Exucuted?
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
                                            </svg></label>
                                        <div class="d-flex gap-2">
                                            <div class="mb-3">
                                                <input type="radio" id="agreementExecuted_yes"
                                                    {{ $submittal['agreementExecuted'] == 'on' ? 'checked' : '' }}
                                                    name="agreementExecuted">
                                                <label class="" for="agreementExecuted_yes">
                                                    Yes
                                                </label>
                                            </div>
                                            <div class="mb-3">
                                                <input type="radio" id="agreementExecuted_no"
                                                    {{ $submittal['agreementExecuted'] == 'off' ? 'checked' : '' }}
                                                    name="agreementExecuted">
                                                <label class="" for="agreementExecuted_no">
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
                                        <label for="buyerLenderEmail">Price<svg xmlns="http://www.w3.org/2000/svg"
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
                                        <input type="number" value="{{ $submittal['price'] }}" placeholder="$"
                                            class="form-control validate_err required-field" id="price">
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
                                            <label for="photoDate">Photo Date</label>
                                            <input type="date" value="{{ $submittal['photoDate'] }}"
                                                class="form-control" id="photoDate">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="photoURL">Photo Url</label>
                                            <input type="text" value="{{ $submittal['photoURL'] }}"
                                                class="form-control" placeholder="" id="photoURL">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="bedsBathsTotal">Beds,baths,total sq.ft.</label>
                                            <input type="text" value="{{ $submittal['bedsBathsTotal'] }}"
                                                class="form-control" placeholder="" id="bedsBathsTotal">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="tourURL">3D Tour URL</label>
                                            <input type="text" value="{{ $submittal['tourURL'] }}"
                                                class="form-control validate_err" placeholder="" id="tourURL">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="usingCHR">Using CHR TM <svg xmlns="http://www.w3.org/2000/svg"
                                                    width="19" height="18" viewBox="0 0 19 18"
                                                    fill="none">
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
                                            <div class="d-flex gap-2">
                                                <div class="mb-3">
                                                    <input value="Yes" id="usingCHR_yes"
                                                        {{ $submittal['usingCHR'] == 'Yes' ? 'checked' : '' }}
                                                        type="radio" name="usingCHR">
                                                    <label class="" id="chkNo" for="formCheck1">
                                                        Yes
                                                    </label>
                                                </div>
                                                <div class="mb-3">
                                                    <input value="No" id="usingCHR_no"
                                                        {{ $submittal['usingCHR'] == 'No' ? 'checked' : '' }}
                                                        type="radio" name="usingCHR">
                                                    <label class="" for="formCheck1">
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

                    <h3>Commission Details</h3>
             <section>
                        <div>
                            <form>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="feesCharged">Fees Charged to Seller at
                                                Closing</label>
                                            <input type="text" name="feesCharged"
                                                value="{{ $submittal['feesCharged'] }}" id="feesCharged"
                                                class="form-control " placeholder="$">
                                            </input>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="referralToPay">Referral to Pay</label>
                                            <select name="additional_charge" id="additonal_fee" class="form-select">
                                                <option value="" selected>None</option>
                                                <option value="Yes"
                                                    {{ $submittal['referralToPay'] == 'Yes' ? 'selected' : '' }}>Yes
                                                </option>
                                                <option value="No"
                                                    {{ $submittal['referralToPay'] == 'No' ? 'selected' : '' }}>No
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="amountToCHR">Amount to CHR Gives </label>
                                            <input type="number"name="amountToCHR" placeholder = "$"
                                                value="{{ $submittal['amountToCHR'] }}" id="amountToCHR"
                                                class="form-control">
                                            </input>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="referralDetails">Referral Details </label>
                                            <input name="referralDetails" value="{{ $submittal['referralDetails'] }}"
                                                id="referralDetails" class="form-control">
                                            </input>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

     </section>
     <h3>Service Providers</h3>
     <section>
             <div>
                 <form>
                     <div class="row">
                              <div class="col-lg-6">
                                     <div class="mb-3">
                                         <label for="builderCommisionPercent">Schedule Sign Install<svg
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
                                         <div class="d-flex gap-2">
                                             <div class="mb-3">
                                                 <input type="radio" id="scheduleSignInstall_yes"
                                                     {{ $submittal['scheduleSignInstall'] == 'on' ? 'checked' : '' }}
                                                     name="scheduleSignInstall">
                                                 <label class="" for="formCheck1">
                                                     Yes
                                                 </label>
                                             </div>
                                             <div class="mb-3">
                                                 <input type="radio" id="scheduleSignInstall_no"
                                                     {{ $submittal['scheduleSignInstall'] == 'off' ? 'checked' : '' }}
                                                     name="scheduleSignInstall">
                                                 <label class="" for="formCheck1">
                                                     No
                                                 </label>
                                             </div>
                                         </div>
                                     </div>
                                 </div>

                                 <div class="col-lg-6">
                                     <div class="mb-3">
                                         <label for="buyerOtherNotes">Draft Showing Instructions? <svg
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
                                         <div class="d-flex gap-2">
                                             <div class="mb-3">
                                                 <input type="radio" id="draftShowingInstructions_yes"
                                                     {{ $submittal['draftShowingInstructions'] == 'on' ? 'checked' : '' }}
                                                     name="draftShowingInstructions">
                                                 <label class="" for="formCheck1">
                                                     Yes
                                                 </label>
                                             </div>
                                             <div class="mb-3">
                                                 <input type="radio" id="draftShowingInstructions_no"
                                                     {{ $submittal['draftShowingInstructions'] == 'off' ? 'checked' : '' }}
                                                     name="draftShowingInstructions">
                                                 <label class="" for="formCheck1">
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
                                         <label for="amountToCHR">Concierge Listing (Optional) </label>
                                         <div class="d-flex gap-2">
                                             <div class="mb-3">
                                                 <input type="radio" id="conciergeListing_yes"
                                                     {{ $submittal['conciergeListing'] == 'on' ? 'checked' : '' }}
                                                     name="conciergeListing">
                                                 <label class="" id="chkNo" for="formCheck1">
                                                     Yes
                                                 </label>
                                             </div>
                                             <div class="mb-3">
                                                 <input type="radio" id="conciergeListing_no"
                                                     {{ $submittal['conciergeListing'] == 'off' ? 'checked' : '' }}
                                                     name="conciergeListing">
                                                 <label class="" for="formCheck1">
                                                     No
                                                 </label>
                                             </div>
                                         </div>
                                         </input>
                                     </div>
                                 </div>

                                 <div class="col-lg-6">
                                     <div class="mb-3">
                                         <label for="titleCompany">Title Company </label>
                                         <input value="{{ $submittal['titleCompany'] }}" name="titleCompany"
                                             id="titleCompany" class="form-control validate_err">
                                         </input>
                                     </div>
                                 </div>
                             </div>
                             <div class="row">
                                 <div class="col-lg-6">
                                     <div class="mb-3">
                                         <label for="signInstallDate">Sign Install Date </label>
                                         <input type="date" value="{{ $submittal['signInstallDate'] }}"
                                             class="form-control" id="signInstallDate">
                                     </div>
                                 </div>

                                 <div class="col-lg-6">
                                     <div class="mb-3">
                                         <label for="titleCompany">Closer Name & Phone<svg
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
                                             </svg> </label>
                                         <input name="closerNamePhone" value="{{ $submittal['closerNamePhone'] }}"
                                             id="closerNamePhone" class="form-control validate_err required-field">
                                         </input>
                                     </div>
                                 </div>
                             </div>
                         </form>
                     </div>

  </section>

  <h3>Service Providers</h3>
       <section>
              <div>
                <form>
                     <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label for="feesCharged">Sign Install Vendor Info</label>
                                        <input value="{{ $submittal['signInstallVendor'] }}" name="signInstallVendor"
                                            id="signInstallVendor" class="form-control">
                                        </input>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label for="feesCharged">Sign Install Vendor (if Other)</label>
                                        <input value="{{ $submittal['signInstallVendorOther'] }}"
                                            name="signInstallVendorOther" id="signInstallVendorOther"
                                            class="form-control">
                                        </input>
                                    </div>
                                </div>


                            </form>
                        </div>

     </section>

  <h3>Select MLS</h3>
  <section>
              <div>
                  <form>
                              <div class="row">
                                  <div class="col-lg-6">
                                      <div class="mb-3">
                                          <div class="row mb-4">
                                              <div class="col-lg-4 d-flex gap-2">
                                                  <div>REColorado</div>
                                                  <div> <input type="checkbox" id="reColorado" <?php if ($submittal['reColorado']) {
                                                      echo 'checked';
                                                  } ?>>
                                                  </div>
                                              </div>
                                              <div class="col-lg-4 d-flex gap-2">
                                                  <div>Navica</div>
                                                  <div> <input type="checkbox" id="navica" <?php if ($submittal['navica']) {
                                                      echo 'checked';
                                                  } ?>>
                                                  </div>
                                              </div>
                                              <div class="col-lg-4 d-flex gap-2">
                                                  <div>PPAR</div>
                                                  <div><input type="checkbox" id="ppar" <?php if ($submittal['ppar']) {
                                                      echo 'checked';
                                                  } ?>>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>

                                  <div class="col-lg-6">
                                      <div class="mb-3">
                                          <div class="row mb-4">
                                              <div class="col-lg-4 d-flex gap-2">
                                                  <div>Grand County</div>
                                                  <div><input type="checkbox" id="grandCounty" <?php if ($submittal['grandCounty']) {
                                                      echo 'checked';
                                                  } ?>>
                                                  </div>
                                              </div>
                                              <div class="col-lg-4 d-flex gap-2">
                                                  <div>IRES</div>
                                                  <div><input type="checkbox" id="ires" <?php if ($submittal['ires']) {
                                                      echo 'checked';
                                                  } ?>>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="col-lg-6">
                                      <div class="mb-3">
                                          <label for="amountToCHR">MLS public remarks </label>
                                          <textarea class="form-control" id="mlsPrivateRemarks" aria-label="With textarea">{{ $submittal['mlsPrivateRemarks'] }}</textarea>
                                      </div>
                                  </div>

                                  <div class="col-lg-6">
                                      <div class="mb-3">
                                          <label for="titleCompany">MLS private remarks</label>
                                          <textarea class="form-control" id="mlsPublicRemarks" aria-label="With textarea">{{ $submittal['mlsPublicRemarks'] }}</textarea>
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
{{-- <script src="{{ URL::asset('build/js/pages/form-wizard.init.js') }}"></script> --}}

<script>
    $(document).ready(function() {
        const $stepsContainer = $('#basic-example-seller');

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
                    // API call here
                    window.validateSubmittal(true);
                }
            });
        }

        // Initial steps plugin setup
        initializeSteps();
           //chr tm start
           const CommissionDetails = ` <h3>Commission Details</h3>
        <section>
            <div>
                <form>
                    <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                                <label for="feesCharged">Need O&E <svg
                                                xmlns="http://www.w3.org/2000/svg" width="19" height="18"
                                                viewBox="0 0 19 18" fill="none">
                                                <mask id="mask0_2151_10662" style="mask-type:alpha"
                                                    maskUnits="userSpaceOnUse" x="0" y="0" width="19" height="18">
                                                    <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                                </mask>
                                                <g mask="url(#mask0_2151_10662)">
                                                    <path
                                                        d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z"
                                                        fill="#AC5353" />
                                                </g>
                                            </svg></label>
                                            <div class="d-flex gap-2">
                                                <div class="mb-3">
                                                    <input type="radio" id="needOE_yes" value="Yes" {{ $submittal['needOE'] == 'Yes' ? 'checked' : '' }} name="needO&E">
                                                    <label class="" id="chkNo" for="formCheck1">
                                                        Yes
                                                    </label>
                                                </div>
                                                <div class="mb-3">
                                                    <input {{ $submittal['needOE'] == 'No' ? 'checked' : '' }} type="radio" id="needOE_no" value="No" name="needO&E">
                                                    <label class="" for="formCheck1">
                                                        No
                                                    </label>
                                                </div>
                                            </div>
                                        </input>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="referralToPay">Has HOA? <svg
                                                xmlns="http://www.w3.org/2000/svg" width="19" height="18"
                                                viewBox="0 0 19 18" fill="none">
                                                <mask id="mask0_2151_10662" style="mask-type:alpha"
                                                    maskUnits="userSpaceOnUse" x="0" y="0" width="19" height="18">
                                                    <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                                </mask>
                                                <g mask="url(#mask0_2151_10662)">
                                                    <path
                                                        d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z"
                                                        fill="#AC5353" />
                                                </g>
                                            </svg></label>
                                            <div class="d-flex gap-2">
                                                <div class="mb-3">
                                                    <input type="radio" id="hasHOA_yes" value="Yes" {{ $submittal['hasHOA'] == 'Yes' ? 'checked' : '' }} name="hasHOA">
                                                    <label class="" id="chkNo" for="formCheck1">
                                                        Yes
                                                    </label>
                                                </div>
                                                <div class="mb-3">
                                                    <input type="radio" id="hasHOA_no" value="No" {{ $submittal['hasHOA'] == 'No' ? 'checked' : '' }} name="hasHOA">
                                                    <label class="" for="formCheck1">
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
                                                <label for="feesCharged">Include Insights in Intro? <svg
                                                    xmlns="http://www.w3.org/2000/svg" width="19" height="18"
                                                    viewBox="0 0 19 18" fill="none">
                                                    <mask id="mask0_2151_10662" style="mask-type:alpha"
                                                        maskUnits="userSpaceOnUse" x="0" y="0" width="19" height="18">
                                                    <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                                </mask>
                                                <g mask="url(#mask0_2151_10662)">
                                                    <path
                                                        d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z"
                                                        fill="#AC5353" />
                                                </g>
                                            </svg></label>
                                            <div class="d-flex gap-2">
                                                <div class="mb-3">
                                                    <input type="radio" id="includeInsights_yes" value="Yes" {{ $submittal['includeInsights'] == 'Yes' ? 'checked' : '' }} name="includeInsights">
                                                    <label class="" id="chkNo" for="formCheck1">
                                                        Yes
                                                    </label>
                                                </div>
                                                <div class="mb-3">
                                                    <input id="includeInsights_no" value="No" {{ $submittal['includeInsights'] == 'No' ? 'checked' : '' }} type="radio" name="includeInsights">
                                                    <label class="" for="formCheck1">
                                                        No
                                                    </label>
                                                </div>
                                            </div>
                                        </input>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="referralToPay">Title to Order HOA docs?  <svg
                                                xmlns="http://www.w3.org/2000/svg" width="19" height="18"
                                                viewBox="0 0 19 18" fill="none">
                                                <mask id="mask0_2151_10662" style="mask-type:alpha"
                                                    maskUnits="userSpaceOnUse" x="0" y="0" width="19" height="18">
                                                    <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                                </mask>
                                                <g mask="url(#mask0_2151_10662)">
                                                    <path
                                                        d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z"
                                                        fill="#AC5353" />
                                                </g>
                                            </svg></label>
                                            <div class="d-flex gap-2">
                                                <div class="mb-3">
                                                    <input type="radio" id="titleToOrderHOA_yes" value="Yes" {{ $submittal['titleToOrderHOA'] == 'Yes' ? 'checked' : '' }} name="titleToOrderHOA">
                                                    <label class="" id="chkNo" for="formCheck1">
                                                        Yes
                                                    </label>
                                                </div>
                                                <div class="mb-3"> 
                                                    <input type="radio" id="titleToOrderHOA_no" value="No" {{ $submittal['titleToOrderHOA'] == 'No' ? 'checked' : '' }} name="titleToOrderHOA">
                                                    <label class="" for="formCheck1">
                                                        No
                                                    </label>
                                                </div>
                                                 <div class="mb-3">
                                                    <input type="radio" id="titleToOrderHOA_tbd" value="No" {{ $submittal['titleToOrderHOA'] == 'TBD' ? 'checked' : '' }} name="titleToOrderHOA">
                                                    <label class="" for="formCheck1">
                                                        TBD
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="feesCharged">Mailout Needed? <svg
                                                xmlns="http://www.w3.org/2000/svg" width="19" height="18"
                                                viewBox="0 0 19 18" fill="none">
                                                <mask id="mask0_2151_10662" style="mask-type:alpha"
                                                    maskUnits="userSpaceOnUse" x="0" y="0" width="19" height="18">
                                                    <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                                </mask>
                                                <g mask="url(#mask0_2151_10662)">
                                                    <path
                                                        d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z"
                                                        fill="#AC5353" />
                                                </g>
                                            </svg></label>
                                            <div class="d-flex gap-2">
                                                <div class="mb-3">
                                                    <input type="radio" id="mailoutNeeded_yes" value="Yes" {{ $submittal['mailoutNeeded'] == 'Yes' ? 'checked' : '' }} name="mailoutNeeded">
                                                    <label class="" id="chkNo" for="formCheck1">
                                                        Yes
                                                    </label>
                                                </div>
                                                <div class="mb-3">
                                                    <input type="radio" id="mailoutNeeded_no" value="No" {{ $submittal['mailoutNeeded'] == 'No' ? 'checked' : '' }} name="mailoutNeeded">
                                                    <label class="" for="formCheck1">
                                                        No
                                                    </label>
                                                </div>
                                            </div>
                                        </input>
                                        </div>
                                    </div>

                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="referralToPay">Power of Attny Needed?  <svg
                                    xmlns="http://www.w3.org/2000/svg" width="19" height="18"
                                    viewBox="0 0 19 18" fill="none">
                                    <mask id="mask0_2151_10662" style="mask-type:alpha"
                                        maskUnits="userSpaceOnUse" x="0" y="0" width="19" height="18">
                                        <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                    </mask>
                                    <g mask="url(#mask0_2151_10662)">
                                        <path
                                            d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z"
                                            fill="#AC5353" />
                                    </g>
                                </svg></label>
                                <div class="d-flex gap-2">
                                    <div class="mb-3">
                                        <input type="radio" id="powerOfAttnyNeeded_yes" value="Yes" {{ $submittal['powerOfAttnyNeeded'] == 'Yes' ? 'checked' : '' }} name="powerOfAttnyNeeded">
                                        <label class="" id="chkNo" for="formCheck1">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="mb-3">
                                        <input type="radio" id="powerOfAttnyNeeded_no" value="No" {{ $submittal['powerOfAttnyNeeded'] == 'No' ? 'checked' : '' }} name="powerOfAttnyNeeded">
                                        <label class="" for="formCheck1">
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

        const serviceProvider = `<h3>Service Providerssss</h3>
         <section>
                        <div>
                            <form>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="builderCommisionPercent">HOA Name</label>
                                            <input type="text" name="additional_charge" id="hoaName" value="{{ $submittal['hoaName'] }}"
                                            class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="buyerOtherNotes">HOA Phone</label>
                                            <input type="text" name="additional_charge" value="{{ $submittal['hoaPhone'] }}" id="hoaPhone"
                                            class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="amountToCHR">HOA Website </label>
                                            <input type="text"
                                   value="{{ $submittal['hoaWebsite'] }}"
                                    class="form-control" placeholder="" id="hoaWebsite">
                                    </input>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="titleCompany">Misc Notes - Seller, Communication, etc </label>
                                            <textarea class="form-control" id="miscNotes"  rows="4" cols="50">{{ $submittal['miscNotes'] }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="signInstallDate">Are you ready to continue to Property Promotion? </label>
                                            <div class="d-flex gap-2">
                                                <div class="mb-3">
                                                    <input value="1" {{ $submittal['showPromotion'] == 1 ? 'checked' : '' }} type="radio"  name="showPromotion">
                                                    <label class=""  for="formCheck1">
                                                        Yes
                                                    </label>
                                                </div>
                                                <div class="mb-3">
                                                    <input  value="0"{{ $submittal['showPromotion'] == 0 ? 'checked' : '' }} type="radio" name="showPromotion">
                                                    <label class="" for="formCheck1">
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

const defaultCHRSec1 = ` <h3>Commission Details</h3>
     <section>
                        <div>
                            <form>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="feesCharged">Fees Charged to Seller at
                                                Closing</label>
                                            <input type="text" name="feesCharged"
                                                value="{{ $submittal['feesCharged'] }}" id="feesCharged"
                                                class="form-control " placeholder="$">
                                            </input>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="referralToPay">Referral to Pay</label>
                                            <select name="additional_charge" id="additonal_fee" class="form-select">
                                                <option value="" selected>None</option>
                                                <option value="Yes"
                                                    {{ $submittal['referralToPay'] == 'Yes' ? 'selected' : '' }}>Yes
                                                </option>
                                                <option value="No"
                                                    {{ $submittal['referralToPay'] == 'No' ? 'selected' : '' }}>No
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="amountToCHR">Amount to CHR Gives </label>
                                            <input type="number"name="amountToCHR" placeholder = "$"
                                                value="{{ $submittal['amountToCHR'] }}" id="amountToCHR"
                                                class="form-control">
                                            </input>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="referralDetails">Referral Details </label>
                                            <input name="referralDetails" value="{{ $submittal['referralDetails'] }}"
                                                id="referralDetails" class="form-control">
                                            </input>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

     </section>`; 

     const defaultCHRSec2 = `<h3>Service Providers</h3>
        <section>
                <div>
                    <form>
                        <div class="row">
                                 <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="builderCommisionPercent">Schedule Sign Install<svg
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
                                            <div class="d-flex gap-2">
                                                <div class="mb-3">
                                                    <input type="radio" id="scheduleSignInstall_yes"
                                                        {{ $submittal['scheduleSignInstall'] == 'on' ? 'checked' : '' }}
                                                        name="scheduleSignInstall">
                                                    <label class="" for="formCheck1">
                                                        Yes
                                                    </label>
                                                </div>
                                                <div class="mb-3">
                                                    <input type="radio" id="scheduleSignInstall_no"
                                                        {{ $submittal['scheduleSignInstall'] == 'off' ? 'checked' : '' }}
                                                        name="scheduleSignInstall">
                                                    <label class="" for="formCheck1">
                                                        No
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="buyerOtherNotes">Draft Showing Instructions? <svg
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
                                            <div class="d-flex gap-2">
                                                <div class="mb-3">
                                                    <input type="radio" id="draftShowingInstructions_yes"
                                                        {{ $submittal['draftShowingInstructions'] == 'on' ? 'checked' : '' }}
                                                        name="draftShowingInstructions">
                                                    <label class="" for="formCheck1">
                                                        Yes
                                                    </label>
                                                </div>
                                                <div class="mb-3">
                                                    <input type="radio" id="draftShowingInstructions_no"
                                                        {{ $submittal['draftShowingInstructions'] == 'off' ? 'checked' : '' }}
                                                        name="draftShowingInstructions">
                                                    <label class="" for="formCheck1">
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
                                            <label for="amountToCHR">Concierge Listing (Optional) </label>
                                            <div class="d-flex gap-2">
                                                <div class="mb-3">
                                                    <input type="radio" id="conciergeListing_yes"
                                                        {{ $submittal['conciergeListing'] == 'on' ? 'checked' : '' }}
                                                        name="conciergeListing">
                                                    <label class="" id="chkNo" for="formCheck1">
                                                        Yes
                                                    </label>
                                                </div>
                                                <div class="mb-3">
                                                    <input type="radio" id="conciergeListing_no"
                                                        {{ $submittal['conciergeListing'] == 'off' ? 'checked' : '' }}
                                                        name="conciergeListing">
                                                    <label class="" for="formCheck1">
                                                        No
                                                    </label>
                                                </div>
                                            </div>
                                            </input>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="titleCompany">Title Company </label>
                                            <input value="{{ $submittal['titleCompany'] }}" name="titleCompany"
                                                id="titleCompany" class="form-control validate_err">
                                            </input>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="signInstallDate">Sign Install Date </label>
                                            <input type="date" value="{{ $submittal['signInstallDate'] }}"
                                                class="form-control" id="signInstallDate">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="titleCompany">Closer Name & Phone<svg
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
                                                </svg> </label>
                                            <input name="closerNamePhone" value="{{ $submittal['closerNamePhone'] }}"
                                                id="closerNamePhone" class="form-control validate_err required-field">
                                            </input>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

     </section>`;

     const defaultCHRSec3 = `<h3>Service Providers</h3>
       <section>
              <div>
                <form>
                     <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label for="feesCharged">Sign Install Vendor Info</label>
                                        <input value="{{ $submittal['signInstallVendor'] }}" name="signInstallVendor"
                                            id="signInstallVendor" class="form-control">
                                        </input>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label for="feesCharged">Sign Install Vendor (if Other)</label>
                                        <input value="{{ $submittal['signInstallVendorOther'] }}"
                                            name="signInstallVendorOther" id="signInstallVendorOther"
                                            class="form-control">
                                        </input>
                                    </div>
                                </div>


                            </form>
                        </div>

     </section>`;

     const defaultCHRSec4 = ` <h3>Select MLS</h3>
    <section>
                <div>
                    <form>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <div class="row mb-4">
                                                <div class="col-lg-4 d-flex gap-2">
                                                    <div>REColorado</div>
                                                    <div> <input type="checkbox" id="reColorado" <?php if ($submittal['reColorado']) {
                                                        echo 'checked';
                                                    } ?>>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 d-flex gap-2">
                                                    <div>Navica</div>
                                                    <div> <input type="checkbox" id="navica" <?php if ($submittal['navica']) {
                                                        echo 'checked';
                                                    } ?>>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 d-flex gap-2">
                                                    <div>PPAR</div>
                                                    <div><input type="checkbox" id="ppar" <?php if ($submittal['ppar']) {
                                                        echo 'checked';
                                                    } ?>>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <div class="row mb-4">
                                                <div class="col-lg-4 d-flex gap-2">
                                                    <div>Grand County</div>
                                                    <div><input type="checkbox" id="grandCounty" <?php if ($submittal['grandCounty']) {
                                                        echo 'checked';
                                                    } ?>>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 d-flex gap-2">
                                                    <div>IRES</div>
                                                    <div><input type="checkbox" id="ires" <?php if ($submittal['ires']) {
                                                        echo 'checked';
                                                    } ?>>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="amountToCHR">MLS public remarks </label>
                                            <textarea class="form-control" id="mlsPrivateRemarks" aria-label="With textarea">{{ $submittal['mlsPrivateRemarks'] }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="titleCompany">MLS private remarks</label>
                                            <textarea class="form-control" id="mlsPublicRemarks" aria-label="With textarea">{{ $submittal['mlsPublicRemarks'] }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

 </section>`;
                      //chr tm End
          
        //confirm details
        const confirmDetail = ` <h3>Confirm Detail</h3>
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

        //properties start
        const innrtHtml = `<div class="row property">
            <div class="gap-2 col-lg-6">
                <div class="d-flex gap-2">
                    <label for="add_email" class="common-label">Matterport</label>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" <?php if ($submittal['matterport']) {
                                            echo 'checked';
                                        } ?>  id="matterport">
                    </div>
                </div>
            </div>
            <div class="gap-2 col-lg-6 label-div-mb">
                <div class="d-flex gap-2">
                    <label for="add_email" class="common-label">Floor Plans</label>
                    <div class="form-check mb-3">
                        <input class="form-check-input" <?php if ($submittal['floorPlans']) {
                                            echo 'checked';
                                        } ?> type="checkbox" id="floorPlans">

                    </div>
                </div>
            </div>
            </div>

            <div class="row">
                <div class="gap-2 col-lg-6">
                    <div class="d-flex gap-2">
                        <label for="add_email" class="common-label">3D Zillow Tour</label>
                        <div class="form-check mb-3">
                            <input class="form-check-input" <?php if ($submittal['threeDZillowTour']) {
                                            echo 'checked';
                                        } ?> type="checkbox" id="threeDZillowTour">
                        </div>
                    </div>
                </div>
                <div class="gap-2 col-lg-6 label-div-mb">
                    <div class="d-flex gap-2">
                        <label for="add_email" class="common-label">Onsite Video</label>
                        <div class="form-check mb-3">
                            <input class="form-check-input" <?php if ($submittal['onsiteVideo']) {
                                            echo 'checked';
                                        } ?> type="checkbox" id="onsiteVideo">

                        </div>
                    </div>
                </div>
            </div>
            </div>`;

        const innrtHtml2 = `<div class="row property">
                <div class="gap-2 col-lg-6">
                    <div class="d-flex gap-2">
                        <label for="add_email" class="common-label">Email Blast to Sphere</label>
                        <div class="form-check mb-3">
                            <input class="form-check-input" <?php if ($submittal['emailBlastSphere']) {
                                            echo 'checked';
                                        } ?> type="checkbox" id="emailBlastSphere">
                        </div>
                    </div>
                </div>
                <div class="gap-2 col-lg-6 label-div-mb">
                    <div class="d-flex gap-2">
                        <label for="add_email" class="common-label">Email Blast to Reverse Prospect List</label>
                        <div class="form-check mb-3">
                            <input class="form-check-input" <?php if ($submittal['emailBlastReverseProspect']) {
                                            echo 'checked';
                                        } ?> type="checkbox" id="emailBlastReverseProspect">

                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="gap-2 col-lg-6">
                    <div class="d-flex gap-2">
                        <label for="add_email" class="common-label">Social Media Ads</label>
                        <div class="form-check mb-3">
                            <input class="form-check-input" <?php if ($submittal['socialMediaAds']) {
                                            echo 'checked';
                                        } ?> type="checkbox" id="socialMediaAds">
                        </div>
                    </div>
                </div>
                <div class="gap-2 col-lg-6 label-div-mb">
                    <div class="d-flex gap-2">
                        <label for="add_email" class="common-label">Social Media Images</label>
                        <div class="form-check mb-3">
                            <input class="form-check-input"  <?php if ($submittal['socialMediaImages']) {
                                            echo 'checked';
                                        } ?> type="checkbox" id="socialMediaImages">

                        </div>
                    </div>
                </div>
            </div>
            <div class="gap-2 col-lg-6 label-div-mb">
                <div class="d-flex gap-2">
                    <label for="add_email" class="common-label">Price Improvement Package</label>
                    <div class="form-check mb-3">
                        <input class="form-check-input"  <?php if ($submittal['priceImprovementPackage']) {
                                            echo 'checked';
                                        } ?> type="checkbox" id="priceImprovementPackage">

                    </div>
                </div>
            </div>
            </div>`;
        const innrtHtml3 = `<div class="row property">
                    <div class="gap-2 col-lg-6">
                        <div class="d-flex gap-2">
                            <label for="add_email" class="common-label">Property Website</label>
                            <div class="form-check mb-3">
                                <input <?php if ($submittal['propertyWebsite']) {
                                            echo 'checked';
                                        } ?>  type="checkbox" id="propertyWebsite">
                            </div>
                        </div>
                    </div>
                   <div class="gap-2 col-lg-6 label-div-mb">
                    <div class="additional_email label-div-mb">
                        <label for="customDomainName123" class="common-label">Custom Domain Name</label>
                        <input type="text" value="{{ $submittal['customDomainName'] ?? '' }}" class="form-control" placeholder="" id="customDomainName">
                    </div>
                </div>

                    <div class="gap-2 col-lg-6 label-div-mb">
                       <div class="d-flex gap-2">
                        <label for="propertyHighlightVideo" class="common-label">Property Highlight Video</label>
                        <div class="form-check mb-3">
                            <input 
                                type="checkbox" 
                                id="propertyHighlightVideo" 
                                name="propertyHighlightVideo" 
                                {{ isset($submittal['propertyHighlightVideo']) && $submittal['propertyHighlightVideo'] == 1 ? 'checked' : '' }}>
                        </div>
                    </div>

                    </div>
                    </div>
                    <div class="gap-2 col-lg-6 label-div-mb">
                    <div class="additional_email label-div-mb">
                        <label for="add_email" class="common-label">8-12 Features Needed for Video</label>
                        <input type="text" value="{{ isset($submittal['featuresNeededForVideo']) ? $submittal['featuresNeededForVideo'] : '' }}" class="form-control"
                            placeholder="" id="featuresNeededForVideo">
                    </div>
                    </div>
            `;

        const innrtHtml4 = `<div class="label-div-mb property"><label for="add_email"              class="common-label">Brochure Design - <b>Click for
                                options<b>
                                
                            </label>
                            <div class="nontm-select-div">
                                <select name="brochureLine" id="brochureLine" class="nontm-select form-select">
                                    @foreach ($broucherLines as $brochureLine)
                                        <option value="{{ $brochureLine }}"
                                            {{ $brochureLine == $submittal->brochureLine ? 'selected' : '' }}>
                                            {{ $brochureLine }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>s
                        <div class="label-div-mb"><label for="add_email" class="common-label">Brochure - Print, Deliver or PDF
                            
                            </label>
                            <div class="nontm-select-div">
                                <select name="brochurePrint" id="brochurePrint" class="nontm-select form-select">
                                    <option value ="">--None--</option>
                                    @foreach ($broucherPrint as $broucherprint)
                                        <option value="{{ $broucherprint }}"
                                            {{ $broucherprint == $submittal->brochurePrint ? 'selected' : '' }}>
                                            {{ $broucherprint }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="label-div-mb"><label for="add_email" class="common-label">12 bullets, 4 words per bullet</label>
                            <textarea class="form-control" id='bullets' rows="4" cols="50">{{ $submittal->bullets }}</textarea>
                        </div>`;
        const innrtHtml5 = `<div class="label-div-mb property"><label for="add_email"  class="common-label">
                                    4 Word Headline - If Opting for A-Line Brochure</label>
                                <textarea class="form-control" id="headlineForBrochure" rows="4" cols="50">{{ $submittal->headlineForBrochure }}</textarea>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="label-div-mb"><label for="add_email" class="common-label">Sticky Dots</label>
                                        <div class="nontm-select-div">
                                            <select name="stickyDots" id="stickyDots" class="nontm-select form-select">
                                                @foreach ($stickyDots as $stickyDot)
                                                    <option value="{{ $stickyDot }}"
                                                        {{ $stickyDot == $submittal->stickyDots ? 'selected' : '' }}>
                                                        {{ $stickyDot }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 label-div-mb">
                                    <div class="label-div-mb"><label for="add_email" class="common-label">QR Code Sheet</label>
                                        <div class="nontm-select-div">
                                            <select name="qrCodeSheet" id="qrCodeSheet" class="nontm-select form-select">
                                                @foreach ($qrCodeSheets as $qrCodeSheet)
                                                    <option value="{{ $qrCodeSheet }}"
                                                        {{ $qrCodeSheet == $submittal->qrCodeSheet ? 'selected' : '' }}>
                                                        {{ $qrCodeSheet }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                             <div class="row">
                            <div class="gap-2 col-lg-6">
                                <div class="d-flex gap-2">
                                    <div class="form-check mb-3">
                                        <input type="checkbox" id="qrCodeSignRider" <?php if ($submittal['qrCodeSignRider']) {
                                            echo 'checked';
                                        } ?>>
                                    </div>
                                    <label for="add_email" class="common-label">QR Code Sign Rider</label>
                              </div>
                          </div>
                          <div class="gap-2 col-lg-6">
                                <div class="d-flex gap-2">
                                    <div class="form-check mb-3">
                                        <input type="checkbox" id="qrCodeMainPanel" <?php if ($submittal['qrCodeMainPanel']) {
                                            echo 'checked';
                                        } ?>>
                                    </div>
                                    <label for="add_email" class="common-label">QR Code Main Panel</label>
                              </div>
                          </div>
                          </div>
                                `;

        const innrtHtml6 = `<div class="label-div-mb property">
                            <h3 for="add_email">
                                Feature Cards</h3>
                            <div class="label-div-mb"><label for="add_email" class="common-label">Feature Card</label>
                                <div class="nontm-select-div">
                                    <select name="featureCards" id="featureCards" class="nontm-select form-select">
                                        @foreach ($featuresCard as $featureCards)
                                            <option value="{{ $featureCards }}"
                                                {{ $featureCards == $submittal->featureCards ? 'selected' : '' }}>
                                                {{ $featureCards }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="label-div-mb"><label for="add_email" class="common-label">Add Feature Card Copy</label>
                            <textarea id="featureCardCopy" class="form-control" rows="4" cols="50">{{ $submittal->featureCardCopy }}</textarea>
                        </div>
                        `;
        const innrtHtml7 = ` <div class="close-date-nontm label-div-mb property">
                            <label for="brochureDeliveryDate" class="common-label">Brochure Date</label>
                            <input type="date"
                                value="{{ isset($submittal['brochureDeliveryDate']) ? \Carbon\Carbon::parse($submittal['brochureDeliveryDate'])->format('Y-m-d') : '' }}"
                                class="form-control nontm-input" id="brochureDeliveryDate">
                            <div id="brochureDeliveryDate_error" class="text-danger">

                            </div>
                        </div>
                        <div class="label-div-mb"><label for="add_email" class="common-label">Shipping Address</label>
                            <textarea id="deliveryAddress" class="form-control" rows="4" cols="50">{{ $submittal->deliveryAddress }}</textarea>
                        </div>
                        
                        <div class="close-date-nontm label-div-mb">
                            <label for="printedItemsPickupDate" class="common-label">Printed Items Pick Up or PDF Date</label>
                            <input type="date"
                                value="{{ isset($submittal['printedItemsPickupDate']) ? \Carbon\Carbon::parse($submittal['printedItemsPickupDate'])->format('Y-m-d') : '' }}"
                                class="form-control nontm-input" id="printedItemsPickupDate">
                            <div id="printedItemsPickupDate_error" class="text-danger">

                            </div>
                        </div>
                        <div class="close-date-nontm label-div-mb">
                            <label for="brochurePickupDate" class="common-label">Brochure Pick Up or PDF Date
                           </label>
                            <input type="date"
                                value="{{ isset($submittal['brochurePickupDate']) ? \Carbon\Carbon::parse($submittal['brochurePickupDate'])->format('Y-m-d') : '' }}"
                                class="form-control nontm-input" id="brochurePickupDate">
                            <div id="brochurePickupDate_error" class="text-danger">

        </div>
    </div>
    `;
        const innrtHtml8 = `<div class="property"><label for="add_email" class="text-bold">Is there anything else the Marketing Team should
                                know?</label>
                        </div>
                        <div class="label-div-mb"><label for="add_email" class="common-label">Please Add your Notes</label>
                            <textarea id="marketingNotes" class="form-control" rows="4" cols="50">{{ $submittal->marketingNotes }}</textarea>
                        </div>
                        <div class="text-end" id="saveSubmit">
                            <button type="button" onclick="validateSubmittal(true)"
                            class="btn btn-white fw-bold">Save & Submit</button>
                        </div>
                        `;

        //properties end
        // Event listeners and other logic
        let initialCHrusingValue = document.querySelector('input[name="usingCHR"]:checked')?.value;
        setTimeout(() => {
            let initialshowPromotion = document.querySelector('input[name="showPromotion"]:checked')?.value;
            showAndDisableValues(initialshowPromotion,'showPropinitial');
        }, 500);
        showAndDisableValues(initialCHrusingValue);
        

        document.addEventListener('change', event => {
            if (event.target.matches('input[name="usingCHR"]')) {
                const radioButton = event.target;
                const value = radioButton.value;
                showAndDisableValues(value);
            }
        });

        document.addEventListener('change', event => {
            if (event.target.matches('input[name="showPromotion"]')) {
                const radioButton = event.target;
                const value = radioButton.value;
                showAndDisableValues(value,'showProp');
            }
        });

        
     
        function showAndDisableValues(value,show) {
             if(value==1 && show ==="showProp"){
                addStepChr('Commission Details', innrtHtml);
                addStepChr('Service Providers', innrtHtml2);
                addStepChr('Commission Details', innrtHtml3);
                addStepChr('Service Providers', innrtHtml4);
                addStepChr('Commission Details', innrtHtml5);
                addStepChr('Service Providers', innrtHtml6);
                addStepChr('Commission Details', innrtHtml7);
                addStepChr('Service Providers', innrtHtml8);
             }else if(value==0 && show ==="showProp"){
                   removeStep(8);
                   removeStep(9);
                   removeStep(10);
                   removeStep(11);
                   removeStep(12);
                   removeStep(13);
                   removeStep(14);
                   for (let i = 8; i <=13; i++) {
                    removeStep(i);
                    }
                    for (let i = 8; i <=10; i++) {
                    removeStep(i);
                    }
                    for (let i = 8; i <=9; i++) {
                    removeStep(i);
                    }
             }
            if (value === "Yes" && value !== undefined) {
                if($('.property').length===8){
                    console.log( $('.property').length,'lengththth')
                    for (let i = 3; i <=14; i++) {
                    removeStep(i);
                    }
                    for (let i = 3; i <=7; i++) {
                    removeStep(i);
                    }
                    for (let i = 3; i <=4; i++) {
                    removeStep(i);
                    }
                    for (let i = 3; i <=3; i++) {
                    removeStep(i);
                    }
                    addStepChr('Commission Details', defaultCHRSec1);
                    addStepChr('Commission Details', defaultCHRSec2);
                    addStepChr('Service Providers', defaultCHRSec3);
                    addStepChr('Select MLS', defaultCHRSec4);
                    
                    }
                addStepChr('Commission Details', CommissionDetails);
                addStepChr('Service Providers', serviceProvider);
            
            } else if (value === "No") {
                for (let i = 6; i >= 3; i--) {
                    removeStep(i);
                }
                for (let i = 4; i >= 3; i--) {
                    removeStep(i);
                }
                addStepChr('Commission Details', innrtHtml);
                addStepChr('Service Providers', innrtHtml2);
                addStepChr('Commission Details', innrtHtml3);
                addStepChr('Service Providers', innrtHtml4);
                addStepChr('Commission Details', innrtHtml5);
                addStepChr('Service Providers', innrtHtml6);
                addStepChr('Commission Details', innrtHtml7);
                addStepChr('Service Providers', innrtHtml8);
            } else {
                console.log('yes hereee')
                if(show !=="showProp" && show !=="showPropinitial"){ 
                    removeStep(7)
                }
            }
        }

        function validateStep(stepIndex) {
            let isValid = true;
            const $currentSection = $stepsContainer.find(`section:eq(${stepIndex})`);

            // Check required fields in this section
            $currentSection.find('.required-field').each(function() {
                if($(this).val()==="-None-"){
                    isValid = false;
                    $(this).addClass('error'); 
                }else {
                    $(this).removeClass('error'); // Remove error class if valid
                }
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
                showToastError("Please fill out all required fields.");
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
            console.log(index,'index is hereee')
            if (!$stepsContainer || !$stepsContainer.length) {
                console.error('$stepsContainer is not initialized.');
                return;
            }
          
                $stepsContainer.steps('remove', index);
          
            
        }
    })

</script>
