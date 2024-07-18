<div class="row justify-content-center" id="listingSubmittal">
    <div class="col-xl-8 align-items-center">
        <div class="card">
            <div class="card-body p-0">
                <h4 class="card-title p-3" id="title-corousal">CHR TM -Basic Information</h4>

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
                                    </svg></label>
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
