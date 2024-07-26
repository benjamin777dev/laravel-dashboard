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
                                        <select name="related_transaction" id="transactionName" class="form-select validate_err" disabled>
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
                                        <input type="email" class="form-control" value="{{$submittal['additionalEmail']}}" id="additionalEmailBuyer" placeholder="Enter Your Email.">
                                    </div>
                                </div>
                            </div>
                           

                            <div class="row">
                               
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="basicpill-lastname-input">Agent Name on Material <svg
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
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="basicpill-email-input">Coming Soon? <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18" fill="none">
                                            <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="19" height="18">
                                                <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                            </mask>
                                            <g mask="url(#mask0_2151_10662)">
                                                <path d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z" fill="#AC5353" />
                                            </g>
                                        </svg></label>
                                        <div class="d-flex gap-2">
                                            <div class="mb-3">
                                                <input type="radio"  id="commingSoon_yes" {{ $submittal['commingSoon'] == 'Yes' ? 'selected' : '' }}
                                                    name="commingSoon">
                                                <label class=""  for="commingSoon_yes">
                                                    Yes
                                                </label>
                                            </div>
                                            <div class="mb-3">
                                                <input type="radio" id="commingSoon_no" {{ $submittal['commingSoon'] == 'No' ? 'selected' : '' }}
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
                                    <input type="text" value="{{ $submittal['dealData']['tmName']['name'] }}"
                                    class="form-control validate_err" placeholder="" id="tmName">
                                </div>
                            </div>
                        </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="basicpill-cstno-input">Active Date
                                            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18" fill="none">
                                                <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="19" height="18">
                                                    <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                                </mask>
                                                <g mask="url(#mask0_2151_10662)">
                                                    <path d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z" fill="#AC5353" />
                                                </g>
                                            </svg></label>
                                            <input type="date" id="activeDate" value="{{ $submittal['activeDate'] }}"
                                    class="form-control validate_err" id="activeDate">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="basicpill-servicetax-input">Listing Agreement Exucuted?
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
                                                    <input type="radio" id="agreementExecuted_yes"
                                                        {{ $submittal['agreementExecuted'] == 'Yes' ? 'selected' : '' }}
                                                        name="agreementExecuted">
                                                    <label class="" for="agreementExecuted_yes">
                                                        Yes
                                                    </label>
                                                </div>
                                                <div class="mb-3">
                                                    <input type="radio" id="agreementExecuted_no"
                                                        {{ $submittal['agreementExecuted'] == 'No' ? 'selected' : '' }}
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
                                        <label for="buyerLenderEmail">Price<svg
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
                                        <input type="number" value="{{ $submittal['price'] }}" placeholder="$"
                                        class="form-control validate_err" id="price">
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
                                            <input type="text" value="{{ $submittal['photoURL'] }}" class="form-control"
                                            placeholder="" id="photoURL">
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
                                            <input type="text" value="{{ $submittal['tourURL'] }}" class="form-control validate_err"
                                            placeholder="" id="tourURL">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="usingCHR">Using CHR TM <svg
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
                                            <div class="d-flex gap-2">
                                                <div class="mb-3">
                                                    <input  id="usingCHR_yes"
                                                        {{ $submittal['usingCHR'] == 'Yes' ? 'selected' : '' }} type="radio"
                                                        name="usingCHR">
                                                    <label class="" id="chkNo" for="formCheck1">
                                                        Yes
                                                    </label>
                                                </div>
                                                <div class="mb-3">
                                                    <input id="usingCHR_no"
                                                        {{ $submittal['usingCHR'] == 'No' ? 'selected' : '' }} type="radio"
                                                        name="usingCHR">
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

                      <!-- Confirm Details -->
                      <h3>Commission Details</h3>
                      <section>
                        <div>
                            <form>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="feesCharged">Fees Charged to Seller at
                                                Closing</label>
                                            <input type="text" name="feesCharged" value="{{ $submittal['feesCharged'] }}"
                                            id="feesCharged" class="form-control " placeholder="$">
                                        </input>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="referralToPay">Referral to Pay</label>
                                            <select name="additional_charge" id="additonal_fee"
                                            class="form-select" >
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
                                            id="referralDetails"
                                            class="form-control">
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
                                            <label for="builderCommisionPercent">Schedule Sign Install<svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18" fill="none">
                                                <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="19" height="18">
                                                    <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                                </mask>
                                                <g mask="url(#mask0_2151_10662)">
                                                    <path d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z" fill="#AC5353" />
                                                </g>
                                            </svg></label>
                                            <div class="d-flex gap-2">
                                                <div class="mb-3">
                                                    <input type="radio" id="scheduleSignInstall_yes"
                                                        {{ $submittal['scheduleSignInstall'] == 'Yes' ? 'checked' : '' }}
                                                        name="scheduleSignInstall">
                                                    <label class="" for="formCheck1">
                                                        Yes
                                                    </label>
                                                </div>
                                                <div class="mb-3">
                                                    <input type="radio" id="scheduleSignInstall_no"
                                                        {{ $submittal['scheduleSignInstall'] == 'No' ? 'checked' : '' }}
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
                                            <label for="buyerOtherNotes">Draft Showing Instructions? <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18" fill="none">
                                                <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="19" height="18">
                                                    <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                                </mask>
                                                <g mask="url(#mask0_2151_10662)">
                                                    <path d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z" fill="#AC5353" />
                                                </g>
                                            </svg></label>
                                            <div class="d-flex gap-2">
                                                <div class="mb-3">
                                                    <input type="radio" id="draftShowingInstructions_yes"
                                                        {{ $submittal['draftShowingInstructions'] == 'Yes' ? 'checked' : '' }}
                                                        name="draftShowingInstructions">
                                                    <label class="" for="formCheck1">
                                                        Yes
                                                    </label>
                                                </div>
                                                <div class="mb-3">
                                                    <input type="radio" id="draftShowingInstructions_no"
                                                        {{ $submittal['draftShowingInstructions'] == 'No' ? 'checked' : '' }}
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
                                                        {{ $submittal['conciergeListing'] == 'Yes' ? 'checked' : '' }}
                                                        name="conciergeListing">
                                                    <label class="" id="chkNo" for="formCheck1">
                                                        Yes
                                                    </label>
                                                </div>
                                                <div class="mb-3">
                                                    <input type="radio" id="conciergeListing_no"
                                                        {{ $submittal['conciergeListing'] == 'No' ? 'checked' : '' }}
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
                                                    maskUnits="userSpaceOnUse" x="0" y="0" width="19" height="18">
                                                    <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                                </mask>
                                                <g mask="url(#mask0_2151_10662)">
                                                    <path
                                                        d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z"
                                                        fill="#AC5353" />
                                                </g>
                                            </svg> </label>
                                            <input name="closerNamePhone" value="{{ $submittal['closerNamePhone'] }}"
                                    id="closerNamePhone"
                                    class="form-control validate_err">
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
                                            <input value="{{ $submittal['signInstallVendor'] }}"
                                            name="signInstallVendor" id="signInstallVendor"
                                            class="form-control">
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
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
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
                    
                      </section>

                      <h3>Service Providers</h3>
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
                                                    <input onclick="propertyParmotion(false)" value=1 {{ $submittal['showPromotion'] == 1 ? 'checked' : '' }} type="radio"  name="showPromotion">
                                                    <label class=""  for="formCheck1">
                                                        Yes
                                                    </label>
                                                </div>
                                                <div class="mb-3">
                                                    <input onclick="propertyParmotion(true)" value=0 {{ $submittal['showPromotion'] == 0 ? 'checked' : '' }} type="radio" name="showPromotion">
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
<!-- end row -->
<!-- jquery step -->
<script defer src="{{ URL::asset('build/libs/jquery-steps/build/jquery.steps.min.js') }}"></script>

<!-- form wizard init -->
<script src="{{ URL::asset('build/js/pages/form-wizard.init.js') }}"></script>

<script>
     $(document).ready(function() {
            const $stepsContainer = $('#basic-example-seller');
            // Function to initialize the steps plugin
            function initializeSteps() {
                $stepsContainer.steps({
                    headerTag: "h3",
                    bodyTag: "section",
                    transitionEffect: "slide",
                    onFinished: function (event, currentIndex) {
                        //api call hereee
                        alert("Finished!");
                    }
                });
            }

            // Initial steps plugin setup
            initializeSteps();

          
        });
           
</script>
