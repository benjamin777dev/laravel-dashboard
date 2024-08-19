<div class="card mt-4">
    <div class="card-body">
        <h4 class="card-title mb-4">Listing Submittal Defaults</h4>
        <form method="POST" id="update-agent-listing-submittal-defaults-form">
            @csrf
            <input type="hidden" value="{{ Auth::user()->contact->id }}" id="id">

            <!-- Agent Name on Material -->
            <div class="mb-3">
                <label for="agent_name_on_marketing" class="form-label">Agent Name on Material</label>
                <input type="text" class="form-control @error('agent_name_on_marketing') is-invalid @enderror"
                    value="{{ Auth::user()->contact->agent_name_on_marketing }}" id="agent_name_on_marketing" name="agent_name_on_marketing"
                    placeholder="Agent Name on Material?"> 
                @error('agent_name_on_marketing')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Using CHR TM -->
            <div class="mb-3">
                <label for="tm_preference" class="form-label">Using CHR TM?</label>
                <div class="form-check form-switch">
                    <input class="form-check-input @error('tm_preference') is-invalid @enderror" type="checkbox" id="tm_preference" name="tm_preference"
                        {{ Auth::user()->contact->tm_preference ? 'checked' : '' }}>
                    @error('tm_preference')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Additional Email for Confirmations -->
            <div class="mb-3">
                <label for="additional_email_for_confirmation" class="form-label">Additional Email for Confirmations</label>
                <input type="text" class="form-control @error('additional_email_for_confirmation') is-invalid @enderror"
                    value="{{ Auth::user()->contact->additional_email_for_confirmation }}" id="additional_email_for_confirmation" name="additional_email_for_confirmation"
                    placeholder="Additional Email for Confirmations?"> 
                @error('additional_email_for_confirmation')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email to CC on All Marketing Comms -->
            <div class="mb-3">
                <label for="email_to_cc_on_all_marketing_comms" class="form-label">Email to CC on All Marketing Comms</label>
                <input type="text" class="form-control @error('email_to_cc_on_all_marketing_comms') is-invalid @enderror"
                    value="{{ Auth::user()->contact->email_to_cc_on_all_marketing_comms }}" id="email_to_cc_on_all_marketing_comms" name="email_to_cc_on_all_marketing_comms"
                    placeholder="Email to CC on all Marketing Comms?"> 
                @error('email_to_cc_on_all_marketing_comms')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Need O&E -->
            <div class="mb-3">
                <label for="need_o_e" class="form-label">Need O&E?</label>
                <div class="form-check form-switch">
                    <input class="form-check-input @error('need_o_e') is-invalid @enderror" type="checkbox" id="need_o_e" name="need_o_e"
                        {{ Auth::user()->contact->need_o_e ? 'checked' : '' }}>
                    @error('need_o_e')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Include Insights in Intro -->
            <div class="mb-3">
                <label for="include_insights_in_intro" class="form-label">Include Insights in Intro?</label>
                <div class="form-check form-switch">
                    <input class="form-check-input @error('include_insights_in_intro') is-invalid @enderror" type="checkbox" id="include_insights_in_intro" name="include_insights_in_intro"
                        {{ Auth::user()->contact->include_insights_in_intro ? 'checked' : '' }}>
                    @error('include_insights_in_intro')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Schedule Sign Install -->
            <div class="mb-3">
                <label for="sign_install" class="form-label">Schedule Sign Install?</label>
                <div class="form-check form-switch">
                    <input class="form-check-input @error('sign_install') is-invalid @enderror" type="checkbox" id="sign_install" name="sign_install"
                        {{ Auth::user()->contact->sign_install ? 'checked' : '' }}>
                    @error('sign_install')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Sign Install Vendor -->
            <div class="mb-3">
                <label for="sign_vendor" class="form-label">Sign Install Vendor</label>
                <input type="text" class="form-control @error('sign_vendor') is-invalid @enderror"
                    value="{{ Auth::user()->contact->sign_vendor }}" id="sign_vendor" name="sign_vendor"
                    placeholder="Sign Install Vendor"> 
                @error('sign_vendor')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Draft Showing Instructions -->
            <div class="mb-3">
                <label for="draft_showing_instructions" class="form-label">Draft Showing Instructions?</label>
                <input type="text" class="form-control @error('draft_showing_instructions') is-invalid @enderror"
                    value="{{ Auth::user()->contact->draft_showing_instructions }}" id="draft_showing_instructions" name="draft_showing_instructions"
                    placeholder="Draft Showing Instructions?"> 
                @error('draft_showing_instructions')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Title Company -->
            <div class="mb-3">
                <label for="title_company" class="form-label">Title Company</label>
                <input type="text" class="form-control @error('title_company') is-invalid @enderror"
                    value="{{ Auth::user()->contact->title_company }}" id="title_company" name="title_company"
                    placeholder="Title Company"> 
                @error('title_company')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Closer Name & Phone -->
            <div class="mb-3">
                <label for="closer_name_phone" class="form-label">Closer Name & Phone</label>
                <input type="text" class="form-control @error('closer_name_phone') is-invalid @enderror"
                    value="{{ Auth::user()->contact->closer_name_phone }}" id="closer_name_phone" name="closer_name_phone"
                    placeholder="Closer Name and Phone"> 
                @error('closer_name_phone')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- MLS-ReColorado -->
            <div class="mb-3">
                <label for="mls_recolorado" class="form-label">MLS-ReColorado</label>
                <div class="form-check form-switch">
                    <input class="form-check-input @error('mls_recolorado') is-invalid @enderror" type="checkbox" id="mls_recolorado" name="mls_recolorado"
                        {{ Auth::user()->contact->mls_recolorado ? 'checked' : '' }}>
                    @error('mls_recolorado')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- MLS-PPAR -->
            <div class="mb-3">
                <label for="mls_ppar" class="form-label">MLS-PPAR</label>
                <div class="form-check form-switch">
                    <input class="form-check-input @error('mls_ppar') is-invalid @enderror" type="checkbox" id="mls_ppar" name="mls_ppar"
                        {{ Auth::user()->contact->mls_ppar ? 'checked' : '' }}>
                    @error('mls_ppar')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- MLS-IRES -->
            <div class="mb-3">
                <label for="mls_ires" class="form-label">MLS-IRES</label>
                <div class="form-check form-switch">
                    <input class="form-check-input @error('mls_ires') is-invalid @enderror" type="checkbox" id="mls_ires" name="mls_ires"
                        {{ Auth::user()->contact->mls_ires ? 'checked' : '' }}>
                    @error('mls_ires')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- MLS-Navica -->
            <div class="mb-3">
                <label for="mls_navica" class="form-label">MLS-Navica</label>
                <div class="form-check form-switch">
                    <input class="form-check-input @error('mls_navica') is-invalid @enderror" type="checkbox" id="mls_navica" name="mls_navica"
                        {{ Auth::user()->contact->mls_navica ? 'checked' : '' }}>
                    @error('mls_navica')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Fees Charged to Seller at Closing -->
            <div class="mb-3">
                <label for="fees_charged_to_seller_at_closing" class="form-label">Fees Charged to Seller at Closing</label>
                <input type="text" class="form-control @error('fees_charged_to_seller_at_closing') is-invalid @enderror"
                    value="{{ Auth::user()->contact->fees_charged_to_seller_at_closing }}" id="fees_charged_to_seller_at_closing" name="fees_charged_to_seller_at_closing"
                    placeholder="Fees Charged to Seller at Closing"> 
                @error('fees_charged_to_seller_at_closing')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Amount to CHR Gives -->
            <div class="mb-3">
                <label for="chr_gives_amount" class="form-label">Amount to CHR Gives</label>
                <input type="text" class="form-control @error('chr_gives_amount') is-invalid @enderror"
                    value="{{ Auth::user()->contact->chr_gives_amount }}" id="chr_gives_amount" name="chr_gives_amount"
                    placeholder="Amount to CHR Gives"> 
                @error('chr_gives_amount')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- 3D Zillow Tour -->
            <div class="mb-3">
                <label for="outsourced_mktg_3d_zillow_tour" class="form-label">3D Zillow Tour?</label>
                <div class="form-check form-switch">
                    <input class="form-check-input @error('outsourced_mktg_3d_zillow_tour') is-invalid @enderror" type="checkbox" id="outsourced_mktg_3d_zillow_tour" name="outsourced_mktg_3d_zillow_tour"
                        {{ Auth::user()->contact->outsourced_mktg_3d_zillow_tour ? 'checked' : '' }}>
                    @error('outsourced_mktg_3d_zillow_tour')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Floor Plans -->
            <div class="mb-3">
                <label for="outsourced_mktg_floorplans" class="form-label">Floor Plans?</label>
                <div class="form-check form-switch">
                    <input class="form-check-input @error('outsourced_mktg_floorplans') is-invalid @enderror" type="checkbox" id="outsourced_mktg_floorplans" name="outsourced_mktg_floorplans"
                        {{ Auth::user()->contact->outsourced_mktg_floorplans ? 'checked' : '' }}>
                    @error('outsourced_mktg_floorplans')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Onsite Video -->
            <div class="mb-3">
                <label for="outsourced_mktg_onsite_video" class="form-label">Onsite Video?</label>
                <div class="form-check form-switch">
                    <input class="form-check-input @error('outsourced_mktg_onsite_video') is-invalid @enderror" type="checkbox" id="outsourced_mktg_onsite_video" name="outsourced_mktg_onsite_video"
                        {{ Auth::user()->contact->outsourced_mktg_onsite_video ? 'checked' : '' }}>
                    @error('outsourced_mktg_onsite_video')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Property Website -->
            <div class="mb-3">
                <label for="property_website_qr_code" class="form-label">Property Website?</label>
                <small>$15 fee</small>
                <div class="form-check form-switch">
                    <input class="form-check-input @error('property_website_qr_code') is-invalid @enderror" type="checkbox" id="property_website_qr_code" name="property_website_qr_code"
                        {{ Auth::user()->contact->property_website_qr_code ? 'checked' : '' }}>
                    @error('property_website_qr_code')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Social Media Images -->
            <div class="mb-3">
                <label for="social_media_images" class="form-label">Social Media Images?</label>
                <div class="form-check form-switch">
                    <input class="form-check-input @error('social_media_images') is-invalid @enderror" type="checkbox" id="social_media_images" name="social_media_images"
                        {{ Auth::user()->contact->social_media_images ? 'checked' : '' }}>
                    @error('social_media_images')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Social Media Ads -->
            <div class="mb-3">
                <label for="social_media_ads" class="form-label">Social Media Ads?</label>
                <div class="form-check form-switch">
                    <input class="form-check-input @error('social_media_ads') is-invalid @enderror" type="checkbox" id="social_media_ads" name="social_media_ads"
                        {{ Auth::user()->contact->social_media_ads ? 'checked' : '' }}>
                    @error('social_media_ads')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Select Your Brochures -->
            <div class="mb-3">
                <label for="select_your_prints" class="form-label">Select Your Brochures?</label>
                <input type="text" class="form-control @error('select_your_prints') is-invalid @enderror"
                    value="{{ Auth::user()->contact->select_your_prints }}" id="select_your_prints" name="select_your_prints"
                    placeholder="Select Your Brochures?"> 
                @error('select_your_prints')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Feature Cards -->
            <div class="mb-3">
                <label for="feature_cards_or_sheets" class="form-label">Feature Cards?</label>
                <div class="form-check form-switch">
                    <input class="form-check-input @error('feature_cards_or_sheets') is-invalid @enderror" type="checkbox" id="feature_cards_or_sheets" name="feature_cards_or_sheets"
                        {{ Auth::user()->contact->feature_cards_or_sheets ? 'checked' : '' }}>
                    @error('feature_cards_or_sheets')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- QR Code Sheet -->
            <div class="mb-3">
                <label for="print_qr_code_sheet" class="form-label">QR Code Sheet?</label>
                <div class="form-check form-switch">
                    <input class="form-check-input @error('print_qr_code_sheet') is-invalid @enderror" type="checkbox" id="print_qr_code_sheet" name="print_qr_code_sheet"
                        {{ Auth::user()->contact->print_qr_code_sheet ? 'checked' : '' }}>
                    @error('print_qr_code_sheet')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- QR Code Sign Rider -->
            <div class="mb-3">
                <label for="qr_code_sign_rider" class="form-label">QR Code Sign Rider?</label>
                <div class="form-check form-switch">
                    <input class="form-check-input @error('qr_code_sign_rider') is-invalid @enderror" type="checkbox" id="qr_code_sign_rider" name="qr_code_sign_rider"
                        {{ Auth::user()->contact->qr_code_sign_rider ? 'checked' : '' }}>
                    @error('qr_code_sign_rider')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mt-3 d-grid">
                <button class="btn btn-primary waves-effect waves-light" type="submit">Update Listing Submittal Defaults</button>
            </div>
        </form>
    </div>
</div>
