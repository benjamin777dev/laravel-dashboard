@if ($label=="New Transaction" || $label=="New Contact" || $label=="New Submittal")   
<button type="button" class="btn btn-primary btn-lg waves-effect waves-light d-flex h-100 p-3 justify-content-center align-items-center gap-1 align-self-stretch rounded-3 border border-1 border-dark shadow-sm text-white text-center fw-bolder w-100 dcontactBtns" onclick="{{ $clickEvent }}">  <i class="{{$icon}}"></i>{{$label}}</button>
@endif
@if ($label=="Filter" || $label=="Reset All")    
<button type="button" class="d-flex justify-content-center align-items-center gap-4 rounded-pill border border-1 border-dark shadow-sm text-white text-center" style="width: 226px; height: 40px; padding: 12px; cursor: pointer;background:#222; font-size: 14px; font-weight: 800;" {{$attributes ?? ""}} onclick="{{ $clickEvent ?? "" }}">  <i class="{{$icon}}"></i>{{$label}}</button>
@endif