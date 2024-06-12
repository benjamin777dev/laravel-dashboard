<div class="table-responsive dtranstiontable mt-3">
    <div class="d-flex justify-content-between align-items-center npNom-TMRoles">
        <p class="nproletext">Submittals</p>
        <div class="input-group-text npcontactbtn" id="addSubmittal" onclick="showSubmittalFormType('{{$deal}}')">
            <i class="fas fa-plus plusicon"></i>
            Add New Submittal
        </div>

    </div>
    <div class="row npNom-TM-Table">
        <div class="col-md-2 ">Submittal Name</div>
        <div class="col-md-2 ">Submittal Type</div>
        <div class="col-md-3 ">Owner</div>
        <div class="col-md-3 ">Created Time</div>
        <div class="col-md-1 "></div>
    </div>
    @if (count($submittals)==0)
    <div>
        <p class="text-center notesAsignedText">No Submittal assigned</p>
    </div>
    @else
    @foreach($submittals as $submittal)
    <div class="row npNom-TM-Body">
        <div class="col-md-2 ">{{$submittal['submittalName']}}</div>
        <div class="col-md-2 ">{{$submittal['submittalType']}}</div>
        <div class="col-md-3 ">{{$submittal['userData']['name']}}</div>
        <div class="col-md-3 commonTextEllipsis">{{$submittal['created_at']}}</div>
        <div class="col-md-1 tooltip-wrapper">
            <a href="{{ url('/submittal-view/' . $submittal['submittalType'] . '/' . $submittal['id']) }}" target="_blank">
                <img src="{{ URL::asset('/images/open.svg') }}" alt="Open icon"
                    class="ppiplinecommonIcon" title="Submittal Details">
                <span class="tooltiptext">Submittal Details</span>
            </a>
        </div>
    </div>
    @endforeach
    @endif
    @foreach($submittals as $submittal)
    <div class="npNom-TM-Card">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <p class="npcommonheaderText">Submittal Name</p>
                <p class="npcommontableBodytext">{{$submittal['submittalName']}}</p>
            </div>
            <div>
                <p class="npcommonheaderText">Submittal Type</p>
                <p class="npcommontableBodytext">{{$submittal['submittalType']}}</p>
            </div>
            <div>
                <p class="npcommonheaderText">Owner</p>
                <p class="npcommontableBodyDatetext">{{$submittal['closed_date']}}</p>
            </div>
        </div>
        <div class="npCardPhoneDiv">
            <p class="npcommonheaderText">Created Time</p>
            <p class="npcommontableBodyDatetext">{{$submittal['created_at']}}</p>
        </div>
    </div>
    @endforeach
    {{-- <div class="dpagination">
        <nav aria-label="..." class="dpaginationNav">
            <ul class="pagination ppipelinepage d-flex justify-content-end">
                <li class="page-item disabled">
                    <a class="page-link">Previous</a>
                </li>
                <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item active" aria-current="page">
                    <a class="page-link" href="#">2</a>
                </li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                    <a class="page-link" href="#">Next</a>
                </li>
            </ul>
        </nav>
    </div> --}}

</div>

<script>
    var deal = @json($deal);
        console.log("sub form sdjkfkdsj",deal);
        if(deal.representing==""||deal.tm_preference==""||deal.representing==null||deal.tm_preference==null){
            $('#addSubmittal').attr('disabled', true);
            $('#addSubmittal').addClass('btn-disabled');
        }else{
            $('#addSubmittal').removeAttr('disabled').removeClass('btn-disabled')
        }
        /* if (subForm == "Listing Submittal") {
            $('#listingSubmittal').show();
            $('#buyerSubmittal').hide();
        } else if (subForm == "Buyer Submittal") {
            $('#buyerSubmittal').show();
            $('#listingSubmittal').hide();
    } */
    function showSubmittalFormType(deal) {
        deal = JSON.parse(deal);
        console.log(deal.representing,deal.tm_preference);
        let submittalData;
        if (deal.representing === "Buyer" && deal.tm_preference === "CHR TM") {
            addSubmittal('buyer-submittal',deal);
        }else if(deal.representing === "Seller" && deal.tm_preference === "CHR TM"){
            addSubmittal('listing-submittal',deal)
            window.location.href = `{{ url('submittal-create/Listing/${submittalData.id}') }}`;
        }else if(deal.representing === "Seller" && deal.tm_preference === "Non TM"){
            addSubmittal('listing-submittal',deal,'Non TM');
            window.location.href = `{{ url('submittal-create/Listing/${submittalData.id}') }}'+'?formType="Non TM"`;
        }
    }

    function redirectUrl(submittalType=null,submittalData = null,formType =null){
        window.location.href = `{{ url('submittal-create/${submittalType}/${submittalData.id}?formType=${formType}')}}`
    }

    function generateRandom4DigitNumber() {
            return Math.floor(1000 + Math.random() * 9000);
        }

    window.addSubmittal = function(type,deal,formType){
        if(type == "buyer-submittal"){
            var formData = {
                "data": [{
                    "Transaction_Name": {
                        "id":deal.zoho_deal_id,
                        "name":deal.deal_name
                    },
                    "TM_Name": deal.tmName,
                    'Name':'BS-'+(generateRandom4DigitNumber()),
                    "Owner": {
                        "id": "{{ auth()->user()->root_user_id }}",
                        "name": "{{ auth()->user()->name }}",
                        "email": "{{ auth()->user()->email }}",
                    },
                    'formType':formType
                }]
            };
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
                // Send AJAX request
            $.ajax({
                url: "/buyer/submittal/create/"+deal.zoho_deal_id,
                type: 'POST',
                contentType: 'application/json',
                dataType: 'json',
                data: JSON.stringify(formData),
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
        }else if(type == "listing-submittal"){
            var formData = {
                "data": [{
                    "Transaction_Name": {
                        "id":deal.zoho_deal_id,
                        "name":deal.deal_name
                    },
                    "TM_Name": deal.tmName,
                    'Name':'BS-'+(generateRandom4DigitNumber()),
                    "Owner": {
                        "id": "{{ auth()->user()->root_user_id }}",
                        "name": "{{ auth()->user()->name }}",
                        "email": "{{ auth()->user()->email }}",
                    },
                    'formType':formType
                }]
            };
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
                // Send AJAX request
            $.ajax({
                url: "/listing/submittal/create/"+deal.zoho_deal_id,
                type: 'POST',
                contentType: 'application/json',
                dataType: 'json',
                data: JSON.stringify(formData),
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