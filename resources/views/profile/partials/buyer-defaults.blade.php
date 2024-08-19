<div class="card mt-4">
            <div class="card-body">
                <h4 class="card-title mb-4">Buyer Submittal Defaults</h4>
                <form method="POST" id="update-agent-buyer-submittal-defaults-form">
                    @csrf
                    <input type="hidden" value="{{ Auth::user()->contact->id }}" id="id">

                    <div class="mb-3">
                        <label for="lender_company_name" class="form-label">Lender Company Name</label>
                        <input type="text" class="form-control currency-input @error('lender_company_name') is-invalid @enderror"
                            value="{{ Auth::user()->contact->lender_company_name }}" id="lender_company_name" name="lender_company_name"
                            placeholder="Lender Company Name"> 
                        @error('lender_company_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="lender_email" class="form-label">Lender Email?</label>
                        <input type="text" class="form-control currency-input @error('lender_email') is-invalid @enderror"
                            value="{{ Auth::user()->contact->lender_email }}" id="lender_email" name="lender_email"
                            placeholder="Lender Email?"> 
                        @error('lender_email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                   
                    <div class="mt-3 d-grid">
                        <button class="btn btn-primary waves-effect waves-light" type="submit">Update Buyer Submittal Defaults</button>
                    </div>
                </form>
            </div>
        </div>