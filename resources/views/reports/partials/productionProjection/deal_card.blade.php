<div class="card mb-3">
    <div class="card-header text-white bg-dark">
        <strong>Deal {{ $index + 1 }}: {{ $deal['deal_name'] }}</strong>
        <a href="{{ $deal['deal_link'] }}" target="_blank" class="float-end btn btn-link btn-sm text-white">View Deal</a>
    </div>
    <div class="card-body">
        @include('reports.partials.productionProjection.deal_information', ['deal' => $deal])
        @include('reports.partials.productionProjection.split_breakdown', ['deal' => $deal])
        @include('reports.partials.productionProjection.caps_information', ['deal' => $deal, 'settings' => $settings])
        @include('reports.partials.productionProjection.transaction_cap_usage', ['deal' => $deal])
    </div>
</div>
