@extends('layouts.master')

@section('title', 'Agent Commander | Submittals Create')

@section('content')
@vite(['resources/css/pipeline.css'])
@vite(['resources/js/toast.js'])

<div class="container-fluid">
    <div class="submittaldiv">
        <a>
            <div class="input-group-text text-white justify-content-center ppipeBtn" id="savebutton" data-bs-toggle="modal" data-bs-target="#"><i class="fas fa-save">
                </i>
                Cancel
            </div>
        </a>
        <a>
            <div class="input-group-text text-white justify-content-center ppipeBtn" id="savebutton" data-bs-toggle="modal" data-bs-target="#"><i class="fas fa-save">
                </i>
                Save and New
            </div>
        </a>
        <a>
            <div class="input-group-text text-white justify-content-center ppipeBtn" id="savebutton" data-bs-toggle="modal" data-bs-target="#" onclick="validateSubmittal({{$submittal}})"><i class="fas fa-save">
                </i>
                Save
            </div>
        </a>
    </div>

    <div class="submittalType">
        <label for="submittalType" class="form-label nplabelText">Submittal Type</label>
        {{--<input type="text" placeholder="Enter Client’s name" class="form-control npinputinfo" id="leadAgent"
            required value="{{ $deal['client_name_primary'] }}">--}}
        <select class="form-select npinputinfo" id="submittalType" onchange="showSubmittalForm(this)">
            <option value="Buyer Submittal" {{$submittalType=="buyer-submittal" ?'selected':''}}>Buyer Submittal
            </option>
            <option value="Listing Submittal" {{$submittalType=="listing-submittal" ?'selected':''}}>Listing
                Submittal</option>
        </select>

    </div>
    {{-- Listing Submittals--}}
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
                    <input type="text" class="form-control npinputinfo" id="additionalEmail" required value="">
                </div>
                <div class="col-md-6">
                    <label for="agentName" class="form-label nplabelText">Agent Name on Material</label>
                    <input type="text" class="form-control npinputinfo validate" id="agentName" required value="">
                </div>
                <div class="col-md-6">
                    <label for="commingSoon" class="form-label nplabelText">Coming Soon?</label>
                    <select class="form-select npinputinfo validate" id="commingSoon">
                        <option value="Yes">Yes
                        </option>
                        <option value="No">No</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="comingSoonDate" class="form-label nplabelText">Coming Soon MLS date</label>
                    <input type="date" class="form-control npinputinfo " id="comingSoonDate" required value="">
                </div>
                <div class="col-md-6">
                    <label for="tmName" class="form-label nplabelText">TM Name</label>
                    <input type="text" class="form-control npinputinfo validate" id="tmName" required value="{{$submittal['dealData']['tm_name']}}">
                </div>

                <div class="col-md-6">
                    <label for="activeDate" class="form-label nplabelText">Active Date</label>
                    <input type="date" class="form-control npinputinfo validate" id="activeDate" required value="">
                </div>
                <div class="col-md-6">
                    <label for="agreementExecuted" class="form-label nplabelText">Listing Agreement Executed? </label>
                    <select class="form-select npinputinfo validate" id="agreementExecuted">
                        <option value="Yes">Yes
                        </option>
                        <option value="No">No</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="price" class="form-label nplabelText">Price</label>
                    <input type="text" class="form-control npinputinfo validate" id="price" required value="$">
                </div>
                <div class="col-md-6">
                    <label for="photoDate" class="form-label nplabelText">Photo Date</label>
                    <input type="date" class="form-control npinputinfo " id="photoDate" required value="">
                </div>
                <div class="col-md-6">
                    <label for="photoURL" class="form-label nplabelText">Photo URL</label>
                    <input type="text" class="form-control npinputinfo" id="photoURL" required value="">
                </div>
                <div class="col-md-6">
                    <label for="bedsBathsTotal" class="form-label nplabelText">Beds,baths,total sq.ft.</label>
                    <input type="text" class="form-control npinputinfo validate" id="bedsBathsTotal" required value="">
                </div>
                <div class="col-md-6">
                    <label for="tourURL" class="form-label nplabelText">3D Tour URL</label>
                    <input type="text" class="form-control npinputinfo" id="tourURL" required value="">
                </div>
                <div class="col-md-6">
                    <label for="usingCHR" class="form-label nplabelText">Using CHR TM </label>
                    <select class="form-select npinputinfo validate" id="usingCHR">
                        <option value="Yes">Yes
                        </option>
                        <option value="No">No</option>
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
                        <option value="Yes">Yes
                        </option>
                        <option value="No">No</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="hasHOA" class="form-label nplabelText">Has HOA?</label>
                    <select class="form-select npinputinfo" id="hasHOA">
                        <option value="Yes">Yes
                        </option>
                        <option value="No">No</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="includeInsights" class="form-label nplabelText">Include Insights in Intro?</label>
                    <select class="form-select npinputinfo" id="includeInsights">
                        <option value="Yes">Yes
                        </option>
                        <option value="No">No</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="titleToOrderHOA" class="form-label nplabelText">Title to Order HOA docs?</label>
                    <select class="form-select npinputinfo" id="titleToOrderHOA">
                        <option value="Yes">Yes
                        </option>
                        <option value="No">No</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="mailoutNeeded" class="form-label nplabelText">Mailout Needed?</label>
                    <select class="form-select npinputinfo" id="mailoutNeeded">
                        <option value="Yes">Yes
                        </option>
                        <option value="No">No</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="powerOfAttnyNeeded" class="form-label nplabelText">Power of Attny Needed?</label>
                    <select class="form-select npinputinfo" id="powerOfAttnyNeeded">
                        <option value="Yes">Yes
                        </option>
                        <option value="No">No</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="hoaName" class="form-label nplabelText">HOA Name</label>
                    <input type="text" class="form-control npinputinfo" id="hoaName" required value="">
                </div>
                <div class="col-md-6">
                    <label for="hoaPhone" class="form-label nplabelText">HOA Phone</label>
                    <input type="text" class="form-control npinputinfo" id="hoaPhone" required value="">
                </div>
                <div class="col-md-12">
                    <label for="hoaWebsite" class="form-label nplabelText">HOA Website</label>
                    <input type="text" class="form-control npinputinfo" id="hoaWebsite" required value="">
                </div>
                <div class="col-md-12">
                    <label for="miscNotes" class="form-label nplabelText">Misc Notes - Seller, Communication,
                        etc</label>
                    <textarea class="form-control" id="miscNotes" aria-label="With textarea"></textarea>
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
                        <option value="Yes">Yes
                        </option>
                        <option value="No">No</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="conciergeListing" class="form-label nplabelText">Concierge Listing (Optional)</label>
                    <select class="form-select npinputinfo" id="conciergeListing">
                        <option value="Yes">Yes
                        </option>
                        <option value="No">No</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="signInstallVendor" class="form-label nplabelText">Sign Install Vendor Info</label>
                    <select class="form-select npinputinfo" id="signInstallVendor" required>
                        <option selected disabled value="">Please Select</option>
                        <option value="AXIUM">AXIUM</option>
                        <option value="Rocky Mountain - Brandon">Rocky Mountain - Brandon</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="draftShowingInstructions" class="form-label nplabelText">Draft Showing
                        Instructions?</label>
                    <select class="form-select npinputinfo" id="draftShowingInstructions">
                        <option value="Yes">Yes
                        </option>
                        <option value="No">No</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="titleCompany" class="form-label nplabelText">Title Company</label>
                    <input type="text" class="form-control npinputinfo" id="titleCompany" required value="">
                </div>
                <div class="col-md-6">
                    <label for="closerNamePhone" class="form-label nplabelText">Closer Name & Phone</label>
                    <input type="text" class="form-control npinputinfo" id="closerNamePhone" required value="">
                </div>
                <div class="col-md-12">
                    <label for="signInstallVendorOther" class="form-label nplabelText">Sign Install Vendor (if
                        Other)</label>
                    <input type="text" class="form-control npinputinfo" id="signInstallVendorOther" required value="">
                </div>
                <div class="col-md-12">
                    <label for="signInstallDate" class="form-label nplabelText">Sign Install Date</label>
                    <input type="date" class="form-control npinputinfo" id="signInstallDate" required value="">
                </div>

            </form>
        </div>
        {{--CHR TM - Select MLS--}}
        <div class="col-md-12 col-sm-24" style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03);">

            <p class="npinfoText">CHR TM - Select MLS</p>
            <form class="row g-3">
                <div class="col-md-6">
                    <label for="reColorado" class="form-label nplabelText">REColorado</label>
                    <input type="checkbox" id="reColorado" required value="">
                </div>

                <div class="col-md-6">
                    <label for="navica" class="form-label nplabelText">Navica</label>
                    <input type="checkbox" id="navica" required value="">
                </div>
                <div class="col-md-6">
                    <label for="ppar" class="form-label nplabelText">PPAR</label>
                    <input type="checkbox" id="ppar" required value="">
                </div>
                <div class="col-md-6">
                    <label for="grandCounty" class="form-label nplabelText">Grand County</label>
                    <input type="checkbox" id="grandCounty" required value="">
                </div>

                <div class="col-md-6">
                    <label for="ires" class="form-label nplabelText">IRES</label>
                    <input type="checkbox" id="ires" required value="">
                </div>
                <div class="col-md-12">
                    <label for="mlsPrivateRemarks" class="form-label nplabelText">MLS Private Remarks</label>
                    <textarea class="form-control" id="mlsPrivateRemarks" aria-label="With textarea"></textarea>
                </div>
                <div class="col-md-12">
                    <label for="mlsPublicRemarks" class="form-label nplabelText">MLS Public Remarks</label>
                    <textarea class="form-control" id="mlsPublicRemarks" aria-label="With textarea"></textarea>
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
                    <input type="text" class="form-control npinputinfo" id="feesCharged" required value="$">
                </div>

                <div class="col-md-6">
                    <label for="referralToPay" class="form-label nplabelText">Referral to Pay</label>

                    <select class="form-select npinputinfo" id="referralToPay">
                        <option value="">None
                        </option>
                        <option value="Yes">Yes
                        </option>
                        <option value="No">No</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="amountToCHR" class="form-label nplabelText">Amount to CHR Gives</label>
                    <input type="text" class="form-control npinputinfo" id="amountToCHR" required value="$">
                </div>
                <div class="col-md-6">
                    <label for="referralDetails" class="form-label nplabelText">Referral Details</label>
                    <input type="text" class="form-control npinputinfo" id="referralDetails" required value="">
                </div>

            </form>
        </div>
        {{--PROPERTY PROMOTION - Outside Services--}}
        <div class="col-md-12 col-sm-24 promotionOutside" style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03); display:none">

            <p class="npinfoText">PROPERTY PROMOTION - Outside Services</p>
            <form class="row g-3">
                <div class="col-md-6">
                    <label for="matterport" class="form-label nplabelText">Matterport</label>
                    <input type="checkbox" id="matterport" required value="">
                </div>

                <div class="col-md-6">
                    <label for="floorPlans" class="form-label nplabelText">Floor Plans</label>
                    <input type="checkbox" id="floorPlans" required value="">
                </div>
                <div class="col-md-6">
                    <label for="threeDZillowTour" class="form-label nplabelText">3D Zillow Tour</label>
                    <input type="checkbox" id="threeDZillowTour" required value="">
                </div>
                <div class="col-md-6">
                    <label for="onsiteVideo" class="form-label nplabelText">Onsite Video</label>
                    <input type="checkbox" id="onsiteVideo" required value="">
                </div>

            </form>
        </div>
        {{--PROPERTY PROMOTION - Marketing Items--}}
        <div class="col-md-12 col-sm-24 promotionMarket" style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03); display:none">

            <p class="npinfoText">PROPERTY PROMOTION - Marketing Items</p>
            <form class="row g-3">
                <div class="col-md-6">
                    <label for="propertyWebsite" class="form-label nplabelText">Property Website</label>
                    <input type="checkbox" id="propertyWebsite" required value="">
                </div>

                <div class="col-md-6">
                    <label for="emailBlastSphere" class="form-label nplabelText">Email Blast to Sphere</label>
                    <input type="checkbox" id="emailBlastSphere" required value="">
                </div>
                <div class="col-md-6">
                    <label for="emailBlastReverseProspect" class="form-label nplabelText">Email Blast to Reverse
                        Prospect
                        List</label>
                    <input type="checkbox" id="emailBlastReverseProspect" required value="">
                </div>
                <div class="col-md-6">
                    <label for="propertyHighlightVideo" class="form-label nplabelText">Property Highlight Video</label>
                    <input type="checkbox" id="propertyHighlightVideo" required value="">
                </div>
                <div class="col-md-6">
                    <label for="socialMediaImages" class="form-label nplabelText">Social Media Images</label>
                    <input type="checkbox" id="socialMediaImages" required value="">
                </div>
                <div class="col-md-6">
                    <label for="socialMediaAds" class="form-label nplabelText">Social Media Ads</label>
                    <input type="checkbox" id="socialMediaAds" required value="">
                </div>
                <div class="col-md-12">
                    <label for="priceImprovementPackage" class="form-label nplabelText">Price Improvement
                        Package</label>
                    <input type="checkbox" id="priceImprovementPackage" required value="">
                </div>
                <div class="col-md-12">
                    <label for="customDomainName" class="form-label nplabelText">Custom Domain Name</label>
                    <input type="text" class="form-control npinputinfo" id="customDomainName" required value="">
                </div>
                <div class="col-md-12">
                    <label for="featuresNeededForVideo" class="form-label nplabelText">8-12 Features Needed for
                        Video</label>
                    <textarea class="form-control" id="featuresNeededForVideo" aria-label="With textarea"></textarea>
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
                    <textarea class="form-control" id="marketingNotes" aria-label="With textarea"></textarea>
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
                        <option value="Yes">Yes
                        </option>
                        <option value="No">No</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="brochurePrint" class="form-label nplabelText">Brochure - Print, Deliver or PDF</label>
                    <select class="form-select npinputinfo" id="brochurePrint">
                        <option value="Yes">Yes
                        </option>
                        <option value="No">No</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="bullets" class="form-label nplabelText">12 bullets, 4 words per bullet</label>
                    <textarea class="form-control" id="bullets" aria-label="With textarea"></textarea>
                </div>
                <div class="col-md-6">
                    <label for="headlineForBrochure" class="form-label nplabelText">4 Word Headline - If Opting for
                        A-Line
                        Brochure</label>
                    <input type="text" class="form-control npinputinfo" id="headlineForBrochure" required value="">
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
                    <input type="checkbox" id="qrCodeSignRider" required value="">
                </div>
                <div class="col-md-6">
                    <label for="featureCards" class="form-label nplabelText">Feature Cards</label>
                    <select class="form-select npinputinfo " id="featureCards">
                        <option value="">--None--</option>
                        <option value="Print 1 at Alamo - $1">Print 1 at Alamo - $1
                        </option>
                        <option value="Print 2 at Alamo - $2">Print 2 at Alamo - $2</option>
                        <option value="PDF - I'll Print it Myself">PDF - I'll Print it Myself</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="featureCardCopy" class="form-label nplabelText">Add Feature Card Copy</label>
                    <input type="text" class="form-control npinputinfo" id="featureCardCopy" required value="">
                </div>
                <div class="col-md-6">
                    <label for="brochureDeliveryDate" class="form-label nplabelText">Delivery Only - Brochure
                        Date</label>
                    <input type="date" class="form-control npinputinfo" id="brochureDeliveryDate" required value="">
                </div>
                <div class="col-md-6">
                    <label for="deliveryAddress" class="form-label nplabelText">Delivery Only - Shipping Address &
                        Name</label>
                    <input type="text" class="form-control npinputinfo" id="deliveryAddress" required value="">
                </div>
                <div class="col-md-6">
                    <label for="printedItemsPickupDate" class="form-label nplabelText">Printed Items Pick Up or PDF
                        Date</label>
                    <input type="date" class="form-control npinputinfo" id="printedItemsPickupDate" required value="">
                </div>
                <div class="col-md-6">
                    <label for="brochurePickupDate" class="form-label nplabelText">Brochure Pick Up or PDF Date</label>
                    <input type="date" class="form-control npinputinfo" id="brochurePickupDate" required value="">
                </div>

            </form>
        </div>

    </div>

    {{-- Buyer Submittals--}}
    <div class="row " id="buyerSubmittal" style="display:none">
        <p>Buyer Submittal Information</p>
        <div class="col-md-12 col-sm-24" style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03);">
            <form class="row g-3" id="additionalFields">

                <div class="col-md-6 ">
                    <label for="validationDefault01" class="form-label nplabelText">Related Transaction</label>
                    {{--<input type="text" placeholder="Enter Client’s name" class="form-control npinputinfo"
                        id="validationDefault01" required value="{{ $deal['contactId'] }}">--}}
                    <select class="form-select npinputinfo" id="validationDefault01" required>
                        @foreach($deals as $currDeal)
                        <option value="{{$currDeal}}" {{ $currDeal['deal_name']? 'selected' : '' }}>
                            {{$currDeal['deal_name']}}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="validationDefault03" class="form-label nplabelText">Additional Email for
                        confirmation</label>
                    <input type="text" class="form-control npinputinfo validate" id="validationDefault03" required value="">
                </div>
                <div class="col-md-6">
                    <label for="validationDefault03" class="form-label nplabelText">Buyer Package</label>
                    <select class="form-select npinputinfo" id="submittalType">
                        <option value="">--None--
                        </option>
                        <option value="Standard">Standard
                        </option>
                        <option value="New Construction">New Construction</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="submittalType" class="form-label nplabelText">Mailout Needed</label>
                    {{--<input type="text" placeholder="Enter Client’s name" class="form-control npinputinfo"
                        id="leadAgent" required value="{{ $deal['client_name_primary'] }}">--}}
                    <select class="form-select npinputinfo" id="submittalType">
                        <option value="Yes">Yes
                        </option>
                        <option value="No">No</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="validationDefault03" class="form-label nplabelText">Closing Date</label>
                    <input type="date" class="form-control npinputinfo validate" id="validationDefault03" required value="">
                </div>
                <div class="col-md-6">
                    <label for="submittalType" class="form-label nplabelText">Power of Attny Needed?</label>
                    {{--<input type="text" placeholder="Enter Client’s name" class="form-control npinputinfo"
                        id="leadAgent" required value="{{ $deal['client_name_primary'] }}">--}}
                    <select class="form-select npinputinfo" id="submittalType">
                        <option value="Yes">Yes
                        </option>
                        <option value="No">No</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="validationDefault03" class="form-label nplabelText">Lender Email</label>
                    <input type="text" class="form-control npinputinfo validate" id="validationDefault03" required value="">
                </div>

                <div class="col-md-6">
                    <label for="submittalType" class="form-label nplabelText">Include Insights in Intro?</label>
                    {{--<input type="text" placeholder="Enter Client’s name" class="form-control npinputinfo"
                        id="leadAgent" required value="{{ $deal['client_name_primary'] }}">--}}
                    <select class="form-select npinputinfo" id="submittalType">
                        <option value="Yes">Yes
                        </option>
                        <option value="No">No</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="validationDefault03" class="form-label nplabelText">Lender Phone</label>
                    <input type="text" class="form-control npinputinfo validate" id="validationDefault03" required value="">
                </div>
                <div class="col-md-6">
                    <label for="validationDefault05" class="form-label nplabelText">Fees Charged to Buyer at
                        Closing</label>
                    <input type="text" class="form-control npinputinfo validate" id="validationDefault05" required value="$">
                </div>
                <div class="col-md-6">
                    <label for="validationDefault07" class="form-label nplabelText">TM Name</label>
                    <input type="text" class="form-control npinputinfo" id="validationDefault07" required value="">
                </div>
                <div class="col-md-6">
                    <label for="validationDefault03" class="form-label nplabelText">Amount to CHR Gives</label>
                    <input type="text" class="form-control npinputinfo validate" id="validationDefault05" required value="$">
                </div>

                <div class="col-md-6">
                    <label for="validationDefault08" class="form-label nplabelText">Other Important Notes</label>
                    <textarea class="form-control" aria-label="With textarea"></textarea>
                </div>
                <div class="col-md-6">
                    <label for="submittalType" class="form-label nplabelText">Referral to Pay</label>
                    {{--<input type="text" placeholder="Enter Client’s name" class="form-control npinputinfo"
                        id="leadAgent" required value="{{ $deal['client_name_primary'] }}">--}}
                    <select class="form-select npinputinfo" id="submittalType">
                        <option value="Yes">Yes
                        </option>
                        <option value="No">No</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="validationDefault09" class="form-label nplabelText">Referral Details</label>
                    {{-- <select class="form-select npinputinfo" id="validationDefault09" required>
                        <option selected disabled value=""></option>
                        <option>...</option>
                    </select> --}}
                    <input type="text" class="form-control npinputinfo" id="validationDefault09" required value="">
                </div>

            </form>
        </div>
        <div class="col-md-12 col-sm-24" style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03);">
            <p class="npinfoText">New Construction</p>
            <form class="row g-3">
                <div class="col-md-6">
                    <label for="validationDefault09" class="form-label nplabelText">Builder Representative</label>
                    <input type="text" class="form-control npinputinfo" id="validationDefault09" required value="">
                </div>
                <div class="col-md-6">
                    <label for="validationDefault09" class="form-label nplabelText">Title Company/Closer Info</label>
                    <input type="text" class="form-control npinputinfo" id="validationDefault09" required value="">
                </div>
                <div class="col-md-6">
                    <label for="validationDefault09" class="form-label nplabelText">Builder Commission (% and/or flat
                        fee)</label>
                    <input type="text" class="form-control npinputinfo" id="validationDefault09" required value="">
                </div>
                <div class="col-md-6">
                    <label for="submittalType" class="form-label nplabelText">Builder Commission Based On</label>
                    <select class="form-select npinputinfo" id="submittalType">
                        <option value="">--None--
                        </option>
                        <option value="Base Price">Base Price</option>
                        <option value="Flat Fee">Flat Fee</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="submittalType" class="form-label nplabelText">Contract Fully Executed</label>
                    {{--<input type="text" placeholder="Enter Client’s name" class="form-control npinputinfo"
                        id="leadAgent" required value="{{ $deal['client_name_primary'] }}">--}}
                    <select class="form-select npinputinfo" id="submittalType">
                        <option value="Yes">Yes
                        </option>
                        <option value="No">No</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="submittalType" class="form-label nplabelText">Buyer Agency Executed</label>
                    {{--<input type="text" placeholder="Enter Client’s name" class="form-control npinputinfo"
                        id="leadAgent" required value="{{ $deal['client_name_primary'] }}">--}}
                    <select class="form-select npinputinfo" id="submittalType">
                        <option value="Yes">Yes
                        </option>
                        <option value="No">No</option>
                    </select>
                </div>
            </form>
        </div>

    </div>

    @vite(['resources/js/pipeline.js'])

    @section('pipelineScript')

    @endsection
    <script>
        var showOtherListingForm;
        $(document).ready(function() {
            showOtherListingForm = @json($listingSubmittaltype);
            var subForm = $('#submittalType').val();
            if (subForm == "Listing Submittal") {
                $('#listingSubmittal').show();
                $('#buyerSubmittal').hide();
                if (showOtherListingForm!='null') {
                    console.log("SUBmittal form", showOtherListingForm);
                    $('.transactionForm').show();
                    $('.promotionOutside').show();
                    $('.promotionMarket').show();
                    $('.promotionNote').show();
                    $('.promotionPrint').show();
                    $('#broucherLine').addClass('validate');
                    $('#featureCards').addClass('validate');
                    $('#stickyDots').addClass('validate');

                } else {
                    $('.transactionForm').hide();
                    $('.promotionOutside').hide();
                    $('.promotionMarket').hide();
                    $('.promotionNote').hide();
                    $('.promotionPrint').hide();
                    $('#broucherLine').removeClass('validate');
                    $('#featureCards').removeClass('validate');
                    $('#stickyDots').removeClass('validate');
                }
            } else if (subForm == "Buyer Submittal") {
                $('#buyerSubmittal').show();
                $('#listingSubmittal').hide();
            }
            console.log(subForm);
        });

        function showSubmittalForm() {
            subForm = $('#submittalType').val()
            if (subForm == "Listing Submittal") {
                $('#listingSubmittal').show();
                $('#buyerSubmittal').hide();
            } else if (subForm == "Buyer Submittal") {
                $('#buyerSubmittal').show();
                $('#listingSubmittal').hide();
            }
            console.log(subForm);
        }
        function convertInInteger(string) {
           return parseInt(string.replace('$', ''))
        }

        function validateSubmittal(submittal) {
            isValid = false
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
            var reColorado = $('#reColorado').val();
            var navica = $('#navica').val();
            var ppar = $('#ppar').val();
            var grandCounty = $('#grandCounty').val();
            var ires = $('#ires').val();
            var mlsPrivateRemarks = $('#mlsPrivateRemarks').val();
            var mlsPublicRemarks = $('#mlsPublicRemarks').val();

            // Get values from CHR TM - Commission Details section
            var feesCharged = $('#feesCharged').val();
            var referralToPay = $('#referralToPay').val();
            var amountToCHR = $('#amountToCHR').val();
            var referralDetails = $('#referralDetails').val();

            // Get values from PROPERTY PROMOTION - Outside Services
            var matterport = $('#matterport').val();
            var floorPlans = $('#floorPlans').val();
            var threeDZillowTour = $('#threeDZillowTour').val();
            var onsiteVideo = $('#onsiteVideo').val();

            // Get values from PROPERTY PROMOTION - Marketing Items
            var propertyWebsite = $('#propertyWebsite').val();
            var emailBlastSphere = $('#emailBlastSphere').val();
            var emailBlastReverseProspect = $('#emailBlastReverseProspect').val();
            var propertyHighlightVideo = $('#propertyHighlightVideo').val();
            var socialMediaImages = $('#socialMediaImages').val();
            var socialMediaAds = $('#socialMediaAds').val();
            var priceImprovementPackage = $('#priceImprovementPackage').val();
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
            var qrCodeSignRider = $('#qrCodeSignRider').val();
            var featureCards = $('#featureCards').val();
            var featureCardCopy = $('#featureCardCopy').val();
            var brochureDeliveryDate = $('#brochureDeliveryDate').val();
            var deliveryAddress = $('#deliveryAddress').val();
            var printedItemsPickupDate = $('#printedItemsPickupDate').val();
            var brochurePickupDate = $('#brochurePickupDate').val();
            if ((transactionName && agentName && commingSoon && tmName && activeDate && agreementExecuted && price && bedsBathsTotal && usingCHR) !== '') {
                isValid = true
                if(showOtherListingForm!="null") {
                    if ((stickyDots && featureCards && brochureLine) !== ""){
                    isValid = true
                } else {
                    showToastError("Please fill in all the required fields in the PROPERTY PROMOTION - Print Requests section.")
                }
            }
            } else {
                showToastError("Please fill in all the required fields.")
            }
            console.log("isValid", isValid);
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
                        "HOA_Website": hoaWebsite,
                        "Photo_URL": photoURL,
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
                        "IRES": ires,
                        "Price": convertInInteger(price),
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
                        "Amount_to_CHR_Gives": convertInInteger(amountToCHR),
                        "REColorado": reColorado,
                        "Active_Date": activeDate,
                        "Need_O_E1": needOE,
                        "Sign_Install_Vendor_Info": signInstallVendor,
                        "Delivery_Only_Shipping_Address_Name": deliveryAddress,
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
                    url: "/listing/submittal/update/"+submittal.zoho_submittal_id,
                    type: 'PUT',
                    contentType: 'application/json',
                    dataType: 'json',
                    data: JSON.stringify(formdata),
                    success: function (response) {
                        console.log("response",response);
                        redirectUrl(type,response,formType)
                        if (response?.data && response.data[0]?.message) {
                            // Convert message to uppercase and then display
                            const upperCaseMessage = response.data[0].message.toUpperCase();
                            showToast(upperCaseMessage);
                            // window.location.reload();
                        }
                    },
                    error: function (xhr, status, error) {
                        // Handle error response
                        console.error(xhr.responseText);
                    }
                })
            }
        }
    </script>
    @endsection