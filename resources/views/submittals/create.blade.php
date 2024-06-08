@extends('layouts.master')

@section('title', 'Agent Commander | Submittals Create')

@section('content')
@vite(['resources/css/pipeline.css'])
@vite(['resources/js/toast.js'])

<div class="container-fluid">
    <div class="submittaldiv">
        <a>
            <div class="input-group-text text-white justify-content-center ppipeBtn" id="savebutton"
                data-bs-toggle="modal" data-bs-target="#"><i class="fas fa-save">
                </i>
                Cancel
            </div>
        </a>
        <a>
            <div class="input-group-text text-white justify-content-center ppipeBtn" id="savebutton"
                data-bs-toggle="modal" data-bs-target="#"><i class="fas fa-save">
                </i>
                Save and New
            </div>
        </a>
        <a>
            <div class="input-group-text text-white justify-content-center ppipeBtn" id="savebutton"
                data-bs-toggle="modal" data-bs-target="#"><i class="fas fa-save">
                </i>
                Save
            </div>
        </a>
    </div>

    <div class="submittalType">
        <label for="submittalType" class="form-label nplabelText">Submittal Type</label>
        {{--<input type="text" placeholder="Enter Client’s name" class="form-control npinputinfo" id="leadAgent"
            required value="{{ $deal['client_name_primary'] }}">--}}
        <select class="form-select npinputinfo" id="submittalType" onchange="showForm(this)">
            <option value="Buyer Submittal" {{$submittalType=="Buyer" ?'selected':''}}>Buyer Submittal
            </option>
            <option value="Listing Submittal" {{$submittalType=="Listing" ?'selected':''}}>Listing
                Submittal</option>
        </select>

    </div>
    {{-- Listing Submittals--}}
    <div class="row " id="listingSubmittal" style="display:none">
        <p>Listing Submittal Information</p>
        {{-- Basic Info --}}
        <div class="col-md-12 col-sm-24"
            style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03);">
            <p class="npinfoText">Basic Info</p>
            <form class="row g-3" id="additionalFields">
                <div class="col-md-6 ">
                    <label for="validationDefault01" class="form-label nplabelText">Transaction Name</label>
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
                    <input type="text" class="form-control npinputinfo validate" id="validationDefault03" required
                        value="">
                </div>
                <div class="col-md-6">
                    <label for="validationDefault03" class="form-label nplabelText">Agent Name on Material</label>
                    <input type="text" class="form-control npinputinfo validate" id="validationDefault03" required
                        value="">
                </div>
                <div class="col-md-6">
                    <label for="submittalType" class="form-label nplabelText">Coming Soon?</label>
                    {{--<input type="text" placeholder="Enter Client’s name" class="form-control npinputinfo"
                        id="leadAgent" required value="{{ $deal['client_name_primary'] }}">--}}
                    <select class="form-select npinputinfo" id="submittalType">
                        <option value="Yes">Yes
                        </option>
                        <option value="No">No</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="validationDefault03" class="form-label nplabelText">Coming Soon MLS date</label>
                    <input type="date" class="form-control npinputinfo validate" id="validationDefault03" required
                        value="">
                </div>
                <div class="col-md-6">
                    <label for="validationDefault03" class="form-label nplabelText">TM Name</label>
                    <input type="text" class="form-control npinputinfo validate" id="validationDefault03" required
                        value="">
                </div>

                <div class="col-md-6">
                    <label for="validationDefault03" class="form-label nplabelText">Active Date</label>
                    <input type="date" class="form-control npinputinfo validate" id="validationDefault03" required
                        value="">
                </div>
                <div class="col-md-6">
                    <label for="submittalType" class="form-label nplabelText">Listing AgreementExecuted? </label>
                    {{--<input type="text" placeholder="Enter Client’s name" class="form-control npinputinfo"
                        id="leadAgent" required value="{{ $deal['client_name_primary'] }}">--}}
                    <select class="form-select npinputinfo" id="submittalType">
                        <option value="Yes">Yes
                        </option>
                        <option value="No">No</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="validationDefault05" class="form-label nplabelText">Price</label>
                    <input type="text" class="form-control npinputinfo validate" id="validationDefault05" required
                        value="$">
                </div>
                <div class="col-md-6">
                    <label for="validationDefault03" class="form-label nplabelText">Photo Date</label>
                    <input type="date" class="form-control npinputinfo validate" id="validationDefault03" required
                        value="">
                </div>
                <div class="col-md-6">
                    <label for="validationDefault07" class="form-label nplabelText">Photo URL</label>
                    <input type="text" class="form-control npinputinfo" id="validationDefault07" required value="">
                </div>
                <div class="col-md-6">
                    <label for="validationDefault08" class="form-label nplabelText">Beds,baths,total sq.ft.</label>
                    <input type="text" class="form-control npinputinfo" id="validationDefault08" required value="">
                </div>
                <div class="col-md-6">
                    <label for="validationDefault09" class="form-label nplabelText">3D Tour URL</label>
                    {{-- <select class="form-select npinputinfo" id="validationDefault09" required>
                        <option selected disabled value=""></option>
                        <option>...</option>
                    </select> --}}
                    <input type="text" class="form-control npinputinfo" id="validationDefault09" required value="">
                </div>
                <div class="col-md-6">
                    <label for="submittalType" class="form-label nplabelText">Using CHR TM </label>
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
        <div class="col-md-12 col-sm-24"
            style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03);">

            <p class="npinfoText">CHR TM - Transaction Details and Preferences</p>
            <form class="row g-3">
                <div class="col-md-6">
                    <label for="submittalType" class="form-label nplabelText">Need O&E</label>
                    {{--<input type="text" placeholder="Enter Client’s name" class="form-control npinputinfo"
                        id="leadAgent" required value="{{ $deal['client_name_primary'] }}">--}}
                    <select class="form-select npinputinfo" id="submittalType">
                        <option value="Yes">Yes
                        </option>
                        <option value="No">No</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="submittalType" class="form-label nplabelText">Has HOA?</label>
                    {{--<input type="text" placeholder="Enter Client’s name" class="form-control npinputinfo"
                        id="leadAgent" required value="{{ $deal['client_name_primary'] }}">--}}
                    <select class="form-select npinputinfo" id="submittalType">
                        <option value="Yes">Yes
                        </option>
                        <option value="No">No</option>
                    </select>
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
                    <label for="submittalType" class="form-label nplabelText">Title to Order HOA docs?</label>
                    {{--<input type="text" placeholder="Enter Client’s name" class="form-control npinputinfo"
                        id="leadAgent" required value="{{ $deal['client_name_primary'] }}">--}}
                    <select class="form-select npinputinfo" id="submittalType">
                        <option value="Yes">Yes
                        </option>
                        <option value="No">No</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="submittalType" class="form-label nplabelText">Mailout Needed?</label>
                    {{--<input type="text" placeholder="Enter Client’s name" class="form-control npinputinfo"
                        id="leadAgent" required value="{{ $deal['client_name_primary'] }}">--}}
                    <select class="form-select npinputinfo" id="submittalType">
                        <option value="Yes">Yes
                        </option>
                        <option value="No">No</option>
                    </select>
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
                    <label for="validationDefault09" class="form-label nplabelText">HOA Name</label>
                    {{-- <select class="form-select npinputinfo" id="validationDefault09" required>
                        <option selected disabled value=""></option>
                        <option>...</option>
                    </select> --}}
                    <input type="text" class="form-control npinputinfo" id="validationDefault09" required value="">
                </div>
                <div class="col-md-6">
                    <label for="validationDefault09" class="form-label nplabelText">HOA Phone</label>
                    {{-- <select class="form-select npinputinfo" id="validationDefault09" required>
                        <option selected disabled value=""></option>
                        <option>...</option>
                    </select> --}}
                    <input type="text" class="form-control npinputinfo" id="validationDefault09" required value="">
                </div>
                <div class="col-md-12">
                    <label for="validationDefault09" class="form-label nplabelText">HOA Website</label>
                    {{-- <select class="form-select npinputinfo" id="validationDefault09" required>
                        <option selected disabled value=""></option>
                        <option>...</option>
                    </select> --}}
                    <input type="text" class="form-control npinputinfo" id="validationDefault09" required value="">
                </div>
                <div class="col-md-12">
                    <label for="validationDefault09" class="form-label nplabelText">Misc Notes - Seller, Communication,
                        etc</label>
                    <textarea class="form-control" aria-label="With textarea"></textarea>
                </div>

            </form>
        </div>
        <div class="col-md-12 col-sm-24"
            style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03);">

            <p class="npinfoText">CHR TM - Service Providers</p>
            <form class="row g-3">
                <div class="col-md-6">
                    <label for="submittalType" class="form-label nplabelText">Schedule Sign Install</label>
                    {{--<input type="text" placeholder="Enter Client’s name" class="form-control npinputinfo"
                        id="leadAgent" required value="{{ $deal['client_name_primary'] }}">--}}
                    <select class="form-select npinputinfo" id="submittalType">
                        <option value="Yes">Yes
                        </option>
                        <option value="No">No</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="submittalType" class="form-label nplabelText">Concierge Listing (Optional)</label>
                    {{--<input type="text" placeholder="Enter Client’s name" class="form-control npinputinfo"
                        id="leadAgent" required value="{{ $deal['client_name_primary'] }}">--}}
                    <select class="form-select npinputinfo" id="submittalType">
                        <option value="Yes">Yes
                        </option>
                        <option value="No">No</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="submittalType" class="form-label nplabelText">Sign Install Vendor Info</label>
                    <select class="form-select npinputinfo" id="validationDefault09" required>
                        <option selected disabled value="">Please Select</option>
                        <option value="AXIUM">AXIUM</option>
                        <option value="Rocky Mountain - Brandon">Rocky Mountain - Brandon</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="submittalType" class="form-label nplabelText">Draft Showing Instructions?</label>
                    {{--<input type="text" placeholder="Enter Client’s name" class="form-control npinputinfo"
                        id="leadAgent" required value="{{ $deal['client_name_primary'] }}">--}}
                    <select class="form-select npinputinfo" id="submittalType">
                        <option value="Yes">Yes
                        </option>
                        <option value="No">No</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="validationDefault09" class="form-label nplabelText">Title Company</label>
                    {{-- <select class="form-select npinputinfo" id="validationDefault09" required>
                        <option selected disabled value=""></option>
                        <option>...</option>
                    </select> --}}
                    <input type="text" class="form-control npinputinfo" id="validationDefault09" required value="">
                </div>
                <div class="col-md-6">
                    <label for="validationDefault09" class="form-label nplabelText">Closer Name & Phone</label>
                    {{-- <select class="form-select npinputinfo" id="validationDefault09" required>
                        <option selected disabled value=""></option>
                        <option>...</option>
                    </select> --}}
                    <input type="text" class="form-control npinputinfo" id="validationDefault09" required value="">
                </div>
                <div class="col-md-12">
                    <label for="validationDefault09" class="form-label nplabelText">Sign Install Vendor (if
                        Other)</label>
                    <input type="text" class="form-control npinputinfo" id="validationDefault09" required value="">
                </div>
                <div class="col-md-12">
                    <label for="validationDefault09" class="form-label nplabelText">Sign Install Date</label>
                    <input type="date" class="form-control npinputinfo" id="validationDefault09" required value="">
                </div>

            </form>
        </div>
        <div class="col-md-12 col-sm-24"
            style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03);">

            <p class="npinfoText">CHR TM - Select MLS</p>
            <form class="row g-3">
                <div class="col-md-6">
                    <label for="submittalType" class="form-label nplabelText">REColorado</label>
                    <input type="checkbox" id="leadAgent" required value="">
                </div>

                <div class="col-md-6">
                    <label for="submittalType" class="form-label nplabelText">Navica</label>
                    <input type="checkbox" id="leadAgent" required value="">
                </div>
                <div class="col-md-6">
                    <label for="submittalType" class="form-label nplabelText">PPAR</label>
                    <input type="checkbox" id="leadAgent" required value="">
                </div>
                <div class="col-md-6">
                    <label for="submittalType" class="form-label nplabelText">Grand County</label>
                    <input type="checkbox" id="leadAgent" required value="">
                </div>

                <div class="col-md-6">
                    <label for="submittalType" class="form-label nplabelText">IRES</label>
                    <input type="checkbox" id="leadAgent" required value="">
                </div>
                <div class="col-md-12">
                    <label for="validationDefault09" class="form-label nplabelText">MLS Private Remarks</label>
                    <textarea class="form-control" aria-label="With textarea"></textarea>
                </div>
                <div class="col-md-12">
                    <label for="validationDefault09" class="form-label nplabelText">MLS Public Remarks</label>
                    <textarea class="form-control" aria-label="With textarea"></textarea>
                </div>

            </form>
        </div>
        <div class="col-md-12 col-sm-24"
            style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03);">

            <p class="npinfoText">CHR TM - Commission Details</p>
            <form class="row g-3">
                <div class="col-md-6">
                    <label for="validationDefault09" class="form-label nplabelText">Fees Charged to Seller at
                        Closing</label>
                    <input type="text" class="form-control npinputinfo" id="validationDefault09" required value="$">
                </div>

                <div class="col-md-6">
                    <label for="submittalType" class="form-label nplabelText">Referral to Pay</label>

                    <select class="form-select npinputinfo" id="submittalType">
                        <option value="">None
                        </option>
                        <option value="Yes">Yes
                        </option>
                        <option value="No">No</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="validationDefault09" class="form-label nplabelText">Amount to CHR Gives</label>
                    <input type="text" class="form-control npinputinfo" id="validationDefault09" required value="$">
                </div>
                <div class="col-md-6">
                    <label for="validationDefault09" class="form-label nplabelText">Referral Details</label>
                    <input type="text" class="form-control npinputinfo" id="validationDefault09" required value="">
                </div>

            </form>
        </div>

        <div class="col-md-12 col-sm-24"
            style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03);">

            <p class="npinfoText">PROPERTY PROMOTION - Outside Services</p>
            <form class="row g-3">
                <div class="col-md-6">
                    <label for="submittalType" class="form-label nplabelText">Matterport</label>
                    <input type="checkbox" id="leadAgent" required value="">
                </div>

                <div class="col-md-6">
                    <label for="submittalType" class="form-label nplabelText">Floor Plans</label>
                    <input type="checkbox" id="leadAgent" required value="">
                </div>
                <div class="col-md-6">
                    <label for="submittalType" class="form-label nplabelText">3D Zillow Tour</label>
                    <input type="checkbox" id="leadAgent" required value="">
                </div>
                <div class="col-md-6">
                    <label for="submittalType" class="form-label nplabelText">Onsite Video</label>
                    <input type="checkbox" id="leadAgent" required value="">
                </div>

            </form>
        </div>
        <div class="col-md-12 col-sm-24"
            style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03);">

            <p class="npinfoText">PROPERTY PROMOTION - Marketing Items</p>
            <form class="row g-3">
                <div class="col-md-6">
                    <label for="submittalType" class="form-label nplabelText">Property Website</label>
                    <input type="checkbox" id="leadAgent" required value="">
                </div>

                <div class="col-md-6">
                    <label for="submittalType" class="form-label nplabelText">Email Blast to Sphere</label>
                    <input type="checkbox" id="leadAgent" required value="">
                </div>
                <div class="col-md-6">
                    <label for="submittalType" class="form-label nplabelText">Email Blast to Reverse Prospect
                        List</label>
                    <input type="checkbox" id="leadAgent" required value="">
                </div>
                <div class="col-md-6">
                    <label for="submittalType" class="form-label nplabelText">Property Highlight Video</label>
                    <input type="checkbox" id="leadAgent" required value="">
                </div>
                <div class="col-md-6">
                    <label for="submittalType" class="form-label nplabelText">Social Media Images</label>
                    <input type="checkbox" id="leadAgent" required value="">
                </div>
                <div class="col-md-6">
                    <label for="submittalType" class="form-label nplabelText">Social Media Ads</label>
                    <input type="checkbox" id="leadAgent" required value="">
                </div>
                <div class="col-md-12">
                    <label for="submittalType" class="form-label nplabelText">Price Improvement Package</label>
                    <input type="checkbox" id="leadAgent" required value="">
                </div>
                <div class="col-md-12">
                    <label for="validationDefault09" class="form-label nplabelText">Custom Domain Name</label>
                    <input type="text" class="form-control npinputinfo" id="validationDefault09" required value="">
                </div>
                <div class="col-md-12">
                    <label for="validationDefault09" class="form-label nplabelText">8-12 Features Needed for
                        Video</label>
                    <textarea class="form-control" aria-label="With textarea"></textarea>
                </div>

            </form>
        </div>
        <div class="col-md-12 col-sm-24"
            style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03);">

            <p class="npinfoText">PROPERTY PROMOTION - Notes</p>
            <form class="row g-3">
                <p class="npinfoText">Is there anything else Marketing Team
                    Should know?</p>
                <div class="col-md-6">
                    <label for="submittalType" class="form-label nplabelText">Please add your Notes</label>
                    <textarea class="form-control" aria-label="With textarea"></textarea>
                </div>

            </form>
        </div>
        <div class="col-md-12 col-sm-24"
            style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03);">

            <p class="npinfoText">PROPERTY PROMOTION - Print Requests</p>
            <form class="row g-3">
                <div class="col-md-6">
                    <label for="submittalType" class="form-label nplabelText">Brochure Line - Top Right Brochure Preview
                        button</label>
                    {{--<input type="text" placeholder="Enter Client’s name" class="form-control npinputinfo"
                        id="leadAgent" required value="{{ $deal['client_name_primary'] }}">--}}
                    <select class="form-select npinputinfo" id="submittalType">
                        <option value="Yes">Yes
                        </option>
                        <option value="No">No</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="submittalType" class="form-label nplabelText">Brochure - Print, Deliver or PDF</label>
                    {{--<input type="text" placeholder="Enter Client’s name" class="form-control npinputinfo"
                        id="leadAgent" required value="{{ $deal['client_name_primary'] }}">--}}
                    <select class="form-select npinputinfo" id="submittalType">
                        <option value="Yes">Yes
                        </option>
                        <option value="No">No</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="submittalType" class="form-label nplabelText">12 bullets, 4 words per bullet</label>
                    <textarea class="form-control" aria-label="With textarea"></textarea>
                </div>
                <div class="col-md-6">
                    <label for="submittalType" class="form-label nplabelText">4 Word Headline - If Opting for A-Line
                        Brochure</label>
                    <input type="text" class="form-control npinputinfo" id="validationDefault09" required value="">
                </div>
                <div class="col-md-6">
                    <label for="submittalType" class="form-label nplabelText">Sticky Dots</label>
                    {{--<input type="text" placeholder="Enter Client’s name" class="form-control npinputinfo"
                        id="leadAgent" required value="{{ $deal['client_name_primary'] }}">--}}
                    <select class="form-select npinputinfo" id="submittalType">
                        <option value="">--None--</option>
                        <option value="1 Per Feature Card - $.75 each">1 Per Feature Card - $.75 each
                        </option>
                        <option value="2 Per Feature Card - $.75 each">2 Per Feature Card - $.75 each</option>
                        <option value="No Dots">No Dots</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="submittalType" class="form-label nplabelText">QR Code Sheet</label>
                    <select class="form-select npinputinfo" id="submittalType">
                        <option value="">--None--</option>
                        <option value="Print 1 at Alamo - $1">Print 1 at Alamo - $1
                        </option>
                        <option value="Print 2 at Alamo - $2">Print 2 at Alamo - $2</option>
                        <option value="PDF - I'll Print it Myself">PDF - I'll Print it Myself</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="validationDefault09" class="form-label nplabelText">QR Code Sign Rider</label>
                    <input type="checkbox" id="validationDefault09" required value="">
                </div>
                <div class="col-md-6">
                    <label for="validationDefault09" class="form-label nplabelText">Feature Cards</label>
                    <select class="form-select npinputinfo" id="submittalType">
                        <option value="">--None--</option>
                        <option value="Print 1 at Alamo - $1">Print 1 at Alamo - $1
                        </option>
                        <option value="Print 2 at Alamo - $2">Print 2 at Alamo - $2</option>
                        <option value="PDF - I'll Print it Myself">PDF - I'll Print it Myself</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="validationDefault09" class="form-label nplabelText">Add Feature Card Copy</label>
                    <input type="text" class="form-control npinputinfo" id="validationDefault09" required value="">
                </div>
                <div class="col-md-6">
                    <label for="validationDefault09" class="form-label nplabelText">Delivery Only - Brochure
                        Date</label>
                    <input type="date" class="form-control npinputinfo" id="validationDefault09" required value="">
                </div>
                <div class="col-md-6">
                    <label for="validationDefault09" class="form-label nplabelText">Delivery Only - Shipping Address &
                        Name</label>
                    <input type="text" class="form-control npinputinfo" id="validationDefault09" required value="">
                </div>
                <div class="col-md-6">
                    <label for="validationDefault09" class="form-label nplabelText">Printed Items Pick Up or PDF
                        Date</label>
                    <input type="date" class="form-control npinputinfo" id="validationDefault09" required value="">
                </div>
                <div class="col-md-6">
                    <label for="validationDefault09" class="form-label nplabelText">Brochure Pick Up or PDF Date</label>
                    <input type="date" class="form-control npinputinfo" id="validationDefault09" required value="">
                </div>

            </form>
        </div>

    </div>

    {{-- Buyer Submittals--}}
    <div class="row " id="buyerSubmittal" style="display:none">
        <p>Buyer Submittal Information</p>
        <div class="col-md-12 col-sm-24"
            style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03);">
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
                    <input type="text" class="form-control npinputinfo validate" id="validationDefault03" required
                        value="">
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
                    <input type="date" class="form-control npinputinfo validate" id="validationDefault03" required
                        value="">
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
                    <input type="text" class="form-control npinputinfo validate" id="validationDefault03" required
                        value="">
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
                    <input type="text" class="form-control npinputinfo validate" id="validationDefault03" required
                        value="">
                </div>
                <div class="col-md-6">
                    <label for="validationDefault05" class="form-label nplabelText">Fees Charged to Buyer at
                        Closing</label>
                    <input type="text" class="form-control npinputinfo validate" id="validationDefault05" required
                        value="$">
                </div>
                <div class="col-md-6">
                    <label for="validationDefault07" class="form-label nplabelText">TM Name</label>
                    <input type="text" class="form-control npinputinfo" id="validationDefault07" required value="">
                </div>
                <div class="col-md-6">
                    <label for="validationDefault03" class="form-label nplabelText">Amount to CHR Gives</label>
                    <input type="text" class="form-control npinputinfo validate" id="validationDefault05" required
                        value="$">
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
        <div class="col-md-12 col-sm-24"
            style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03);">
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
        $(document).ready(function() {
            var subForm = $('#submittalType').val();
            if (subForm == "Listing Submittal") {
                $('#listingSubmittal').show();
                $('#buyerSubmittal').hide();
            } else if (subForm == "Buyer Submittal") {
                $('#buyerSubmittal').show();
                $('#listingSubmittal').hide();
            }
            console.log(subForm);
        });
        function showForm(){
           subForm=  $('#submittalType').val()
           if(subForm=="Listing Submittal"){
            $('#listingSubmittal').show();
            $('#buyerSubmittal').hide();
           }else if(subForm=="Buyer Submittal"){
            $('#buyerSubmittal').show();
            $('#listingSubmittal').hide();
           }
           console.log(subForm);
        }
    </script>
    @endsection