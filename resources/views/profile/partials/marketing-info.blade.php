<div class="card">
    <div class="card-body">
        <h4 class="card-title mb-4">Marketing Information</h4>
        <form method="POST" id="update-agent-marketing-information-form">
            @csrf
            <input type="hidden" value="{{ Auth::user()->contact->id }}" id="id">

            <!-- Email Blast Opt-In -->
            <div class="mb-3">
                <label for="email_blast_opt_in" class="form-label">Email Blast Opt-In</label>
                <div class="form-check form-switch">
                    <input class="form-check-input @error('email_blast_opt_in') is-invalid @enderror" type="checkbox" id="email_blast_opt_in" name="email_blast_opt_in"
                        {{ Auth::user()->contact->email_blast_opt_in ? 'checked' : '' }}>
                    @error('email_blast_opt_in')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Notepad Mailer Opt-In -->
            <div class="mb-3">
                <label for="notepad_mailer_opt_in" class="form-label">Notepad Mailer Opt-In</label>
                <div class="form-check form-switch">
                    <input class="form-check-input @error('notepad_mailer_opt_in') is-invalid @enderror" type="checkbox" id="notepad_mailer_opt_in" name="notepad_mailer_opt_in"
                        {{ Auth::user()->contact->notepad_mailer_opt_in ? 'checked' : '' }}>
                    @error('notepad_mailer_opt_in')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Colorado Home Realty Opt-In -->
            <div class="mb-3">
                <label for="market_mailer_opt_in" class="form-label">Colorado Home Realty Opt-In</label>
                <div class="form-check form-switch">
                    <input class="form-check-input @error('market_mailer_opt_in') is-invalid @enderror" type="checkbox" id="market_mailer_opt_in" name="market_mailer_opt_in"
                        {{ Auth::user()->contact->market_mailer_opt_in ? 'checked' : '' }}>
                    @error('market_mailer_opt_in')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Review Generation -->
            <div class="mb-3">
                <label for="review_generation" class="form-label">Review Generation</label>
                <div class="form-check form-switch">
                    <input class="form-check-input @error('review_generation') is-invalid @enderror" type="checkbox" id="review_generation" name="review_generation"
                        {{ Auth::user()->contact->review_generation ? 'checked' : '' }}>
                    @error('review_generation')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Google Business Page URL -->
            <div class="mb-3">
                <label for="google_business_page_url" class="form-label">Google Business Page URL</label>
                <input type="text" class="form-control @error('google_business_page_url') is-invalid @enderror"
                    value="{{ Auth::user()->contact->google_business_page_url }}" id="google_business_page_url" name="google_business_page_url"
                    placeholder="Google Business Page URL">
                @error('google_business_page_url')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mt-3 d-grid">
                <button class="btn btn-primary waves-effect waves-light" type="submit">Update Marketing Information</button>
            </div>
        </form>
    </div>
</div>
