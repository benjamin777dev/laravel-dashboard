@if ($label=="New Transaction" && $type=="database" || $label=="New Contact" && $type=="database" || $label=="New Submittal" || $label=="Compose Email")   
<button type="button" id="{{$id}}" class="input-group-text text-white justify-content-center pTransactionBtn " >  <i class="{{$icon}}"></i>{{$label}}</button>
@endif
@if ($label=="New Transaction" && $type=="dashboard" || $label=="New Contact" && $type=="dashboard")   
<button type="button" id="{{$id}}" class="btn btn-primary btn-lg waves-effect waves-light d-flex h-100 p-3 justify-content-center align-items-center gap-1 align-self-stretch rounded-3 border border-1 border-dark shadow-sm text-white text-center fw-bolder w-100 dcontactBtns" >  <i class="{{$icon}}"></i>{{$label}}</button>
@endif
@if ($label=="Filter" || $label=="Reset All")    
<button type="button" id="{{$label==='Filter' ?'Filter' : "Reset_All" }}" class="mbottom d-flex justify-content-center align-items-center gap-4 rounded-pill border border-1 border-dark shadow-sm text-white text-center" style="width: 226px; height: 40px; padding: 12px; cursor: pointer;background:#222; font-size: 14px; font-weight: 800;" {{$attributes ?? ""}} >  <i class="{{$icon}}"></i>{{$label}}</button>
@endif

<script>
        $("#create_contact").on('click',function(){
            console.log('yes clickeeeeeee')
            createContact();
            })
            
            $("#create_transaction").on('click',function(){
                createTransaction();
            })
</script>