<div class="card mt-4">
            <div class="card-body">
                <h4 class="card-title mb-4">Agent Information</h4>
                <form method="POST" id="update-agent-form">
                    @csrf
                    <input type="hidden" value="{{ Auth::user()->contact->id }}" id="id">

                    <div class="mb-3">
                        <label for="income_goal" class="form-label">Income Goal</label>
                        <input type="text" class="form-control currency-input @error('income_goal') is-invalid @enderror"
                            value="{{ number_format(Auth::user()->contact->income_goal, 2) }}" id="income_goal" name="income_goal"
                            @if(Auth::user()->contact->isPartOfTeam()) readonly @endif
                            placeholder="Enter income goal"> 
                        @error('income_goal')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="initial_cap" class="form-label">Initial Cap</label>
                        <input type="text" class="form-control currency-input @error('initial_cap') is-invalid @enderror"
                            value="{{ number_format(Auth::user()->contact->initial_cap, 2) }}" id="initial_cap" name="initial_cap"
                            @if(Auth::user()->contact->isPartOfTeam()) readonly @endif
                            placeholder="Enter initial cap">
                        @error('initial_cap')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="residual_cap" class="form-label">Residual Cap</label>
                        <input type="text" class="form-control currency-input @error('residual_cap') is-invalid @enderror"
                            value="{{ number_format(Auth::user()->contact->residual_cap, 2) }}" id="residual_cap" name="residual_cap"
                            @if(Auth::user()->contact->isPartOfTeam()) readonly @endif
                            placeholder="Enter residual cap">
                        @error('residual_cap')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mt-3 d-grid">
                        <button class="btn btn-primary waves-effect waves-light" type="submit">Update Agent Information</button>
                    </div>
                </form>
            </div>
        </div>