<div class="modal fade p-5" id="chooseTransactionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Choose Transaction</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title text-center">Transaction</h4>
                                <div class="row">
                                    <div class="col-xl-3 col-sm-6" style="width:100%">
                                        <div class="mt-4">
                                            @foreach($deals as $deal)
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="radio" name="formRadios" id="{{$deal['id']}}" onclick="openSubmittal({{$deal}})">
                                                <label class="form-check-label" for="{{$deal['id']}}">
                                                    {{$deal['deal_name']}}
                                                </label>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function openSubmittal(deal){
        console.log("SubmittalDeal",deal);
        let submittalData;
        if (deal.representing === "Buyer" && deal.tm_preference === "CHR TM") {
            addSubmittal('buyer-submittal',deal);
        }else if(deal.representing === "Seller" && deal.tm_preference === "CHR TM"){
            addSubmittal('listing-submittal',deal)
        }else if(deal.representing === "Seller" && deal.tm_preference === "Non-TM"){
            addSubmittal('listing-submittal',deal,'Non-TM');
        }
    }

    function redirectUrl(submittalType=null,submittalData = null,formType =null){
       const url = `{{ url('submittal-create/${submittalType}/${submittalData.id}?formType=${formType}')}}`
       window.open(url,'_blank')
    }

    function generateRandom4DigitNumber() {
            return Math.floor(1000 + Math.random() * 9000);
        }

    function addSubmittal (type,deal,formType=null){
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
                    'Name':'LS-'+(generateRandom4DigitNumber()),
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
