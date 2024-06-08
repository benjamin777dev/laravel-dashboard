@extends('layouts.master')

@section('title', 'Agent Commander | NonTm')

@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
    </head>

    <body>

        <div class="nontm-header">
            <div class="non-title">
                <h3>NON-TM CHECK REQUEST WIZARD</h3>
            </div>
            <div class="non-btns">
                <div class="nontm-cancel-btn">
                    <button>Cancel</button>

                </div>
                <div class="nontm-savenew-btn">
                    <button>Save and New</button>
                </div>
                <div class="nontm-save-btn">
                    <button>Save</button>

                </div>
            </div>
        </div>
        <div class="col-lg-12 main-carousel">
            <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <div class="prev_btn">
                        <a href=""><span class="prev">
                                < Previous</span></a>
                    </div>
                    <div class="bullets">
                        <button type="button" data-bs-target="#carouselExampleControls" data-bs-slide-to="0" class="active"
                            aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#carouselExampleControls" data-bs-slide-to="1"
                            aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#carouselExampleControls" data-bs-slide-to="2"
                            aria-label="Slide 3"></button>
                    </div>
                    <div class="next_btn">
                        <a href=""><span class="next">Next ></span></a>
                    </div>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="main_form_div">
                            <div class="related_trxn">
                                <label for="relatedto">Related Transaction <svg xmlns="http://www.w3.org/2000/svg"
                                        width="19" height="18" viewBox="0 0 19 18" fill="none">
                                        <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0"
                                            y="0" width="19" height="18">
                                            <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                        </mask>
                                        <g mask="url(#mask0_2151_10662)">
                                            <path
                                                d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z"
                                                fill="#AC5353" />
                                        </g>
                                    </svg></label>
                                <select name="" class="form-select" id="">
                                    <option value="" selected>Peters Listing Castle Rock</option>
                                </select>
                            </div>
                            <div class="additional_email">
                                <label for="add_email">Additional Email for Confirmation</label>
                                <input type="email" class="form-control" placeholder="Enter email" id="add_email">
                            </div>
                            <div class="row close-date-comm">
                                <div class="col-6 close-date">
                                    <label for="close_date">Close Date <svg xmlns="http://www.w3.org/2000/svg"
                                            width="19" height="18" viewBox="0 0 19 18" fill="none">
                                            <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse"
                                                x="0" y="0" width="19" height="18">
                                                <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                            </mask>
                                            <g mask="url(#mask0_2151_10662)">
                                                <path
                                                    d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z"
                                                    fill="#AC5353" />
                                            </g>
                                        </svg></label>
                                    <input type="date" class="form-control" id="close_date">
                                </div>
                                <div class="col-6 commission">
                                    <label for="commission">Commission % <svg xmlns="http://www.w3.org/2000/svg"
                                            width="19" height="18" viewBox="0 0 19 18" fill="none">
                                            <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse"
                                                x="0" y="0" width="19" height="18">
                                                <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                            </mask>
                                            <g mask="url(#mask0_2151_10662)">
                                                <path
                                                    d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z"
                                                    fill="#AC5353" />
                                            </g>
                                        </svg></label>
                                    <input type="text" class="form-control" id="commission">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="main_form_div">
                            <div class="related_trxn">
                                <label for="relatedto">Related Transaction <svg xmlns="http://www.w3.org/2000/svg"
                                        width="19" height="18" viewBox="0 0 19 18" fill="none">
                                        <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0"
                                            y="0" width="19" height="18">
                                            <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                        </mask>
                                        <g mask="url(#mask0_2151_10662)">
                                            <path
                                                d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z"
                                                fill="#AC5353" />
                                        </g>
                                    </svg></label>
                                <select name="" class="form-select" id="">
                                    <option value="" selected>Peters Listing Castle Rock</option>
                                </select>
                            </div>
                            <div class="additional_email">
                                <label for="add_email">Additional Email for Confirmation</label>
                                <input type="email" class="form-control" placeholder="Enter email" id="add_email">
                            </div>
                            <div class="row close-date-comm">
                                <div class="col-6 close-date">
                                    <label for="close_date">Close Date <svg xmlns="http://www.w3.org/2000/svg"
                                            width="19" height="18" viewBox="0 0 19 18" fill="none">
                                            <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse"
                                                x="0" y="0" width="19" height="18">
                                                <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                            </mask>
                                            <g mask="url(#mask0_2151_10662)">
                                                <path
                                                    d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z"
                                                    fill="#AC5353" />
                                            </g>
                                        </svg></label>
                                    <input type="date" class="form-control" id="close_date">
                                </div>
                                <div class="col-6 commission">
                                    <label for="commission">Commission % <svg xmlns="http://www.w3.org/2000/svg"
                                            width="19" height="18" viewBox="0 0 19 18" fill="none">
                                            <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse"
                                                x="0" y="0" width="19" height="18">
                                                <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                            </mask>
                                            <g mask="url(#mask0_2151_10662)">
                                                <path
                                                    d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z"
                                                    fill="#AC5353" />
                                            </g>
                                        </svg></label>
                                    <input type="text" class="form-control" id="commission">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="main_form_div">
                            <div class="related_trxn">
                                <label for="relatedto">Related Transaction <svg xmlns="http://www.w3.org/2000/svg"
                                        width="19" height="18" viewBox="0 0 19 18" fill="none">
                                        <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0"
                                            y="0" width="19" height="18">
                                            <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                        </mask>
                                        <g mask="url(#mask0_2151_10662)">
                                            <path
                                                d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z"
                                                fill="#AC5353" />
                                        </g>
                                    </svg></label>
                                <select name="" class="form-select" id="">
                                    <option value="" selected>Peters Listing Castle Rock</option>
                                </select>
                            </div>
                            <div class="additional_email">
                                <label for="add_email">Additional Email for Confirmation</label>
                                <input type="email" class="form-control" placeholder="Enter email" id="add_email">
                            </div>
                            <div class="row close-date-comm">
                                <div class="col-6 close-date">
                                    <label for="close_date">Close Date <svg xmlns="http://www.w3.org/2000/svg"
                                            width="19" height="18" viewBox="0 0 19 18" fill="none">
                                            <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse"
                                                x="0" y="0" width="19" height="18">
                                                <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                            </mask>
                                            <g mask="url(#mask0_2151_10662)">
                                                <path
                                                    d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z"
                                                    fill="#AC5353" />
                                            </g>
                                        </svg></label>
                                    <input type="date" class="form-control" id="close_date">
                                </div>
                                <div class="col-6 commission">
                                    <label for="commission">Commission % <svg xmlns="http://www.w3.org/2000/svg"
                                            width="19" height="18" viewBox="0 0 19 18" fill="none">
                                            <mask id="mask0_2151_10662" style="mask-type:alpha" maskUnits="userSpaceOnUse"
                                                x="0" y="0" width="19" height="18">
                                                <rect x="0.5" width="18" height="18" fill="#D9D9D9" />
                                            </mask>
                                            <g mask="url(#mask0_2151_10662)">
                                                <path
                                                    d="M8.1877 15.75V11.2875L4.3252 13.5188L3.0127 11.25L6.8752 9L3.0127 6.76875L4.3252 4.5L8.1877 6.73125V2.25H10.8127V6.73125L14.6752 4.5L15.9877 6.76875L12.1252 9L15.9877 11.25L14.6752 13.5188L10.8127 11.2875V15.75H8.1877Z"
                                                    fill="#AC5353" />
                                            </g>
                                        </svg></label>
                                    <input type="text" class="form-control" id="commission">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>

    </body>

    </html>

@endsection

<script type="text/javascript">
    window.onload = function() {
        $(".main-carousel a span.prev").click(function(e) {
            e.preventDefault();
            $(".main-carousel .carousel-control-prev").trigger("click");
        });
        $(".main-carousel a span.next").click(function(e) {
            e.preventDefault();
            $(".main-carousel .carousel-control-next").trigger("click");
        });
    };
</script>
