<div class="row">
    <div class="col-md-6 col-sm-12"
        style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03);">
        <p class="npinfoText">Transaction Details
            @if($deal['locked_s']) 
                <span class="text-warning">
                    <i class="fas fa-lock"></i> This transaction is locked!
                </span>
            @endif
        </p>

        <form class="row g-3" id="additionalFields">
            
            <div class="col-md-6 selectSearch">
                <label for="validationDefault01" class="form-label nplabelText">Client Name</label>
                {{--<input type="text" placeholder="Enter Client’s name" class="form-control npinputinfo"
                    id="validationDefault01" required value="{{ $deal['contactId'] }}">--}}
                    <select style="display:none;" 
                        id="validationDefault01" 
                        @if($deal['locked_s']) disabled @endif
                        required>
                    <option value="" disabled {{ empty($deal['client_name_primary']) ? 'selected' : '' }}>Please select</option>
                    @foreach($contacts as $contact)
                        <option value="{{ $contact['first_name'] . ' ' . $contact['last_name'] }}" 
                            {{ $deal['client_name_primary'] == $contact['first_name'] . ' ' . $contact['last_name'] ? 'selected' : '' }}>
                            {{ $contact['first_name'] }} {{ $contact['last_name'] }}
                        </option>
                    @endforeach
                </select>

            </div>
            <div class="col-md-6">
                <label for="validationDefault02" class="form-label nplabelText">Representing</label>
                <select class="form-select npinputinfo validate"
                    id="validationDefault02"
                    @if($deal['locked_s']) disabled @endif
                    required
                    onchange="checkValidate('{{$deal}}')">
                    <option value="" {{ empty($deal['representing']) ? 'selected' : '' }}>--None--</option>
                    <option value="Buyer" {{ $deal['representing']=='Buyer' ? 'selected' : '' }}>Buyer</option>
                    <option value="Seller" {{ $deal['representing']=='Seller' ? 'selected' : '' }}>Seller
                    </option>
                </select>
            </div>

            <div class="col-md-6">
                <label for="validationDefault03" class="form-label nplabelText">Transaction Name</label>
                <input type="text" 
                    class="form-control npinputinfo validate" 
                    placeholder="Transaction Name"
                    id="validationDefault03" 
                    required 
                    @if($deal['locked_s']) disabled @endif
                    value="{{$deal['deal_name']=='Untitled'?'':$deal['deal_name']}}">
            </div>
            <div class="col-md-6">
                <label for="validationDefault04" class="form-label nplabelText">Stage</label>
                <select class="form-select npinputinfo validate" 
                    id="validationDefault04" 
                    required
                    @if($deal['locked_s']) disabled @endif
                    onchange="checkValidate('{{$deal}}')">
                    <option value="" disabled {{ empty($deal['stage']) ? 'selected' : '' }}>Please select</option>
                    @foreach ($allStages as $stage)
                    <option value="{{ $stage }}" {{ $deal['stage']==$stage ? 'selected' : '' }}>
                        {{ $stage }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label for="validationDefault05" class="form-label nplabelText">Sale Price</label>
                <input type="text" 
                    class="form-control npinputinfo validate" 
                    id="validationDefault05"
                    @if($deal['locked_s']) disabled @endif 
                    required
                    value="{{ $deal['sale_price'] }}">
            </div>
            <div class="col-md-6">
                <label for="validationDefault06" class="form-label nplabelText">Closing Date</label>
                <input type="date" 
                    class="form-control npinputinfo validate" 
                    id="validationDefault06"
                    @if($deal['locked_s']) disabled @endif 
                    required
                    value="{{ $deal['closing_date'] ? \Carbon\Carbon::parse($deal['closing_date'])->format('Y-m-d') : '' }}">
            </div>
            <div class="col-md-6">
                <label for="validationDefault07" class="form-label nplabelText">Address</label>
                <input type="text" 
                    class="form-control npinputinfo" 
                    id="validationDefault07" 
                    @if($deal['locked_s']) disabled @endif
                    required
                    value="{{ $deal['address'] }}">
            </div>
            <div class="col-md-6">
                <label for="validationDefault08" class="form-label nplabelText">City</label>
                <input type="text" 
                    class="form-control npinputinfo" 
                    id="validationDefault08" 
                    @if($deal['locked_s']) disabled @endif
                    required
                    value="{{ $deal['city'] }}">
            </div>
            <div class="col-md-6">
                <label for="validationDefault09" class="form-label nplabelText">State</label>
                <input type="text" 
                    class="form-control npinputinfo" 
                    id="validationDefault09" 
                    @if($deal['locked_s']) disabled @endif
                    required
                    value="{{ $deal['state'] }}">
            </div>
            <div class="col-md-6">
                <label for="validationDefault10" class="form-label nplabelText">ZIP</label>
                <input type="text" 
                    class="form-control npinputinfo" 
                    id="validationDefault10"
                    @if($deal['locked_s']) disabled @endif 
                    required
                    value="{{ $deal['zip'] }}">
            </div>
            <div class="col-md-6">
                <label for="validationDefault12" class="form-label nplabelText">Property Type</label>
                <select class="form-select npinputinfo" 
                    id="validationDefault12" 
                    @if($deal['locked_s']) disabled @endif
                    required>
                    <option selected disabled value="">--None--</option>
                    <option value="Residential" {{$deal['property_type']=='Residential' ? 'selected' : '' }}>
                        Residential</option>
                    <option value="Land" {{$deal['property_type']=='Land' ? 'selected' : '' }}>Land</option>
                    <option value="Farm" {{$deal['property_type']=='Farm' ? 'selected' : '' }}>Farm</option>
                    <option value="Commercial" {{$deal['property_type']=='Commercial' ? 'selected' : '' }}>
                        Commercial
                    </option>
                    <option value="Lease" {{$deal['property_type']=='Lease' ? 'selected' : '' }}>Lease</option>
                </select>
            </div>

            <div class="col-md-6">
                <label for="validationDefault13" class="form-label nplabelText">Ownership Type</label>
                <select class="form-select npinputinfo" 
                    id="validationDefault13" 
                    @if($deal['locked_s']) disabled @endif
                    required>
                    <option selected disabled value="">--None--</option>
                    <option value="Primary Residence" {{$deal['ownership_type']=='Primary Residence' ? 'selected' : ''
                        }}>Primary Residence</option>
                    <option value="Second Home" {{$deal['ownership_type']=='Second Home' ? 'selected' : '' }}>Second
                        Home</option>
                    <option value="Investment Property" {{$deal['ownership_type']=='Investment Property' ? 'selected'
                        : '' }}>Investment Property</option>
                </select>
            </div>
        </form>
    </div>
    <div class="col-md-6 col-sm-12"
        style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03);">

        <p class="npinfoText">Earnings Information</p>
        <form class="row g-3">
            <div class="col-md-6">
                <label for="validationDefault11" class="form-label nplabelText">Commission %</label>
                <input type="text" 
                    class="form-control npinputinfo validate" 
                    id="validationDefault11" 
                    @if($deal['locked_s']) disabled @endif
                    required
                    value="{{$deal['commission']}}">
            </div>
            <div class="col-md-6">
                <label for="commissionflat" class="form-label nplabelText">Commission Flat Fee</label>
                <input type="text" 
                    class="form-control npinputinfo" 
                    id="commissionflat"
                    @if($deal['locked_s']) disabled @endif 
                    required
                    value="{{ $deal['commission_flat_free'] }}">
            </div>

            <div class="col-md-6">
                <label for="validationDefault15" class="form-label nplabelText">Pipeline Probability (%)</label>
                <input type="text" 
                    class="form-control npinputinfo" 
                    id="validationDefault15" 
                    required
                    value="{{$deal['pipeline_probability']}}">
            </div>
            <div class="col-md-6">
                <label for="validationDefault11" class="form-label nplabelText"></label>
            </div>
            <div class="col-md-6">
                <input class="form-check-input" 
                    type="checkbox" 
                    value="" 
                    id="flexCheckChecked01" 
                    @if($deal['locked_s']) disabled @endif
                    @if($deal['personal_transaction']) checked @endif
                    >
                <label class="form-check-label nplabelText" for="flexCheckChecked01">
                    Personal Transaction
                </label>
            </div>
            <div class="col-md-6">
                <input 
                    class="form-check-input" 
                    type="checkbox" 
                    value="" 
                    id="flexCheckChecked02" 
                    @if($deal['locked_s']) disabled @endif
                    @if($deal['double_ended']) checked @endif
                >
                <label class="form-check-label nplabelText" for="flexCheckChecked02">
                    Double ended
                </label>
            </div>

            <p class="npinfoText">Settings</p>
            <div class="col-md-6 selectSearch">
                <label for="leadAgent" class="form-label nplabelText">Co-Listing Agent</label>
                <select id="leadAgent" 
                    class="form-select"
                    @if($deal['locked_s']) disabled @endif
                >
                    <option value="" disabled {{ empty($deal['leadAgent']) ? 'selected' : '' }}>Please select
                    </option>
                    @foreach($users as $user)
                    <option value="{{ json_encode($user) }}" {{ isset($deal['leadAgent']) &&
                        $deal['leadAgent']['id']==$user->id ? 'selected' : '' }}>
                        {{ $user->name }} - {{ $user->email }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label for="transactionOwner" class="form-label nplabelText">Transaction Owner</label>
                <input type="text" 
                    class="form-control npinputinfo" 
                    id="transactionOwner" 
                    @if($deal['locked_s']) disabled @endif
                    required
                    value="{{$deal['userData']['name']}}">
            </div>
            <div class="col-md-6">
                <label for="tmPreference" class="form-label nplabelText">TM Preference</label>
                <select class="form-select npinputinfo" 
                    id="tmPreference" 
                    required 
                    onchange="setTmName(this)">
                    <option value="CHR TM" {{ trim($deal['tm_preference']) == 'CHR TM' ? 'selected' : '' }}>CHR TM</option>
                    <option value="Non-TM" {{ trim($deal['tm_preference']) == 'Non-TM' ? 'selected' : '' }}>Non-TM</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="tmName" class="form-label nplabelText">TM Name</label>
                <select class="form-select npinputinfo"
                    id="tmName" 
                    required 
                    disabled>
                        @foreach($users as $user)
                        <option value="{{ $user }}" {{ isset($deal['tmName']['name']) && $deal['tmName']['name'] == $user['name'] ? 'selected' : '' }}>
                            {{ $user['name'] }}
                        </option>
                        @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label for="contactName" class="form-label nplabelText">Contact Name</label>
                <input type="hidden" 
                    name="contactName" 
                    id="contactName"
                    @if($deal['locked_s']) disabled @endif
                    value="{{ $deal['contact_name'] }}">
                <input type="hidden" 
                    name="contactName" 
                    id="contactNameId"
                    @if($deal['locked_s']) disabled @endif
                    value="{{ $deal['contact_name_id'] }}">
                <input type="text" 
                    class="form-control npinputinfo validate" 
                    id="contactName" 
                    required
                    value="{{$deal['contact_name'] ?? ''}}"
                    disabled />
            </div>
            <div></div>
            <div class="col-md-6">
                <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked03" <?php if
                    ($deal['review_gen_opt_out']) { echo 'checked' ; } ?>>
                <label class="form-check-label nplabelText" for="flexCheckChecked03">
                    Review Gen Opt Out
                </label>
            </div>
            <div class="col-md-6">
                <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked04" <?php if
                    ($deal['status_rpt_opt_out']) { echo 'checked' ; } ?>>
                <label class="form-check-label nplabelText" for="flexCheckChecked04">
                    Status Rpt Opt out
                </label>
            </div>
            <div class="col-md-6">
                <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked05" <?php if
                    ($deal['deadline_em_opt_out']) { echo 'checked' ; } ?>>
                <label class="form-check-label nplabelText" for="flexCheckChecked05">
                    Deadline EM Opt Out
                </label>
            </div>
        </form>
    </div>
</div>
{{-- contact roles --}}
<div class="contact_role_table_pipeline">                     
</div>

{{-- Add New Submittal --}}
@if ($deal['tm_preference'] == 'CHR TM')
<div class="showsubmittal">
</div>
@endif
{{-- Add Non-TM --}}
@if ($deal['tm_preference'] == 'Non-TM')
<div class="showNonTm"></div>
</div>
@endif
@vite(['resources/js/pipeline.js'])
<script src="https://code.jquery.com/jquery-3.6.0.min.js">
</script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    dealId = @json($dealId);
    deal=@json($deal);
    $(document).ready(function() {
        fetchContactRole();
        getSubmittals();
        getNonTms();
    })
    $(document).ready(function() {
        var getLeadAgent = $('#leadAgent');
        getLeadAgent.select2({placeholder:"Searching.."});
        var getClientName = $('#validationDefault01');
        getClientName.select2({
            placeholder: 'Search...',
        });


        checkValidate(deal);
        setTmName();
    })
    function setTmName() {
        var users = @json($users);

        let tm_preference = document.getElementById("tmPreference").value;
        let tm_name_select = document.getElementById("tmName");

        if (tm_preference === "Non-TM") {
            let user = users.find((val) => val.name === "File Management Team");
            console.log("TMUSERS", user);
            
            // Clear existing options
            tm_name_select.innerHTML = '';

            // Add the new option
            let option = document.createElement("option");
            option.value = JSON.stringify(user);
            option.text = user.name;
            option.selected = true;
            tm_name_select.appendChild(option);
        } else if (tm_preference === "CHR TM") {
            let currentUser = @json(auth()->user());
            console.log("TMUSERS", currentUser);

            // Clear existing options
            tm_name_select.innerHTML = '';

            // Add the new option
            let option = document.createElement("option");
            option.value = JSON.stringify(currentUser);
            option.text = currentUser.name;
            option.selected = true;
            tm_name_select.appendChild(option);
        }
    };
    function fetchContactRole() {
        $.ajax({
            url: `{{ url('/get/deal/contact/role/${dealId}')}}`,
            method: 'GET',
            success: function(data) {
                const card = $('.contact_role_table_pipeline').html(data);
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }
    function getSubmittals () {
        $.ajax({
            url: `{{ url('/submittal/${dealId}')}}`,
            type: 'GET',
            success: function (response) {
                $(".showsubmittal").html(response);
            },
            error: function (xhr, status, error) {
                // Handle error response
                console.error(xhr.responseText);
            },
        });
    };
    function getNonTms () {
        $.ajax({
            url: `{{ url('/nontms/${dealId}')}}`,
            type: 'GET',
            success: function (response) {
                $(".showNonTm").html(response);
            },
            error: function (xhr, status, error) {
                // Handle error response
                console.error(xhr.responseText);
            },
        });
    };

</script>