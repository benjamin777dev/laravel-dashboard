<div class="row justify-content-center" id="listingSubmittal">
    <div class="col-xl-8 align-items-center">
        <div class="card">
            <div class="card-body p-0">
                <h4 class="card-title p-3" id="title-corousal">CHR TM - Basic Information</h4>

                <div id="carouselExampleIndicators" class="carousel slide" data-interval="false">
                    <div class="carousel-indicators">
                        <button type="button"
                            data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"
                            aria-current="true" aria-label="Slide 1"></button>
                        <button type="button"
                            data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"
                            aria-label="Slide 2"></button>
                        <button type="button"
                            data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"
                            aria-label="Slide 3"></button>
                        <button type="button"
                            data-bs-target="#carouselExampleIndicators" data-bs-slide-to="3"
                            aria-label="Slide 4"></button>
                        <button type="button"
                            data-bs-target="#carouselExampleIndicators" data-bs-slide-to="4"
                            aria-label="Slide 5"></button>
                    </div>
                    <div class="carousel-inner" role="listbox">
                        <div class="carousel-item active">
                            <div class="related_trxn label-div-mb">
                                <label for="deal_name" class="common-label">Transaction Name <svg
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
                                <div class="nontm-select-div">
                                    <select class="nontm-select" id="relatedTransactionShow" required disabled>
                                        @foreach($deals as $currDeal)
                                        <option value="{{$currDeal}}" {{ $currDeal['deal_name']==$submittal['dealData']['deal_name']? 'selected' : '' }}>
                                            {{$currDeal['deal_name']}}
                                        </option>
                                        @endforeach
                                    </select>
                                    <select class="form-select npinputinfo validate" id="relatedTransaction" required hidden>
                                        @foreach($deals as $currDeal)
                                        <option value="{{$currDeal}}" {{ $currDeal['deal_name']==$submittal['dealData']['deal_name']? 'selected' : '' }}>
                                            {{$currDeal['deal_name']}}
                                        </option>
                                        @endforeach
                                    </select>

                                    <img src="{{ URL::asset('/images/domain_add.svg') }}" alt="">
                                </div>
                               
                            </div>
                            <div class="related_trxn label-div-mb">
                                <label for="buyerPackage" class="common-label">Buyer Package<svg
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
                                </svg> </label>
                                <div class="nontm-select-div">
                                    <select name="related_transaction" onchange="addFormSlide(this)" id="buyerPackage" class="nontm-select validate_err">
                                        <option value="">--None--</option>
                                        <option value="Standard" {{ $submittal['buyerPackage']=='Standard'? 'selected' : '' }}>Standard</option>
                                        <option value="New Construction" {{ $submittal['buyerPackage']=='New Construction'? 'selected' : '' }}>New Contruction</option>
                                     
                                    </select>
                                </div>
                               
                            </div>
                            <div class="additional_email label-div-mb">
                                <label for="additionalEmailBuyer" class="common-label">Additional Email for Confirmation </label>
                                <input type="email" class="form-control" value="{{$submittal['additionalEmail']}}" id="additionalEmailBuyer"
                                    class="form-control" placeholder="Enter email">
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="row">
                                <div class='col-lg-6 label-div-mb'>
                                        
                                            <label for="buyerClosingDate" class="common-label">Closing Date <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18" fill="none">
                                                <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="19" height="18">
                                                    <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                                </mask>
                                                <g mask="url(#mask0_2151_10662)">
                                                    <path d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z" fill="#AC5353" />
                                                </g>
                                            </svg></label>
                                            <input type="date" value="{{$submittal['buyerClosingDate']?$submittal['buyerClosingDate']:$submittal['dealData']['closing_date']}}"
                                                class="form-control nontm-input" id="buyerClosingDate">
                                </div>
                                <div class="col-lg-6 label-div-mb">
                                        <label for="buyerTmName" class="common-label">TM name <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18" fill="none">
                                            <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="19" height="18">
                                                <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                            </mask>
                                            <g mask="url(#mask0_2151_10662)">
                                                <path d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z" fill="#AC5353" />
                                            </g>
                                        </svg></label>
                                        <input type="email" class="form-control nontm-input" value="{{$submittal['dealData']['tmName']['name']}}" id="buyerTmName"
                                            class="form-control" placeholder="Enter email" id="add_email">
                                </div>
                            </div>
                            <div class="row">
                                <div class="gap-2 col-lg-6 label-div-mb">
                                    <label for="buyerMailoutNeeded" class="common-label">Mailout Needed
                                        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18" fill="none">
                                            <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="19" height="18">
                                                <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                            </mask>
                                            <g mask="url(#mask0_2151_10662)">
                                                <path d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z" fill="#AC5353" />
                                            </g>
                                        </svg>
                                    </label>
                                    <div class="d-flex gap-2">
                                        <div class="mb-3">
                                            <input type="radio" id="mailoutNeeded_yes" name="mailoutNeeded" {{ $submittal['mailoutNeeded']=='Yes'? 'checked' : '' }}>
                                            <label class="form-check-label" for="mailoutNeeded_yes">
                                                Yes
                                            </label>
                                        </div>
                                        <div class="mb-3">
                                            <input type="radio" id="mailoutNeeded_no" name="mailoutNeeded" {{ $submittal['mailoutNeeded']=='Yes'? 'checked' : '' }}>
                                            <label class="form-check-label" for="mailoutNeeded_no">
                                                No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="gap-2 col-lg-6 label-div-mb">
                                    <label for="powerOfAttnyNeeded" class="common-label">Power of Attny Needed?
                                        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18" fill="none">
                                            <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="19" height="18">
                                                <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                            </mask>
                                            <g mask="url(#mask0_2151_10662)">
                                                <path d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z" fill="#AC5353" />
                                            </g>
                                        </svg>
                                    </label>
                                    <div class="d-flex gap-2">
                                        <div class="mb-3">
                                            <input type="radio" id="powerOfAttnyNeeded_yes" name="powerOfAttnyNeeded" {{ $submittal['powerOfAttnyNeeded'] == 'Yes' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="powerOfAttnyNeeded_yes">
                                                Yes
                                            </label>
                                        </div>
                                        <div class="mb-3">
                                            <input type="radio" id="powerOfAttnyNeeded_no" name="powerOfAttnyNeeded" {{ $submittal['powerOfAttnyNeeded'] == 'No' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="powerOfAttnyNeeded_no">
                                                No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        <div class="carousel-item">
                            <div class="row">
                                <div class="gap-2 col-lg-6 label-div-mb">
                                    <label for="buyerincludeInsight" class="common-label">
                                        Include Insights in Intro?
                                        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18" fill="none">
                                            <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="19" height="18">
                                                <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                            </mask>
                                            <g mask="url(#mask0_2151_10662)">
                                                <path d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z" fill="#AC5353" />
                                            </g>
                                        </svg>
                                    </label>
                                    <div class="d-flex gap-2">
                                        <div class="mb-3">
                                            <input type="radio" id="buyerincludeInsight_yes" name="includeInsights"{{ $submittal['includeInsights']=='Yes'? 'checked' : '' }}>
                                            <label class="form-check-label" for="mailoutNeeded_yes">
                                                Yes
                                            </label>
                                        </div>
                                        <div class="mb-3">
                                            <input type="radio" id="buyerincludeInsight_no" name="includeInsights" {{ $submittal['includeInsights']=='Yes'? 'checked' : '' }}>
                                            <label class="form-check-label" for="mailoutNeeded_no">
                                                No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="gap-2 col-lg-6 label-div-mb">
                                    <label for="powerOfAttnyNeeded" class="common-label">Referral to Pay
                                        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18" fill="none">
                                            <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="19" height="18">
                                                <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                            </mask>
                                            <g mask="url(#mask0_2151_10662)">
                                                <path d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z" fill="#AC5353" />
                                            </g>
                                        </svg>
                                    </label>
                                    <div class="d-flex gap-2">
                                        <div class="mb-3">
                                            <input type="radio" id="powerOfAttnyNeeded_yes" name="powerOfAttnyNeeded" {{ $submittal['powerOfAttnyNeeded'] == 'Yes' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="powerOfAttnyNeeded_yes">
                                                Yes
                                            </label>
                                        </div>
                                        <div class="mb-3">
                                            <input type="radio" id="powerOfAttnyNeeded_no" name="powerOfAttnyNeeded" {{ $submittal['powerOfAttnyNeeded'] == 'No' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="powerOfAttnyNeeded_no">
                                                No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="carousel-item">
                            <div class="row">
                                <div class='col-lg-6 label-div-mb'>
                                        
                                            <label for="buyerLenderEmail" class="common-label">Lender Email</label>
                                            <input type="text" placeholder="Enter email" value="{{$submittal['buyerLenderEmail']}}"
                                                class="form-control nontm-input" id="buyerLenderEmail">
                                </div>
                                <div class="col-lg-6 label-div-mb">
                                        <label for="buyerLenderPhone" class="common-label">Lender Phone</label>
                                        <input type="text" class="form-control nontm-input" value="{{$submittal['buyerLenderPhone']}}" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" id="buyerLenderPhone"
                                            class="form-control" placeholder="">
                                </div>
                            </div>
                             <div class="row">
                                <div class='col-lg-6 label-div-mb'>
                                            <label for="buyerFeesCharged" class="common-label">Fees Charged to Buyer at Closing</label>
                                            <input type="text" placeholder="$" value="{{ $submittal['comingSoonDate'] }}"
                                                class="form-control nontm-input" id="comingSoonDate">
                                </div>
                                <div class="col-lg-6 label-div-mb">
                                        <label for="add_email" class="common-label">Amount to CHR Gives</label>
                                        <input type="text" placeholder="$" class="form-control nontm-input" value="{{ $submittal['additionalEmail'] }}" id="additionalEmail"
                                            class="form-control" placeholder="Enter email" id="add_email">
                                        <div class="add_email_error text-danger" id="add_email_error">
                                        </div>
                                    

                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="additional_email label-div-mb">
                                <label for="add_email" class="common-label">Referral Details</label>
                                <input type="email" class="form-control" value="{{ $submittal['additionalEmail'] }}" id="additionalEmail"
                                    class="form-control" placeholder="Enter Details" id="add_details">
                                
                            </div>
                            <div class="additional_email label-div-mb">
                                <label for="add_email" class="common-label">Other important Notes</label>
                            <textarea class="form-control" id="miscNotes"  rows="4" cols="50">{{ $submittal['miscNotes'] }}</textarea>
                                
                            </div>
                        </div>
                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->
<script>
    function addFormSlide(e,state=true) {
        var existingItem1 = document.querySelector('.carousel-item.item1');
        var existingButton1 = document.querySelector('[data-bs-slide-to="5"]');
       
        if(e.value !== "New Contruction"){
            state = false;
        }
        if (!state) {
            // State is false, remove carousel items if they exist
            if (existingItem1 ) {
                existingItem1.remove();
                existingButton1?.remove();
            }
            return;
        }
        
        if(e.value === "New Contruction"){
        if (existingItem1) {
            // Both items already exist, do not create new items
            console.log("Carousel items already exist.");
            return;
        }
        
        // Create a new carousel item
        var carouselItem = document.createElement('div');
        carouselItem.className = 'carousel-item item1';
        var innrtHtml = `<div class="row">
                                <div class='col-lg-6 label-div-mb'>
                                        
                                            <label for="comingSoonDate" class="common-label">Builder Representative <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18" fill="none">
                                                <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="19" height="18">
                                                    <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                                </mask>
                                                <g mask="url(#mask0_2151_10662)">
                                                    <path d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z" fill="#AC5353" />
                                                </g>
                                            </svg></label>
                                            <input type="text" placeholder="Enter Details" value="{{ $submittal['comingSoonDate'] }}"
                                                class="form-control nontm-input" id="comingSoonDate">
                                    
                                </div>
                                <div class="col-lg-6 label-div-mb">
                                        <label for="add_email" class="common-label">
                                            Title Company/Closer Info <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18" fill="none">
                                            <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="19" height="18">
                                                <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                            </mask>
                                            <g mask="url(#mask0_2151_10662)">
                                                <path d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z" fill="#AC5353" />
                                            </g>
                                        </svg></label>
                                        <input type="text" class="form-control nontm-input" value="{{ $submittal['additionalEmail'] }}" id="additionalEmail"
                                            class="form-control" placeholder="Enter Details" id="add_email">
                                        <div class="add_email_error text-danger" id="add_email_error">
                                        </div>
                                    

                                </div>
                            </div>
                            <div class="row">
                                <div class='col-lg-6 label-div-mb'>
                                        
                                            <label for="comingSoonDate" class="common-label">Builder Commission (% and/or flat fee) <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18" fill="none">
                                                <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="19" height="18">
                                                    <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                                </mask>
                                                <g mask="url(#mask0_2151_10662)">
                                                    <path d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z" fill="#AC5353" />
                                                </g>
                                            </svg></label>
                                            <input type="text" placeholder="Enter Details" value="{{ $submittal['comingSoonDate'] }}"
                                                class="form-control nontm-input" id="comingSoonDate">
                                    
                                </div>
                                <div class="col-lg-6 label-div-mb">
                                        <label for="add_email" class="common-label">
                                            
                                            Builder Commission Based On <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18" fill="none">
                                            <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="19" height="18">
                                                <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                            </mask>
                                            <g mask="url(#mask0_2151_10662)">
                                                <path d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z" fill="#AC5353" />
                                            </g>
                                        </svg></label>
                                        <input type="text" class="form-control nontm-input" value="{{ $submittal['additionalEmail'] }}" id="additionalEmail"
                                            class="form-control" placeholder="Enter Details" id="add_email">
                                        <div class="add_email_error text-danger" id="add_email_error">
                                        </div>
                                    

                                </div>
                            </div>
                             <div class="row">
                                <div class="gap-2 col-lg-6 label-div-mb">
                                    <label for="mailoutNeeded" class="common-label">Contract Fully Executed
                                        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18" fill="none">
                                            <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="19" height="18">
                                                <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                            </mask>
                                            <g mask="url(#mask0_2151_10662)">
                                                <path d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z" fill="#AC5353" />
                                            </g>
                                        </svg>
                                    </label>
                                    <div class="d-flex gap-2">
                                        <div class="mb-3">
                                            <input type="radio" id="mailoutNeeded_yes" name="mailoutNeeded" {{ $submittal['mailoutNeeded'] == 'Yes' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="mailoutNeeded_yes">
                                                Yes
                                            </label>
                                        </div>
                                        <div class="mb-3">
                                            <input type="radio" id="mailoutNeeded_no" name="mailoutNeeded" {{ $submittal['mailoutNeeded'] == 'No' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="mailoutNeeded_no">
                                                No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="gap-2 col-lg-6 label-div-mb">
                                    <label for="powerOfAttnyNeeded" class="common-label">Buyer Agency Executed
                                        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18" fill="none">
                                            <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="19" height="18">
                                                <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                            </mask>
                                            <g mask="url(#mask0_2151_10662)">
                                                <path d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z" fill="#AC5353" />
                                            </g>
                                        </svg>
                                    </label>
                                    <div class="d-flex gap-2">
                                        <div class="mb-3">
                                            <input type="radio" id="powerOfAttnyNeeded_yes" name="powerOfAttnyNeeded" {{ $submittal['powerOfAttnyNeeded'] == 'Yes' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="powerOfAttnyNeeded_yes">
                                                Yes
                                            </label>
                                        </div>
                                        <div class="mb-3">
                                            <input type="radio" id="powerOfAttnyNeeded_no" name="powerOfAttnyNeeded" {{ $submittal['powerOfAttnyNeeded'] == 'No' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="powerOfAttnyNeeded_no">
                                                No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
         `;

        // Set inner HTML content of carouselItem
        carouselItem.innerHTML = innrtHtml;

        // Append the new carousel items to the carousel inner
        var carouselInner = document.querySelector('.carousel-inner');
        carouselInner.appendChild(carouselItem);
        // Update the carousel indicators (optional, if you want to show navigation bullets)
        var slideIndex = $('.carousel-item').length - 1;

        // Create the slide buttons for carousel indicators

        var button1 = document.createElement('button');
        button1.type = 'button';
        button1.setAttribute('data-bs-target', '#carouselExampleIndicators');
        button1.setAttribute('data-bs-slide-to', slideIndex.toString());
        button1.setAttribute('aria-label', 'Slide ' + (slideIndex + 1).toString());
        button1.addEventListener('click', function() {
        // Change the inner HTML of 'title-corousal' element
        const titleCorousal = document.getElementById('title-corousal');
        if (titleCorousal) {
                titleCorousal.innerHTML = 'CHR TM - Transaction Details and Preferences';
            }
        })

        var carouselIndicators = document.querySelector('.carousel-indicators');
        carouselIndicators.appendChild(button1);
    }
    }

</script>
