<div class="col-lg-9 col-md-9 col-sm-12 mb-0">
    <div class="row">
        @foreach ($stageData as $stage => $data)
            <div class="col-lg-3 col-md-3 col-sm-6 col-12 mb-5">
                <div class="card h-100 rounded-4">
                    <div class="card-body text-center p-2">
                        <h5 class="card-title mb-1 align-self-stretch text-dark text-center font-family-montserrat font-size-14 font-style-normal fw-bold line-height-21 letter-spacing-minus-0-28">{{ $stage }}</h5>
                        <p class="card-text text-center text-muted fw-bolder font-Montserrat line-height-34 mb-0" style="color:#6c6c6c;font-size:30px;">${{ $data['sum'] }}</p>
                        <p class="card-text text-dark font-family-montserrat font-size-14 fw-bolder mb-0">{{ $data['count'] }} Transactions</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
</div>
