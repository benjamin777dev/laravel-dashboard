<div class="table-responsive dtranstiontable mt-3">
    <div class="d-flex justify-content-between align-items-center npNom-TMRoles">
        <p class="nproletext">Submittals</p>
        @if ($submittals->count() === 0)
            <div class="input-group-text npcontactbtn" id="addSubmittal" onclick="showSubmittalFormTypeindex()">
                <i class="fas fa-plus plusicon"></i>
                    Add New Submittal
            </div>
         @else
         <a href="{{ url('/submittal-view/' . $submittals[0]['submittalType'] . '/' . $submittals[0]['id']) }}" target="_blank">
            <div class="input-group-text npcontactbtn" id="addSubmittal">
            <i class="fas fa-plus plusicon"></i>
                Show Submittal
            </div>
        </a>
        @endif
        @component('components.common-table', [
        'id'=>'datatable_submittal',
        "type" =>"submittal",
        ])
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


<script>
    console.log("yes working")
    $(document).ready(function() {
        console.log(window.submittalId,'window.submittalId')
        const urlPartss = window.location.pathname.split('/'); // Split the URL by '/'
        const dealId = urlPartss.pop();
         $('#datatable_submittal').DataTable({
    paging: true,
    searching: true,
    "processing": true,
    serverSide: true,
    columns: [
        {
            data: 'submittalName',
            title: 'Submittal Name',
            render: function(data, type, row) {
                return `<span class="editable" data-name="submittalName" data-id="${row.id}">${data}</span>`;
            }
        },
        {
            data: 'submittalType',
            title: 'Submittal Type',
            render: function(data, type, row) {
                return `<span class="editable" data-name="submittalType" data-id="${row.id}">${data}</span>`;
            }
},
        {
            data: 'userData.name',
            title: 'Owner',
            render: function(data, type, row) {
                console.log(data,'shdfhsdhf')
                return  `<span class="editable" data-name="phone" data-id="${row.id}">${formateDate(data) || "N/A"}</span>`;
            }
        },
        {
            data: 'created_at',
            title: 'Created Time',
            render: function(data, type, row) {
                console.log(data,'shdfhsdhf')
                return  `<span class="editable" data-name="created_at" data-id="${row.id}">${formateDate(data) || "N/A"}</span>`;
            }
        },
      
    ],
    
    ajax: {
        url: '/submittal/'+dealId, // Ensure this URL is correct
        type: 'GET', // or 'POST' depending on your server setup
        "data": function(request) {
            request._token = "{{ csrf_token() }}";
            request.perPage = request.length;
            request.stage = $('#related_to_stage').val(),
            request.tab = "In Progress";
            request.page = (request.start / request.length) + 1;
            request.search = request.search.value;
            console.log(request,'skdhfkshdfkhsdkfskddfjhsk')

        },
        dataSrc: function(data) {
            console.log(data,'data is hreeeee')
            return data?.data; // Return the data array or object from your response
        }
    },
});
});
    var deal = @json($deal);
    if (deal.representing === "" || deal.tm_preference === "" || deal.representing === null || deal.tm_preference === null || deal.tm_preference === 'Non-TM') {
        $('#addSubmittal').attr('disabled', true).addClass('btn-disabled');
    } else {
        $('#addSubmittal').removeAttr('disabled').removeClass('btn-disabled');
    }

    function showSubmittalFormTypeindex() {
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

</script>
