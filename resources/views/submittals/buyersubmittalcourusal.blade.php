<div class="row justify-content-center">
    <div class="col-xl-10 align-items-center">
        <div class="card">
            <div class="card-body p-0">

                <h4 class="card-title" id="title-corousal">Basic Information</h4>

                <div id="carouselExampleIndicators" class="carousel slide" data-interval="false">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0"
                            class="active" aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"
                            aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"
                            aria-label="Slide 3"></button>
                            <button type="button" onclick="document.getElementById('title-corousal').innerHTML='CHR TM - Commission Details'" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="3"
                            aria-label="Slide 4"></button>

                            <button type="button" onclick="document.getElementById('title-corousal').innerHTML='CHR TM - Service Providers'" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="4"
                            aria-label="Slide 5"></button>
                            <button type="button" onclick="document.getElementById('title-corousal').innerHTML='CHR TM - Select MLS'"  data-bs-target="#carouselExampleIndicators" data-bs-slide-to="5"
                            aria-label="Slide 6"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="6"
                            aria-label="Slide 7"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="7"
                            aria-label="Slide 8"></button>
                    </div>
                    <div class="carousel-inner" role="listbox">
                        <div class="carousel-item active">
                            <div class="related_trxn label-div-mb">
                                <label for="relatedto" class="common-label">Related Transaction <svg
                                        xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18"
                                        fill="none">
                                        <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0"
                                            y="0" width="19" height="18">
                                            <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                        </mask>
                                        <g mask="url(#mask0_2151_10662)">
                                            <path
                                                d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z"
                                                fill="#AC5353" />
                                        </g>
                                    </svg></label>
                                <div class="nontm-select-div">
                                    <select name="related_transaction" id="related_transaction" class="nontm-select">
                                        {{-- @foreach ($deals as $deal)
                                            <option value="{{ $deal->zoho_deal_id }}"
                                                {{ $deal->zoho_deal_id == $dealData->dealId ? 'selected' : '' }}>
                                                {{ $deal->deal_name }}
                                            </option>
                                        @endforeach --}}
                                    </select>
    
                                    <img src="{{ URL::asset('/images/domain_add.svg') }}" alt="">
                                </div>
                                <div id="related_transaction_error" class="text-danger">
                                </div>
                            </div>
                            <div class="additional_email label-div-mb">
                                <label for="add_email" class="common-label">Additional Email for Confirmation</label>
                                <input type="email" value="{{ isset($dealData['email']) ? $dealData['email'] : '' }}"
                                    class="form-control" placeholder="Enter email" id="add_email">
                                <div class="add_email_error text-danger" id="add_email_error">
                                </div>
                            </div>
                            <div class="additional_email label-div-mb">
                                <label for="add_email" class="common-label"> Agent Name on Material</label>
                                <input type="email" value="{{ isset($dealData['email']) ? $dealData['email'] : '' }}"
                                    class="form-control" placeholder="" id="add_email">
                                <div class="add_email_error text-danger" id="add_email_error">
                                </div>
                            </div>
                            <label for="add_email" class="common-label">Comming Soon?</label>
                            <div class="d-flex gap-2">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="formCheck1">
                                <label class="form-check-label" for="formCheck1">
                                   Yes
                                </label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="formCheck1">
                                <label class="form-check-label" for="formCheck1">
                                   No
                                </label>
                            </div>
                        </div>
                        </div>
                        <div class="carousel-item">
                            <div class="close-date-nontm">
                                <label for="close_date" class="common-label">Close Date <svg
                                        xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18"
                                        fill="none">
                                        <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0"
                                            y="0" width="19" height="18">
                                            <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                        </mask>
                                        <g mask="url(#mask0_2151_10662)">
                                            <path
                                                d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z"
                                                fill="#AC5353" />
                                        </g>
                                    </svg></label>
                                <input type="date"
                                    value="{{ isset($dealData['dealData']['closing_date']) ? \Carbon\Carbon::parse($dealData['dealData']['closing_date'])->format('Y-m-d') : '' }}"
                                    class="form-control nontm-input" id="close_date">
                                <div id="close_date_error" class="text-danger">

                                </div>
                            </div>
                            <div class="additional_email label-div-mb">
                                <label for="add_email" class="common-label">Tm Name</label>
                                <input type="email" value="{{ isset($dealData['email']) ? $dealData['email'] : '' }}"
                                    class="form-control" placeholder="" id="add_email">
                                <div class="add_email_error text-danger" id="add_email_error">
                                </div>
                            </div>
                            <div class="close-date-nontm">
                                <label for="close_date" class="common-label">Active Date <svg
                                        xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18"
                                        fill="none">
                                        <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0"
                                            y="0" width="19" height="18">
                                            <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                        </mask>
                                        <g mask="url(#mask0_2151_10662)">
                                            <path
                                                d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z"
                                                fill="#AC5353" />
                                        </g>
                                    </svg></label>
                                <input type="date"
                                    value="{{ isset($dealData['dealData']['closing_date']) ? \Carbon\Carbon::parse($dealData['dealData']['closing_date'])->format('Y-m-d') : '' }}"
                                    class="form-control nontm-input" id="close_date">
                                <div id="close_date_error" class="text-danger">

                                </div>
                            </div>
                            <div class="row">
                                <div class="row col-xl-6">
                                    <label for="add_email" class="common-label">Listing Agreement Executed?</label>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="formCheck1">
                                <label class="form-check-label" for="formCheck1">
                                   Yes
                                </label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="formCheck1">
                                <label class="form-check-label" for="formCheck1">
                                   No
                                </label>
                            </div>
                        </div>
                            <div class="close-date-nontm col-lg-6">
                                <label for="close_date" class="common-label">Price <svg
                                        xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18"
                                        fill="none">
                                        <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0"
                                            y="0" width="19" height="18">
                                            <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                        </mask>
                                        <g mask="url(#mask0_2151_10662)">
                                            <path
                                                d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z"
                                                fill="#AC5353" />
                                        </g>
                                    </svg></label>
                                <input type="text"
                                    value="{{ isset($dealData['dealData']['sale_price']) ? $dealData['dealData']['sale_price'] : '' }}"
                                    placeholder="$" class="form-control nontm-input" id="final_purchase">
                                <div id="final_purchase_error" class="text-danger">
    
                                </div>
                            </div>
                        </div>
                       
                        </div>
                        <div class="carousel-item">
                            <div class="close-date-nontm">
                                <label for="close_date" class="common-label">Photo Date<svg
                                        xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18"
                                        fill="none">
                                        <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0"
                                            y="0" width="19" height="18">
                                            <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                        </mask>
                                        <g mask="url(#mask0_2151_10662)">
                                            <path
                                                d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z"
                                                fill="#AC5353" />
                                        </g>
                                    </svg></label>
                                <input type="date"
                                    value="{{ isset($dealData['dealData']['closing_date']) ? \Carbon\Carbon::parse($dealData['dealData']['closing_date'])->format('Y-m-d') : '' }}"
                                    class="form-control nontm-input" id="close_date">
                                <div id="close_date_error" class="text-danger">

                                </div>
                            </div>
                            <div class="additional_email label-div-mb">
                                <label for="add_email" class="common-label">Photo Url</label>
                                <input type="email" value="{{ isset($dealData['email']) ? $dealData['email'] : '' }}"
                                    class="form-control" placeholder="Enter email" id="add_email">
                                <div class="add_email_error text-danger" id="add_email_error">
                                </div>
                            </div>
                            <div class="additional_email label-div-mb">
                                <label for="add_email" class="common-label">Beds,baths,total sq.ft.</label>
                                <input type="email" value="{{ isset($dealData['email']) ? $dealData['email'] : '' }}"
                                    class="form-control" placeholder="Enter email" id="add_email">
                                <div class="add_email_error text-danger" id="add_email_error">
                                </div>
                            </div>
                            <div class="additional_email label-div-mb">
                                <label for="add_email" class="common-label">3D Tour URL</label>
                                <input type="email" value="{{ isset($dealData['email']) ? $dealData['email'] : '' }}"
                                    class="form-control" placeholder="Enter email" id="add_email">
                                <div class="add_email_error text-danger" id="add_email_error">
                                </div>
                            </div>
                            
                            <label for="add_email" class="common-label">Using CHR TM</label>
                            <div class="d-flex gap-2">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="formCheck1">
                                    <label class="form-check-label" for="formCheck1">
                                       Yes
                                    </label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="formCheck1">
                                    <label class="form-check-label" for="formCheck1">
                                       No
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                                <div class="row">
                                    <div class='pb-4 col-lg-6'>
                                        <label for="payable" class="common-label">Fees Charged to Seller at Closing</label>
                                        <input name="additional_charge" id="additonal_fee"
                                            class="form-control second-step-common-select select-mb24">
                                    </input>
                                    </div>
                                    <div class="col-lg-6 commission-nontm">
                                        <div class='pb-4'>
                                            <label for="payable" class="common-label">Referral to Pay</label>
                                            <select name="additional_charge" id="additonal_fee"
                                                class="form-select second-step-common-select select-mb24" id="">
                                                <option value="" selected>None</option>
                                                <option value="Yes" >Yes</option>
                                                <option value="No" >No</option>
                                            </select>
                                        </div>
            
                                    </div>
                                </div>
                                <div class="row">
                                    <div class='pb-4 col-lg-6'>
                                        <label for="payable" class="common-label">Amount to CHR Gives</label>
                                        <input name="additional_charge" id="additonal_fee"
                                        class="form-control second-step-common-select select-mb24">
                                </input>
                                    </div>
                                    <div class="col-lg-6 commission-nontm">
                                        <div class='pb-4'>
                                            <label for="payable" class="common-label">Referral Details</label>
                                            <input name="additional_charge" id="additonal_fee"
                                            class="form-control second-step-common-select select-mb24">
                                    </input>
                                        </div>
            
                                    </div>
                                </div>
                        </div>
                        <div class="carousel-item">
                            <div class="row">
                                <div class="col-lg-6">
                                    <label for="">Schedule Sign Install</label>
                                    <div class="row">                                   
                                        <div class="form-check mb-3 col-md-6">
                                       <input class="form-check-input" type="checkbox" id="formCheck2">Yes
                                   </div>
                                   <div class="form-check mb-3 col-md-6">
                                       <input class="form-check-input" type="checkbox" id="formCheck3">No
                                   </div>
                               </div>

                                </div>
                                <div class="col-lg-6">
                                    <label for="">Concierge Listing (Optional)</label>
                                    <div class="row">                                   
                                         <div class="form-check mb-3 col-md-6">
                                        <input class="form-check-input" type="checkbox" id="formCheck2">Yes
                                    </div>
                                    <div class="form-check mb-3 col-md-6">
                                        <input class="form-check-input" type="checkbox" id="formCheck3">No
                                    </div>
                                </div>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <label for="">Draft Showing Instructions?</label>
                                    <div class="row">                                   
                                        <div class="form-check mb-3 col-md-6">
                                       <input class="form-check-input" type="checkbox" id="formCheck2">Yes
                                   </div>
                                   <div class="form-check mb-3 col-md-6">
                                       <input class="form-check-input" type="checkbox" id="formCheck3">No
                                   </div>
                               </div>

                                </div>
                                <div class="col-lg-6">
                                    <label for="">Title Company</label>                          
                                        <input name="additional_charge" id="additonal_fee"
                                            class="form-control second-step-common-select select-mb24">
                                </div>
                                <div class="col-lg-6">
                                    <label for="">Closer Name & Phone</label>                          
                                        <input name="additional_charge" id="additonal_fee"
                                            class="form-control second-step-common-select select-mb24">
                                </div>
                                <div class="col-lg-6">
                                    <label for="">Sign Install Vendor (if Other)</label>                          
                                        <input name="additional_charge" id="additonal_fee"
                                            class="form-control second-step-common-select select-mb24">
                                </div>
                                <div class="close-date-nontm">
                                    <label for="close_date" class="common-label">Sign Install Date<svg
                                            xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18"
                                            fill="none">
                                            <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0"
                                                y="0" width="19" height="18">
                                                <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                            </mask>
                                            <g mask="url(#mask0_2151_10662)">
                                                <path
                                                    d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z"
                                                    fill="#AC5353" />
                                            </g>
                                        </svg></label>
                                    <input type="date"
                                        value="{{ isset($dealData['dealData']['closing_date']) ? \Carbon\Carbon::parse($dealData['dealData']['closing_date'])->format('Y-m-d') : '' }}"
                                        class="form-control nontm-input" id="close_date">
                                    <div id="close_date_error" class="text-danger">
    
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                          <div class="row mb-4">
                               <div class="col-lg-4">
                                REColorado<input type="checkbox" class="type">
                               </div>
                               <div class="col-lg-4">
                                Navica <input type="checkbox" class="type">
                           </div>
                           <div class="col-lg-4">
                            PPAR<input type="checkbox" class="type">
                       </div>
                          </div>
                          <div class="row mb-4">
                            <div class="col-lg-4">Grand County<input type="checkbox" class="type">
                            </div>
                            <div class="col-lg-4">IRES <input type="checkbox" class="type">
                            </div>
                        </div>
                        <div class="row">
                        <div class="col-lg-6">
                            <label for="commission" class="common-label">MLS public remarks</label>
                            <input type="textarea"
                                value="{{ isset($dealData['agent_comments']) ? $dealData['agent_comments'] : '' }}"
                                placeholder="Add Copy" class="form-control nontm-input-textarea" id="agent_comments">
                    </div>
                    <div class="col-lg-6">
                        
                            <label for="commission" class="common-label">MLS private remarks</label>
                            <input type="textarea"
                                value="{{ isset($dealData['other_commission_notes']) ? $dealData['other_commission_notes'] : '' }}"
                                placeholder="Add Copy" class="form-control nontm-input-textarea" id="other_comm_notes">
                    </div>
                </div>
                    </div>
                        <div class="carousel-item">
                            <img class="d-block img-fluid" src="{{ URL::asset('build/images/small/img-2.jpg') }}"
                                alt="Second slide">
                        </div>
                        <div class="carousel-item">
                            <img class="d-block img-fluid" src="{{ URL::asset('build/images/small/img-1.jpg') }}"
                                alt="Third slide">
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