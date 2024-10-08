{{-- @component('components.breadcrumb')
@slot('li_1') Form @endslot
@slot('title') Buyer Submittal @endslot
@endcomponent --}}

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4 title_h4">TM and Property Promotion</h4>

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
                                            <label for="bedsBathsTotal">Beds,baths,total sq.ft.<svg
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
                                            <input type="text" value="{{ $submittal['bedsBathsTotal'] }}"
                                                class="form-control required-field" placeholder=""
                                                id="bedsBathsTotal">
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

                    <h3>HOA Information</h3>
                    <section class="chr_section">
                        <div>
                            <form>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="titleToOrderHOA" id="titleToOrderHOA">Title to Order HOA docs?
                                            </label>
                                            <div class="d-flex gap-2">
                                                <div class="mb-3">
                                                    <input type="radio" id="titleToOrderHOA_yes" value="Yes"
                                                        {{ $submittal['titleToOrderHOA'] == 'Yes' ? 'checked' : '' }}
                                                        name="titleToOrderHOA">
                                                    <label class="" id="chkNo" for="formCheck1">
                                                        Yes
                                                    </label>
                                                </div>
                                                <div class="mb-3">
                                                    <input type="radio" id="titleToOrderHOA_no" value="No"
                                                        {{ $submittal['titleToOrderHOA'] == 'No' ? 'checked' : '' }}
                                                        name="titleToOrderHOA">
                                                    <label class="" for="formCheck1">
                                                        No
                                                    </label>
                                                </div>
                                                <div class="mb-3">
                                                    <input type="radio" id="titleToOrderHOA_tbd" value="No"
                                                        {{ $submittal['titleToOrderHOA'] == 'TBD' ? 'checked' : '' }}
                                                        name="titleToOrderHOA">
                                                    <label class="" for="formCheck1">
                                                        TBD
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="referralToPay">Has HOA? <svg
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
                                                    <input type="radio" id="hasHOA_yes" value="Yes"
                                                        {{ $submittal['hasHOA'] == 'Yes' ? 'checked' : '' }}
                                                        name="hasHOA">
                                                    <label class="" id="chkNo" for="formCheck1">
                                                        Yes
                                                    </label>
                                                </div>
                                                <div class="mb-3">
                                                    <input type="radio" id="hasHOA_no" value="No"
                                                        {{ $submittal['hasHOA'] == 'No' ? 'checked' : '' }}
                                                        name="hasHOA">
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
                                            <label for="builderCommisionPercent">HOA Name</label>
                                            <input type="text" name="additional_charge" id="hoaName"
                                                value="{{ $submittal['hoaName'] }}" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="buyerOtherNotes">HOA Phone</label>
                                            <input type="text" name="additional_charge"
                                                value="{{ $submittal['hoaPhone'] }}" id="hoaPhone"
                                                class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="amountToCHR">HOA Website </label>
                                            <input type="text" value="{{ $submittal['hoaWebsite'] }}"
                                                class="form-control" placeholder="" id="hoaWebsite">
                                            </input>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </section>

                    <h3>Service Providers</h3>
                    <section class="chr_section">
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
                                            <label for="signInstallDate">Sign Install Date </label>
                                            <input type="date" value="{{ $submittal['signInstallDate'] }}"
                                                class="form-control" id="signInstallDate">
                                        </div>
                                    </div>


                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="feesCharged">Sign Install Vendor Info</label><br>
                                            <select class="form-select" name="signInstallVendor"
                                                id="signInstallVendor">
                                                <option value="-None-"
                                                    {{ $submittal['signInstallVendor'] === '-None-' ? 'selected' : '' }}>
                                                    -None-</option>
                                                <option value="AXIUM"
                                                    {{ $submittal['signInstallVendor'] === 'AXIUM' ? 'selected' : '' }}>
                                                    AXIUM</option>
                                                <option value="Rocky Mountain - Brandon"
                                                    {{ $submittal['signInstallVendor'] === 'Rocky Mountain - Brandon' ? 'selected' : '' }}>
                                                    Rocky Mountain - Brandon</option>
                                                <option value="Others"
                                                    {{ $submittal['signInstallVendor'] === 'Others' ? 'selected' : '' }}>
                                                    Others</option>
                                            </select>

                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="feesCharged">Sign Install Vendor (if Other)</label>
                                            <input value="{{ $submittal['signInstallVendorOther'] }}"
                                                name="signInstallVendorOther" id="signInstallVendorOther"
                                                class="form-control">
                                            </input>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </section>

                    <h3>Service Providers</h3>
                    <section class="chr_section">
                        <div>
                            <form>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="titleCompany">Title Company </label>
                                            <input value="{{ $submittal['titleCompany'] }}" name="titleCompany"
                                                id="titleCompany" class="form-control validate_err">
                                            </input>
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
                                <div class="row">
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

                            </form>
                        </div>
                    </section>

                    <h3>Select MLS</h3>
                    <section class="chr_section">
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

                    <h3>Commission Details</h3>
                    <section class="chr_section">
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

                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="titleCompany">Misc Notes - Seller, Communication, etc </label>
                                <textarea class="form-control" id="miscNotes" rows="4" cols="50">{{ $submittal['miscNotes'] }}</textarea>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="signInstallDate">Are you ready to continue to Property Promotion? </label>
                                <div class="d-flex gap-2">
                                    <div class="mb-3">
                                        <input value="1" {{ $submittal['showPromotion'] == 1 ? 'checked' : '' }}
                                            type="radio" name="showPromotion">
                                        <label class="" for="formCheck1">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="mb-3">
                                        <input value="0"{{ $submittal['showPromotion'] == 0 ? 'checked' : '' }}
                                            type="radio" name="showPromotion">
                                        <label class="" for="formCheck1">
                                            No
                                        </label>
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
<!-- end row -->
<!-- jquery step -->
<script defer src="{{ URL::asset('build/libs/jquery-steps/build/jquery.steps.min.js') }}"></script>

<script>
    function ValidateHoa() {
        // Select all radio buttons and the additional field
        const referralRadioButtons = document.querySelectorAll('input[name="hasHOA"]');
        const hoaName = document.getElementById('hoaName');
        const hoaPhone = document.getElementById('hoaPhone');
        const hoaWebsite = document.getElementById('hoaWebsite');
        const titleToOrderHOA = document.getElementById('titleToOrderHOA');

        // Function to update the additional field requirement
        function updateAdditionalFieldRequirement() {
            console.log("yesss")
            const isYesSelected = document.querySelector('input[name="hasHOA"]:checked')?.value === 'Yes';
            console.log(isYesSelected, 'isYesSelectedisYesSelected')
            if (isYesSelected) {
                hoaName?.classList?.add('required-field', 'validate');
                hoaPhone?.classList?.add('required-field', 'validate');
                hoaWebsite?.classList?.add('required-field', 'validate');
                titleToOrderHOA.innerHTML = `
                    <label for="referralToPay">
                    Title to Order HOA docs?
                    <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18" fill="none">
                        <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="19" height="18">
                            <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                        </mask>
                        <g mask="url(#mask0_2151_10662)">
                            <path d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z" fill="#AC5353" />
                        </g>
                    </svg>
                </label>
            `;
            } else {
                hoaName?.classList.remove('required-field', 'validate');
                hoaPhone?.classList.remove('required-field', 'validate');
                hoaWebsite?.classList.remove('required-field', 'validate');
                titleToOrderHOA.innerHTML = `
                <label for="referralToPay">
                    Title to Order HOA docs?
                </label>`
            }
        }

        // Add event listeners to all radio buttons
        referralRadioButtons?.forEach(radio => {
            radio.addEventListener('change', updateAdditionalFieldRequirement);
        });

        // Initial check in case the page is loaded with a pre-selected value
        updateAdditionalFieldRequirement();

    }
    $(document).ready(function() {
        var resubmitData = @json($resubmit);
        const $stepsContainer = $('#basic-example-seller');

        function initializeSteps() {
            $stepsContainer.steps({
                headerTag: "h3",
                enableAllSteps: true,
                bodyTag: "section",
                transitionEffect: "slide",
                // onStepChanging: function(event, currentIndex, newIndex) {
                //     // Perform validation before allowing step change
                //     const isValid = validateStep(currentIndex);
                //     return isValid;
                // },
                onFinished: function(event, currentIndex) {

                    window.validateSubmittal(true);
                    // Optionally, you might want to return true explicitly here
                    return true;
                }
            });
        }

        // Initial steps plugin setup
        initializeSteps();
        //chr tm start
        const CommissionDetails = ` <h3></h3>
            <section class="chr_section">
                <div>
                <form>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="feesCharged">Need O&E <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18"
                                    viewBox="0 0 19 18" fill="none">
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
                            <div class="d-flex gap-2">
                                <div class="mb-3">
                                    <input type="radio" id="needOE_yes" value="Yes" {{ $submittal['needOE'] == 'Yes' ? 'checked' : '' }} name="needO&E">
                                    <label class="" id="chkNo" for="formCheck1">
                                        Yes
                                    </label>
                                </div>
                                <div class="mb-3">
                                    <input {{ $submittal['needOE'] == 'No' ? 'checked' : '' }} type="radio" id="needOE_no"
                                        value="No" name="needO&E">
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
                                <label for="feesCharged">Include Insights in Intro? <svg xmlns="http://www.w3.org/2000/svg"
                                        width="19" height="18" viewBox="0 0 19 18" fill="none">
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
                </div>


                    <div class="row">
                        <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="referralToPay">Power of Attny Needed? <svg xmlns="http://www.w3.org/2000/svg"
                                    width="19" height="18" viewBox="0 0 19 18" fill="none">
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
                            <div class="d-flex gap-2">
                                <div class="mb-3">
                                    <input type="radio" id="powerOfAttnyNeeded_yes" value="Yes" {{ $submittal['powerOfAttnyNeeded'] == 'Yes' ? 'checked' : '' }}
                                        name="powerOfAttnyNeeded">
                                    <label class="" id="chkNo" for="formCheck1">
                                        Yes
                                    </label>
                                </div>
                                <div class="mb-3">
                                    <input type="radio" id="powerOfAttnyNeeded_no" value="No" {{ $submittal['powerOfAttnyNeeded'] == 'No' ? 'checked' : '' }}
                                        name="powerOfAttnyNeeded">
                                    <label class="" for="formCheck1">
                                        No
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="feesCharged">Mailout Needed? <svg xmlns="http://www.w3.org/2000/svg" width="19"
                                        height="18" viewBox="0 0 19 18" fill="none">
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
                    </div>
                    
                    </form>
                </div>

            </section>`;

        const defaultCHRSec1 = ` <h3></h3>
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

                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="titleCompany">Misc Notes - Seller, Communication, etc </label>
                                <textarea class="form-control" id="miscNotes" rows="4" cols="50">{{ $submittal['miscNotes'] }}</textarea>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="signInstallDate">Are you ready to continue to Property Promotion? </label>
                                <div class="d-flex gap-2">
                                    <div class="mb-3">
                                        <input value="1" {{ $submittal['showPromotion'] == 1 ? 'checked' : '' }}
                                            type="radio" name="showPromotion">
                                        <label class="" for="formCheck1">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="mb-3">
                                        <input value="0"{{ $submittal['showPromotion'] == 0 ? 'checked' : '' }}
                                            type="radio" name="showPromotion">
                                        <label class="" for="formCheck1">
                                            No
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </section>`;

        const defaultCHRSec2 = `<h3></h3>
          <section class="chr_section">
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
                                            <label for="signInstallDate">Sign Install Date </label>
                                            <input type="date" value="{{ $submittal['signInstallDate'] }}"
                                                class="form-control" id="signInstallDate">
                                        </div>
                                    </div>

                                    
                                </div>  
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="feesCharged">Sign Install Vendor Info</label><br>
                                            <select class="form-select" name="signInstallVendor"
                                                id="signInstallVendor">
                                                <option value="-None-"
                                                    {{ $submittal['signInstallVendor'] === '-None-' ? 'selected' : '' }}>
                                                    -None-</option>
                                                <option value="AXIUM"
                                                    {{ $submittal['signInstallVendor'] === 'AXIUM' ? 'selected' : '' }}>
                                                    AXIUM</option>
                                                <option value="Rocky Mountain - Brandon"
                                                    {{ $submittal['signInstallVendor'] === 'Rocky Mountain - Brandon' ? 'selected' : '' }}>
                                                    Rocky Mountain - Brandon</option>
                                                <option value="Others"
                                                    {{ $submittal['signInstallVendor'] === 'Others' ? 'selected' : '' }}>
                                                    Others</option>
                                            </select>

                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="feesCharged">Sign Install Vendor (if Other)</label>
                                            <input value="{{ $submittal['signInstallVendorOther'] }}"
                                                name="signInstallVendorOther" id="signInstallVendorOther"
                                                class="form-control">
                                            </input>
                                        </div>
                                    </div>
                                </div>
                                
                            </form>
                        </div>
                    </section>`;

        const defaultCHRSec3 = `<h3></h3>
                    <section class="chr_section">
                        <div>
                            <form>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="titleCompany">Title Company </label>
                                            <input value="{{ $submittal['titleCompany'] }}" name="titleCompany"
                                                id="titleCompany" class="form-control validate_err">
                                            </input>
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
                                <div class="row">
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
                                
                            </form>
                        </div>
                    </section>`;

        const defaultCHRSec4 = ` <h3></h3>
            <section class="chr_section">
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

        //properties start
        const innrtHtml = `<div class="row property">
            <div class="gap-2 col-lg-6">
                <div class="d-flex gap-2">
                    <label for="add_email" >Matterport</label>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" <?php if ($submittal['matterport']) {
                            echo 'checked';
                        } ?>  id="matterport">
                    </div>
                </div>
            </div>
            <div class="gap-2 col-lg-6 label-div-mb">
                <div class="d-flex gap-2">
                    <label for="add_email" >Floor Plans</label>
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
                        <label for="add_email" >3D Zillow Tour</label>
                        <div class="form-check mb-3">
                            <input class="form-check-input" <?php if ($submittal['threeDZillowTour']) {
                                echo 'checked';
                            } ?> type="checkbox" id="threeDZillowTour">
                        </div>
                    </div>
                </div>
                <div class="gap-2 col-lg-6 label-div-mb">
                    <div class="d-flex gap-2">
                        <label for="add_email" >Onsite Video</label>
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
                        <label for="add_email" >Email Blast to Sphere</label>
                        <div class="form-check mb-3">
                            <input class="form-check-input" <?php if ($submittal['emailBlastSphere']) {
                                echo 'checked';
                            } ?> type="checkbox" id="emailBlastSphere">
                        </div>
                    </div>
                </div>
                <div class="gap-2 col-lg-6 label-div-mb">
                    <div class="d-flex gap-2">
                        <label for="add_email" >Email Blast to Reverse Prospect List</label>
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
                        <label for="add_email" >Social Media Ads</label>
                        <div class="form-check mb-3">
                            <input class="form-check-input" <?php if ($submittal['socialMediaAds']) {
                                echo 'checked';
                            } ?> type="checkbox" id="socialMediaAds">
                        </div>
                    </div>
                </div>
                <div class="gap-2 col-lg-6 label-div-mb">
                    <div class="d-flex gap-2">
                        <label for="add_email" >Social Media Images</label>
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
                    <label for="add_email" >Price Improvement Package</label>
                    <div class="form-check mb-3">
                        <input class="form-check-input"  <?php if ($submittal['priceImprovementPackage']) {
                            echo 'checked';
                        } ?> type="checkbox" id="priceImprovementPackage">

                    </div>
                </div>
            </div>
            </div>`;
        const innrtHtml3 = `<h3></h3>
                <section>
                    <div class="property">
                        <form>
                            <div class="row">
                                <div class="gap-2 col-lg-6">
                                    <div class="d-flex gap-2">
                                        <label for="add_email" >QR Code Sign Rider</label>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" id="qrCodeSignRider" <?php if ($submittal['qrCodeSignRider']) {
                                                echo 'checked';
                                            } ?>>
                                        </div>
                                </div>
                                </div>
                                <div class="gap-2 col-lg-6 label-div-mb">
                                    <div class="d-flex gap-2">
                                        <label for="add_email" >QR Code Main Panel</label>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" id="qrCodeMainPanel" <?php if ($submittal['qrCodeMainPanel']) {
                                                echo 'checked';
                                            } ?>>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>            
                            <div class="row">
                                <div class="gap-2 col-lg-6">
                                    <div class="d-flex gap-2">
                                        <label for="add_email" >Property Website</label>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" <?php if ($submittal['propertyWebsite']) {
                                                echo 'checked';
                                            } ?>  type="checkbox" id="propertyWebsite">
                                        </div>
                                    </div>
                                </div>
                                <div class="gap-2 col-lg-6 label-div-mb">
                                    <div class="d-flex gap-2">
                                        <label for="propertyHighlightVideo" >Property Highlight Video</label>
                                        <div class="form-check mb-3">
                                            <input
                                                class="form-check-input" 
                                                type="checkbox" 
                                                id="propertyHighlightVideo" 
                                                name="propertyHighlightVideo" 
                                                {{ isset($submittal['propertyHighlightVideo']) && $submittal['propertyHighlightVideo'] == 1 ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                </div>    
                            </div>
                            <div class="row">
                                <div class="gap-2 col-lg-6">
                                    <div class="class="mb-3 label-div-mb">
                                        <label for="customDomainName123" >Custom Domain Name</label>
                                        <input type="text" value="{{ $submittal['customDomainName'] ?? '' }}" class="form-control" placeholder="" id="customDomainName">
                                    </div>
                                </div>

                                        
                                <div class="gap-2 col-lg-6 label-div-mb">
                                    <div class="class="mb-3 label-div-mb">
                                        <label for="add_email" >8-12 Features Needed for Video</label>
                                        <input type="text" value="{{ isset($submittal['featuresNeededForVideo']) ? $submittal['featuresNeededForVideo'] : '' }}" class="form-control"
                                            placeholder="" id="featuresNeededForVideo">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>
                
            `;

        const innrtHtml4 = `<h3></h3>
        <section>
            <div class="property">
                <form>
                    <div class="row">
                        <div class="gap-2 col-lg-6">
                            <div class="d-flex gap-2">
                                <label for="add_email" >Brochure Design-PDF</label>
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
                            </div>
                        </div>
                        <div class="gap-2 col-lg-6 label-div-mb">
                            <div class="d-flex gap-2">
                                <label for="add_email" >Brochure - Print, Deliver or PDF</label>
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
                        </div>
                    </div>
                    <div class="row">
                        <div class="gap-2 col-lg-6  label-div-mb">
                            <div class="d-flex gap-2">
                                <label for="add_email" >Delivery Only - Shipping Address & Name Date</label>
                                <textarea id="deliveryAddress" class="form-control" rows="4" cols="50">{{ $submittal->deliveryAddress }}</textarea>
                            </div>
                        </div>
                        <div class="gap-2 col-lg-6">
                            <div class="d-flex gap-2">
                                <label for="brochurePickupDate" >Brochure Pick Up or PDF Date</label>
                                <input type="date"
                                value="{{ isset($submittal['brochurePickupDate']) ? \Carbon\Carbon::parse($submittal['brochurePickupDate'])->format('Y-m-d') : '' }}"
                                class="form-control nontm-input" id="brochurePickupDate">
                                <div id="brochurePickupDate_error" class="text-danger">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="gap-2 col-lg-6 label-div-mb">
                            <div class="d-flex gap-2">
                                <label for="brochureDeliveryDate" >Delivery Only - Brochure Date</label>
                                <input type="date"
                                    value="{{ isset($submittal['brochureDeliveryDate']) ? \Carbon\Carbon::parse($submittal['brochureDeliveryDate'])->format('Y-m-d') : '' }}"
                                    class="form-control nontm-input" id="brochureDeliveryDate">
                                <div id="brochureDeliveryDate_error" class="text-danger">

                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="gap-2 col-lg-6">
                            <div class="d-flex gap-2">
                                <label for="add_email" >12 bullets, 4 words per bullet</label>
                                <textarea class="form-control" id='bullets' rows="4" cols="50">{{ $submittal->bullets }}</textarea>
                            </div>
                        </div>
                        <div class="gap-2 col-lg-6 label-div-mb">
                            <div class="d-flex gap-2">
                                <label for="add_email"  >
                                    4 Word Headline - If Opting for A-Line Brochure</label>
                                <textarea class="form-control" id="headlineForBrochure" rows="4" cols="50">{{ $submittal->headlineForBrochure }}</textarea>
                            </div>
                        </div>                              
                    </div>
                    <div class="row">
                        <div class="gap-2 col-lg-6">
                            <div class="d-flex gap-2">
                                <label for="add_email" >Paragraph 200 Words (4 page brochure or Look Book)</label>
                                <textarea class="form-control" id='paragraph_200_words_4_page_brochure_or_look_book' rows="4" cols="50">{{ $submittal->paragraph_200_words_4_page_brochure_or_look_book }}</textarea>
                            </div>
                        </div>
                        <div class="gap-2 col-lg-6 label-div-mb">
                            <div class="d-flex gap-2">
                                <label for="add_email"  >Buyer’s Agent Compensation Offering</label>
                                <input type="text" value="{{ isset($submittal['buyer_agent_compensation_offering']) ? $submittal['buyer_agent_compensation_offering'] : '' }}" class="form-control"
                                            placeholder="" id="buyer_agent_compensation_offering">
                            </div>
                        </div>                              
                    </div>                            
                </form>                          
            </div>                       
        </section>`;
        const innrtHtml5 = `<h3></h3>
        <section>
            <div class ="property">
                <form>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="label-div-mb"><label for="add_email" >Feature Card</label>
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
                        <div class="col-lg-6">
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
                        <div class="col-lg-6">
                            <div class="label-div-mb"><label for="add_email" >Sticky Dots</label>
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
                        <div class="col-lg-6">
                            <div class="label-div-mb"><label for="printedItemsPickupDate" >Printed Items Pick Up or PDF Date</label>
                                    <input type="date"
                                        value="{{ isset($submittal['printedItemsPickupDate']) ? \Carbon\Carbon::parse($submittal['printedItemsPickupDate'])->format('Y-m-d') : '' }}"
                                        class="form-control nontm-input" id="printedItemsPickupDate">
                                    <div id="printedItemsPickupDate_error" class="text-danger">

                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="label-div-mb"><label for="add_email" >Add Feature Card Copy</label>
                            <textarea id="featureCardCopy" class="form-control" rows="4" cols="50">{{ $submittal->featureCardCopy }}</textarea>
                            </div>
                        </div>
                    </div>                       
                </form>                           
            </div>                                
        </section>`;
        const innrtHtml8 = `<div class="property"><label for="add_email" class="text-bold">Is there anything else the Marketing Team should
                                know?</label>
                        </div>
                        <div class="label-div-mb"><label for="add_email" >Please Add your Notes</label>
                            <textarea id="marketingNotes" class="form-control" rows="4" cols="50">{{ $submittal->marketingNotes }}</textarea>
                        </div>
                        <div class="text-end" id="saveSubmit">
                            <button type="button" onclick="validateSubmittal(true)"
                            class="btn btn-white fw-bold">Save & Submit</button>
                        </div>
                        `;

        const HoaInfo = ` <h3></h3>
                    <section class="chr_section">
                        <div>
                            <form>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="referralToPay" id="titleToOrderHOA">Title to Order HOA docs?</label>
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
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="builderCommisionPercent">HOA Name</label>
                                            <input type="text" name="additional_charge" id="hoaName"
                                                value="{{ $submittal['hoaName'] }}" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="buyerOtherNotes">HOA Phone</label>
                                            <input type="text" name="additional_charge"
                                                value="{{ $submittal['hoaPhone'] }}" id="hoaPhone"
                                                class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="amountToCHR">HOA Website </label>
                                            <input type="text" value="{{ $submittal['hoaWebsite'] }}"
                                                class="form-control" placeholder="" id="hoaWebsite">
                                            </input>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </section>`;
        const resubmit = `<h3></h3>
                        <section>
                            <div>
                                <form>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="resubmitting_to_which_team">Resubmitting to Which Team?</label>
                                                <select name="resubmitting_to_which_team" id="resubmitting_to_which_team" class="form-select validate">
                                                    <option value="" selected>None</option>
                                                    <option value="TM"
                                                        {{ $submittal['resubmitting_to_which_team'] == 'TM' ? 'selected' : '' }}>TM
                                                    </option>
                                                    <option value="Marketing"
                                                        {{ $submittal['resubmitting_to_which_team'] == 'Marketing' ? 'selected' : '' }}>Marketing
                                                    </option>
                                                    <option value="BOTH"
                                                        {{ $submittal['resubmitting_to_which_team'] == 'BOTH' ? 'selected' : '' }}>BOTH
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="resubmitting_why_list_all_changes">Resubmitting? Why? --LIST ALL CHANGES--</label>
                                                <textarea class="form-control validate" id="resubmitting_why_list_all_changes" aria-label="With textarea">{{ $submittal['resubmitting_why_list_all_changes'] }}</textarea>
                                            </div>
                                        </div>

                                        
                                    </div>
                                </form>
                            </div>
                        </section>`;
        //properties end
        // Event listeners and other logic
        let initialCHrusingValue = document.querySelector('input[name="usingCHR"]:checked')?.value;
        setTimeout(() => {
            let initialshowPromotion = document.querySelector('input[name="showPromotion"]:checked')
                ?.value;
            console.log(initialshowPromotion, 'initialshowPromotion');

            showAndDisableValues(initialshowPromotion, 'showProp', true);
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
                showAndDisableValues(value, 'showProp', false);
            }
        });



        function showAndDisableValues(value, show, domloaded = true) {
            if (value == 1 && show === "showProp") {
                console.log("show prop yes")
                addStepChr('Outside Services', innrtHtml);
                addStepChr('Marketing Items', innrtHtml2);
                addStepChr('Marketing Items', innrtHtml3);
                addStepChr('Print Requests', innrtHtml4);
                addStepChr('Print Request', innrtHtml5);
                addStepChr('Notes', innrtHtml8);
                if (resubmitData) {
                    removeStep(9);
                    addStepChr('Resubmittal Information', resubmit);
                }
            } else if (value == 0 && show === "showProp" && !domloaded) {
                console.log("show prop no")
                removeStep(8);
                removeStep(9);
                removeStep(10);
                removeStep(11);
                removeStep(12);
                for (let i = 8; i <= 11; i++) {
                    removeStep(i);
                }
                for (let i = 8; i <= 9; i++) {
                    removeStep(i);
                }
                if (resubmitData) {
                    addStepChr('Resubmittal Information', resubmit, 9);
                }
            }
            if (value === "Yes" && value !== undefined) {
                if ($('.property').length === 6) {
                    if (resubmitData) {
                        for (let i = 3; i <= 12; i++) {
                            removeStep(i);
                        }
                    } else {
                        for (let i = 3; i <= 13; i++) {
                            removeStep(i);
                        }
                    }
                    for (let i = 3; i <= 6; i++) {
                        removeStep(i);
                    }
                    for (let i = 3; i <= 3; i++) {
                        removeStep(i);
                    }
                    addStepChr('Transaction Details and Preferences', CommissionDetails, 3);
                    addStepChr('HOA Information', HoaInfo, 4);
                    addStepChr('Service Provider', defaultCHRSec2, 5);
                    addStepChr('Service Provider', defaultCHRSec3, 6);
                    addStepChr('Select MLS', defaultCHRSec4, 7);
                    addStepChr('Commission Detail', defaultCHRSec1, 8);
                    if (resubmitData) {
                        removeStep(9);
                        addStepChr('Resubmittal Information', resubmit, 9);
                    }
                    ValidateHoa();

                    return;

                }
                addStepChr('Transaction Details and Preferences', CommissionDetails, 3);
                if (resubmitData) {
                    addStepChr('Commission Detail', defaultCHRSec1, 8);
                    removeStep(9);
                    addStepChr('Resubmittal Information', resubmit, 9);
                }

                // addStepChr('Service Providers', serviceProvider,4);
                ValidateHoa();

            } else if (value === "No") {
                const noRadio = document.querySelector('input[name="showPromotion"][value="0"]');
                if (noRadio) {
                    // Check if the radio button is not checked and click it
                    if (!noRadio.checked) {
                        noRadio.click();
                        noRadio.checked = true;
                    }
                }
                for (let i = 7; i >= 3; i--) {
                    removeStep(i);
                }
                for (let i = 5; i >= 3; i--) {
                    removeStep(i);
                }
                addStepChr('Outside Services', innrtHtml);
                addStepChr('Marketing Items', innrtHtml2);
                addStepChr('Marketing Items', innrtHtml3);
                addStepChr('Print Requests', innrtHtml4);
                addStepChr('Print Requests', innrtHtml5);
                addStepChr('Notes', innrtHtml8);
                if (resubmitData) {
                    addStepChr('Resubmittal Information', resubmit);
                }
            } else {
                if (show !== "showProp" && show !== "showPropinitial") {
                    removeStep(8)
                }
            }
        }

        function validateStep(stepIndex) {
            let isValid = true;
            const $currentSection = $stepsContainer.find(`section:eq(${stepIndex})`);
            // Check required fields in this section
            $currentSection.find('.required-field').each(function() {
                if ($(this).val() === "-None-") {
                    isValid = false;
                    $(this).addClass('error');
                } else {
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

        function addStepChr(title, content, index = null) {
            // Check if index is provided and is a number
            if (index !== null && !Number.isInteger(index)) {
                console.error('Index must be an integer or null.');
                return;
            }

            if (index === null) {
                // Add at the end if no index is provided
                $stepsContainer.steps('add', {
                    headerTag: "h3",
                    bodyTag: "section",
                    title: title,
                    content: content
                });
            } else {
                // Insert at the specified index
                $stepsContainer.steps('insert', index, {
                    headerTag: "h3",
                    bodyTag: "section",
                    title: title,
                    index: index,
                    content: content
                });
            }
        }


        function removeStep(index) {
            console.log(index, 'index is hereee')
            if (!$stepsContainer || !$stepsContainer.length) {
                console.error('$stepsContainer is not initialized.');
                return;
            }

            $stepsContainer.steps('remove', index);


        }

        var dropdown = document.getElementById('signInstallVendor');
        var additionalField = document.getElementById('signInstallVendorOther');

        dropdown.addEventListener('change', function() {
            console.log("hdsfkshdkf")
            if (dropdown.value === 'Others') {
                additionalField.classList.add('required-field', 'validate');
            } else {
                additionalField.classList.remove('required-field', 'validate');
            }
        });

    })
</script>
