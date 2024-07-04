<div class="row table-responsive dtranstiontable mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                
                <!-- Table View -->
                <div class="table-rep-plugin">
                    <div class="table-responsive mb-0" data-pattern="priority-columns">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="card-title nproletext">Submittals</h4>

                            <div class="input-group-text npcontactbtn text-end" id="addSubmittal" onclick="showSubmittalFormType()">
                                <i class="fas fa-plus plusicon"></i>
                                @if ($submittals->count() === 0)
                                    Add New Submittal
                                @else
                                    Show Submittal
                                @endif
                            </div>
                        </div>
                        @if ($submittals->isEmpty())
                            <p class="text-center notesAsignedText">No Submittal assigned</p>
                        @else
                            <table id="submittals-table" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Submittal Name</th>
                                        <th>Submittal Type</th>
                                        <th>Owner</th>
                                        <th>Created Time</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($submittals as $submittal)
                                        <tr>
                                            <td>{{ $submittal['submittalName'] }}</td>
                                            <td>{{ $submittal['submittalType'] }}</td>
                                            <td>{{ $submittal['userData']['name'] }}</td>
                                            <td>{{ $submittal['created_at'] }}</td>
                                            <td class="text-center">
                                                <a href="{{ url('/submittal-view/' . $submittal['submittalType'] . '/' . $submittal['id']) }}" target="_blank">
                                                    <img src="{{ URL::asset('/images/open.svg') }}" alt="Open icon" class="ppiplinecommonIcon" title="Submittal Details">
                                                    {{-- <span class="tooltiptext">Submittal Details</span> --}}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>

                <!-- Card View -->
                <div class="row mt-4">
                    @forelse($submittals as $submittal)
                        <div class="col-md-4 mb-3">
                            <div class="npNom-TM-Card">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="npcommonheaderText">Submittal Name</p>
                                        <p class="npcommontableBodytext">{{ $submittal['submittalName'] }}</p>
                                    </div>
                                    <div>
                                        <p class="npcommonheaderText">Submittal Type</p>
                                        <p class="npcommontableBodytext">{{ $submittal['submittalType'] }}</p>
                                    </div>
                                    <div>
                                        <p class="npcommonheaderText">Owner</p>
                                        <p class="npcommontableBodyDatetext">{{ $submittal['userData']['name'] }}</p>
                                    </div>
                                </div>
                                <div class="npCardPhoneDiv">
                                    <p class="npcommonheaderText">Created Time</p>
                                    <p class="npcommontableBodyDatetext">{{ $submittal['created_at'] }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <p class="text-center notesAsignedText">No Submittal assigned</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var deal = @json($deal);
    if (deal.representing === "" || deal.tm_preference === "" || deal.representing === null || deal.tm_preference === null || deal.tm_preference === 'Non-TM') {
        $('#addSubmittal').attr('disabled', true).addClass('btn-disabled');
    } else {
        $('#addSubmittal').removeAttr('disabled').removeClass('btn-disabled');
    }

    function showSubmittalFormType() {
        console.log("SUBMITTAL DATA", deal.representing, deal.tm_preference);
        let submittalData;
        if (deal.representing === "Buyer" && deal.tm_preference === "CHR TM") {
            addSubmittal('buyer-submittal', deal);
        } else if (deal.representing === "Seller" && deal.tm_preference === "CHR TM") {
            addSubmittal('listing-submittal', deal);
        } else if (deal.representing === "Seller" && deal.tm_preference === "Non-TM") {
            addSubmittal('listing-submittal', deal, 'Non-TM');
        }
    }

    function redirectUrl(submittalType = null, submittalData = null, formType = null) {
        const url = `{{ url('submittal-create/${submittalType}/${submittalData.id}?formType=${formType}') }}`;
        window.open(url, '_blank');
    }

    function generateRandom4DigitNumber() {
        return Math.floor(1000 + Math.random() * 9000);
    }

    function addSubmittal(type, deal, formType = null) {
        let formData = {
            "data": [{
                "Transaction_Name": {
                    "id": deal.zoho_deal_id,
                    "name": deal.deal_name
                },
                "TM_Name": deal.tmName,
                'Name': type === "buyer-submittal" ? 'BS-' + generateRandom4DigitNumber() : 'LS-' + generateRandom4DigitNumber(),
                "Owner": {
                    "id": "{{ auth()->user()->root_user_id }}",
                    "name": "{{ auth()->user()->name }}",
                    "email": "{{ auth()->user()->email }}"
                },
                'formType': formType
            }]
        };

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: `/${type === "buyer-submittal" ? "buyer" : "listing"}/submittal/create/${deal.zoho_deal_id}`,
            type: 'POST',
            contentType: 'application/json',
            dataType: 'json',
            data: JSON.stringify(formData),
            success: function (response) {
                console.log("response", response);
                redirectUrl(type, response, formType);
                if (response?.data && response.data[0]?.message) {
                    const upperCaseMessage = response.data[0].message.toUpperCase();
                    showToast(upperCaseMessage);
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
            }
        });
    }
</script>
