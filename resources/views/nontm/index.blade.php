<div class="table-responsive dtranstiontable mt-3">
    <div class="d-flex justify-content-between align-items-center npNom-TMRoles">
        <p class="nproletext">Non-TM Check request</p>
        <div class="dropdown">
            <button onclick="addNonTm()" class="btn btn-secondary btn-bg dropdown-toggle" type="button"
                id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-plus plusicon"></i>
                Add Non-TM Check request
            </button>

        </div>


    </div>
    <div class="row npNom-TM-Table">
        <div class="col-md-4">Number</div>
        <div class="col-md-3">Close Date</div>
        <div class="col-md-3">Created Time</div>
        <div class="col-md-1"></div>
        <div class="col-md-1"></div>
    </div>
    @if ($nontms->isEmpty())
        <div>
            <p class="text-center notesAsignedText">No Non-TM assigned</p>

        </div>
    @else
        @foreach ($nontms as $nontm)
            <div class="row npNom-TM-Body">
                <div class="col-md-4 ">{{ $nontm['name']??'--' }}</div>
                <div class="col-md-3 ">{{ $nontm['closed_date']??'--' }}</div>
                <div class="col-md-3 commonTextEllipsis">{{ $nontm['created_at'] }}</div>
                <a class="col-md-1 text-end" href="/nontm-view/{{$nontm['id']}}"><div ><img
                        src="{{ URL::asset('/images/open.svg') }}" alt="Open icon" class="ppiplinecommonIcon"
                        title="Non-TM Details"></div></a> 
                        
            </div>
        @endforeach
    @endif

    @foreach ($nontms as $nontm)
        <div class="npNom-TM-Card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="npcommonheaderText">Number</p>
                    <p class="npcommontableBodytext">{{ $nontm['name'] }}</p>
                </div>
                <div>
                    <p class="npcommonheaderText">Close Date</p>
                    <p class="npcommontableBodyDatetext">{{ $nontm['closed_date'] }}</p>
                </div>
            </div>
            <div class="npCardPhoneDiv">
                <p class="npcommonheaderText">Created Time</p>
                <p class="npcommontableBodyDatetext">{{ $nontm['created_at'] }}</p>
            </div>
        </div>
    @endforeach
    <div class="dpagination">
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
    </div>


</div>
<script>
    function generateRandom4DigitNumber() {
        return Math.floor(1000 + Math.random() * 9000);
    }
    window.addNonTm = function() {
        let formData = {
            "data": [{
                "Owner": {
                    "id": "{{ auth()->user()->root_user_id }}",
                    "full_name": "{{ auth()->user()->name }}"
                },
                "Exchange_Rate": 1,
                "Currency": "USD",
                "Related_Transaction": {
                    "id": "{{ $deal->zoho_deal_id }}",
                    "name": "{{ $deal->deal_name }}"
                },
                "Name": 'N'+(generateRandom4DigitNumber()),
                "$zia_owner_assignment": "owner_recommendation_unavailable",
                "zia_suggested_users": {}
            }],
            "skip_mandatory": false
        }
        console.log(formData, 'sdfjsdfjsd');
        
        $.ajax({
            url: '/create-nontm',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            contentType: 'application/json',
            dataType: 'json',
            data: JSON.stringify(formData),
            success: function(response) {
                if (response) {
                    const url = `{{ url('/nontm-create/${response?.id}') }}`
                    window.open(url,'_blank')
                    // window.location.reload();
                }
            },
            error: function(xhr, status, error) {
                // Handle error response
                console.error(xhr.responseText);
            }
        })
    }
</script>