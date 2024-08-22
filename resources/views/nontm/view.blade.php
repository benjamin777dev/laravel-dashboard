@extends('layouts.master')

@section('title', 'zPortal | NonTm')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Non-TM Check Request Information</h4>

                    <div id="basic-example-nontm-view">
                        <!-- Seller Details -->
                        <h3>Basic Information</h3>
                        <section>
                            <form>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label>Related Transaction <svg xmlns="http://www.w3.org/2000/svg" width="19"
                                                    height="18" viewBox="0 0 19 18" fill="none">
                                                    <mask id="mask0_2151_10662" style="mask-type:alpha"
                                                        maskUnits="userSpaceOnUse" x="0" y="0" width="19"
                                                        height="18">
                                                        <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                                    </mask>
                                                    <g mask="url(#mask0_2151_10662)">
                                                        <path
                                                            d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z"
                                                            fill="#AC5353" />
                                                    </g>
                                                </svg></label>
                                            <select name="related_transaction" id="related_transaction"
                                                class="form-select validate_err required-field" disabled>
                                                @foreach ($deals as $deal)
                                                    <option value="{{ $deal->zoho_deal_id }}"
                                                        {{ $deal->zoho_deal_id == $dealData->dealId ? 'selected' : '' }}>
                                                        {{ $deal->deal_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div id="related_transaction_error" class="text-danger">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="basicpill-lastname-input">Additional Email for Confirmation </label>
                                            <input type="email"
                                                value="{{ isset($dealData['email']) ? $dealData['email'] : '' }}"
                                                class="form-control" placeholder="Enter email" id="add_email">
                                            <div class="add_email_error text-danger" id="add_email_error">
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="basicpill-email-input">Close Date <svg
                                                    xmlns="http://www.w3.org/2000/svg" width="19" height="18"
                                                    viewBox="0 0 19 18" fill="none">
                                                    <mask id="mask0_2151_10662" style="mask-type:alpha"
                                                        maskUnits="userSpaceOnUse" x="0" y="0" width="19"
                                                        height="18">
                                                        <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                                    </mask>
                                                    <g mask="url(#mask0_2151_10662)">
                                                        <path
                                                            d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z"
                                                            fill="#AC5353" />
                                                    </g>
                                                </svg></label>
                                            <input type="date" class="form-control required-field"
                                                value="{{ isset($dealData['dealData']['closing_date']) ? \Carbon\Carbon::parse($dealData['dealData']['closing_date'])->format('Y-m-d') : '' }}"
                                                id="close_date">
                                            <div id="close_date_error" class="text-danger">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="basicpill-phoneno-input">Commission %<svg
                                                    xmlns="http://www.w3.org/2000/svg" width="19" height="18"
                                                    viewBox="0 0 19 18" fill="none">
                                                    <mask id="mask0_2151_10662" style="mask-type:alpha"
                                                        maskUnits="userSpaceOnUse" x="0" y="0" width="19"
                                                        height="18">
                                                        <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                                    </mask>
                                                    <g mask="url(#mask0_2151_10662)">
                                                        <path
                                                            d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z"
                                                            fill="#AC5353" />
                                                    </g>
                                                </svg></label>
                                            <input type="text"
                                                value="{{ isset($dealData['dealData']['commission']) ? $dealData['dealData']['commission'] : '' }}"
                                                class="form-control required-field integer-field" id="commission">
                                            <div id="commission_error" class="text-danger">
                                            </div>
                                        </div>
                                    </div>
                            </form>
                        </section>
                        <h3>Basic Information</h3>
                        <section>
                            <form>
                                <div class="accordion" id="accordionExample">
                                    <!-- Referral Fee Paid Out -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingOne">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapseReferral"
                                                aria-expanded="false" aria-controls="collapseReferral">
                                                Referral Fee Paid Out?
                                                <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18"
                                                    viewBox="0 0 19 18" fill="none">
                                                    <mask id="mask0_2151_10662" style="mask-type:alpha"
                                                        maskUnits="userSpaceOnUse" x="0" y="0" width="19"
                                                        height="18">
                                                        <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                                    </mask>
                                                    <g mask="url(#mask0_2151_10662)">
                                                        <path
                                                            d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z"
                                                            fill="#AC5353" />
                                                    </g>
                                                </svg>
                                            </button>
                                        </h2>
                                        <div id="collapseReferral" class="accordion-collapse collapse"
                                            aria-labelledby="headingOne">
                                            <div class="accordion-body">
                                                <label class="switch">
                                                    <input type="checkbox" id="referralSwitch"
                                                        {{ isset($dealData['referral_fee_paid_out']) && $dealData['referral_fee_paid_out'] === 'YES' ? 'checked' : '' }}>
                                                    <span class="slider"></span>
                                                </label>
                                                <div class="referralCustomFields label-div-mb"
                                                    style="margin-top:10px;display:none;">
                                                    <div class="referralFeeAmount label-div-mb">
                                                        <label for="referralFeeAmount" class="common-label">Referral Fee
                                                            Amount</label>
                                                        <input type="text"
                                                            value="{{ isset($dealData['referralFeeAmount']) ? $dealData['referralFeeAmount'] : '' }}"
                                                            class="form-control" id="referralFeeAmount">
                                                        <div class="referralFeeAmount_error text-danger"
                                                            id="referralFeeAmount_error"></div>
                                                    </div>
                                                    <div class="referralFeeBrokerage label-div-mb">
                                                        <label for="referralFeeBrokerage" class="common-label">Referral
                                                            Fee Brokerage Name</label>
                                                        <input type="text"
                                                            value="{{ isset($dealData['referralFeeBrokerage']) ? $dealData['referralFeeBrokerage'] : '' }}"
                                                            class="form-control" id="referralFeeBrokerage">
                                                        <div class="referralFeeBrokerage_error text-danger"
                                                            id="referralFeeBrokerage_error"></div>
                                                    </div>
                                                    <div class="referralAgreement label-div-mb">
                                                        <label for="referralAgreement" class="common-label">Referral Fee
                                                            Agreement Executed</label>
                                                        <input type="text"
                                                            value="{{ isset($dealData['referralAgreement']) ? $dealData['referralAgreement'] : '' }}"
                                                            class="form-control" id="referralAgreement">
                                                        <div class="referralAgreement_error text-danger"
                                                            id="referralAgreement_error"></div>
                                                    </div>
                                                    <div class="hasW9Provided label-div-mb">
                                                        <label for="hasW9Provided" class="common-label">Has the W-9 been
                                                            provided</label>
                                                        <input type="text"
                                                            value="{{ isset($dealData['hasW9Provided']) ? $dealData['hasW9Provided'] : '' }}"
                                                            class="form-control" id="hasW9Provided">
                                                        <div class="hasW9Provided_error text-danger"
                                                            id="hasW9Provided_error"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Home Warranty Paid Out Agent -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingTwo">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapseHomeWarranty1"
                                                aria-expanded="false" aria-controls="collapseHomeWarranty1">
                                                Home Warranty Paid Out Agent?
                                                <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18"
                                                    viewBox="0 0 19 18" fill="none">
                                                    <mask id="mask0_2151_10662" style="mask-type:alpha"
                                                        maskUnits="userSpaceOnUse" x="0" y="0" width="19"
                                                        height="18">
                                                        <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                                    </mask>
                                                    <g mask="url(#mask0_2151_10662)">
                                                        <path
                                                            d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z"
                                                            fill="#AC5353" />
                                                    </g>
                                                </svg>
                                            </button>
                                        </h2>
                                        <div id="collapseHomeWarranty1" class="accordion-collapse collapse"
                                            aria-labelledby="headingTwo">
                                            <div class="accordion-body">
                                                <label class="switch">
                                                    <input type="checkbox" id="homeWarrantyYes"
                                                        {{ isset($dealData['home_warranty_paid_out_agent']) && $dealData['home_warranty_paid_out_agent'] === 'YES' ? 'checked' : '' }}>
                                                    <span class="slider"></span>
                                                </label>
                                                <div class="homeWarrentyFields label-div-mb"
                                                    style="margin-top:10px;display:none;">
                                                    <div class="homeWarrentyAmount label-div-mb">
                                                        <label for="homeWarrentyAmount" class="common-label">Home Warranty
                                                            Amount</label>
                                                        <input type="text"
                                                            value="{{ isset($dealData['homeWarrentyAmount']) ? $dealData['homeWarrentyAmount'] : '' }}"
                                                            class="form-control" id="homeWarrentyAmount">
                                                        <div class="homeWarrentyAmount_error text-danger"
                                                            id="homeWarrentyAmount_error"></div>
                                                    </div>
                                                    <div class="homeWarrentyDescription label-div-mb">
                                                        <label for="homeWarrentyDescription" class="common-label">Home
                                                            Warranty Description</label>
                                                        <input type="text"
                                                            value="{{ isset($dealData['homeWarrentyDescription']) ? $dealData['homeWarrentyDescription'] : '' }}"
                                                            class="form-control" id="homeWarrentyDescription">
                                                        <div class="homeWarrentyDescription_error text-danger"
                                                            id="homeWarrentyDescription_error"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Additional Fees Charged -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingThree">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapseAdditionalFees"
                                                aria-expanded="false" aria-controls="collapseAdditionalFees">
                                                Any Additional Fees Charged?
                                                <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18"
                                                    viewBox="0 0 19 18" fill="none">
                                                    <mask id="mask0_2151_10662" style="mask-type:alpha"
                                                        maskUnits="userSpaceOnUse" x="0" y="0" width="19"
                                                        height="18">
                                                        <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                                    </mask>
                                                    <g mask="url(#mask0_2151_10662)">
                                                        <path
                                                            d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z"
                                                            fill="#AC5353" />
                                                    </g>
                                                </svg>
                                            </button>
                                        </h2>
                                        <div id="collapseAdditionalFees" class="accordion-collapse collapse"
                                            aria-labelledby="headingThree">
                                            <div class="accordion-body">
                                                <label class="switch">
                                                    <input type="checkbox" name="additionalFees" value="yes"
                                                        id="additionalFeesYes"
                                                        {{ isset($dealData['any_additional_fees_charged']) && $dealData['any_additional_fees_charged'] === 'YES' ? 'checked' : '' }}>
                                                    <span class="slider"></span>
                                                </label>



                                                <div class="additionalFeesFields label-div-mb"
                                                    style="margin-top:10px;display:none;">
                                                    <div class="additionalFeeAmount label-div-mb">
                                                        <label for="additionalFeeAmount" class="common-label">Additional
                                                            Fee Amount</label>
                                                        <input type="text"
                                                            value="{{ isset($dealData['additionalFeesAmount']) ? $dealData['additionalFeesAmount'] : '' }}"
                                                            class="form-control " id="additionalFeeAmount">
                                                        <div class="additionalFeeAmount_error text-danger"
                                                            id="additionalFeeAmount_error"></div>
                                                    </div>
                                                    <div class="additionalFeeDescription label-div-mb">
                                                        <label for="additionalFeeDescription"
                                                            class="common-label">Additional Fee Description</label>
                                                        <input type="text"
                                                            value="{{ isset($dealData['additionalFeesDescription']) ? $dealData['additionalFeesDescription'] : '' }}"
                                                            class="form-control " id="additionalFeeDescription">
                                                        <div class="additionalFeeDescription_error text-danger"
                                                            id="additionalFeeDescription_error"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </section>

                        <h3>Basic Information</h3>
                        <section>
                            <form>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label>Final Purchase Price <svg xmlns="http://www.w3.org/2000/svg"
                                                    width="19" height="18" viewBox="0 0 19 18" fill="none">
                                                    <mask id="mask0_2151_10662" style="mask-type:alpha"
                                                        maskUnits="userSpaceOnUse" x="0" y="0" width="19"
                                                        height="18">
                                                        <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                                    </mask>
                                                    <g mask="url(#mask0_2151_10662)">
                                                        <path
                                                            d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z"
                                                            fill="#AC5353" />
                                                    </g>
                                                </svg></label>
                                            <input type="text"
                                                value="{{ isset($dealData['final_purchase_price']) ? $dealData['final_purchase_price'] : '' }}"
                                                placeholder="$" class="form-control required-field integer-field" id="final_purchase">
                                         
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="basicpill-lastname-input">Amount to CHR GIves </label>
                                            <input type="text"
                                                value="{{ isset($dealData['amount_to_chr_gives']) ? $dealData['amount_to_chr_gives'] : '' }}"
                                                placeholder="$" class="form-control nontm-input integer-field" id="amount_chr">
                                            <div id="amount_chr_error" class="text-danger">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="basicpill-email-input">Checks Payable to</label>
                                                <select name="additional_charge" id="additonal_fee"
                                                    class="form-select second-step-common-select select-mb24"
                                                    id="">
                                                    <option value="" selected>{{ $deal->userData->name }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="basicpill-phoneno-input">Agent
                                                    Comments/Remarks/Instructions</label>
                                                <textarea placeholder="Add Copy" id="agent_comments" class="form-control" rows="2"
                                                    placeholder="Enter Your Address">{{ isset($dealData['agent_comments']) ? $dealData['agent_comments'] : '' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="basicpill-phoneno-input">Other Commission Notes</label>
                                                <textarea placeholder="Add Copy" id="other_comm_notes" class="form-control" rows="2"
                                                    placeholder="Enter Your Address">{{ isset($dealData['other_commission_notes']) ? $dealData['other_commission_notes'] : '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                            </form>
                        </section>

                        <h3>Resubmittal Information</h3>
                        <section>
                            <div>
                                <form>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="resubmitting_why_list_all_changes">Resubmitting? Why? --LIST
                                                    ALL CHANGES--</label>
                                                <textarea class="form-control" id="resubmitting_why_list_all_changes" aria-label="With textarea">{{ $dealData['resubmitting_why_list_all_changes'] }}</textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 d-flex gap-2">
                                            <div>Re-Submitted</div>
                                            <div><input type="checkbox" id="resubmit_text" <?php if ($dealData['resubmit_text']) {
                                                echo 'checked';
                                            } ?> disabled>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </section>
                    </div>

                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->
    </div>

@endsection
<!-- jquery step -->
<script defer src="{{ URL::asset('build/libs/jquery-steps/build/jquery.steps.min.js') }}"></script>

<script type="text/javascript">
    var dealData = @json($dealData);

    window.onload = function() {
        const $stepsContainer = $('#basic-example-nontm-view');

        function initializeSteps() {
            $stepsContainer.steps({
                headerTag: "h3",
                enableAllSteps: true,
                bodyTag: "section",
                transitionEffect: "slide",

                onStepChanging: function(event, currentIndex, newIndex) {
                    // Perform validation before allowing step change
                    const isValid = validateStep(currentIndex);
                    return isValid;
                },
                onFinished: function(event, currentIndex) {
                    // API call here
                    window.updateNonTm(dealData, true);
                }
            });
        }

        // Initial steps plugin setup
        initializeSteps();

        // Initialize the values object
        window.values = {};
        
        const fieldUpdates = {
            'Referral Fee Paid Out?': [
                { id: 'referralFeeAmount', required: true,integer:'integer-field' },
                { id: 'referralFeeBrokerage', required: true,integer:'' },
                { id: 'referralAgreement', required: true ,integer:''},
                { id: 'hasW9Provided', required: true ,integer:''}
            ],
            'Home Warranty Paid Out Agent?': [
                { id: 'homeWarrentyAmount', required: true ,integer:'integer-field'},
                { id: 'homeWarrentyDescription', required: true ,integer:''}
            ],
            'Any Additional Fees Charged?': [
                { id: 'additionalFeeAmount', required: true ,integer:'integer-field'},
                { id: 'additionalFeeDescription', required: true,integer:'' }
            ]
        };
        // Handle checkbox state change
        document.addEventListener('change', event => {
            if (event.target.matches('input[type="checkbox"]')) {
                const checkbox = event.target;
                const question = checkbox.closest('.accordion-item').querySelector('button').textContent
                    .trim();
                const value = checkbox.checked ? 'YES' : 'NO';
                console.log(question,'sdfjsldf')
                // Update the window.values object
                window.values[question] = value;

                // Update the UI based on the new values
                openNewFields();
                const updateFields = (question, value) => {
            const updates = fieldUpdates[question];
            if (updates) {
                updates.forEach(({ id, required,integer }) => {
                    const element = document.getElementById(id);
                    if (element) {
                        if (required) {
                            value === 'YES' ? element.classList.add('required-field','validate',integer) : element.classList.remove('required-field');
                        }
                    }
                });
            }
        };

        updateFields(question, value);
            }
        });

        console.log(window.values,'window.values')


        // Get all accordion buttons and toggle their collapse elements
        const accordionButtons = document.querySelectorAll('.accordion-button');
        accordionButtons.forEach((button) => {
            const accordionItem = button.closest('.accordion-item');
            const collapseElement = accordionItem.querySelector('.accordion-collapse');

            button.addEventListener('click', () => {
                collapseElement.classList.toggle('show');
                button.classList.toggle('collapsed');
                button.setAttribute('aria-expanded', collapseElement.classList.contains('show') ?
                    'true' : 'false');
            });
        });

        function openNewFields() {
            let referralData = window.values['Referral Fee Paid Out?'];
            let homeWarrentyData = window.values['Home Warranty Paid Out Agent?'];
            let additionalFees = window.values['Any Additional Fees Charged?'];
            $(".referralCustomFields").toggle(referralData === 'YES');
            $(".homeWarrentyFields").toggle(homeWarrentyData === 'YES');
            $(".additionalFeesFields").toggle(additionalFees === 'YES');

        }

        function isValidUrl(url) {
            const regex = new RegExp(
                '^(https?:\\/\\/)' + // protocol
                '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.?)+[a-z]{2,}|' + // domain name
                '((\\d{1,3}\\.){3}\\d{1,3})|' + // OR ip (v4) address
                '\\[?[a-f\\d]*:[a-f\\d:]+\\]?)' + // OR ip (v6) address
                '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // port and path
                '(\\?[;&a-z\\d%_.~+=-]*)?' + // query string
                '(\\#[-a-z\\d_]*)?$', 'i' // fragment locator
            );
            return regex.test(url);
        }

        // Initialize the values object with the current state of checkboxes
        function initializeCheckboxes() {
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                const accordionItem = checkbox.closest('.accordion-item');
                const button = accordionItem ? accordionItem.querySelector('button') : null;
                const question = button ? button.textContent.trim() : '';
                const value = checkbox.checked ? 'YES' : 'NO';
                window.values[question] = value;
            });

            // Update the UI based on initial values
                openNewFields();
            
            console.log(window.values,'window.values intitiallallall')
        }
        // Call initializeCheckboxes when the page loads
        initializeCheckboxes();


        function validateStep(stepIndex) {
                let isValid = true;
                const $currentSection = $stepsContainer.find(`section:eq(${stepIndex})`);
                // Check required fields in this section
                $currentSection.find('.required-field').each(function() {
                    if ($(this).val() === "-None-") {
                        isValid = false;
                        $(this).addClass('error');
                    } else {
                        $(this).removeClass('error'); // Remove error class if valid
                    }
                    if (!$(this).val()) {
                        isValid = false;
                        $(this).addClass('error'); // Add error class for styling
                    } else {
                        $(this).removeClass('error'); // Remove error class if valid
                    }
                });

                // Check radio buttons
                $currentSection.find('input[type="radio"]').each(function() {
                    const name = $(this).attr('name');
                    const $radioGroup = $currentSection.find(`input[name="${name}"]`);
                    if ($radioGroup.filter(':checked').length === 0) {
                        isValid = false;
                        $radioGroup.addClass('error'); // Add error class for styling
                    } else {
                        $radioGroup.removeClass('error'); // Remove error class if valid
                    }
                });

                let message = "Please fill out all required fields";
                const invalidFields = [];
                  // Check integer fields
                $currentSection.find('.integer-field').each(function() {
                    const value = $(this).val();
                    const $label = $(this).siblings('label');
                    const labelText = $label.text().trim(); 
                    console.log(labelText,'$label')
                    if (!/^\d+$/.test(value)) { // Regular expression to check for integers
                        isValid = false;
                        $(this).addClass('error'); // Add error class for styling
                        $(this).siblings('.error-message').text('Please enter a valid integer.'); // Show error message
                        invalidFields.push(labelText);
                    } else {
                        $(this).removeClass('error'); // Remove error class if valid
                        $(this).siblings('.error-message').text(''); // Clear error message
                    }
                    message = `Invalid ${labelText} format`;
                });
                // After checking all fields, report the invalid fields
            if (invalidFields.length > 0) {
                console.log('Invalid fields:', invalidFields.join(', '));
                // You can also show an overall error message or handle the invalid fields as needed
                showToastError('Please correct the following fields: ' + invalidFields.join(', '));
                return;
            } else {
                console.log('All fields are valid.');
            }

                // Optionally, display a message or highlight errors if invalid
                if (!isValid) {
                    showToastError(message);
                }

        return isValid;
    }


    };


    window.updateNonTm = function(dealData, status) {
        // dealData = JSON.parse(dealData)
        console.log(dealData);
        id = dealData.id

        let related_transaction = document.getElementById("related_transaction");
        let add_email = document.getElementById("add_email");
        let close_date = document.getElementById("close_date");
        let commission = document.getElementById("commission");
        let final_purchase = document.getElementById("final_purchase");
        let amount_chr = document.getElementById("amount_chr");
        // let additonal_fee = document.getElementById("additonal_fee");
        let other_comm_notes = document.getElementById("other_comm_notes");
        let agent_comments = document.getElementById("agent_comments");
        let resubmitting_why_list_all_changes = document.getElementById("resubmitting_why_list_all_changes");
        let referralFeeAmount = document.getElementById("referralFeeAmount");
        let referralFeeBrokerage = document.getElementById("referralFeeBrokerage");
        let referralAgreement = document.getElementById("referralAgreement");
        let hasW9Provided = document.getElementById("hasW9Provided");
        let homeWarrentyAmount = document.getElementById("homeWarrentyAmount");
        let homeWarrentyDescription = document.getElementById("homeWarrentyDescription");
        let additionalFeeAmount = document.getElementById("additionalFeeAmount");
        let additionalFeeDescription = document.getElementById("additionalFeeDescription");
        var selectedOption = related_transaction.options[related_transaction.selectedIndex];
        // Get the value and text of the selected option
        var selectedValue = selectedOption.value;
        var selectedText = selectedOption.textContent;
        let formData = {
            "data": [{
                "Commission": commission?.value?.trim().split('.')[0] ?? undefined,
                "Final_Purchase_Price": final_purchase ? final_purchase.value.trim() : undefined,
                "Any_Additional_Fees_Charged": window?.values["Any Additional Fees Charged?"] ? window
                    ?.values["Any Additional Fees Charged?"]
                    ?.toUpperCase() : undefined,
                "Additonal_Fees_Amount": additionalFeeAmount ? additionalFeeAmount.value.trim() :
                    undefined,
                "Additional_Fees_Description": additionalFeeDescription ? additionalFeeDescription.value
                    .trim() :
                    undefined,
                "Additional_Email_for_Confirmation": add_email ? add_email.value.trim() : undefined,
                "Referral_Fee_Paid_Out": window.values['Referral Fee Paid Out?'] ? window.values[
                    'Referral Fee Paid Out?']?.toUpperCase() : undefined,
                "Referral_Fee_Amount": referralFeeAmount ? referralFeeAmount.value.trim() : undefined,
                "Referral_Fee_Brokerage_Name": referralFeeBrokerage ? referralFeeBrokerage.value
                .trim() : undefined,
                "Referral_Fee_Agreement_Executed": referralAgreement ? referralAgreement.value.trim() :
                    undefined,
                "Has_the_W-9_been_provided": hasW9Provided ? hasW9Provided.value.trim() : undefined,
                "Close_Date": close_date ? close_date.value.trim() : undefined,
                "Home_Warranty_Paid_by_Agent": window.values['Home Warranty Paid Out Agent?'] ? window
                    .values['Home Warranty Paid Out Agent?']?.toUpperCase() : undefined,
                "Home_Warranty_Amount": homeWarrentyAmount ? homeWarrentyAmount.value.trim() :
                    undefined,
                "Home_Warranty_Description": homeWarrentyDescription ? homeWarrentyDescription.value
                    .trim() : undefined,
                "CHR_Gives_Amount_to_Give": amount_chr ? amount_chr.value.trim() : undefined,
                "Other_Commission_Notes": other_comm_notes.value ? other_comm_notes.value.trim() :
                    undefined,
                "Agent_Comments_Remarks_Instructions": agent_comments.value ? agent_comments.value
                .trim() : undefined,
                "Resubmitting_Why_LIST_ALL_CHANGES": resubmitting_why_list_all_changes.value ?
                    resubmitting_why_list_all_changes.value.trim() : undefined,
                "resubmit_text": true,
                "Related_Transaction": selectedValue ? {
                    "id": selectedValue.trim(),
                    "name": selectedText.trim(),
                } : undefined,
            }]
        }

        formData.data[0] = Object.fromEntries(
            Object.entries(formData.data[0]).filter(([_, value]) => value !== undefined)
        );

        $.ajax({
            url: '/nontm-update/' + id + '?status=' + status,
            type: 'PUT',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            contentType: 'application/json',
            dataType: 'json',
            data: JSON.stringify(formData),
            success: function(response) {
                if (response?.data && response.data[0]?.message) {
                    // Convert message to uppercase and then display
                    const upperCaseMessage = response.data[0].message.toUpperCase();
                    showToast(upperCaseMessage);
                    window.location.href = "/pipeline-view/" + dealData.deal_data.id;
                }
            },
            error: function(xhr, status, error) {
                // Handle error response
                console.error(xhr.responseText);
            }
        })

    }
</script>
