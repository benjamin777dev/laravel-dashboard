@extends('layouts.master')

@section('title', 'zPortal | Submittals Create')

@section('content')
@vite(['resources/css/pipeline.css'])
@vite(['resources/js/toast.js'])

<div class="container-fluid">
    <div class="submittaldiv">
        <div class="submittalType">
            <h4 id="submittalType" style="font-weight:bold">
                @if($submittalType == "buyer-submittal")
                    Buyer Submittal
                @elseif($submittalType == "listing-submittal")
                    Listing Submittal
                @else
                    No Submittal Type Selected
                @endif
            </h4>
        </div>
        {{-- <div class = "actions d-flex">
            <a href="{{ url('/pipeline-view/' . (isset($submittal['dealData']['id']) ? $submittal['dealData']['id'] : '')) }}">
                <div class="input-group-text text-white justify-content-center ppipeBtn">
                    <i class="fas fa-times"></i>
                    Cancel
                </div>
            </a>
            <a>
                <div class="input-group-text text-white justify-content-center ppipeBtn"  onclick="return validateSubmittal(true)"><i class="fas fa-save">
                    </i>
                    Save
                </div>
            </a>
        </div> --}}
        
    </div>

    
    {{-- Listing Submittals--}}
    <div class="listingForm">

    </div>
    {{-- Listing Submittals--}}
    <div class="BuyerForm">

    </div>
</div>

@vite(['resources/js/pipeline.js'])

@section('pipelineScript')

@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    var showOtherListingForm = @json($listingSubmittaltype);
    var submittalId = @json($submittalId);
    $(document).ready(function(){
        var subForm = $('#submittalType').text().trim();
        console.log("subForm",subForm);
        if(subForm == "Listing Submittal"){
            getListingForm();
        }else if(subForm == "Buyer Submittal"){
            getBuyerForm();
        }
    })

    function getListingForm(){
        $.ajax({
            url: "/listing/form/"+submittalId+`?formType=${showOtherListingForm}`,
            type: 'GET',
            success: function (response) {
                $(".listingForm").html(response)
            },
            error: function (xhr, status, error) {
                // Handle error response
                console.error(xhr.responseText);
            }
        })
    }

    function getBuyerForm(){
        $.ajax({
            url: "/buyer/form/"+submittalId,
            type: 'GET',
            success: function (response) {
                $(".listingForm").html(response)
            },
            error: function (xhr, status, error) {
                // Handle error response
                console.error(xhr.responseText);
            }
        })
    }

    function convertInInteger(string) {
        try {
            console.log("String:", typeof string,string);
            if (string!='') {
                // Parse the string to a floating-point number
                let num = parseFloat(string);
                if (isNaN(num)) {
                    throw new Error("Conversion Error: Invalid input");
                }
                return num;
            }
            return null;
        } catch (error) {
            console.log(error.message);
            throw new Error(error.message);
        }
    }

    function isValidEmail(email) {
        var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
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

     window.validateSubmittal=function(isNew) {
        let submittal = @json($submittal);
        isValid = true;
        if(submittal.submittalType == 'buyer-submittal'){
            // Get values from Basic Info section
            var relatedTransaction = $('#relatedTransaction').val();
            var additionalEmailBuyer = $('#additionalEmailBuyer').val();
            var buyerPackage = $('#buyerPackage').val();
            var buyerMailoutNeeded = $('input[name="buyerMailoutNeeded"]:checked').val();
            var buyerClosingDate = $('#buyerClosingDate').val();
            var buyerPowerAttny = $('input[name="buyerPowerAttny"]:checked').val();
            var buyerincludeInsight = $('input[name="buyerincludeInsight"]:checked').val();
            var buyerLenderEmail = $('#buyerLenderEmail').val();
            var buyerLenderPhone = $('#buyerLenderPhone').val();
            var buyerFeesCharged = $('#buyerFeesCharged').val();
            var buyerTmName = $('#buyerTmName').val();
            var buyerAmountChr = $('#buyerAmountChr').val();
            var buyerOtherNotes = $('#buyerOtherNotes').val();
            var buyerRefrralPay = $('input[name="buyerRefrralPay"]:checked').val();
            var buyerRefrealDetails = $('#buyerRefrealDetails').val();

            if ((relatedTransaction && buyerPackage && buyerMailoutNeeded && buyerClosingDate && buyerPowerAttny && buyerTmName && buyerRefrralPay && buyerincludeInsight) !== '') {
                isValid = true
            } else {
                showToastError("Please fill in all the required fields.")
                isValid = false
            }

            if (buyerPackage=="New Construction"){
                 // Get values from New Construction
                var buyerBuilderrepresent = $('#buyerBuilderrepresent').val();
                var BuyerTitleCompany = $('#BuyerTitleCompany').val();
                var builderCommisionPercent = $('#builderCommisionPercent').val();
                var builderCommision = $('#builderCommision').val();
                var contractExecuted = $('#contractExecuted').val();
                var buyerAgency = $('#buyerAgency').val();
                if((buyerBuilderrepresent && BuyerTitleCompany && builderCommisionPercent && builderCommision && contractExecuted && buyerAgency ) !== '') {
                    isValid = true
                } else {
                    showToastError("Please fill New Construction all required fields.")
                    isValid = false
                }
            }

            if((additionalEmailBuyer!='')&&(!(isValidEmail(additionalEmailBuyer)))){
                showToastError("Additional Email for confirmation should be in email format")
                isValid = false
            }
            if((buyerLenderEmail!='')&&(!(isValidEmail(buyerLenderEmail)))){
                showToastError("Lender Email should be in email format")
                isValid = false
            }
            try {
                buyerFeesCharged = (buyerFeesCharged&&buyerFeesCharged!="")?convertInInteger(buyerFeesCharged):null;
                buyerAmountChr = (buyerAmountChr&&buyerAmountChr!="")?convertInInteger(buyerAmountChr):null;
            } catch (error) {
                isValid = false;
                showToastError(error.message)
                return false
            }
            console.log("isValid", isValid,additionalEmail);
            if(isValid == true){
                var formdata = {
                "data": [{
                        "Related_Transaction": {
                            "module": "Potentials",
                            "name": submittal.deal_data.deal_name,
                            "id": submittal.deal_data.zoho_deal_id
                        },
                        "Additional_Email_for_Confirmation": additionalEmailBuyer,
                        'Buyer_Package':buyerPackage,
                        'Mailout_Needed':buyerMailoutNeeded,
                        'Closing_Date':buyerClosingDate,
                        'Power_of_Attny_Needed':buyerPowerAttny,
                        'Include_Insights_in_Intro':buyerincludeInsight,
                        'Lender_Email':buyerLenderEmail,
                        'Lender_Phone':buyerLenderPhone,
                        'Fees_Charged_to_Buyer_at_Closing':buyerFeesCharged,
                        'TM_Name':buyerTmName,
                        'Amount_to_CHR_Gives':buyerAmountChr,
                        'Other_Important_Notes':buyerOtherNotes,
                        'Referral_to_Pay':buyerRefrralPay,
                        "Referral_Details": buyerRefrealDetails,
                        'Builder_Representative' : buyerBuilderrepresent,
                        'Title_Company_Closer_Info' : BuyerTitleCompany,
                        'Builder_Commission_and_or_flat_fee' : builderCommisionPercent,
                        'Builder_Commission_Based_On' : builderCommision,
                        'Contract_Fully_Executed' : contractExecuted,
                        'Buyer_Agency_Executed' : buyerAgency,
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
                    url: "/buyer/submittal/update/"+submittal.id+`?isNew=${isNew}`,
                    type: 'PUT',
                    contentType: 'application/json',
                    dataType: 'json',
                    data: JSON.stringify(formdata),
                    success: function (response) {
                        console.log("response",response);
                        showToast("Submittal updated successfully");
                        window.location.href = `{{ url('/pipeline-view/${submittal.deal_data.id}') }}`;
                    },
                    error: function (xhr, status, error) {
                        // Handle error response
                        console.error(xhr.responseText);
                        showToastError(xhr.responseText);
                    }
                })
            }
        }else if(submittal.submittalType == 'listing-submittal'){
            console.log("jasgdfjashj");
            // Get values from Basic Info section
            var transactionName = $('#transactionName').val();
            var additionalEmail = $('#additionalEmail').val();
            var agentName = $('#agentName').val();
            var commingSoon = $('input[name="commingSoon"]:checked').val();
            var comingSoonDate = $('#comingSoonDate').val();
            var tmName = $('#tmName').val();
            var activeDate = $('#activeDate').val();
            var agreementExecuted = $('input[name="agreementExecuted"]:checked').val();
            var price = $('#price').val();
            var photoDate = $('#photoDate').val();
            var photoURL = $('#photoURL').val();
            var bedsBathsTotal = $('#bedsBathsTotal').val();
            var tourURL = $('#tourURL').val();
            var usingCHR = $('input[name="usingCHR"]:checked').val();


            // Get values from CHR TM - Transaction Details and Preferences section
            var needOE = $('input[name="needO&E"]:checked').val();
            var hasHOA = $('input[name="hasHOA"]:checked').val();
            var includeInsights = $('input[name="includeInsights"]:checked').val();
            var titleToOrderHOA = $('input[name="titleToOrderHOA"]:checked').val();
            var mailoutNeeded = $('input[name="mailoutNeeded"]:checked').val();
            var powerOfAttnyNeeded =$('input[name="powerOfAttnyNeeded"]:checked').val();
            var hoaName = $('#hoaName').val();
            var hoaPhone = $('#hoaPhone').val();
            var hoaWebsite = $('#hoaWebsite').val();
            var miscNotes = $('#miscNotes').val();

            // Get values from CHR TM - Service Providers section
            var scheduleSignInstall = $('input[name="scheduleSignInstall"]:checked').val();
            var conciergeListing = $('input[name="conciergeListing"]:checked').val();
            var signInstallVendor = $('#signInstallVendor').val();
            var draftShowingInstructions = $('input[name="draftShowingInstructions"]:checked').val();
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
            var showPromotion = $('input[name="showPromotion"]:checked').val();
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
            var paragraph_200_words_4_page_brochure_or_look_book = $('#paragraph_200_words_4_page_brochure_or_look_book').val();
            var buyer_agent_compensation_offering  = $('#buyer_agent_compensation_offering').val();
            var stickyDots = $('#stickyDots').val();
            var qrCodeSheet = $('#qrCodeSheet').val();
            var qrCodeSignRider = $('#qrCodeSignRider').prop('checked');
            var qrCodeMainPanel = $('#qrCodeMainPanel').prop('checked');
            var featureCards = $('#featureCards').val();
            var featureCardCopy = $('#featureCardCopy').val();
            var brochureDeliveryDate = $('#brochureDeliveryDate').val();
            var deliveryAddress = $('#deliveryAddress').val();
            var printedItemsPickupDate = $('#printedItemsPickupDate').val();
            var brochurePickupDate = $('#brochurePickupDate').val();
            // Select all div elements
            
                if (
                    (!transactionName&&transactionName=="" ) ||
                    (!agentName &&agentName=="" ) ||
                    (!commingSoon &&commingSoon=="" ) ||
                    (!tmName &&tmName=="" ) ||
                    (!activeDate &&activeDate=="" ) ||
                    (!agreementExecuted &&agreementExecuted=="" ) ||
                    (!price &&price=="" ) ||
                    (!bedsBathsTotal &&bedsBathsTotal=="" ) ||
                    (!usingCHR &&usingCHR=="" ) ||
                    (!needOE &&needOE=="") ||
                    (!includeInsights &&includeInsights=="" ) ||
                    (!powerOfAttnyNeeded &&powerOfAttnyNeeded=="" ) ||
                    (!mailoutNeeded &&mailoutNeeded=="" ) ||
                    (!hasHOA &&hasHOA=="" )||
                    (!scheduleSignInstall &&scheduleSignInstall=="" ) ||
                    (!closerNamePhone &&closerNamePhone=="" ) ||
                    (!draftShowingInstructions &&draftShowingInstructions=="" ) ||
                    (!showPromotion &&showPromotion=="" ) 
                ) {
                    isValid = false
                    showToastError('Please fill all required fields');
                }

                if(hasHOA &&hasHOA=="Yes"){
                    if(
                        (!titleToOrderHOA &&titleToOrderHOA=="") ||
                        (!hoaName &&hoaName=="") ||
                        (!hoaPhone &&hoaPhone=="") ||
                        (!hoaWebsite &&hoaWebsite=="") 
                    ){
                        isValid = false
                        showToastError('Please fill all required fields');
                    }
                }

                if(scheduleSignInstall &&scheduleSignInstall=="Other"){
                    if(
                        (!signInstallVendorOther &&signInstallVendorOther=="") 
                    ){
                        isValid = false
                        showToastError('Please enter Sign Install Vendor (if Other)');
                    }
                }

                if(scheduleSignInstall &&scheduleSignInstall=="Others"){
                    if(
                        (!signInstallVendorOther &&signInstallVendorOther=="") 
                    ){
                        isValid = false
                        showToastError('Please enter Sign Install Vendor (if Other)');
                    }
                }
                

            if((additionalEmail!='')&&(!(isValidEmail(additionalEmail)))){
                showToastError("Additional Email for confirmation should be in email format")
                isValid = false
            }
            console.log("photo url", isValidUrl(photoURL));
            if((photoURL!='')&&(!(isValidUrl(photoURL)))){
                showToastError("Photo URL should be in URL format")
                isValid = false
            }
            if((tourURL!='')&&(!(isValidUrl(tourURL)))){
                showToastError("3D Tour URL should be in URL format")
                isValid = false
            }

            try {
                console.log("price,amount to chr, fees changes",price,amountToCHR,feesCharged);
                price = (price&&price!='')?convertInInteger(price):null;
                amountToCHR = (amountToCHR&&amountToCHR!='')?convertInInteger(amountToCHR):null;
                feesCharged = (feesCharged&&feesCharged!='')?convertInInteger(feesCharged):null;
            } catch (error) {
                console.log(error);
                isValid = false;
                showToastError(error.message) 
            }
            console.log("isValid", isValid,additionalEmail);
            if(isValid == true){
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
                        "MLS_Public_Remarks":mlsPublicRemarks,
                        "Feature_Cards_or_Sheets": featureCards,
                        "Sticky_Dots": stickyDots,
                        "Brochure_Line": brochureLine,
                        "Select_your_prints": brochurePrint,
                        "HOA_Website": hoaWebsite,
                        "HOA_Website": hoaWebsite,
                        "Photo_URL": photoURL,
                        "3D_Tour_URL":tourURL,
                        "Closer_Name_Phone": closerNamePhone,
                        "Listing_Agreement_Executed": agreementExecuted,
                        "Sign_Install_Vendor_if_Other": signInstallVendorOther,
                        "D_Zillow_Tour": threeDZillowTour,
                        "Email_Blast_to_Reverse_Prospect_List": emailBlastReverseProspect,
                        "Social_Media_Ads": socialMediaAds,
                        "QR_Code_Sign_Rider": qrCodeSignRider,
                        "QR_Code_Main_Panel": qrCodeMainPanel,
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
                        "Concierge_Listing_Optional":conciergeListing,
                        "Draft_Showing_Instructions1":draftShowingInstructions,
                        "Floor_Plans":floorPlans,
                        "Onsite_Video":onsiteVideo,
                        "Custom_Domain_Name":customDomainName,
                        "bullets_4_words_per_bullet":bullets,
                        "Paragraph_200_Words_4_Page_Brochure_or_Look_Book":paragraph_200_words_4_page_brochure_or_look_book,
                        "Buyer_Agent_Compensation_Offering":buyer_agent_compensation_offering,
                        "In_House_Printed_Brochure_Pick_Up_Date":printedItemsPickupDate,
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
                        "showPromotion":showPromotion
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
                    url: "/listing/submittal/update/"+submittal.id+`?isNew=${isNew}`,
                    type: 'PUT',
                    contentType: 'application/json',
                    dataType: 'json',
                    data: JSON.stringify(formdata),
                    success: function (response) {
                        console.log("response",response);
                        showToast("Listing Submittal updated successfully");
                        window.location.href = "/pipeline-view/" + submittal['deal_data']['id'];
                    },
                    error: function (xhr, status, error) {
                        // Handle error response
                        console.error(xhr.responseText);
                        showToastError(xhr.responseText);
                    }
                })
            }
        }
    }
   
</script>
@endsection