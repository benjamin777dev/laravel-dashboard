{{-- Buyer Submittals--}}
<div class="row " id="buyerSubmittal">
    <p>Buyer Submittal Information</p>
    <div class="col-md-12 col-sm-24" style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03);">
        <form class="row g-3">
            <div class="col-md-6 ">
                <label for="relatedTransaction" class="form-label nplabelText">Related Transaction</label>
                <select class="form-select npinputinfo validate" id="relatedTransactionShow" required disabled>
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
            </div>
            <div class="col-md-6">
                <label for="additionalEmailBuyer" class="form-label nplabelText">Additional Email for
                    confirmation</label>
                <input type="email" class="form-control npinputinfo" id="additionalEmailBuyer" required value="{{$submittal['additionalEmail']}}">
            </div>
            <div class="col-md-6">
                <label for="buyerPackage" class="form-label nplabelText">Buyer Package</label>
                <select class="form-select npinputinfo validate" id="buyerPackage" onchange="showConstructionFields()">
                    <option value="">--None--</option>
                    <option value="Standard" {{ $submittal['buyerPackage']=='Standard'? 'selected' : '' }}>Standard</option>
                    <option value="New Construction" {{ $submittal['buyerPackage']=='New Construction'? 'selected' : '' }}>New Construction</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="buyerMailoutNeeded" class="form-label nplabelText">Mailout Needed</label>
                <select class="form-select npinputinfo validate" id="buyerMailoutNeeded">
                    <option value="">--None--</option>
                    <option value="Yes" {{ $submittal['mailoutNeeded']=='Yes'? 'selected' : '' }}>Yes</option>
                    <option value="No" {{ $submittal['mailoutNeeded']=='No'? 'selected' : '' }}>No</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="buyerClosingDate" class="form-label nplabelText">Closing Date</label>
                <input type="date" class="form-control npinputinfo validate" id="buyerClosingDate" required value="{{$submittal['buyerClosingDate']?$submittal['buyerClosingDate']:$submittal['dealData']['closing_date']}}">
            </div>
            <div class="col-md-6">
                <label for="buyerPowerAttny" class="form-label nplabelText">Power of Attny Needed?</label>
                <select class="form-select npinputinfo validate" id="buyerPowerAttny">
                    <option value="">--None--</option>
                    <option value="Yes" {{ $submittal['powerOfAttnyNeeded']=='Yes'? 'selected' : '' }}>Yes</option>
                    <option value="No" {{ $submittal['powerOfAttnyNeeded']=='No'? 'selected' : '' }}>No</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="buyerLenderEmail" class="form-label nplabelText">Lender Email</label>
                <input type="email" class="form-control npinputinfo" id="buyerLenderEmail" required value="{{$submittal['buyerLenderEmail']}}">
            </div>

            <div class="col-md-6">
                <label for="buyerincludeInsight" class="form-label nplabelText">Include Insights in Intro?</label>
                <select class="form-select npinputinfo validate" id="buyerincludeInsight">
                    <option value="">--None--</option>
                    <option value="Yes" {{ $submittal['includeInsights']=='Yes'? 'selected' : '' }}>Yes</option>
                    <option value="No" {{ $submittal['includeInsights']=='No'? 'selected' : '' }}>No</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="buyerLenderPhone" class="form-label nplabelText">Lender Phone</label>
                <input type="tel" class="form-control npinputinfo" id="buyerLenderPhone" required value="{{$submittal['buyerLenderPhone']}}" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}">
            </div>
            <div class="col-md-6">
                <label for="buyerFeesCharged" class="form-label nplabelText">Fees Charged to Buyer at Closing</label>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" class="form-control npinputinfo" id="buyerFeesCharged" required placeholder="0.00" step="0.01" min="0">
                </div>
            </div>
            <div class="col-md-6">
                <label for="buyerTmName" class="form-label nplabelText">TM Name</label>
                <input type="text" class="form-control npinputinfo validate" id="buyerTmName" required value="{{$submittal['dealData']['tmName']['name']}}" disabled>
            </div>
            <div class="col-md-6">
                <label for="buyerAmountChr" class="form-label nplabelText">Amount to CHR Gives</label>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" class="form-control npinputinfo" id="buyerAmountChr" required placeholder="0.00" step="0.01" min="0">
                </div>
            </div>

            <div class="col-md-6">
                <label for="buyerOtherNotes" class="form-label nplabelText">Other Important Notes</label>
                <textarea class="form-control" aria-label="With textarea" id="buyerOtherNotes">{{$submittal['marketingNotes']}}</textarea>
            </div>
            <div class="col-md-6">
                <label for="buyerRefrralPay" class="form-label nplabelText">Referral to Pay</label>
                <select class="form-select npinputinfo validate" id="buyerRefrralPay">
                    <option value="">--None--</option>
                    <option value="Yes" {{ $submittal['referralToPay']=='Yes'? 'selected' : '' }}>Yes</option>
                    <option value="No" {{ $submittal['referralToPay']=='No'? 'selected' : '' }}>No</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="buyerRefrealDetails" class="form-label nplabelText">Referral Details</label>
                <input type="text" class="form-control npinputinfo" id="buyerRefrealDetails" required value="{{$submittal['referralDetails']}}">
            </div>

        </form>
    </div>
    <div class="col-md-12 col-sm-24 constructionFrom" style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03);">
        <p class="npinfoText">New Construction</p>
        <form class="row g-3">
            <div class="col-md-6">
                <label for="buyerBuilderrepresent" class="form-label nplabelText">Builder Representative</label>
                <input type="text" class="form-control npinputinfo" id="buyerBuilderrepresent" required value="{{$submittal['referralDetails']}}">
            </div>
            <div class="col-md-6">
                <label for="BuyerTitleCompany" class="form-label nplabelText">Title Company/Closer Info</label>
                <input type="text" class="form-control npinputinfo" id="BuyerTitleCompany" required value="{{$submittal['titleCompany']}}">
            </div>
            <div class="col-md-6">
                <label for="builderCommisionPercent" class="form-label nplabelText">Builder Commission (% and/or flat
                    fee)</label>
                <input type="number" class="form-control npinputinfo" id="builderCommisionPercent" required value="{{$submittal['builderCommisionPercent']}}" step="0.01" min="0">
            </div>
            <div class="col-md-6">
                <label for="builderCommision" class="form-label nplabelText">Builder Commission Based On</label>
                <select class="form-select npinputinfo" id="builderCommision">
                    <option value="">--None--</option>
                    <option value="Base Price" {{ $submittal['builderCommision']=='Base Price'? 'selected' : '' }}>Base Price</option>
                    <option value="Flat Fee" {{ $submittal['builderCommision']=='Flat Fee'? 'selected' : '' }}>Flat Fee</option>
                    <option value="Other" {{ $submittal['builderCommision']=='Other'? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <div class="col-md-6">
                <label for="contractExecuted" class="form-label nplabelText">Contract Fully Executed</label>
                <select class="form-select npinputinfo" id="contractExecuted">
                    <option value="">--None--</option>
                    <option value="Yes" {{ $submittal['contractExecuted']=='Yes'? 'selected' : '' }}>Yes</option>
                    <option value="No" {{ $submittal['contractExecuted']=='No'? 'selected' : '' }}>No</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="buyerAgency" class="form-label nplabelText">Buyer Agency Executed</label>
                <select class="form-select npinputinfo" id="buyerAgency">
                    <option value="">--None--</option>
                    <option value="Yes" {{ $submittal['buyerAgency']=='Yes'? 'selected' : '' }}>Yes</option>
                    <option value="No" {{ $submittal['buyerAgency']=='No'? 'selected' : '' }}>No</option>
                </select>
            </div>
        </form>
    </div>

</div>
<script>
    $(document).ready(function(){
        showConstructionFields();
        $('input[type="number"]').on('blur', function() {
            let value = $(this).val();
            // Remove invalid characters (anything that's not a digit or a period)
            value = value.replace(/[^\d.]/g, '');

            // Ensure only one decimal point
            const parts = value.split('.');
            if (parts.length > 2) {
                value = parts[0] + '.' + parts.slice(1).join('');
            }

            $(this).val(value);
        });

    });

    function showConstructionFields(){
        var buyerPackage = $("#buyerPackage").val();
        if(buyerPackage === "New Construction"){
            $(".constructionFrom").show();
            $("#buyerBuilderrepresent").addClass("validate");
            $("#BuyerTitleCompany").addClass("validate");
            $("#builderCommisionPercent").addClass("validate");
            $("#builderCommision").addClass("validate");
            $("#contractExecuted").addClass("validate");
            $("#buyerAgency").addClass("validate");
        } else {
            $(".constructionFrom").hide();
            $("#buyerBuilderrepresent").removeClass("validate");
            $("#BuyerTitleCompany").removeClass("validate");
            $("#builderCommisionPercent").removeClass("validate");
            $("#builderCommision").removeClass("validate");
            $("#contractExecuted").removeClass("validate");
            $("#buyerAgency").removeClass("validate");
        }
    }
</script>
