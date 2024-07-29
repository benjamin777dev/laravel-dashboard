<div class="row " id="listingSubmittal">
    <p>Listing Submittal Information</p>
    {{-- Basic Info --}}
    <div class="col-md-12 col-sm-24" style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03);">
        <p class="npinfoText">Basic Info</p>
        <form class="row g-3" id="additionalFields">
            <div class="col-md-6 ">
                <label for="transactionName" class="form-label nplabelText">Transaction Name</label>
                <select class="form-select npinputinfo validate" id="transactionNameShow" required disabled>
                    @foreach($deals as $currDeal)
                    <option value="{{$currDeal}}" {{ $currDeal['deal_name']==$submittal['dealData']['deal_name']? 'selected' : '' }}>
                        {{$currDeal['deal_name']}}
                    </option>
                    @endforeach
                </select>
                <select class="form-select npinputinfo validate" id="transactionName" required hidden>
                    @foreach($deals as $currDeal)
                    <option value="{{$currDeal}}" {{ $currDeal['deal_name']==$submittal['dealData']['deal_name']? 'selected' : '' }}>
                        {{$currDeal['deal_name']}}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label for="additionalEmail" class="form-label nplabelText">Additional Email for
                    confirmation</label>
                <input type="text" class="form-control npinputinfo" id="additionalEmail" required value="{{$submittal['additionalEmail']}}">
            </div>
            <div class="col-md-6">
                <label for="agentName" class="form-label nplabelText">Agent Name on Material</label>
                <input type="text" class="form-control npinputinfo validate" id="agentName" required value="{{$submittal['agentName']}}">
            </div>
            <div class="col-md-6">
                <label for="commingSoon" class="form-label nplabelText">Coming Soon?</label>
                <select class="form-select npinputinfo validate" id="commingSoon">
                    <option value="">--None--
                    </option>
                    <option value="Yes" {{$submittal['commingSoon']=="Yes" ?'selected':''}}>Yes
                    </option>
                    <option value="No" {{$submittal['commingSoon']=="No" ?'selected':''}}>No</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="comingSoonDate" class="form-label nplabelText">Coming Soon MLS date</label>
                <input type="date" class="form-control npinputinfo " id="comingSoonDate" required value="{{$submittal['comingSoonDate']}}">
            </div>
            <div class="col-md-6">
                <label for="tmName" class="form-label nplabelText">TM Name</label>
                <input type="text" class="form-control npinputinfo validate" id="tmName" required value="{{$submittal['dealData']['tmName']['name']}}">
            </div>

            </div>
        </div>

    </div>
    {{-- CHR TM - Transaction Details and Preferences --}}
    <div class="col-12 transactionForm">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">CHR TM - Transaction Details and Preferences</h4>

                <form class="row g-3">
                    <div class="col-md-6">
                        <label for="needOE" class="form-label nplabelText">Need O&E</label>
                        <select class="form-select npinputinfo validate" id="needOE">
                            <option value="">--None--
                            </option>
                            <option value="Yes" {{ $submittal['needOE'] == 'Yes' ? 'selected' : '' }}>Yes
                            </option>
                            <option value="No" {{ $submittal['needOE'] == 'No' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="hasHOA" class="form-label nplabelText">Has HOA?</label>
                        <select class="form-select npinputinfo validate" id="hasHOA">
                            <option value="">--None--
                            </option>
                            <option value="Yes" {{ $submittal['hasHOA'] == 'Yes' ? 'selected' : '' }}>Yes
                            </option>
                            <option value="No" {{ $submittal['hasHOA'] == 'No' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="includeInsights" class="form-label nplabelText">Include Insights in Intro?</label>
                        <select class="form-select npinputinfo validate" id="includeInsights">
                            <option value="">--None--
                            </option>
                            <option value="Yes" {{ $submittal['includeInsights'] == 'Yes' ? 'selected' : '' }}>Yes
                            </option>
                            <option value="No" {{ $submittal['includeInsights'] == 'No' ? 'selected' : '' }}>No
                            </option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="titleToOrderHOA" class="form-label nplabelText">Title to Order HOA docs?</label>
                        <select class="form-select npinputinfo validate" id="titleToOrderHOA">
                            <option value="">--None--
                            </option>
                            <option value="Yes" {{ $submittal['titleToOrderHOA'] == 'Yes' ? 'selected' : '' }}>Yes
                            </option>
                            <option value="No" {{ $submittal['titleToOrderHOA'] == 'No' ? 'selected' : '' }}>No
                            </option>
                            <option value="TBD" {{ $submittal['titleToOrderHOA'] == 'TBD' ? 'selected' : '' }}>TBD
                            </option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="mailoutNeeded" class="form-label nplabelText">Mailout Needed?</label>
                        <select class="form-select npinputinfo validate" id="mailoutNeeded">
                            <option value="">--None--
                            </option>
                            <option value="Yes" {{ $submittal['mailoutNeeded'] == 'Yes' ? 'selected' : '' }}>Yes
                            </option>
                            <option value="No" {{ $submittal['mailoutNeeded'] == 'No' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="powerOfAttnyNeeded" class="form-label nplabelText">Power of Attny Needed?</label>
                        <select class="form-select npinputinfo validate" id="powerOfAttnyNeeded">
                            <option value="">--None--
                            </option>
                            <option value="Yes" {{ $submittal['powerOfAttnyNeeded'] == 'Yes' ? 'selected' : '' }}>Yes
                            </option>
                            <option value="No" {{ $submittal['powerOfAttnyNeeded'] == 'No' ? 'selected' : '' }}>No
                            </option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="hoaName" class="form-label nplabelText">HOA Name</label>
                        <input type="text" class="form-control npinputinfo" id="hoaName" required
                            value="{{ $submittal['hoaName'] }}">
                    </div>
                    <div class="col-md-6">
                        <label for="hoaPhone" class="form-label nplabelText">HOA Phone</label>
                        <input type="text" class="form-control npinputinfo" id="hoaPhone" required
                            value="{{ $submittal['hoaPhone'] }}">
                    </div>
                    <div class="col-md-12">
                        <label for="hoaWebsite" class="form-label nplabelText">HOA Website</label>
                        <input type="text" class="form-control npinputinfo" id="hoaWebsite" required
                            value="{{ $submittal['hoaWebsite'] }}">
                    </div>
                    <div class="col-md-12">
                        <label for="miscNotes" class="form-label nplabelText">Misc Notes - Seller, Communication,
                            etc</label>
                        <textarea class="form-control" id="miscNotes" aria-label="With textarea">{{ $submittal['miscNotes'] }}</textarea>
                    </div>

                </form>

            </div>
        </div>

    </div>
    {{-- CHR TM - Commission Details --}}
    <div class="col-12 commDetails">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">CHR TM - Commission Details</h4>
                <form class="row g-3">
                    <div class="col-md-6">
                        <label for="feesCharged" class="form-label nplabelText">Fees Charged to Seller at
                            Closing</label>
                        <input type="text" class="form-control npinputinfo" id="feesCharged" required
                            placeholder="$" value="{{ $submittal['feesCharged'] }}">
                    </div>

                    <div class="col-md-6">
                        <label for="referralToPay" class="form-label nplabelText">Referral to Pay</label>

                        <select class="form-select npinputinfo" id="referralToPay">
                            <option value="">--None--
                            </option>
                            <option value="Yes" {{ $submittal['referralToPay'] == 'Yes' ? 'selected' : '' }}>Yes
                            </option>
                            <option value="No" {{ $submittal['referralToPay'] == 'No' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="amountToCHR" class="form-label nplabelText">Amount to CHR Gives</label>
                        <input type="text" class="form-control npinputinfo" id="amountToCHR" required
                            placeholder = "$" value="{{ $submittal['amountToCHR'] }}">
                    </div>
                    <div class="col-md-6">
                        <label for="referralDetails" class="form-label nplabelText">Referral Details</label>
                        <input type="text" class="form-control npinputinfo" id="referralDetails" required
                            value="{{ $submittal['referralDetails'] }}">
                    </div>

                </form>

            </div>
        </div>

    </div>

    {{-- CHR TM - Service Providers --}}
    <div class="col-12 serviceProvider">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">CHR TM - Service Providers</h4>
                <form class="row g-3">
                    <div class="col-md-6">
                        <label for="scheduleSignInstall" class="form-label nplabelText">Schedule Sign Install</label>
                        <select class="form-select npinputinfo validate" id="scheduleSignInstall">
                            <option value="">--None--
                            </option>
                            <option value="Yes" {{ $submittal['scheduleSignInstall'] == 'Yes' ? 'selected' : '' }}>Yes
                            </option>
                            <option value="No" {{ $submittal['scheduleSignInstall'] == 'No' ? 'selected' : '' }}>No
                            </option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="conciergeListing" class="form-label nplabelText">Concierge Listing
                            (Optional)</label>
                        <select class="form-select npinputinfo " id="conciergeListing">
                            <option value="">--None--
                            </option>
                            <option value="Yes" {{ $submittal['conciergeListing'] == 'Yes' ? 'selected' : '' }}>Yes
                            </option>
                            <option value="No" {{ $submittal['conciergeListing'] == 'No' ? 'selected' : '' }}>No
                            </option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="signInstallVendor" class="form-label nplabelText">Sign Install Vendor Info</label>
                        <select class="form-select npinputinfo" id="signInstallVendor" required>
                            <option selected value="">--None--</option>
                            <option value="AXIUM" {{ $submittal['signInstallVendor'] == 'AXIUM' ? 'selected' : '' }}>AXIUM
                            </option>
                            <option value="Rocky Mountain - Brandon"
                                {{ $submittal['signInstallVendor'] == 'Rocky Mountain - Brandon' ? 'selected' : '' }}>Rocky
                                Mountain - Brandon</option>
                            <option value="Other" {{ $submittal['signInstallVendor'] == 'Other' ? 'selected' : '' }}>Other
                            </option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="draftShowingInstructions" class="form-label nplabelText">Draft Showing
                            Instructions?</label>
                        <select class="form-select npinputinfo validate" id="draftShowingInstructions">
                            <option value="">--None--
                            </option>
                            <option value="Yes" {{ $submittal['draftShowingInstructions'] == 'Yes' ? 'selected' : '' }}>
                                Yes
                            </option>
                            <option value="No" {{ $submittal['draftShowingInstructions'] == 'No' ? 'selected' : '' }}>
                                No</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="titleCompany" class="form-label nplabelText">Title Company</label>
                        <input type="text" class="form-control npinputinfo" id="titleCompany" required
                            value="{{ $submittal['titleCompany'] }}">
                    </div>
                    <div class="col-md-6">
                        <label for="closerNamePhone" class="form-label nplabelText">Closer Name & Phone</label>
                        <input type="text" class="form-control npinputinfo validate" id="closerNamePhone" required
                            value="{{ $submittal['closerNamePhone'] }}">
                    </div>
                    <div class="col-md-12">
                        <label for="signInstallVendorOther" class="form-label nplabelText">Sign Install Vendor (if
                            Other)</label>
                        <input type="text" class="form-control npinputinfo" id="signInstallVendorOther" required
                            value="{{ $submittal['signInstallVendorOther'] }}">
                    </div>
                    <div class="col-md-12">
                        <label for="signInstallDate" class="form-label nplabelText">Sign Install Date</label>
                        <input type="date" class="form-control npinputinfo" id="signInstallDate" required
                            value="{{ $submittal['signInstallDate'] }}">
                    </div>

                </form>

            </div>
        </div>

            <div class="col-md-6">
                <label for="emailBlastSphere" class="form-label nplabelText">Email Blast to Sphere</label>
                <input type="checkbox" id="emailBlastSphere" <?php if
                ($submittal['emailBlastSphere']) { echo 'checked' ; } ?>>
            </div>
            <div class="col-md-6">
                <label for="emailBlastReverseProspect" class="form-label nplabelText">Email Blast to Reverse
                    Prospect
                    List</label>
                <input type="checkbox" id="emailBlastReverseProspect" <?php if
                ($submittal['emailBlastReverseProspect']) { echo 'checked' ; } ?>>
            </div>
            <div class="col-md-6">
                <label for="propertyHighlightVideo" class="form-label nplabelText">Property Highlight Video</label>
                <input type="checkbox" id="propertyHighlightVideo" <?php if
                ($submittal['propertyHighlightVideo']) { echo 'checked' ; } ?>>
            </div>
            <div class="col-md-6">
                <label for="socialMediaImages" class="form-label nplabelText">Social Media Images</label>
                <input type="checkbox" id="socialMediaImages" <?php if
                ($submittal['socialMediaImages']) { echo 'checked' ; } ?>>
            </div>
            <div class="col-md-6">
                <label for="socialMediaAds" class="form-label nplabelText">Social Media Ads</label>
                <input type="checkbox" id="socialMediaAds" <?php if
                ($submittal['socialMediaAds']) { echo 'checked' ; } ?>>
            </div>
            <div class="col-md-12">
                <label for="priceImprovementPackage" class="form-label nplabelText">Price Improvement
                    Package</label>
                <input type="checkbox" id="priceImprovementPackage" <?php if
                ($submittal['priceImprovementPackage']) { echo 'checked' ; } ?>>
            </div>
            <div class="col-md-12">
                <label for="customDomainName" class="form-label nplabelText">Custom Domain Name</label>
                <input type="text" class="form-control npinputinfo" id="customDomainName" required value="{{$submittal['customDomainName']}}">
            </div>
            <div class="col-md-12">
                <label for="featuresNeededForVideo" class="form-label nplabelText">8-12 Features Needed for
                    Video</label>
                <textarea class="form-control" id="featuresNeededForVideo" aria-label="With textarea">{{$submittal['featuresNeededForVideo']}}</textarea>
            </div>

        </form>
    </div>
    
   

    {{-- CHR TM - Select MLS --}}
    <div class="col-12 selectMLS">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">CHR TM - Select MLS</h4>

        <p class="npinfoText">PROPERTY PROMOTION - Signs</p>
        <form class="row g-3">
            <div class="col-md-6">
                    <label for="qrCodeMainPanel" class="form-label nplabelText">QR Code Main Panel</label>
                    <input type="checkbox" id="qrCodeMainPanel" <?php if
                    ($submittal['qrCodeMainPanel']) { echo 'checked' ; } ?>>
                </div>
            <div class="col-md-6">
                <label for="qrCodeSignRider" class="form-label nplabelText">OLD QR Code Sign Rider</label>
                <input type="checkbox" id="qrCodeSignRider" <?php if
                ($submittal['qrCodeSignRider']) { echo 'checked' ; } ?>>
            </div>
        </form>
    </div>
    
    {{--PROPERTY PROMOTION - Print Requests--}}
    <div class="col-md-12 col-sm-24 promotionPrint" style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03); display:none">

                    <div class="col-md-6">
                        <label for="navica" class="form-label nplabelText">Navica</label>
                        <input type="checkbox" id="navica" <?php if ($submittal['navica']) {
                            echo 'checked';
                        } ?>>
                    </div>
                    <div class="col-md-6">
                        <label for="ppar" class="form-label nplabelText">PPAR</label>
                        <input type="checkbox" id="ppar" <?php if ($submittal['ppar']) {
                            echo 'checked';
                        } ?>>
                    </div>
                    <div class="col-md-6">
                        <label for="grandCounty" class="form-label nplabelText">Grand County</label>
                        <input type="checkbox" id="grandCounty" <?php if ($submittal['grandCounty']) {
                            echo 'checked';
                        } ?>>
                    </div>

                    <div class="col-md-6">
                        <label for="ires" class="form-label nplabelText">IRES</label>
                        <input type="checkbox" id="ires" <?php if ($submittal['ires']) {
                            echo 'checked';
                        } ?>>
                    </div>
                    <div class="col-md-12">
                        <label for="mlsPrivateRemarks" class="form-label nplabelText">MLS Private Remarks</label>
                        <textarea class="form-control" id="mlsPrivateRemarks" aria-label="With textarea">{{ $submittal['mlsPrivateRemarks'] }}</textarea>
                    </div>
                    <div class="col-md-12">
                        <label for="mlsPublicRemarks" class="form-label nplabelText">MLS Public Remarks</label>
                        <textarea class="form-control" id="mlsPublicRemarks" aria-label="With textarea">{{ $submittal['mlsPublicRemarks'] }}</textarea>
                    </div>

                </form>

            </div>
        </div>

    </div>

     {{--PROPERTY PROMOTION - Notes--}}
    <div class="col-md-12 col-sm-24 promotionNote" style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03); display:none">

        <p class="npinfoText">PROPERTY PROMOTION - Notes</p>
        <form class="row g-3">
            <p class="npinfoText">Is there anything else Marketing Team
                Should know?</p>
            <div class="col-md-6">
                <label for="marketingNotes" class="form-label nplabelText">Please add your Notes</label>
                <textarea class="form-control" id="marketingNotes" aria-label="With textarea">{{$submittal['marketingNotes']}}</textarea>
            </div>

        </form>
    </div>

</div>
<script>
    var showOtherListingForm = @json($listingSubmittaltype);
    var submittal = @json($submittal);
    console.log(submittal);
    $(document).ready(function() {
        if (showOtherListingForm != 'null') {
            console.log("SUBmittal form", showOtherListingForm);
            $('.transactionForm').show();
            $('.promotionOutside').show();
            $('.promotionMarket').show();
            $('.promotionNote').show();
            $('.promotionSign').show();
            $('.promotionPrint').show();

        } else {
            $('.transactionForm').hide();
            $('.promotionOutside').hide();
            $('.promotionMarket').hide();
            $('.promotionNote').hide();
            $('.promotionSign').hide();
            $('.promotionPrint').hide();
        }
        putConditionOnForm();
        showPromotion();
    })

    function putConditionOnForm() {
        var usingCHRValue = $("#usingCHR").val()
        console.log("Consition");
        if (usingCHRValue == "No") {
            $('.transactionForm').hide();
            $('.serviceProvider').hide();
            $('.selectMLS').hide();
            $('.commDetails').hide();
            $('.promotionOutside').show();
            $('.promotionMarket').show();
            $('.promotionNote').show();
            $('.promotionSign').show();
            $('.promotionPrint').show();
            $('.showPromotion').hide()

        } else if (usingCHRValue == "Yes") {
            $('.transactionForm').show();
            $('.serviceProvider').show();
            $('.selectMLS').show();
            $('.commDetails').show();
            $('.promotionOutside').hide();
            $('.promotionMarket').hide();
            $('.promotionNote').hide();
            $('.promotionSign').hide();
            $('.promotionPrint').hide();
            $('.selectMLS').append(`
                <div class="col-md-6 showPromotion">
                    <label for="showPromotion" class="form-label nplabelText">Are you ready to continue to Property Promotion?</label>
                    <select class="form-select npinputinfo validate" id="showPromotion" onchange="showPromotion()">
                        <option value="">--None--
                        </option>
                        <option value=1 {{ $submittal['showPromotion'] == true ? 'selected' : '' }}>Yes
                        </option>
                        <option value=0 {{ $submittal['showPromotion'] == false ? 'selected' : '' }}>No</option>
                    </select>
                </div>
            `)
        } else {
            $('.serviceProvider').show();
            $('.selectMLS').show();
            $('.commDetails').show();
            $('.promotionOutside').hide();
            $('.promotionMarket').hide();
            $('.promotionNote').hide();
            $('.promotionPrint').hide();
            $('.transactionForm').hide();
            $('.showPromotion').hide()
        }

    }

    function showPromotion() {
        var showPromotionValue = $("#showPromotion").val();
        if (showPromotionValue == false) {
            $('.submitToCHR').hide();
            $('.submitToCHRContent').hide();
            $('.promotionOutside').hide();
            $('.promotionMarket').hide();
            $('.promotionNote').hide();
            $('.promotionPrint').hide();
            $('.promotionSign').hide();
            $('#listingSubmittal').append(`
                <div class="col-md-12 col-sm-24 surityChoose " style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03); display: flex;
                justify-content: center;">
                    <div class="col-md-3">
                        <a>
                            <div class="input-group-text text-white justify-content-center ppipeBtn"  onclick="validateSubmittal({{ json_encode($submittal) }},true)"><i class="fas fa-save">
                                </i>
                                Save and Submit
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-md-12 col-sm-24 surityChooseContent " style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03); display: flex;
                justify-content: center;">
                    <div class="col-md-6">
                    <p>When you are ready to continue your Listing Submittal select View Submittal from your Pipeline and Resubmit it to the Marketing Team.</p>
                    </div>
                </div>
            `)
        } else if (showPromotionValue == true) {
            $('.surityChoose').hide();
            $('.surityChooseContent').hide();
            $('.promotionOutside').show();
            $('.promotionMarket').show();
            $('.promotionNote').show();
            $('.promotionPrint').show();
            $('.promotionSign').show();
            $('#listingSubmittal').append(`
                <div class="col-md-12 col-sm-24 submitToCHR " style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03); display: flex;
                justify-content: center;">
                    <div class="col-md-3">
                        <a>
                            <div class="input-group-text text-white justify-content-center ppipeBtn"  onclick="validateSubmittal({{$submittal}},true)"><i class="fas fa-save">
                                </i>
                                Save and Submit to CHR
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-md-12 col-sm-24 submitToCHRContent " style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03); display: flex;
                justify-content: center;">
                    <div class="col-md-6">
                    <p>Thank you for your Listing Submittal. We have received your request and are taking action to create a wonderful experience for you and your client. Congratulations on the new listing!</p>
                    </div>
                </div>
            `)
        } else {
            $('.submitToCHR').hide();
            $('.submitToCHRContent').hide();
            $('.surityChoose').hide();
            $('.surityChooseContent').hide();
            $('.promotionOutside').hide();
            $('.promotionMarket').hide();
            $('.promotionNote').hide();
            $('.promotionPrint').hide();
            $('.promotionSign').hide();

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

    window.validateSubmittal = function(submittal, isNew) {
        console.log(isValidJSON(submittal), 'validahasekhfkusdhkfh');
        if (isValidJSON(submittal)) {
            submittal = JSON.parse(jsonString);
            console.log(submittal.name); // Output: John
        }
        console.log(submittal, 'validahasekhfkusdhkfh');
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
            console.log("isValid", isValid,additionalEmail);
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
            console.log("jasgdfjashj");
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
                console.log("visibleDivs", visibleDivs);
                // Loop through each visible div and validate form fields within it
                visibleDivs.forEach(div => {
                    const validatedElements = div.querySelectorAll('.validate');
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
            console.log("isValid", isValid,additionalEmail);
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
                        window.location.href = "/pipeline-view/" + submittal['deal_data']['id'];
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
