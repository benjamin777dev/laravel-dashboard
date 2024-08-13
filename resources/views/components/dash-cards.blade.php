<div class="row dashboard-cards-resp">
    @foreach ($stageData as $stage => $data)
        <div class="col-lg-3 col-md-3 col-sm-6 text-center dCardsCols" data-stage="{{ $stage }}">
            <div class="card dash-card">
                <div class="card-body dash-front-cards">
                    <h5
                        class="card-title dTitle mb-0"
                        >{{ $stage }}</h5>
                    <h4 class="dSumValue">${{ $data['sum'] }}</h4>
                    <p class="card-text dcountText">{{ $data['count'] }} Transactions</p>
                </div>
            </div>
        </div>
    @endforeach
</div>