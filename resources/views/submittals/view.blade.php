@extends('layouts.master')

@section('title', 'Agent Commander | Submittals View')

@section('content')
@vite(['resources/css/pipeline.css'])
@vite(['resources/js/toast.js'])

<div class="container-fluid">
    <div class="submittaldiv">
        <a>
            <div class="input-group-text text-white justify-content-center ppipeBtn" data-bs-toggle="modal" data-bs-target="#" onclick="validateSubmittal({{$submittal}},true)"><i class="fas fa-save">
                </i>
                Update
            </div>
        </a>
    </div>

    <div class="submittalType">
        <label for="submittalType" class="form-label nplabelText">Submittal Type</label>
        <select class="form-select npinputinfo" id="submittalType" onchange="showSubmittalForm(this)" disabled>
            <option value="Buyer Submittal" {{$submittalType=="buyer-submittal" ?'selected':''}}>Buyer Submittal
            </option>
            <option value="Listing Submittal" {{$submittalType=="listing-submittal" ?'selected':''}}>Listing
                Submittal</option>
        </select>

    </div>
    {{-- Listing Submittals--}}
    <div class="listingForm">

    </div>
    {{-- Listing Submittals--}}
    <div class="BuyerForm">

    </div>
</div>

@vite(['resources/js/pipeline.js'])

@section('pipelineScript')

@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    var showOtherListingForm = @json($listingSubmittaltype);
    var submittalId = @json($submittalId);
    $(document).ready(function(){
        var subForm = $('#submittalType').val();
        if(subForm == "Listing Submittal"){
            getListingForm();
        }else if(subForm == "Buyer Submittal"){
            getBuyerForm();
        }
    })

    function getListingForm(){
        $.ajax({
            url: "/listing/form/"+submittalId+`?formType=${showOtherListingForm}`,
            type: 'GET',
            success: function (response) {
                $(".listingForm").html(response)
            },
            error: function (xhr, status, error) {
                // Handle error response
                console.error(xhr.responseText);
            }
        })
    }

    function getBuyerForm(){
        $.ajax({
            url: "/buyer/form/"+submittalId,
            type: 'GET',
            success: function (response) {
                $(".listingForm").html(response)
            },
            error: function (xhr, status, error) {
                // Handle error response
                console.error(xhr.responseText);
            }
        })
    }

    function convertInInteger(string) {
        try {
            console.log("String:", typeof string,string);
            if (string!='') {
                // Parse the string to a floating-point number
                let num = parseFloat(string);
                if (isNaN(num)) {
                    throw new Error("Conversion Error: Invalid input");
                }
                return num;
            }
            return null;
        } catch (error) {
            console.log(error.message);
            throw new Error(error.message);
        }
    }

    function isValidEmail(email) {
        var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }
    function isValidUrl(url) {
        var regex = new RegExp(
            '^(https?:\\/\\/)' + // protocol
            '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.?)+[a-z]{2,}|' + // domain name
            '((\\d{1,3}\\.){3}\\d{1,3})|' + // OR ip (v4) address
            '\\[?[a-f\\d]*:[a-f\\d:]+\\]?)' + // OR ip (v6) address
            '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // port and path
            '(\\?[;&a-z\\d%_.~+=-]*)?' + // query string
            '(\\#[-a-z\\d_]*)?$', 'i' // fragment locator
        );
        return regex.test(url);
    }

   

</script>
@endsection


