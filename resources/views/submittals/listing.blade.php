<div class="row " id="listingSubmittal" style="display:none">
    <p>Listing Submittal Information</p>
    {{-- Basic Info --}}
    <div class="col-md-12 col-sm-24" style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03);">
        <p class="npinfoText">Basic Info</p>
        <form class="row g-3" id="additionalFields">
            <div class="col-md-6 ">
                <label for="transactionName" class="form-label nplabelText">Transaction Name</label>
                <select class="form-select npinputinfo validate" id="transactionName" required>
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
                <input type="text" class="form-control npinputinfo validate" id="tmName" required value="{{$submittal['tmName']}}">
            </div>

            <div class="col-md-6">
                <label for="activeDate" class="form-label nplabelText">Active Date</label>
                <input type="date" class="form-control npinputinfo validate" id="activeDate" required value="{{$submittal['activeDate']}}">
            </div>
            <div class="col-md-6">
                <label for="agreementExecuted" class="form-label nplabelText">Listing Agreement Executed? </label>
                <select class="form-select npinputinfo validate" id="agreementExecuted">
                    <option value="">--None--
                    </option>
                    <option value="Yes" {{$submittal['agreementExecuted']=="Yes" ?'selected':''}}>Yes
                    </option>
                    <option value="No" {{$submittal['agreementExecuted']=="No" ?'selected':''}}>No</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="price" class="form-label nplabelText">Price</label>
                <input type="text" class="form-control npinputinfo validate" id="price" required value="${{$submittal['price']}}">
            </div>
            <div class="col-md-6">
                <label for="photoDate" class="form-label nplabelText">Photo Date</label>
                <input type="date" class="form-control npinputinfo " id="photoDate" required value="{{$submittal['photoDate']}}">
            </div>
            <div class="col-md-6">
                <label for="photoURL" class="form-label nplabelText">Photo URL</label>
                <input type="text" class="form-control npinputinfo" id="photoURL" required value="{{$submittal['photoURL']}}">
            </div>
            <div class="col-md-6">
                <label for="bedsBathsTotal" class="form-label nplabelText">Beds,baths,total sq.ft.</label>
                <input type="text" class="form-control npinputinfo validate" id="bedsBathsTotal" required value="{{$submittal['bedsBathsTotal']}}">
            </div>
            <div class="col-md-6">
                <label for="tourURL" class="form-label nplabelText">3D Tour URL</label>
                <input type="text" class="form-control npinputinfo" id="tourURL" required value="{{$submittal['tourURL']}}">
            </div>
            <div class="col-md-6">
                <label for="usingCHR" class="form-label nplabelText">Using CHR TM </label>
                <select class="form-select npinputinfo validate" id="usingCHR">
                        <option value="">--None--
                    </option>
                    <option value="Yes" {{$submittal['usingCHR']=="Yes" ?'selected':''}}>Yes
                    </option>
                    <option value="No" {{$submittal['usingCHR']=="No" ?'selected':''}}>No</option>
                </select>
            </div>
        </form>
    </div>
    {{--CHR TM - Transaction Details and Preferences--}}
    <div class="col-md-12 col-sm-24 transactionForm" style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03); display:none">
        <p class="npinfoText">CHR TM - Transaction Details and Preferences</p>
        <form class="row g-3">
            <div class="col-md-6">
                <label for="needOE" class="form-label nplabelText">Need O&E</label>
                <select class="form-select npinputinfo" id="needOE">
                    <option value="">--None--
                    </option>
                    <option value="Yes" {{$submittal['needOE']=="Yes" ?'selected':''}}>Yes
                    </option>
                    <option value="No" {{$submittal['needOE']=="No" ?'selected':''}}>No</option>
                </select>
            </div>

            <div class="col-md-6">
                <label for="hasHOA" class="form-label nplabelText">Has HOA?</label>
                <select class="form-select npinputinfo" id="hasHOA">
                    <option value="">--None--
                    </option>
                    <option value="Yes" {{$submittal['hasHOA']=="Yes" ?'selected':''}}>Yes
                    </option>
                    <option value="No" {{$submittal['hasHOA']=="No" ?'selected':''}}>No</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="includeInsights" class="form-label nplabelText">Include Insights in Intro?</label>
                <select class="form-select npinputinfo" id="includeInsights">
                    <option value="">--None--
                    </option>
                    <option value="Yes" {{$submittal['includeInsights']=="Yes" ?'selected':''}}>Yes
                    </option>
                    <option value="No" {{$submittal['includeInsights']=="No" ?'selected':''}}>No</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="titleToOrderHOA" class="form-label nplabelText">Title to Order HOA docs?</label>
                <select class="form-select npinputinfo" id="titleToOrderHOA">
                    <option value="">--None--
                    </option>
                    <option value="Yes" {{$submittal['titleToOrderHOA']=="Yes" ?'selected':''}}>Yes
                    </option>
                    <option value="No" {{$submittal['titleToOrderHOA']=="No" ?'selected':''}}>No</option>
                    <option value="TBD" {{$submittal['titleToOrderHOA']=="TBD" ?'selected':''}}>TBD</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="mailoutNeeded" class="form-label nplabelText">Mailout Needed?</label>
                <select class="form-select npinputinfo" id="mailoutNeeded">
                    <option value="">--None--
                    </option>
                    <option value="Yes" {{$submittal['mailoutNeeded']=="Yes" ?'selected':''}}>Yes
                    </option>
                    <option value="No" {{$submittal['mailoutNeeded']=="No" ?'selected':''}}>No</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="powerOfAttnyNeeded" class="form-label nplabelText">Power of Attny Needed?</label>
                <select class="form-select npinputinfo" id="powerOfAttnyNeeded">
                    <option value="">--None--
                    </option>
                    <option value="Yes" {{$submittal['powerOfAttnyNeeded']=="Yes" ?'selected':''}}>Yes
                    </option>
                    <option value="No" {{$submittal['powerOfAttnyNeeded']=="No" ?'selected':''}}>No</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="hoaName" class="form-label nplabelText">HOA Name</label>
                <input type="text" class="form-control npinputinfo" id="hoaName" required value="{{$submittal['hoaName']}}">
            </div>
            <div class="col-md-6">
                <label for="hoaPhone" class="form-label nplabelText">HOA Phone</label>
                <input type="text" class="form-control npinputinfo" id="hoaPhone" required value="{{$submittal['hoaPhone']}}">
            </div>
            <div class="col-md-12">
                <label for="hoaWebsite" class="form-label nplabelText">HOA Website</label>
                <input type="text" class="form-control npinputinfo" id="hoaWebsite" required value="{{$submittal['hoaWebsite']}}">
            </div>
            <div class="col-md-12">
                <label for="miscNotes" class="form-label nplabelText">Misc Notes - Seller, Communication,
                    etc</label>
                <textarea class="form-control" id="miscNotes" aria-label="With textarea">{{$submittal['miscNotes']}}</textarea>
            </div>

        </form>
    </div>
    {{--CHR TM - Service Providers--}}
    <div class="col-md-12 col-sm-24" style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03);">

        <p class="npinfoText">CHR TM - Service Providers</p>
        <form class="row g-3">
            <div class="col-md-6">
                <label for="scheduleSignInstall" class="form-label nplabelText">Schedule Sign Install</label>
                <select class="form-select npinputinfo" id="scheduleSignInstall">
                    <option value="">--None--
                    </option>
                    <option value="Yes" {{$submittal['scheduleSignInstall']=="Yes" ?'selected':''}}>Yes
                    </option>
                    <option value="No" {{$submittal['scheduleSignInstall']=="No" ?'selected':''}}>No</option>
                </select>
            </div>

            <div class="col-md-6">
                <label for="conciergeListing" class="form-label nplabelText">Concierge Listing (Optional)</label>
                <select class="form-select npinputinfo" id="conciergeListing">
                    <option value="">--None--
                    </option>
                    <option value="Yes" {{$submittal['conciergeListing']=="Yes" ?'selected':''}}>Yes
                    </option>
                    <option value="No" {{$submittal['conciergeListing']=="No" ?'selected':''}}>No</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="signInstallVendor" class="form-label nplabelText">Sign Install Vendor Info</label>
                <select class="form-select npinputinfo" id="signInstallVendor" required>
                    <option selected value="">--None--</option>
                    <option value="AXIUM" {{$submittal['signInstallVendor']=="AXIUM" ?'selected':''}}>AXIUM</option>
                    <option value="Rocky Mountain - Brandon" {{$submittal['signInstallVendor']=="Rocky Mountain - Brandon" ?'selected':''}}>Rocky Mountain - Brandon</option>
                    <option value="Other" {{$submittal['signInstallVendor']=="Other" ?'selected':''}}>Other</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="draftShowingInstructions" class="form-label nplabelText">Draft Showing
                    Instructions?</label>
                <select class="form-select npinputinfo" id="draftShowingInstructions">
                    <option value="">--None--
                    </option>
                    <option value="Yes" {{$submittal['draftShowingInstructions']=="Yes" ?'selected':''}}>Yes
                    </option>
                    <option value="No" {{$submittal['draftShowingInstructions']=="No" ?'selected':''}}>No</option>
                </select>
            </div>

            <div class="col-md-6">
                <label for="titleCompany" class="form-label nplabelText">Title Company</label>
                <input type="text" class="form-control npinputinfo" id="titleCompany" required value="{{$submittal['titleCompany']}}">
            </div>
            <div class="col-md-6">
                <label for="closerNamePhone" class="form-label nplabelText">Closer Name & Phone</label>
                <input type="text" class="form-control npinputinfo" id="closerNamePhone" required value="{{$submittal['closerNamePhone']}}">
            </div>
            <div class="col-md-12">
                <label for="signInstallVendorOther" class="form-label nplabelText">Sign Install Vendor (if
                    Other)</label>
                <input type="text" class="form-control npinputinfo" id="signInstallVendorOther" required value="{{$submittal['signInstallVendorOther']}}">
            </div>
            <div class="col-md-12">
                <label for="signInstallDate" class="form-label nplabelText">Sign Install Date</label>
                <input type="date" class="form-control npinputinfo" id="signInstallDate" required value="{{$submittal['signInstallDate']}}">
            </div>

        </form>
    </div>
    {{--CHR TM - Select MLS--}}
    <div class="col-md-12 col-sm-24" style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03);">

        <p class="npinfoText">CHR TM - Select MLS</p>
        <form class="row g-3">
            <div class="col-md-6">
                <label for="reColorado" class="form-label nplabelText">REColorado</label>
                <input type="checkbox" id="reColorado" <?php if
                ($submittal['reColorado']) { echo 'checked' ; } ?>>
            </div>

            <div class="col-md-6">
                <label for="navica" class="form-label nplabelText">Navica</label>
                <input type="checkbox" id="navica" <?php if
                ($submittal['navica']) { echo 'checked' ; } ?>>
            </div>
            <div class="col-md-6">
                <label for="ppar" class="form-label nplabelText">PPAR</label>
                <input type="checkbox" id="ppar" <?php if
                ($submittal['ppar']) { echo 'checked' ; } ?>>
            </div>
            <div class="col-md-6">
                <label for="grandCounty" class="form-label nplabelText">Grand County</label>
                <input type="checkbox" id="grandCounty" <?php if
                ($submittal['grandCounty']) { echo 'checked' ; } ?>>
            </div>

            <div class="col-md-6">
                <label for="ires" class="form-label nplabelText">IRES</label>
                <input type="checkbox" id="ires" <?php if
                ($submittal['ires']) { echo 'checked' ; } ?>>
            </div>
            <div class="col-md-12">
                <label for="mlsPrivateRemarks" class="form-label nplabelText">MLS Private Remarks</label>
                <textarea class="form-control" id="mlsPrivateRemarks" aria-label="With textarea">{{$submittal['mlsPrivateRemarks']}}</textarea>
            </div>
            <div class="col-md-12">
                <label for="mlsPublicRemarks" class="form-label nplabelText">MLS Public Remarks</label>
                <textarea class="form-control" id="mlsPublicRemarks" aria-label="With textarea">{{$submittal['mlsPublicRemarks']}}</textarea>
            </div>

        </form>
    </div>
    {{--CHR TM - Commission Details--}}
    <div class="col-md-12 col-sm-24" style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03);">

        <p class="npinfoText">CHR TM - Commission Details</p>
        <form class="row g-3">
            <div class="col-md-6">
                <label for="feesCharged" class="form-label nplabelText">Fees Charged to Seller at
                    Closing</label>
                <input type="text" class="form-control npinputinfo" id="feesCharged" required value="${{$submittal['feesCharged']}}">
            </div>

            <div class="col-md-6">
                <label for="referralToPay" class="form-label nplabelText">Referral to Pay</label>

                <select class="form-select npinputinfo" id="referralToPay">
                    <option value="">--None--
                    </option>
                    <option value="Yes" {{$submittal['referralToPay']=="Yes" ?'selected':''}}>Yes
                    </option>
                    <option value="No" {{$submittal['referralToPay']=="No" ?'selected':''}}>No</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="amountToCHR" class="form-label nplabelText">Amount to CHR Gives</label>
                <input type="text" class="form-control npinputinfo" id="amountToCHR" required value="${{$submittal['amountToCHR']}}">
            </div>
            <div class="col-md-6">
                <label for="referralDetails" class="form-label nplabelText">Referral Details</label>
                <input type="text" class="form-control npinputinfo" id="referralDetails" required value="{{$submittal['referralDetails']}}">
            </div>

        </form>
    </div>
    {{--PROPERTY PROMOTION - Outside Services--}}
    <div class="col-md-12 col-sm-24 promotionOutside" style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03); display:none">

        <p class="npinfoText">PROPERTY PROMOTION - Outside Services</p>
        <form class="row g-3">
            <div class="col-md-6">
                <label for="matterport" class="form-label nplabelText">Matterport</label>
                <input type="checkbox" id="matterport" <?php if
                ($submittal['matterport']) { echo 'checked' ; } ?>>
            </div>

            <div class="col-md-6">
                <label for="floorPlans" class="form-label nplabelText">Floor Plans</label>
                <input type="checkbox" id="floorPlans" required <?php if
                ($submittal['floorPlans']) { echo 'checked' ; } ?>>
            </div>
            <div class="col-md-6">
                <label for="threeDZillowTour" class="form-label nplabelText">3D Zillow Tour</label>
                <input type="checkbox" id="threeDZillowTour" <?php if
                ($submittal['threeDZillowTour']) { echo 'checked' ; } ?>>
            </div>
            <div class="col-md-6">
                <label for="onsiteVideo" class="form-label nplabelText">Onsite Video</label>
                <input type="checkbox" id="onsiteVideo" <?php if
                ($submittal['onsiteVideo']) { echo 'checked' ; } ?>>
            </div>

        </form>
    </div>
    {{--PROPERTY PROMOTION - Marketing Items--}}
    <div class="col-md-12 col-sm-24 promotionMarket" style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03); display:none">

        <p class="npinfoText">PROPERTY PROMOTION - Marketing Items</p>
        <form class="row g-3">
            <div class="col-md-6">
                <label for="propertyWebsite" class="form-label nplabelText">Property Website</label>
                <input type="checkbox" id="propertyWebsite" <?php if
                ($submittal['propertyWebsite']) { echo 'checked' ; } ?>>
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
    {{--PROPERTY PROMOTION - Print Requests--}}
    <div class="col-md-12 col-sm-24 promotionPrint" style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03); display:none">

        <p class="npinfoText">PROPERTY PROMOTION - Print Requests</p>
        <form class="row g-3">
            <div class="col-md-6">
                <label for="brochureLine" class="form-label nplabelText">Brochure Line - Top Right Brochure Preview
                    button</label>
                <select class="form-select npinputinfo " id="broucherLine">
                    <option value="">--None--
                    </option>
                    <option value="No Brochure" {{$submittal['brochureLine']=="No Brochure" ?'selected':''}}>No Brochure
                    </option>
                    <option value="A-Line - 2 Page Horizontal"{{$submittal['brochureLine']=="A-Line - 2 Page Horizontal" ?'selected':''}}>A-Line - 2 Page Horizontal</option>
                    <option value="A-Line - 2 Page Vertical" {{$submittal['brochureLine']=="A-Line - 2 Page Vertical" ?'selected':''}}>A-Line - 2 Page Vertical
                    </option>
                    <option value="A-Line - 4 Page" {{$submittal['brochureLine']=="A-Line - 4 Page" ?'selected':''}}>A-Line - 4 Page'
                    </option>
                    <option value="A-Line - 8 Page - Delivery Only" {{$submittal['brochureLine']=="A-Line - 8 Page - Delivery Only" ?'selected':''}}>A-Line - 8 Page - Delivery Only
                    </option>
                    <option value="B-Line - 2 Page" {{$submittal['brochureLine']=="B-Line - 2 Page" ?'selected':''}}>B-Line - 2 Page
                    </option>
                    <option value="B-Line - 4 Page" {{$submittal['brochureLine']=="B-Line - 4 Page" ?'selected':''}}>B-Line - 4 Page
                    </option>
                    <option value="B-Line - 8 Page - Delivery Only" {{$submittal['brochureLine']=="B-Line - 8 Page - Delivery Only" ?'selected':''}}>B-Line - 8 Page - Delivery Only
                    </option>
                    <option value="C-Line - 2 Page" {{$submittal['brochureLine']=="C-Line - 2 Page" ?'selected':''}}>C-Line - 2 Page
                    </option>
                    <option value="C-Line - 4 Page" {{$submittal['brochureLine']=="C-Line - 4 Page" ?'selected':''}}>C-Line - 4 Page
                    </option>
                </select>
            </div>

            <div class="col-md-6">
                <label for="brochurePrint" class="form-label nplabelText">Brochure - Print, Deliver or PDF</label>
                <select class="form-select npinputinfo" id="brochurePrint">
                    @foreach ($broucherPrint as $brochure)
                        <option value="{{ $brochure }}" {{ $submittal['brochurePrint'] == $brochure ? 'selected' : '' }}>
                            {{ $brochure }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label for="bullets" class="form-label nplabelText">12 bullets, 4 words per bullet</label>
                <textarea class="form-control" id="bullets" aria-label="With textarea">{{$submittal['bullets']}}</textarea>
            </div>
            <div class="col-md-6">
                <label for="headlineForBrochure" class="form-label nplabelText">4 Word Headline - If Opting for
                    A-Line
                    Brochure</label>
                <input type="text" class="form-control npinputinfo" id="headlineForBrochure" required value="{{$submittal['headlineForBrochure']}}">
            </div>
            <div class="col-md-6">
                <label for="stickyDots" class="form-label nplabelText">Sticky Dots</label>
                <select class="form-select npinputinfo " id="stickyDots">
                    <option value="">--None--</option>
                    <option value="1 Per Feature Card - $.75 each">1 Per Feature Card - $.75 each
                    </option>
                    <option value="2 Per Feature Card - $.75 each">2 Per Feature Card - $.75 each</option>
                    <option value="No Dots">No Dots</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="qrCodeSheet" class="form-label nplabelText">QR Code Sheet</label>
                <select class="form-select npinputinfo" id="qrCodeSheet">
                    <option value="">--None--</option>
                    <option value="Print 1 at Alamo - $1">Print 1 at Alamo - $1
                    </option>
                    <option value="Print 2 at Alamo - $2">Print 2 at Alamo - $2</option>
                    <option value="PDF - I'll Print it Myself">PDF - I'll Print it Myself</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="qrCodeSignRider" class="form-label nplabelText">QR Code Sign Rider</label>
                <input type="checkbox" id="qrCodeSignRider" <?php if
                ($submittal['qrCodeSignRider']) { echo 'checked' ; } ?>>
            </div>
            <div class="col-md-6">
                <label for="featureCards" class="form-label nplabelText">Feature Cards</label>
                <select class="form-select npinputinfo " id="featureCards">
                        @foreach ($featuresCard as $currfeaturesCard)
                        <option value="{{ $currfeaturesCard }}" {{ $submittal['featureCards'] == $currfeaturesCard ? 'selected' : '' }}>
                            {{ $currfeaturesCard }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label for="featureCardCopy" class="form-label nplabelText">Add Feature Card Copy</label>
                <input type="text" class="form-control npinputinfo" id="featureCardCopy" required value="{{$submittal['featureCardCopy']}}">
            </div>
            <div class="col-md-6">
                <label for="brochureDeliveryDate" class="form-label nplabelText">Delivery Only - Brochure
                    Date</label>
                <input type="date" class="form-control npinputinfo" id="brochureDeliveryDate" required value="{{$submittal['brochureDeliveryDate']}}">
            </div>
            <div class="col-md-6">
                <label for="deliveryAddress" class="form-label nplabelText">Delivery Only - Shipping Address &
                    Name</label>
                <input type="text" class="form-control npinputinfo" id="deliveryAddress" required value="{{$submittal['deliveryAddress']}}">
            </div>
            <div class="col-md-6">
                <label for="printedItemsPickupDate" class="form-label nplabelText">Printed Items Pick Up or PDF
                    Date</label>
                <input type="date" class="form-control npinputinfo" id="printedItemsPickupDate" required value="{{$submittal['printedItemsPickupDate']}}">
            </div>
            <div class="col-md-6">
                <label for="brochurePickupDate" class="form-label nplabelText">Brochure Pick Up or PDF Date</label>
                <input type="date" class="form-control npinputinfo" id="brochurePickupDate" required value="{{$submittal['brochurePickupDate']}}">
            </div>

        </form>
    </div>

</div>