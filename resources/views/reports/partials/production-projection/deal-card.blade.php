@if(isset($deal))
    <div class="card mb-3">
        <div class="card-header text-white bg-dark">
            <strong>Deal {{ $index + 1 }}: {{ $deal['deal_name'] }}</strong>
            <a href="{{ $deal['deal_link'] }}" target="_blank" class="float-end btn btn-link btn-sm text-white">View Deal</a>
        </div>
        <div class="card-body">
            @include('reports.partials.production-projection.deal-information', ['deal' => $deal])
            @include('reports.partials.production-projection.split-breakdown', ['deal' => $deal])
            @include('reports.partials.production-projection.caps-information', ['deal' => $deal, 'settings' => $settings])
            @include('reports.partials.production-projection.transaction-cap-usage', ['deal' => $deal])
        </div>
    </div>
@endif
