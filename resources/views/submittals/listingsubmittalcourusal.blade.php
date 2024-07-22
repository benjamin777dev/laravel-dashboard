<div class="row justify-content-center" id="listingSubmittal">
    <div class="col-xl-8 align-items-center">
        <div class="card">
            <div class="card-body p-0">
                <h4 class="card-title p-3" id="title-corousal">CHR TM -Basic Information</h4>
                <div id="carouselExampleIndicators" class="carousel slide" data-interval="false">
                    <div class="carousel-indicators justify-content-center">
                        <button type="button"
                            onclick="document.getElementById('title-corousal').innerHTML='CHR TM - Basic Information'"
                            data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"
                            aria-current="true" aria-label="Slide 1"></button>
                        <button type="button"
                            onclick="document.getElementById('title-corousal').innerHTML='CHR TM - Basic Information'"
                            data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"
                            aria-label="Slide 2"></button>
                        <button type="button"
                            onclick="document.getElementById('title-corousal').innerHTML='CHR TM - Basic Information'"
                            data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"
                            aria-label="Slide 3"></button>
                        <button type="button"
                            onclick="document.getElementById('title-corousal').innerHTML='CHR TM - Commission Details'"
                            data-bs-target="#carouselExampleIndicators" data-bs-slide-to="3"
                            aria-label="Slide 4"></button>

                        <button type="button"
                            onclick="document.getElementById('title-corousal').innerHTML='CHR TM - Service Providers'"
                            data-bs-target="#carouselExampleIndicators" data-bs-slide-to="4"
                            aria-label="Slide 5"></button>
                        <button type="button"
                            onclick="document.getElementById('title-corousal').innerHTML='CHR TM - Service Providers'"
                            data-bs-target="#carouselExampleIndicators" data-bs-slide-to="5"
                            aria-label="Slide 6"></button>
                        <button type="button"
                            onclick="document.getElementById('title-corousal').innerHTML='CHR TM - Select MLS'"
                            data-bs-target="#carouselExampleIndicators" data-bs-slide-to="6"
                            aria-label="Slide 7"></button>

                    </div>
                    <div class="carousel-inner" role="listbox">
                        <div class="carousel-item active">
                            <div class="related_trxn label-div-mb">
                                <label for="transactionName" class="common-label">Transaction Name <svg
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
                                <div class="nontm-select-div">
                                    <select name="related_transaction" id="transactionName" class="nontm-select validate_err">
                                        @foreach ($deals as $currDeal)
                                            <option value="{{ $currDeal }}"
                                                {{ $currDeal['deal_name'] == $submittal['dealData']['deal_name'] ? 'selected' : '' }}>
                                                {{ $currDeal['deal_name'] }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <img src="{{ URL::asset('/images/domain_add.svg') }}" alt="">
                                </div>
                                <div id="related_transaction_error" class="text-danger">
                                </div>
                            </div>
                            <div class="additional_email label-div-mb">
                                <label for="add_email" class="common-label">Additional Email for Confirmation </label>
                                <input type="email" class="form-control" value="{{ $submittal['additionalEmail'] }}" id="additionalEmail"
                                    class="form-control" placeholder="Enter email" id="add_email">
                                <div class="add_email_error text-danger" id="add_email_error">
                                </div>
                            </div>
                            <div class="additional_email label-div-mb">
                                <label for="agentName" class="common-label"> Agent Name on Material <svg
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
                                <input type="text" class="validate_err form-control" value="{{ $submittal['agentName'] }}" 
                                    placeholder="Enter agent Name" id="agentName" />
                            </div>
                            <label for="commingSoon" class="common-label">Comming Soon? 
                                <svg
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
                                </svg>
                            </label>
                            <div class="d-flex gap-2">
                                <div class="mb-3">
                                    <input type="radio"  id="commingSoon_yes" {{ $submittal['commingSoon'] == 'Yes' ? 'selected' : '' }}
                                        name="commingSoon">
                                    <label class="form-check-label"  for="commingSoon_yes">
                                        Yes
                                    </label>
                                </div>
                                <div class="mb-3">
                                    <input type="radio" id="commingSoon_no" {{ $submittal['commingSoon'] == 'No' ? 'selected' : '' }}
                                        name="commingSoon">
                                    <label class="form-check-label" for="commingSoon_no">
                                        No
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="close-date-nontm label-div-mb">
                                <label for="comingSoonDate" class="common-label">Coming Soon MLS date</label>
                                <input type="date" value="{{ $submittal['comingSoonDate'] }}"
                                    class="form-control nontm-input" id="comingSoonDate">
                                <div id="close_date_error" class="text-danger">

                                </div>
                            </div>
                            <div class="label-div-mb">
                                <label for="dealData" class="common-label">Tm Name <svg
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
                                <input type="text" value="{{ $submittal['dealData']['tmName']['name'] }}"
                                    class="form-control validate_err" placeholder="" id="tmName">
                                <div class="tmName_err text-danger" id="tmName_err">
                                </div>
                            </div>
                            <div class="close-date-nontm label-div-mb">
                                <label for="activeDate" class="common-label">Active Date <svg
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
                                <input type="date" value="{{ $submittal['activeDate'] }}"
                                    class="form-control nontm-input validate_err" id="activeDate">
                                <div id="activeDate_err" class="text-danger">

                                </div>
                            </div>
                            <div class="row">
                                <div class="gap-2 col-lg-6 label-div-mb">
                                    <label for="agreementExecuted" class="common-label">Listing Agreement Exucuted?
                                        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18"
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
                                        </svg>
                                    </label>
                                    <div class="d-flex gap-2">
                                        <div class="mb-3">
                                            <input type="radio" id="agreementExecuted_yes"
                                                {{ $submittal['agreementExecuted'] == 'Yes' ? 'selected' : '' }}
                                                name="agreementExecuted">
                                            <label class="form-check-label" for="agreementExecuted_yes">
                                                Yes
                                            </label>
                                        </div>
                                        <div class="mb-3">
                                            <input type="radio" id="agreementExecuted_no"
                                                {{ $submittal['agreementExecuted'] == 'No' ? 'selected' : '' }}
                                                name="agreementExecuted">
                                            <label class="form-check-label" for="agreementExecuted_no">
                                                No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="close-date-nontm col-lg-6 label-div-mb">
                                    <label for="price" class="common-label">Price</label>
                                    <input type="text" value="{{ $submittal['price'] }}" placeholder="$"
                                        class="form-control nontm-input validate_err" id="price">
                                    <div id="final_purchase_error" class="text-danger">

                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="carousel-item ">
                            <div class="row">
                                <div class='col-lg-6 label-div-mb'>
                                    <div class="close-date-nontm label-div-mb">
                                        <label for="photoDate" class="common-label">Photo Date</label>
                                        <input type="date" value="{{ $submittal['photoDate'] }}"
                                            class="form-control nontm-input" id="photoDate">
                                    </div>
                                    <div class="additional_email label-div-mb">
                                        <label for="photoURL" class="common-label">Photo Url</label>
                                        <input type="text" value="{{ $submittal['photoURL'] }}" class="form-control"
                                            placeholder="" id="photoURL">
                                        
                                    </div>
                                </div>
                                <div class='col-lg-6 label-div-mb'>
                                    <div class="additional_email label-div-mb">
                                        <label for="bedsBathsTotal" class="common-label">Beds,baths,total sq.ft.</label>
                                        <input type="text" value="{{ $submittal['bedsBathsTotal'] }}"
                                            class="form-control validate_err" placeholder="" id="bedsBathsTotal">
                                    
                                    </div>
                                    <div class="additional_email label-div-mb">
                                        <label for="tourURL" class="common-label">3D Tour URL</label>
                                        <input type="text" value="{{ $submittal['tourURL'] }}" class="form-control validate_err"
                                            placeholder="" id="tourURL">
                                        
                                    </div>
                                </div>
                            </div>
                            <label for="usingCHR" class="common-label">Using CHR TM 
                                <svg
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
                                </svg>
                            </label>
                            <div class="d-flex gap-2">
                                <div class="mb-3">
                                    <input onclick="addFormSlide(true)" id="usingCHR_yes"
                                        {{ $submittal['usingCHR'] == 'Yes' ? 'selected' : '' }} type="radio"
                                        name="usingCHR">
                                    <label class="form-check-label" id="chkNo" for="formCheck1">
                                        Yes
                                    </label>
                                </div>
                                <div class="mb-3">
                                    <input onclick="addFormSlide(false)" id="usingCHR_no"
                                        {{ $submittal['usingCHR'] == 'No' ? 'selected' : '' }} type="radio"
                                        name="usingCHR">
                                    <label class="form-check-label" for="formCheck1">
                                        No
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item item-default">
                            <div class="row">
                                <div class='col-lg-6 label-div-mb'>
                                    <label for="feesCharged" class="common-label">Fees Charged to Seller at
                                        Closing</label>
                                    <input type="text" name="feesCharged" value="{{ $submittal['feesCharged'] }}"
                                        id="feesCharged" class="form-control " placeholder="$">
                                    </input>
                                </div>
                                <div class="col-lg-6 commission-nontm label-div-mb">
                                    <div class='pb-4'>
                                        <label for="additonal_fee" class="common-label">Referral to Pay</label>
                                        <select name="additional_charge" id="additonal_fee"
                                            class="form-select validate_err" id="">
                                            <option value="" selected>None</option>
                                            <option value="Yes"
                                                {{ $submittal['referralToPay'] == 'Yes' ? 'selected' : '' }}>Yes
                                            </option>
                                            <option value="No"
                                                {{ $submittal['referralToPay'] == 'No' ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                <div class='pb-4 col-lg-6 label-div-mb'>
                                    <label for="amountToCHR" class="common-label">Amount to CHR Gives</label>
                                    <input name="amountToCHR" placeholder = "$"
                                        value="{{ $submittal['amountToCHR'] }}" id="amountToCHR"
                                        class="form-control validate_err">
                                    </input>
                                </div>
                                <div class="col-lg-6 commission-nontm label-div-mb">
                                    <div class='pb-4'>
                                        <label for="referralDetails" class="common-label">Referral Details</label>
                                        <input name="referralDetails" value="{{ $submittal['referralDetails'] }}"
                                            id="referralDetails"
                                            class="form-control  validate_err">
                                        </input>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="carousel-item item-default1">
                            <div class="row">
                                <div class="col-lg-6 label-div-mb">
                                    <label for="scheduleSignInstall">Schedule Sign Install
                                        <svg
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
                                        </svg>
                                    </label>
                                    <div class="row">
                                        <div class="d-flex gap-2">
                                            <div class="mb-3">
                                                <input type="radio" id="scheduleSignInstall_yes"
                                                    {{ $submittal['scheduleSignInstall'] == 'Yes' ? 'checked' : '' }}
                                                    name="radio">
                                                <label class="form-check-label" id="chkNo" for="formCheck1">
                                                    Yes
                                                </label>
                                            </div>
                                            <div class="mb-3">
                                                <input type="radio" id="scheduleSignInstall_no"
                                                    {{ $submittal['scheduleSignInstall'] == 'No' ? 'checked' : '' }}
                                                    name="radio">
                                                <label class="form-check-label" for="formCheck1">
                                                    No
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                 <div class="col-lg-6 label-div-mb">
                                    <label for="">Draft Showing Instructions?
                                        <svg
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
                                        </svg>
                                    </label>
                                    <div class="row">
                                        <div class="d-flex gap-2">
                                            <div class="mb-3">
                                                <input type="radio" id="draftShowingInstructions_yes"
                                                    {{ $submittal['draftShowingInstructions'] == 'Yes' ? 'checked' : '' }}
                                                    name="radio">
                                                <label class="form-check-label" id="chkNo" for="formCheck1">
                                                    Yes
                                                </label>
                                            </div>
                                            <div class="mb-3">
                                                <input type="radio" id="draftShowingInstructions_no"
                                                    {{ $submittal['draftShowingInstructions'] == 'No' ? 'checked' : '' }}
                                                    name="radio">
                                                <label class="form-check-label" for="formCheck1">
                                                    No
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 label-div-mb">
                                    <label for="conciergeListing">Concierge Listing (Optional)</label>
                                    <div class="row">
                                        <div class="d-flex gap-2">
                                            <div class="mb-3"> 
                                                <input type="radio" id="conciergeListing_yes"
                                                    {{ $submittal['conciergeListing'] == 'Yes' ? 'checked' : '' }}
                                                    name="radio">
                                                <label class="form-check-label" id="chkNo" for="formCheck1">
                                                    Yes
                                                </label>
                                            </div>
                                            <div class="mb-3">
                                                <input type="radio" id="conciergeListing_no"
                                                    {{ $submittal['conciergeListing'] == 'No' ? 'checked' : '' }}
                                                    name="radio">
                                                <label class="form-check-label" for="formCheck1">
                                                    No
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-lg-6 label-div-mb">
                                    <label for="titleCompany">Title Company</label>
                                    <input value="{{ $submittal['titleCompany'] }}" name="titleCompany"
                                        id="titleCompany" class="form-control validate_err">
                                </div>
                            </div>
                            <div class="close-date-nontm label-div-mb">
                                <label for="signInstallDate">Sign Install Date</label>
                                <input type="date" value="{{ $submittal['signInstallDate'] }}"
                                    class="form-control nontm-input" id="signInstallDate">
                                <div id="signInstallDate_err" class="text-danger">
                                </div>
                            </div>
                            <div class="label-div-mb">
                                <label for="closerNamePhone">Closer Name & Phone
                                    <svg
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
                                    </svg>
                                </label>
                                <input name="closerNamePhone" value="{{ $submittal['closerNamePhone'] }}"
                                    id="closerNamePhone"
                                    class="form-control validate_err">
                            </div>
                        </div>
                        <div class="carousel-item item-default2">
                            <div class="label-div-mb">
                                <label for="signInstallVendor">Sign Install Vendor Info</label>
                                <input value="{{ $submittal['signInstallVendor'] }}"
                                    name="signInstallVendor" id="signInstallVendor"
                                    class="form-control validate_err">
                            </div> 
                            <div class="label-div-mb">
                                <label for="signInstallVendorOther">Sign Install Vendor (if Other)</label>
                                <input value="{{ $submittal['signInstallVendorOther'] }}"
                                    name="signInstallVendorOther" id="signInstallVendorOther"
                                    class="form-control validate_err">
                            </div>   
                        </div>
                        <div class="carousel-item item-default3">
                            <div class="row mb-4">
                                <div class="col-lg-4 d-flex gap-2">
                                    <div>REColorado</div>
                                    <div> <input type="checkbox" id="reColorado" <?php if ($submittal['reColorado']) {
                                        echo 'checked';
                                    } ?>></div>
                                </div>
                                <div class="col-lg-4 d-flex gap-2">
                                    <div>Navica</div>
                                    <div> <input type="checkbox" id="navica" <?php if ($submittal['navica']) {
                                        echo 'checked';
                                    } ?>></div>
                                </div>
                                <div class="col-lg-4 d-flex gap-2">
                                    <div>PPAR</div>
                                    <div><input type="checkbox" id="ppar" <?php if ($submittal['ppar']) {
                                        echo 'checked';
                                    } ?>></div>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 d-flex gap-2">
                                    <div>Grand County</div>
                                    <div><input type="checkbox" id="grandCounty" <?php if ($submittal['grandCounty']) {
                                        echo 'checked';
                                    } ?>></div>
                                </div>
                                <div class="col-lg-4 d-flex gap-2">
                                    <div>IRES</div>
                                    <div><input type="checkbox" id="ires" <?php if ($submittal['ires']) {
                                        echo 'checked';
                                    } ?>></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 label-div-mb">
                                    <label for="mlsPrivateRemarks" class="common-label">MLS public remarks</label>
                                    <textarea class="form-control" id="mlsPrivateRemarks" aria-label="With textarea">{{ $submittal['mlsPrivateRemarks'] }}</textarea>
                                </div>
                                <div class="col-lg-6 label-div-mb">

                                    <label for="commission" class="common-label">MLS private remarks</label>
                                    <textarea class="form-control" id="mlsPublicRemarks" aria-label="With textarea">{{ $submittal['mlsPublicRemarks'] }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->
<script>
    function addFormSlide(state, test = null) {
        
        var itemDefault = document.querySelector('.item-default');
        var itemDefault1 = document.querySelector('.item-default1');
        var itemDefault2 = document.querySelector('.item-default2');
        var itemDefault3 = document.querySelector('.item-default3');
        var existingItem1 = document.querySelector('.carousel-item.item1');
        var existingItem2 = document.querySelector('.carousel-item.item2');
        var existingItem3 = document.querySelector('.carousel-item.item3');
        var existingItem4 = document.querySelector('.carousel-item.item4');
        var existingItem5 = document.querySelector('.carousel-item.item5');
        var existingItem6 = document.querySelector('.carousel-item.item6');
        var existingItem7 = document.querySelector('.carousel-item.item7');
        var existingItem8 = document.querySelector('.carousel-item.item8');
        var existingItem9 = document.querySelector('.carousel-item.item9');
        var existingItem10 = document.querySelector('.carousel-item.item10');
        var existingButton1 = document.querySelector('[data-bs-slide-to="3"]');
        var existingButton2 = document.querySelector('[data-bs-slide-to="4"]');
        var existingButton3 = document.querySelector('[data-bs-slide-to="5"]');
        var existingButton4 = document.querySelector('[data-bs-slide-to="6"]');
        var existingButton5 = document.querySelector('[data-bs-slide-to="7"]');
        var existingButton6 = document.querySelector('[data-bs-slide-to="8"]');
        var existingButton7 = document.querySelector('[data-bs-slide-to="9"]');
        var existingButton8 = document.querySelector('[data-bs-slide-to="10"]');
        if (existingButton3 && existingItem4 && existingItem5 && existingItem6 && existingItem7 && existingItem8 &&
            existingItem9 && existingItem10 && state) {
            console.log('yes hereee')
            existingItem3.remove();
            existingItem4.remove();
            existingItem5.remove();
            existingItem6.remove();
            existingItem7.remove();
            existingItem8.remove();
            existingItem9.remove();
            existingItem10.remove();
            existingButton1?.remove();
            existingButton2?.remove();
            existingButton3?.remove();
            existingButton4?.remove();
            existingButton5?.remove();
            existingButton6?.remove();
            existingButton7?.remove();
            existingButton8?.remove();
            showChrdefault();
        }
        if (!state) {
             console.log('triggerererer',existingItem1,existingItem2,itemDefault,itemDefault1,itemDefault2,itemDefault3)
            if(!existingItem1 && !existingItem2){
                itemDefault.remove();
                itemDefault1.remove();
                itemDefault2.remove();
                itemDefault3.remove();
                existingButton2.remove();
                existingButton1.remove();
                existingButton3.remove();
                existingButton4.remove();
                showPropertyCarousalItem();
            }
           
            // State is false, remove carousel items if they exist
            if (existingItem1 && existingItem2 && itemDefault && itemDefault1 && itemDefault2 && itemDefault3) {
                itemDefault.remove();
                existingItem1.remove();
                existingItem2.remove();
                existingButton1.remove();
                existingButton2.remove();
                existingButton3.remove();
                existingButton4.remove();
                existingButton5.remove();
                existingButton6.remove();
                itemDefault1.remove();
                itemDefault2.remove();
                itemDefault3.remove();
                showPropertyCarousalItem();
            }
            return;
        }

        if (existingItem1 && existingItem2) {
            // Both items already exist, do not create new items
            console.log("Carousel items already exist.");
            return;
        }

        // Create a new carousel item
        var carouselItem = document.createElement('div');
        var carouselItem2 = document.createElement('div');
        carouselItem.className = 'carousel-item item1';
        carouselItem2.className = 'carousel-item item2';

        var innrtHtml = `<div class="row">
                        <div class="gap-2 col-lg-6">
                            <label for="needO&E" class="common-label">Need O&E <svg
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
                               <div class="row">
                                        <div class="d-flex gap-2">
                                            <div class="mb-3">
                                                <input type="radio" id="needOE_yes" {{ $submittal['needOE'] == 'Yes' ? 'checked' : '' }} name="needO&E">
                                                <label class="form-check-label" id="chkNo" for="formCheck1">
                                                    Yes
                                                </label>
                                            </div>
                                            <div class="mb-3">
                                                <input {{ $submittal['needOE'] == 'No' ? 'checked' : '' }} type="radio" id="needOE_no" name="needO&E">
                                                <label class="form-check-label" for="formCheck1">
                                                    No
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                        </div>
                        <div class="gap-2 col-lg-6 label-div-mb">
                            <label for="hasHOA" class="common-label">Has HOA? <svg
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
                             <div class="row">
                                        <div class="d-flex gap-2">
                                            <div class="mb-3">
                                                <input type="radio" id="hasHOA_yes" {{ $submittal['hasHOA'] == 'Yes' ? 'checked' : '' }} name="hasHOA">
                                                <label class="form-check-label" id="chkNo" for="formCheck1">
                                                    Yes
                                                </label>
                                            </div>
                                            <div class="mb-3">
                                                <input type="radio" id="hasHOA_no" {{ $submittal['hasHOA'] == 'No' ? 'checked' : '' }} name="hasHOA">
                                                <label class="form-check-label" for="formCheck1">
                                                    No
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="gap-2 col-lg-6">
                            <label for="includeInsights" class="common-label">Include Insights in Intro? <svg
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
                              <div class="row">
                                        <div class="d-flex gap-2">
                                            <div class="mb-3">
                                                <input type="radio" id="includeInsights_yes" {{ $submittal['includeInsights'] == 'Yes' ? 'checked' : '' }} name="includeInsights">
                                                <label class="form-check-label" id="chkNo" for="formCheck1">
                                                    Yes
                                                </label>
                                            </div>
                                            <div class="mb-3">
                                                <input id="includeInsights_no" {{ $submittal['includeInsights'] == 'No' ? 'checked' : '' }} type="radio" name="includeInsights">
                                                <label class="form-check-label" for="formCheck1">
                                                    No
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                        </div>
                        <div class="gap-2 col-lg-6 label-div-mb">
                            <label for="titleToOrderHOA" class="common-label">Title to Order HOA docs? <svg
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
                               <div class="row">
                                        <div class="d-flex gap-2">
                                            <div class="mb-3">
                                                <input type="radio" id="titleToOrderHOA_yes" {{ $submittal['titleToOrderHOA'] == 'Yes' ? 'checked' : '' }} name="titleToOrderHOA">
                                                <label class="form-check-label" id="chkNo" for="formCheck1">
                                                    Yes
                                                </label>
                                            </div>
                                            <div class="mb-3">
                                                <input type="radio" id="titleToOrderHOA_no" {{ $submittal['titleToOrderHOA'] == 'No' ? 'checked' : '' }} name="titleToOrderHOA">
                                                <label class="form-check-label" for="formCheck1">
                                                    No
                                                </label>
                                            </div>
                                             <div class="mb-3">
                                                <input type="radio" id="titleToOrderHOA_tbd" {{ $submittal['titleToOrderHOA'] == 'TBD' ? 'checked' : '' }} name="titleToOrderHOA">
                                                <label class="form-check-label" for="formCheck1">
                                                    TBD
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="gap-2 col-lg-6">
                            <label for="mailoutNeeded" class="common-label">Mailout Needed? <svg
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
                             <div class="row">
                                        <div class="d-flex gap-2">
                                            <div class="mb-3">
                                                <input type="radio" id="mailoutNeeded_yes" {{ $submittal['mailoutNeeded'] == 'Yes' ? 'checked' : '' }} name="mailoutNeeded">
                                                <label class="form-check-label" id="chkNo" for="formCheck1">
                                                    Yes
                                                </label>
                                            </div>
                                            <div class="mb-3">
                                                <input type="radio" id="mailoutNeeded_no" {{ $submittal['mailoutNeeded'] == 'No' ? 'checked' : '' }} name="mailoutNeeded">
                                                <label class="form-check-label" for="formCheck1">
                                                    No
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                        </div>
                        <div class="gap-2 col-lg-6 label-div-mb">
                            <label for="powerOfAttnyNeeded" class="common-label">Power of Attny Needed? <svg
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
                              <div class="row">
                                        <div class="d-flex gap-2">
                                            <div class="mb-3">
                                                <input type="radio" id="powerOfAttnyNeeded_yes" {{ $submittal['powerOfAttnyNeeded'] == 'Yes' ? 'checked' : '' }} name="powerOfAttnyNeeded">
                                                <label class="form-check-label" id="chkNo" for="formCheck1">
                                                    Yes
                                                </label>
                                            </div>
                                            <div class="mb-3">
                                                <input type="radio" id="powerOfAttnyNeeded_no" {{ $submittal['powerOfAttnyNeeded'] == 'No' ? 'checked' : '' }} name="powerOfAttnyNeeded">
                                                <label class="form-check-label" for="formCheck1">
                                                    No
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                        </div>
                    </div>`;

        var innrtHtml2 = `
                            <div class="row">
                                <div class='col-lg-6'>
                                    <label for="hoaName" class="common-label">HOA Name</label>
                                    <input type="text" name="additional_charge" id="hoaName" value="{{ $submittal['hoaName'] }}"
                                        class="form-control second-step-common-select select-mb24">
                                    </input>
                                </div>
                                <div class="col-lg-6 commission-nontm">
                                    <div class='pb-4'>
                                        <label for="hoaPhone" class="common-label">HOA Phone</label>
                                        <input type="text" name="additional_charge" value="{{ $submittal['hoaPhone'] }}" id="hoaPhone"
                                        class="form-control second-step-common-select select-mb24">
                                    </input>
                                    </div>

                                </div>
                            </div>
                            <div class="additional_email label-div-mb">
                                <label for="hoaWebsite" class="common-label">HOA Website</label>
                                <input type="text"
                                   value="{{ $submittal['hoaWebsite'] }}"
                                    class="form-control" placeholder="" id="hoaWebsite">
                            </div>
                            <div class="additional_email label-div-mb">
                                <label for="add_email" class="common-label">Misc Notes - Seller, Communication, etc</label>
                            <textarea class="form-control" id="miscNotes"  rows="4" cols="50">{{ $submittal['miscNotes'] }}</textarea>
                                <div class="add_email_error text-danger" id="add_email_error">
                                </div>
                            </div>
                               <div class="gap-2 col-lg-6 label-div-mb">
                            <label for="hoaName" class="common-label">Are you ready to continue to Property Promotion?</label>
                               <div class="row">
                                        <div class="d-flex gap-2">
                                            <div class="mb-3">
                                                <input type="radio" onclick="propertyParmotion(false)" name="hoaName">
                                                <label class="form-check-label"  for="formCheck1">
                                                    Yes
                                                </label>
                                            </div>
                                            <div class="mb-3">
                                                <input type="radio" onclick="propertyParmotion(true)" name="hoaName" checked>
                                                <label class="form-check-label" for="formCheck1">
                                                    No
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                        </div>
                        <div class="text-end" id="saveSubmit">
                    <button type="button" onclick="validateSubmittal()"
                        class="btn btn-white fw-bold">Save & Submit</button>
                    </div>
            </div>  
         `;

        // Set inner HTML content of carouselItem
        carouselItem.innerHTML = innrtHtml;
        carouselItem2.innerHTML = innrtHtml2;

        // Append the new carousel items to the carousel inner
        var carouselInner = document.querySelector('.carousel-inner');
        carouselInner.appendChild(carouselItem);
        carouselInner.appendChild(carouselItem2);

        // Update the carousel indicators (optional, if you want to show navigation bullets)
        var slideIndex = $('.carousel-item').length - 1;

        // Create the slide buttons for carousel indicators
        var button1 = document.createElement('button');
        button1.type = 'button';
        button1.setAttribute('data-bs-target', '#carouselExampleIndicators');
        button1.setAttribute('data-bs-slide-to', (slideIndex - 1).toString());
        button1.setAttribute('aria-label', 'Slide ' + slideIndex.toString());
        button1.addEventListener('click', function() {
        // Change the inner HTML of 'title-corousal' element
        const titleCorousal = document.getElementById('title-corousal');
        if (titleCorousal) {
                titleCorousal.innerHTML = 'CHR TM - Transaction Details and Preferences';
            }
        })

        var button2 = document.createElement('button');
        button2.type = 'button';
        button2.setAttribute('data-bs-target', '#carouselExampleIndicators');
        button2.setAttribute('data-bs-slide-to', slideIndex.toString());
        button2.setAttribute('aria-label', 'Slide ' + (slideIndex + 1).toString());
        button2.addEventListener('click', function() {
        // Change the inner HTML of 'title-corousal' element
        const titleCorousal = document.getElementById('title-corousal');
        if (titleCorousal) {
                titleCorousal.innerHTML = 'CHR TM - Transaction Details and Preferences';
            }
        })

        var carouselIndicators = document.querySelector('.carousel-indicators');
        carouselIndicators.appendChild(button1);
        carouselIndicators.appendChild(button2);
    }

    function showChrdefault(){
       
        var carouselItem = document.createElement('div');
        var carouselItem2 = document.createElement('div');
        var carouselItem3 = document.createElement('div');
        var carouselItem4 = document.createElement('div');
      
        carouselItem.className = 'carousel-item item-default';
        carouselItem2.className = 'carousel-item item-default1';
        carouselItem3.className = 'carousel-item item-default2';
        carouselItem4.className = 'carousel-item item-default3';

        var innrtHtml = `
                            <div class="row">
                                <div class='col-lg-6 label-div-mb'>
                                    <label for="feesCharged" class="common-label">Fees Charged to Seller at
                                        Closing</label>
                                    <input type="text" name="feesCharged" value="{{ $submittal['feesCharged'] }}"
                                        id="feesCharged" class="form-control " placeholder="$">
                                    </input>
                                </div>
                                <div class="col-lg-6 commission-nontm label-div-mb">
                                    <div class='pb-4'>
                                        <label for="additonal_fee" class="common-label">Referral to Pay</label>
                                        <select name="additional_charge" id="additonal_fee"
                                            class="form-select validate_err" id="">
                                            <option value="" selected>None</option>
                                            <option value="Yes"
                                                {{ $submittal['referralToPay'] == 'Yes' ? 'selected' : '' }}>Yes
                                            </option>
                                            <option value="No"
                                                {{ $submittal['referralToPay'] == 'No' ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                <div class='pb-4 col-lg-6 label-div-mb'>
                                    <label for="amountToCHR" class="common-label">Amount to CHR Gives</label>
                                    <input name="amountToCHR" placeholder = "$"
                                        value="{{ $submittal['amountToCHR'] }}" id="amountToCHR"
                                        class="form-control validate_err">
                                    </input>
                                </div>
                                <div class="col-lg-6 commission-nontm label-div-mb">
                                    <div class='pb-4'>
                                        <label for="referralDetails" class="common-label">Referral Details</label>
                                        <input name="referralDetails" value="{{ $submittal['referralDetails'] }}"
                                            id="referralDetails"
                                            class="form-control  validate_err">
                                        </input>
                                    </div>

                                </div>
                            </div>`;

        var innrtHtml2 = ` <div class="row">
                                <div class="col-lg-6 label-div-mb">
                                    <label for="scheduleSignInstall">Schedule Sign Install
                                        <svg
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
                                        </svg>
                                    </label>
                                    <div class="row">
                                        <div class="d-flex gap-2">
                                            <div class="mb-3">
                                                <input type="radio" id="scheduleSignInstall_yes"
                                                    {{ $submittal['scheduleSignInstall'] == 'Yes' ? 'checked' : '' }}
                                                    name="radio">
                                                <label class="form-check-label" id="chkNo" for="formCheck1">
                                                    Yes
                                                </label>
                                            </div>
                                            <div class="mb-3">
                                                <input type="radio" id="scheduleSignInstall_no"
                                                    {{ $submittal['scheduleSignInstall'] == 'No' ? 'checked' : '' }}
                                                    name="radio">
                                                <label class="form-check-label" for="formCheck1">
                                                    No
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                 <div class="col-lg-6 label-div-mb">
                                    <label for="">Draft Showing Instructions?
                                        <svg
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
                                        </svg>
                                    </label>
                                    <div class="row">
                                        <div class="d-flex gap-2">
                                            <div class="mb-3">
                                                <input type="radio" id="draftShowingInstructions_yes"
                                                    {{ $submittal['draftShowingInstructions'] == 'Yes' ? 'checked' : '' }}
                                                    name="radio">
                                                <label class="form-check-label" id="chkNo" for="formCheck1">
                                                    Yes
                                                </label>
                                            </div>
                                            <div class="mb-3">
                                                <input type="radio" id="draftShowingInstructions_no"
                                                    {{ $submittal['draftShowingInstructions'] == 'No' ? 'checked' : '' }}
                                                    name="radio">
                                                <label class="form-check-label" for="formCheck1">
                                                    No
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 label-div-mb">
                                    <label for="conciergeListing">Concierge Listing (Optional)</label>
                                    <div class="row">
                                        <div class="d-flex gap-2">
                                            <div class="mb-3"> 
                                                <input type="radio" id="conciergeListing_yes"
                                                    {{ $submittal['conciergeListing'] == 'Yes' ? 'checked' : '' }}
                                                    name="radio">
                                                <label class="form-check-label" id="chkNo" for="formCheck1">
                                                    Yes
                                                </label>
                                            </div>
                                            <div class="mb-3">
                                                <input type="radio" id="conciergeListing_no"
                                                    {{ $submittal['conciergeListing'] == 'No' ? 'checked' : '' }}
                                                    name="radio">
                                                <label class="form-check-label" for="formCheck1">
                                                    No
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-lg-6 label-div-mb">
                                    <label for="titleCompany">Title Company</label>
                                    <input value="{{ $submittal['titleCompany'] }}" name="titleCompany"
                                        id="titleCompany" class="form-control validate_err">
                                </div>
                            </div>
                            <div class="close-date-nontm label-div-mb">
                                <label for="signInstallDate">Sign Install Date</label>
                                <input type="date" value="{{ $submittal['signInstallDate'] }}"
                                    class="form-control nontm-input" id="signInstallDate">
                                <div id="signInstallDate_err" class="text-danger">
                                </div>
                            </div>
                            <div class="label-div-mb">
                                <label for="closerNamePhone">Closer Name & Phone
                                    <svg
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
                                    </svg>
                                </label>
                                <input name="closerNamePhone" value="{{ $submittal['closerNamePhone'] }}"
                                    id="closerNamePhone"
                                    class="form-control validate_err">
                            </div>`;
        var innrtHtml3 = `  <div class="label-div-mb">
                                <label for="signInstallVendor">Sign Install Vendor Info</label>
                                <input value="{{ $submittal['signInstallVendor'] }}"
                                    name="signInstallVendor" id="signInstallVendor"
                                    class="form-control validate_err">
                            </div> 
                            <div class="label-div-mb">
                                <label for="signInstallVendorOther">Sign Install Vendor (if Other)</label>
                                <input value="{{ $submittal['signInstallVendorOther'] }}"
                                    name="signInstallVendorOther" id="signInstallVendorOther"
                                    class="form-control validate_err">
                            </div> 
`;

        var innrtHtml4 = ` <div class="row mb-4">
                                <div class="col-lg-4 d-flex gap-2">
                                    <div>REColorado</div>
                                    <div> <input type="checkbox" id="reColorado" <?php if ($submittal['reColorado']) {
                                        echo 'checked';
                                    } ?>></div>
                                </div>
                                <div class="col-lg-4 d-flex gap-2">
                                    <div>Navica</div>
                                    <div> <input type="checkbox" id="navica" <?php if ($submittal['navica']) {
                                        echo 'checked';
                                    } ?>></div>
                                </div>
                                <div class="col-lg-4 d-flex gap-2">
                                    <div>PPAR</div>
                                    <div><input type="checkbox" id="ppar" <?php if ($submittal['ppar']) {
                                        echo 'checked';
                                    } ?>></div>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 d-flex gap-2">
                                    <div>Grand County</div>
                                    <div><input type="checkbox" id="grandCounty" <?php if ($submittal['grandCounty']) {
                                        echo 'checked';
                                    } ?>></div>
                                </div>
                                <div class="col-lg-4 d-flex gap-2">
                                    <div>IRES</div>
                                    <div><input type="checkbox" id="ires" <?php if ($submittal['ires']) {
                                        echo 'checked';
                                    } ?>></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 label-div-mb">
                                    <label for="mlsPrivateRemarks" class="common-label">MLS public remarks</label>
                                    <textarea class="form-control" id="mlsPrivateRemarks" aria-label="With textarea">{{ $submittal['mlsPrivateRemarks'] }}</textarea>
                                </div>
                                <div class="col-lg-6 label-div-mb">

                                    <label for="commission" class="common-label">MLS private remarks</label>
                                    <textarea class="form-control" id="mlsPublicRemarks" aria-label="With textarea">{{ $submittal['mlsPublicRemarks'] }}</textarea>
                                </div>
                            </div>`;

        // Set inner HTML content of carouselItem
        carouselItem.innerHTML = innrtHtml;
        carouselItem2.innerHTML = innrtHtml2;
        carouselItem3.innerHTML = innrtHtml3;
        carouselItem4.innerHTML = innrtHtml4;

        // Append the new carousel items to the carousel inner
        var carouselInner = document.querySelector('.carousel-inner');
        carouselInner.appendChild(carouselItem);
        carouselInner.appendChild(carouselItem2);
        carouselInner.appendChild(carouselItem3);
        carouselInner.appendChild(carouselItem4);

        // Update the carousel indicators (optional, if you want to show navigation bullets)
        var slideIndex = $('.carousel-item').length - 1;

        // Create the slide buttons for carousel indicators
        var button1 = document.createElement('button');
        button1.type = 'button';
        button1.setAttribute('data-bs-target', '#carouselExampleIndicators');
        button1.setAttribute('data-bs-slide-to', (slideIndex - 3).toString());
        button1.setAttribute('aria-label', 'Slide ' + (slideIndex - 3).toString());
        button1.addEventListener('click', function() {
        // Change the inner HTML of 'title-corousal' element
        const titleCorousal = document.getElementById('title-corousal');
        if (titleCorousal) {
                titleCorousal.innerHTML = 'CHR TM - Commission Details';
            }
        })

        var button2 = document.createElement('button');
        button2.type = 'button';
        button2.setAttribute('data-bs-target', '#carouselExampleIndicators');
        button2.setAttribute('data-bs-slide-to', (slideIndex - 2).toString());
        button2.setAttribute('aria-label', 'Slide ' + (slideIndex - 2).toString());
        button2.addEventListener('click', function() {
        // Change the inner HTML of 'title-corousal' element
        const titleCorousal = document.getElementById('title-corousal');
        if (titleCorousal) {
                titleCorousal.innerHTML = 'CHR TM - Service Providers';
            }
        })

        var button3 = document.createElement('button');
        button3.type = 'button';
        button3.setAttribute('data-bs-target', '#carouselExampleIndicators');
        button3.setAttribute('data-bs-slide-to', (slideIndex - 1).toString());
        button3.setAttribute('aria-label', 'Slide ' + (slideIndex - 1).toString());
        button3.addEventListener('click', function() {
        // Change the inner HTML of 'title-corousal' element
        const titleCorousal = document.getElementById('title-corousal');
        if (titleCorousal) {
                titleCorousal.innerHTML = 'CHR TM - Service Providers';
            }
        })

        var button4 = document.createElement('button');
        button4.type = 'button';
        button4.setAttribute('data-bs-target', '#carouselExampleIndicators');
        button4.setAttribute('data-bs-slide-to', slideIndex.toString());
        button4.setAttribute('aria-label', 'Slide ' + slideIndex.toString());
        button4.addEventListener('click', function() {
        // Change the inner HTML of 'title-corousal' element
        const titleCorousal = document.getElementById('title-corousal');
        if (titleCorousal) {
                titleCorousal.innerHTML = 'PROPERTY PROMOTION - Notes';
            }
        })

        var carouselIndicators = document.querySelector('.carousel-indicators');
        carouselIndicators.appendChild(button1);
        carouselIndicators.appendChild(button2);
        carouselIndicators.appendChild(button3);
        carouselIndicators.appendChild(button4);

    }

    function showPropertyCarousalItem() {
        let existingItem1 = document.querySelector('.carousel-item.item3');
        let existingItem2 = document.querySelector('.carousel-item.item4');
        let existingItem3 = document.querySelector('.carousel-item.item5');
        let existingItem4 = document.querySelector('.carousel-item.item6');
        let existingItem5 = document.querySelector('.carousel-item.item7');
        let existingItem6 = document.querySelector('.carousel-item.item8');
        let existingItem7 = document.querySelector('.carousel-item.item9');
        let existingItem8 = document.querySelector('.carousel-item.item10');
        if (existingItem1 && existingItem2 && existingItem3 && existingItem4 && existingItem5 && existingItem6 &&
            existingItem7 &&
            existingItem8) {
            // Both items already exist, do not create new items
            console.log("Carousel items already exist.");
            return;
        }
        var carouselItem = document.createElement('div');
        var carouselItem2 = document.createElement('div');
        var carouselItem3 = document.createElement('div');
        var carouselItem4 = document.createElement('div');
        var carouselItem5 = document.createElement('div');
        var carouselItem6 = document.createElement('div');
        var carouselItem7 = document.createElement('div');
        var carouselItem8 = document.createElement('div');
        carouselItem.className = 'carousel-item item3';
        carouselItem2.className = 'carousel-item item4';
        carouselItem3.className = 'carousel-item item5';
        carouselItem4.className = 'carousel-item item6';
        carouselItem5.className = 'carousel-item item7';
        carouselItem6.className = 'carousel-item item8';
        carouselItem7.className = 'carousel-item item9';
        carouselItem8.className = 'carousel-item item10';

        var innrtHtml = `<div class="row">
    <div class="gap-2 col-lg-6">
        <div class="d-flex gap-2">
            <label for="add_email" class="common-label">Matterport</label>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="formCheck1">
            </div>
        </div>
    </div>
    <div class="gap-2 col-lg-6 label-div-mb">
        <div class="d-flex gap-2">
            <label for="add_email" class="common-label">Floor Plans</label>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="formCheck3">

            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="gap-2 col-lg-6">
        <div class="d-flex gap-2">
            <label for="add_email" class="common-label">3D Zillow Tour</label>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="formCheck5">
            </div>
        </div>
    </div>
    <div class="gap-2 col-lg-6 label-div-mb">
        <div class="d-flex gap-2">
            <label for="add_email" class="common-label">Onsite Video</label>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="formCheck7">

            </div>
        </div>
    </div>
</div>
</div>`;

        var innrtHtml2 = `<div class="row">
    <div class="gap-2 col-lg-6">
        <div class="d-flex gap-2">
            <label for="add_email" class="common-label">Email Blast to Sphere</label>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="formCheck1">
            </div>
        </div>
    </div>
    <div class="gap-2 col-lg-6 label-div-mb">
        <div class="d-flex gap-2">
            <label for="add_email" class="common-label">Email Blast to Reverse Prospect List</label>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="formCheck3">

            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="gap-2 col-lg-6">
        <div class="d-flex gap-2">
            <label for="add_email" class="common-label">Social Media Ads</label>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="formCheck5">
            </div>
        </div>
    </div>
    <div class="gap-2 col-lg-6 label-div-mb">
        <div class="d-flex gap-2">
            <label for="add_email" class="common-label">Social Media Images</label>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="formCheck7">

            </div>
        </div>
    </div>
</div>
<div class="gap-2 col-lg-6 label-div-mb">
    <div class="d-flex gap-2">
        <label for="add_email" class="common-label">Price Improvement Package</label>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="formCheck7">

        </div>
    </div>
</div>
</div>`;
        var innrtHtml3 = `<div class="row">
    <div class="gap-2 col-lg-6">
        <div class="d-flex gap-2">
            <label for="add_email" class="common-label">Property Website</label>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="formCheck5">
            </div>
        </div>
    </div>
    <div class="gap-2 col-lg-6 label-div-mb">
        <div class="additional_email label-div-mb">
            <label for="add_email" class="common-label">Custom Domain Name</label>
            <input type="text" value="{{ isset($dealData['email']) ? $dealData['email'] : '' }}"
                class="form-control" placeholder="" id="add_email">
            <div class="add_email_error text-danger" id="add_email_error">
            </div>
        </div>
    </div>
    <div class="gap-2 col-lg-6 label-div-mb">
        <div class="d-flex gap-2">
            <label for="add_email" class="common-label">Property Highlight Video</label>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="formCheck5">
            </div>
        </div>
    </div>
</div>
<div class="gap-2 col-lg-6 label-div-mb">
    <div class="additional_email label-div-mb">
        <label for="add_email" class="common-label">8-12 Features Needed for Video</label>
        <input type="text" value="{{ isset($dealData['email']) ? $dealData['email'] : '' }}" class="form-control"
            placeholder="" id="add_email">
        <div class="add_email_error text-danger" id="add_email_error">
        </div>
    </div>
</div>
`;

        var innrtHtml4 = `<div class="label-div-mb"><label for="add_email" class="common-label">Brochure Design - Click for
        options</label>
    <div class="nontm-select-div">
        <select name="related_transaction" id="related_transaction" class="nontm-select form-select">
            {{-- @foreach ($deals as $deal)
                                            <option value="{{ $deal->zoho_deal_id }}"
                                                {{ $deal->zoho_deal_id == $dealData->dealId ? 'selected' : '' }}>
                                                {{ $deal->deal_name }}
                                            </option>
                                        @endforeach --}}
        </select>
    </div>
</div>
<div class="label-div-mb"><label for="add_email" class="common-label">Brochure - Print, Deliver or PDF</label>
    <div class="nontm-select-div">
        <select name="related_transaction" id="related_transaction" class="nontm-select form-select">
            <option value="Yes"> Yes </option>
            <option value="Yes"> No </option>
        </select>
    </div>
</div>
<div class="label-div-mb"><label for="add_email" class="common-label">12 bullets, 4 words per bullet</label>

    <textarea class="form-control" rows="4" cols="50"></textarea>

</div>`;
        var innrtHtml5 = `<div class="label-div-mb"><label for="add_email" class="common-label">
        4 Word Headline - If Opting for A-Line Brochure</label>

    <input class="form-control" type="text"></input>

</div>
<div class="row">
    <div class="col-lg-6">
        <div class="label-div-mb"><label for="add_email" class="common-label">Sticky Dots</label>
            <div class="nontm-select-div">
                <select name="related_transaction" id="related_transaction" class="nontm-select form-select">
                    <option value="Yes"> Yes </option>
                    <option value="Yes"> No </option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-lg-6 label-div-mb">
        <div class="label-div-mb"><label for="add_email" class="common-label">QR Code Sheet</label>
            <div class="nontm-select-div">
                <select name="related_transaction" id="related_transaction" class="nontm-select form-select">
                    <option value="Yes"> Yes </option>
                    <option value="Yes"> No </option>
                </select>
            </div>
        </div>
    </div>
</div>
<div class="gap-2 col-lg-6">
    <div class="d-flex gap-2">
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="formCheck5">
        </div>
        <label for="add_email" class="common-label">QR Code Sign Rider</label>
    </div>
    `;

        var innrtHtml6 = `<div class="label-div-mb">
        <h3 for="add_email">
            Feature Cards</h3>
        <div class="label-div-mb"><label for="add_email" class="common-label">Feature Card</label>
            <div class="nontm-select-div">
                <select name="related_transaction" id="related_transaction" class="nontm-select form-select">
                    <option value="Yes"> Yes </option>
                    <option value="Yes"> No </option>
                </select>
            </div>
        </div>
    </div>
    <div class="label-div-mb"><label for="add_email" class="common-label">Add Feature Card Copy</label>
        <textarea class="form-control" rows="4" cols="50"></textarea>
    </div>
    `;
        var innrtHtml7 = ` <div class="close-date-nontm label-div-mb">
        <label for="close_date" class="common-label">Brochure Date</label>
        <input type="date"
            value="{{ isset($dealData['dealData']['closing_date']) ? \Carbon\Carbon::parse($dealData['dealData']['closing_date'])->format('Y-m-d') : '' }}"
            class="form-control nontm-input" id="close_date">
        <div id="close_date_error" class="text-danger">

        </div>
    </div>
    <div><label for="add_email" class="common-label">
            Shipping Address</label>

        <input class="form-control" placeholder="address line1" type="text"></input>
    </div>
    <div class="mt-1">

        <input class="form-control" placeholder="address line2" type="text"></input>
    </div>
    <div class="row">
        <div class="col-lg-4 label-div-mb">
            <div class="pt-1">
                <input class="form-control" placeholder="City or town" type="text"></input>
            </div>
        </div>
        <div class="col-lg-4 label-div-mb">
            <div class="pt-1">
                <input class="form-control" placeholder="State" type="text"></input>
            </div>
        </div>
        <div class="col-lg-4 label-div-mb">
            <div class="pt-1">
                <input class="form-control" placeholder="Zip" type="text"></input>
            </div>
        </div>
    </div>
    <div class="close-date-nontm label-div-mb">
        <label for="close_date" class="common-label">Printed Items Pick Up or PDF Date</label>
        <input type="date"
            value="{{ isset($dealData['dealData']['closing_date']) ? \Carbon\Carbon::parse($dealData['dealData']['closing_date'])->format('Y-m-d') : '' }}"
            class="form-control nontm-input" id="close_date">
        <div id="close_date_error" class="text-danger">

        </div>
    </div>
    <div class="close-date-nontm label-div-mb">
        <label for="close_date" class="common-label">Brochure Pick Up or PDF Date</label>
        <input type="date"
            value="{{ isset($dealData['dealData']['closing_date']) ? \Carbon\Carbon::parse($dealData['dealData']['closing_date'])->format('Y-m-d') : '' }}"
            class="form-control nontm-input" id="close_date">
        <div id="close_date_error" class="text-danger">

        </div>
    </div>
    `;
        var innrtHtml8 = `<label for="add_email" class="text-bold">Is there anything else the Marketing Team should
        know?</label>
</div>
<div class="label-div-mb"><label for="add_email" class="common-label">Please Add your Notes</label>
    <textarea class="form-control" rows="4" cols="50"></textarea>
</div>
<div class="text-end" id="saveSubmit">
    <button type="button" class="btn btn-white fw-bold">Save & Submit</button>
</div>
`;

        // Set inner HTML content of carouselItem
        carouselItem.innerHTML = innrtHtml;
        carouselItem2.innerHTML = innrtHtml2;
        carouselItem3.innerHTML = innrtHtml3;
        carouselItem4.innerHTML = innrtHtml4;
        carouselItem5.innerHTML = innrtHtml5;
        carouselItem6.innerHTML = innrtHtml6;
        carouselItem7.innerHTML = innrtHtml7;
        carouselItem8.innerHTML = innrtHtml8;

        // Append the new carousel items to the carousel inner
        var carouselInner = document.querySelector('.carousel-inner');
        carouselInner.appendChild(carouselItem);
        carouselInner.appendChild(carouselItem2);
        carouselInner.appendChild(carouselItem3);
        carouselInner.appendChild(carouselItem4);
        carouselInner.appendChild(carouselItem5);
        carouselInner.appendChild(carouselItem6);
        carouselInner.appendChild(carouselItem7);
        carouselInner.appendChild(carouselItem8);

        // Update the carousel indicators (optional, if you want to show navigation bullets)
        var slideIndex = $('.carousel-item').length - 1;

        // Create the slide buttons for carousel indicators
        var button1 = document.createElement('button');
        button1.type = 'button';
        button1.setAttribute('data-bs-target', '#carouselExampleIndicators');
        button1.setAttribute('data-bs-slide-to', (slideIndex - 7).toString());
        button1.setAttribute('aria-label', 'Slide ' + (slideIndex - 7).toString());
        button1.addEventListener('click', function() {
        // Change the inner HTML of 'title-corousal' element
        const titleCorousal = document.getElementById('title-corousal');
        if (titleCorousal) {
                titleCorousal.innerHTML = 'PROPERTY PROMOTION - Outside Services';
            }
        })

        var button2 = document.createElement('button');
        button2.type = 'button';
        button2.setAttribute('data-bs-target', '#carouselExampleIndicators');
        button2.setAttribute('data-bs-slide-to', (slideIndex - 6).toString());
        button2.setAttribute('aria-label', 'Slide ' + (slideIndex - 6).toString());
        button2.addEventListener('click', function() {
        // Change the inner HTML of 'title-corousal' element
        const titleCorousal = document.getElementById('title-corousal');
        if (titleCorousal) {
                titleCorousal.innerHTML = 'PROPERTY PROMOTION - Marketing Items';
            }
        })

        var button3 = document.createElement('button');
        button3.type = 'button';
        button3.setAttribute('data-bs-target', '#carouselExampleIndicators');
        button3.setAttribute('data-bs-slide-to', (slideIndex - 5).toString());
        button3.setAttribute('aria-label', 'Slide ' + (slideIndex - 5).toString());
        button3.addEventListener('click', function() {
        // Change the inner HTML of 'title-corousal' element
        const titleCorousal = document.getElementById('title-corousal');
        if (titleCorousal) {
                titleCorousal.innerHTML = 'PROPERTY PROMOTION - Marketing Items';
            }
        })

        var button4 = document.createElement('button');
        button4.type = 'button';
        button4.setAttribute('data-bs-target', '#carouselExampleIndicators');
        button4.setAttribute('data-bs-slide-to', (slideIndex - 4).toString());
        button4.setAttribute('aria-label', 'Slide ' + (slideIndex - 4).toString());
        button4.addEventListener('click', function() {
        // Change the inner HTML of 'title-corousal' element
        const titleCorousal = document.getElementById('title-corousal');
        if (titleCorousal) {
                titleCorousal.innerHTML = 'PROPERTY PROMOTION - Print Requests';
            }
        })

        var button5 = document.createElement('button');
        button5.type = 'button';
        button5.setAttribute('data-bs-target', '#carouselExampleIndicators');
        button5.setAttribute('data-bs-slide-to', (slideIndex - 3).toString());
        button5.setAttribute('aria-label', 'Slide ' + (slideIndex - 3).toString());
        button5.addEventListener('click', function() {
        // Change the inner HTML of 'title-corousal' element
        const titleCorousal = document.getElementById('title-corousal');
        if (titleCorousal) {
                titleCorousal.innerHTML = 'PROPERTY PROMOTION - Print Requests';
            }
        })

        var button6 = document.createElement('button');
        button6.type = 'button';
        button6.setAttribute('data-bs-target', '#carouselExampleIndicators');
        button6.setAttribute('data-bs-slide-to', (slideIndex - 2).toString());
        button6.setAttribute('aria-label', 'Slide ' + (slideIndex - 2).toString());
        button6.addEventListener('click', function() {
        // Change the inner HTML of 'title-corousal' element
        const titleCorousal = document.getElementById('title-corousal');
        if (titleCorousal) {
                titleCorousal.innerHTML = 'PROPERTY PROMOTION - Print Requests';
            }
        })

        var button7 = document.createElement('button');
        button7.type = 'button';
        button7.setAttribute('data-bs-target', '#carouselExampleIndicators');
        button7.setAttribute('data-bs-slide-to', (slideIndex - 1).toString());
        button7.setAttribute('aria-label', 'Slide ' + (slideIndex - 1).toString());
        button7.addEventListener('click', function() {
        // Change the inner HTML of 'title-corousal' element
        const titleCorousal = document.getElementById('title-corousal');
        if (titleCorousal) {
                titleCorousal.innerHTML = 'PROPERTY PROMOTION - Print Requests';
            }
        })


        var button8 = document.createElement('button');
        button8.type = 'button';
        button8.setAttribute('data-bs-target', '#carouselExampleIndicators');
        button8.setAttribute('data-bs-slide-to', slideIndex.toString());
        button8.setAttribute('aria-label', 'Slide ' + slideIndex.toString());
        button8.addEventListener('click', function() {
        // Change the inner HTML of 'title-corousal' element
        const titleCorousal = document.getElementById('title-corousal');
        if (titleCorousal) {
                titleCorousal.innerHTML = 'PROPERTY PROMOTION - Notes';
            }
        })

        var carouselIndicators = document.querySelector('.carousel-indicators');
        carouselIndicators.appendChild(button1);
        carouselIndicators.appendChild(button2);
        carouselIndicators.appendChild(button3);
        carouselIndicators.appendChild(button4);
        carouselIndicators.appendChild(button5);
        carouselIndicators.appendChild(button6);
        carouselIndicators.appendChild(button7);
        carouselIndicators.appendChild(button8);
    }

    function propertyParmotion(input) {
        console.log(input, 'inputinputinput')
        if (input === false) {
            $("#saveSubmit").hide();
            showPropertyCarousalItem();
        } else {
            addFormSlide(input);
        }
    }

    function isValidJSON(str) {
        try {
            JSON.parse(str);
        } catch (e) {
            console.log("error", e);
            return false;
        }
        return true;
    }

    window.validateSubmittal = function(isNew=true) {
         let submittal = @json($submittal);
        isValid = true
        // submittal = JSON.parse(submittal)
        if (submittal.submittalType == 'buyer-submittal') {
            // Get values from Basic Info section
            var relatedTransaction = $('#relatedTransaction').val();
            var additionalEmailBuyer = $('#additionalEmailBuyer').val();
            var buyerPackage = $('#buyerPackage').val();
            var buyerMailoutNeeded = $('#buyerMailoutNeeded').val();
            var buyerClosingDate = $('#buyerClosingDate').val();
            var buyerPowerAttny = $('#buyerPowerAttny').val();
            var buyerincludeInsight = $('#buyerincludeInsight').val();
            var buyerLenderEmail = $('#buyerLenderEmail').val();
            var buyerLenderPhone = $('#buyerLenderPhone').val();
            var buyerFeesCharged = $('#buyerFeesCharged').val();
            var buyerTmName = $('#buyerTmName').val();
            var buyerAmountChr = $('#buyerAmountChr').val();
            var buyerOtherNotes = $('#buyerOtherNotes').val();
            var buyerRefrralPay = $('#buyerRefrralPay').val();
            var buyerRefrealDetails = $('#buyerRefrealDetails').val();

            if ((relatedTransaction && buyerPackage && buyerMailoutNeeded && buyerClosingDate && buyerPowerAttny &&
                    buyerTmName && buyerRefrralPay && buyerincludeInsight) !== '') {
                isValid = true
            } else {
                showToastError("Please fill in all the required fields.")
                isValid = false
            }

            if (buyerPackage == "New Construction") {
                // Get values from New Construction
                var buyerBuilderrepresent = $('#buyerBuilderrepresent').val();
                var BuyerTitleCompany = $('#BuyerTitleCompany').val();
                var builderCommisionPercent = $('#builderCommisionPercent').val();
                var builderCommision = $('#builderCommision').val();
                var contractExecuted = $('#contractExecuted').val();
                var buyerAgency = $('#buyerAgency').val();
                if ((buyerBuilderrepresent && BuyerTitleCompany && builderCommisionPercent && builderCommision &&
                        contractExecuted && buyerAgency) !== '') {
                    isValid = true
                } else {
                    showToastError("Please fill New Construction all required fields.")
                    isValid = false
                }
            }

            if ((additionalEmailBuyer != '') && (!(isValidEmail(additionalEmailBuyer)))) {
                showToastError("Additional Email for confirmation should be in email format")
                isValid = false
            }
            if ((buyerLenderEmail != '') && (!(isValidEmail(buyerLenderEmail)))) {
                showToastError("Lender Email should be in email format")
                isValid = false
            }
            try {
                var buyerFeesCharged = ($('#buyerFeesCharged').val() != "") ? convertInInteger($(
                    '#buyerFeesCharged').val()) : null;
                var buyerAmountChr = ($('#buyerAmountChr').val() != "") ? convertInInteger($('#buyerAmountChr')
                .val()) : null;
            } catch (error) {
                isValid = false;
                showToastError(error.message)
                return false
            }
            console.log("isValid", isValid);
            if (isValid == true) {
                var formdata = {
                    "data": [{
                        "Related_Transaction": {
                            "module": "Potentials",
                            "name": submittal.deal_data.deal_name,
                            "id": submittal.deal_data.zoho_deal_id
                        },
                        "Additional_Email_for_Confirmation": additionalEmailBuyer,
                        'Buyer_Package': buyerPackage,
                        'Mailout_Needed': buyerMailoutNeeded,
                        'Closing_Date': buyerClosingDate,
                        'Power_of_Attny_Needed': buyerPowerAttny,
                        'Include_Insights_in_Intro': buyerincludeInsight,
                        'Lender_Email': buyerLenderEmail,
                        'Lender_Phone': buyerLenderPhone,
                        'Fees_Charged_to_Buyer_at_Closing': buyerFeesCharged,
                        'TM_Name': buyerTmName,
                        'Amount_to_CHR_Gives': buyerAmountChr,
                        'Other_Important_Notes': buyerOtherNotes,
                        'Referral_to_Pay': buyerRefrralPay,
                        "Referral_Details": buyerRefrealDetails,
                        'Builder_Representative': buyerBuilderrepresent,
                        'Title_Company_Closer_Info': BuyerTitleCompany,
                        'Builder_Commission_and_or_flat_fee': builderCommisionPercent,
                        'Builder_Commission_Based_On': builderCommision,
                        'Contract_Fully_Executed': contractExecuted,
                        'Buyer_Agency_Executed': buyerAgency,
                    }],
                    "_token": '{{ csrf_token() }}'
                }
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                // Send AJAX request
                $.ajax({
                    url: "/buyer/submittal/update/" + submittal.id + `?isNew=${isNew}`,
                    type: 'PUT',
                    contentType: 'application/json',
                    dataType: 'json',
                    data: JSON.stringify(formdata),
                    success: function(response) {
                        console.log("response", response);
                        showToast("Buyer Submittal updated successfully");
                        window.location.href = "/pipeline-view/" + submittal['dealData']['id'];
                        if (response?.data && response.data[0]?.message) {
                            // Convert message to uppercase and then display
                            const upperCaseMessage = response.data[0].message.toUpperCase();

                            // window.location.reload();
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        console.error(xhr.responseText);
                        showToastError(xhr.responseText);
                    }
                })
            }
        } else if (submittal.submittalType == 'listing-submittal') {
               
            // Get values from Basic Info section
            var transactionName = $('#transactionName').val();
            var additionalEmail = $('#additionalEmail').val();
            var agentName = $('#agentName').val();
            var commingSoon = $('#commingSoon').val();
            var comingSoonDate = $('#comingSoonDate').val();
            var tmName = $('#tmName').val();
            var activeDate = $('#activeDate').val();
            var agreementExecuted = $('#agreementExecuted').val();
            var price = $('#price').val();
            var photoDate = $('#photoDate').val();
            var photoURL = $('#photoURL').val();
            var bedsBathsTotal = $('#bedsBathsTotal').val();
            var tourURL = $('#tourURL').val();
            var usingCHR = $('#usingCHR').val();


            // Get values from CHR TM - Transaction Details and Preferences section
            var needOE = $('#needOE').val();
            var hasHOA = $('#hasHOA').val();
            var includeInsights = $('#includeInsights').val();
            var titleToOrderHOA = $('#titleToOrderHOA').val();
            var mailoutNeeded = $('#mailoutNeeded').val();
            var powerOfAttnyNeeded = $('#powerOfAttnyNeeded').val();
            var hoaName = $('#hoaName').val();
            var hoaPhone = $('#hoaPhone').val();
            var hoaWebsite = $('#hoaWebsite').val();
            var miscNotes = $('#miscNotes').val();

            // Get values from CHR TM - Service Providers section
            var scheduleSignInstall = $('#scheduleSignInstall').val();
            var conciergeListing = $('#conciergeListing').val();
            var signInstallVendor = $('#signInstallVendor').val();
            var draftShowingInstructions = $('#draftShowingInstructions').val();
            var titleCompany = $('#titleCompany').val();
            var closerNamePhone = $('#closerNamePhone').val();
            var signInstallVendorOther = $('#signInstallVendorOther').val();
            var signInstallDate = $('#signInstallDate').val();

            // Get values from CHR TM - Select MLS section
            var reColorado = $('#reColorado').prop('checked');
            var navica = $('#navica').prop('checked');
            var ppar = $('#ppar').prop('checked');
            var grandCounty = $('#grandCounty').prop('checked');
            var ires = $('#ires').prop('checked');
            var mlsPrivateRemarks = $('#mlsPrivateRemarks').val();
            var mlsPublicRemarks = $('#mlsPublicRemarks').val();

            // Get values from CHR TM - Commission Details section
            var feesCharged = $('#feesCharged').val();
            var referralToPay = $('#referralToPay').val();
            var amountToCHR = $('#amountToCHR').val();
            var referralDetails = $('#referralDetails').val();

            // Get values from PROPERTY PROMOTION - Outside Services
            var matterport = $('#matterport').prop('checked');
            var floorPlans = $('#floorPlans').prop('checked');
            var threeDZillowTour = $('#threeDZillowTour').prop('checked');
            var onsiteVideo = $('#onsiteVideo').prop('checked');

            // Get values from PROPERTY PROMOTION - Marketing Items
            var propertyWebsite = $('#propertyWebsite').prop('checked');
            var emailBlastSphere = $('#emailBlastSphere').prop('checked');
            var emailBlastReverseProspect = $('#emailBlastReverseProspect').prop('checked');
            var propertyHighlightVideo = $('#propertyHighlightVideo').prop('checked');
            var socialMediaImages = $('#socialMediaImages').prop('checked');
            var showPromotion = $('#showPromotion').val();
            var socialMediaAds = $('#socialMediaAds').prop('checked');
            var priceImprovementPackage = $('#priceImprovementPackage').prop('checked');
            var customDomainName = $('#customDomainName').val();
            var featuresNeededForVideo = $('#featuresNeededForVideo').val();

            // Get value from PROPERTY PROMOTION - Notes
            var marketingNotes = $('#marketingNotes').val();

            // Get values from PROPERTY PROMOTION - Print Requests
            var brochureLine = $('#brochureLine').val();
            var brochurePrint = $('#brochurePrint').val();
            var bullets = $('#bullets').val();
            var headlineForBrochure = $('#headlineForBrochure').val();
            var stickyDots = $('#stickyDots').val();
            var qrCodeSheet = $('#qrCodeSheet').val();
            var qrCodeSignRider = $('#qrCodeSignRider').prop('checked');
            var featureCards = $('#featureCards').val();
            var featureCardCopy = $('#featureCardCopy').val();
            var brochureDeliveryDate = $('#brochureDeliveryDate').val();
            var deliveryAddress = $('#deliveryAddress').val();
            var printedItemsPickupDate = $('#printedItemsPickupDate').val();
            var brochurePickupDate = $('#brochurePickupDate').val();
            // Select all div elements
            const listingSubmittalsContainer = document.getElementById('listingSubmittal');
            console.log("listingSubmittalsContainer", listingSubmittalsContainer);
            if (listingSubmittalsContainer) {
                const allDivs = listingSubmittalsContainer.querySelectorAll(':scope > div');

                // Filter out divs that are hidden (display: none)
                const visibleDivs = Array.from(allDivs).filter(div => window.getComputedStyle(div).display !==
                    'none');
                // Loop through each visible div and validate form fields within it
                visibleDivs.forEach(div => {
                    const validatedElements = div.querySelectorAll('.validate_err');
                    console.log("validatedElements", validatedElements);
                    validatedElements.forEach(element => {
                        if (element.value.trim() === '') {
                            const label = document.querySelector(`label[for="${element.id}"]`);
                            const text = label ? label.innerHTML : "This field";
                            showToastError(text + " cannot be empty");
                            isValid = false;
                        }
                    });
                });

            }

            if ((additionalEmail != '') && (!(isValidEmail(additionalEmail)))) {
                showToastError("Additional Email for confirmation should be in email format")
                isValid = false
            }
            console.log("photo url", isValidUrl(photoURL));
            if ((photoURL != '') && (!(isValidUrl(photoURL)))) {
                showToastError("Photo URL should be in URL format")
                isValid = false
            }
            if ((tourURL != '') && (!(isValidUrl(tourURL)))) {
                showToastError("3D Tour URL should be in URL format")
                isValid = false
            }

            try {
                var price = ($('#price').val() != '') ? convertInInteger($('#price').val()) : null;
                var amountToCHR = ($('#amountToCHR').val() != '') ? convertInInteger($('#amountToCHR').val()) :
                null;
                var feesCharged = ($('#feesCharged').val() != '') ? convertInInteger($('#feesCharged').val()) :
                null;
            } catch (error) {
                console.log(error);
                isValid = false;
                showToastError(error.message)
            }
            console.log("isValid", isValid);
            if (isValid == true) {
                var formdata = {
                    "data": [{
                        "Transaction_Name": {
                            "module": "Potentials",
                            "name": submittal.deal_data.deal_name,
                            "id": submittal.deal_data.zoho_deal_id
                        },
                        "Beds_Baths_Total_Sq_Ft": bedsBathsTotal,
                        "Referral_Details": referralDetails,
                        "Navica": navica,
                        "HOA_Phone": hoaPhone,
                        "Has_HOA": hasHOA,
                        "PPAR": ppar,
                        "Sign_Install_Date": signInstallDate,
                        "Currency": "USD",
                        "Pick_Up_Date": brochurePickupDate,
                        "HOA_Name": hoaName,
                        "Using_CHR_TM": usingCHR,
                        "Email_Blast_to_Sphere": emailBlastSphere,
                        "Print_QR_Code_Sheet": qrCodeSheet,
                        "MLS_Private_Remarks": mlsPrivateRemarks,
                        "MLS_Public_Remarks": mlsPublicRemarks,
                        "Feature_Cards_or_Sheets": featureCards,
                        "Sticky_Dots": stickyDots,
                        "Brochure_Line": brochureLine,
                        "Select_your_prints": brochurePrint,
                        "HOA_Website": hoaWebsite,
                        "HOA_Website": hoaWebsite,
                        "Photo_URL": photoURL,
                        "3D_Tour_URL": tourURL,
                        "Closer_Name_Phone": closerNamePhone,
                        "Listing_Agreement_Executed": agreementExecuted,
                        "Sign_Install_Vendor_if_Other": signInstallVendorOther,
                        "D_Zillow_Tour": threeDZillowTour,
                        "Email_Blast_to_Reverse_Prospect_List": emailBlastReverseProspect,
                        "Social_Media_Ads": socialMediaAds,
                        "QR_Code_Sign_Rider": qrCodeSignRider,
                        "Grand_County": grandCounty,
                        "Agent_Name": agentName,
                        "Mailout_Needed1": mailoutNeeded,
                        "Photo_Date": photoDate,
                        "Social_Media_Images": socialMediaImages,
                        "Add_Feature_Card_or_Sheet_Copy": featureCardCopy,
                        "Title_Company": titleCompany,
                        "Referral_to_Pay": referralToPay,
                        "Property_Promotion_Notes": marketingNotes,
                        "TM_Notes": miscNotes,
                        "Concierge_Listing_Optional": conciergeListing,
                        "Draft_Showing_Instructions1": draftShowingInstructions,
                        "Floor_Plans": floorPlans,
                        "Onsite_Video": onsiteVideo,
                        "Custom_Domain_Name": customDomainName,
                        "bullets_4_words_per_bullet": bullets,
                        "Word_Headline_If_Opting_for_A_Line_Brochure": headlineForBrochure,
                        "In_House_Printed_Brochure_Pick_Up_Date": printedItemsPickupDate,
                        "IRES": ires,
                        "Price": price,
                        "Coming_Soon": commingSoon,
                        "Title_to_Order_HOA_docs": titleToOrderHOA,
                        "Include_Insights_in_Intro1": includeInsights,
                        "Features_Needed_for_Video": featuresNeededForVideo,
                        "Matterport": matterport,
                        "Schedule_Sign_Install": scheduleSignInstall,
                        "Pick_Up_Delivery_Date": brochureDeliveryDate,
                        "Property_Website_QR_Code": propertyWebsite,
                        "Power_of_Attny_Needed1": powerOfAttnyNeeded,
                        "Additional_Email_for_Confirmation": additionalEmail,
                        "TM_Name": tmName,
                        "Property_Highlight_Video": propertyHighlightVideo,
                        "Coming_Soon_MLS_Date": comingSoonDate,
                        "Amount_to_CHR_Gives": amountToCHR,
                        "REColorado": reColorado,
                        "Active_Date": activeDate,
                        "Need_O_E1": needOE,
                        "Sign_Install_Vendor_Info": signInstallVendor,
                        "Delivery_Only_Shipping_Address_Name": deliveryAddress,
                        "Fees_Charged_to_Seller_at_Closing": feesCharged,
                        "showPromotion": showPromotion
                    }],
                    "_token": '{{ csrf_token() }}'
                }
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                // Send AJAX request
                $.ajax({
                    url: "/listing/submittal/update/" + submittal.id + `?isNew=${isNew}`,
                    type: 'PUT',
                    contentType: 'application/json',
                    dataType: 'json',
                    data: JSON.stringify(formdata),
                    success: function(response) {
                        console.log("response", response);
                        showToast("Listing Submittal updated successfully");
                        // window.location.href = "/pipeline-view/" + submittal['deal_data']['id'];
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        console.error(xhr.responseText);
                        showToastError(xhr.responseText);
                    }
                })
            }
        }
    }
</script>
