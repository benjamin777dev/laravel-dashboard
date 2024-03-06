@extends('layouts.master')

@section('title') @lang('Dashboards') @endsection

@section('content')

@component('components.breadcrumb')
@slot('li_1') Dashboards @endslot
@slot('title') Dashboard @endslot
@endcomponent
<!--
    <div id="stacked-column-chart" data-colors='["--bs-primary", "--bs-warning", "--bs-success"]' class="apex-charts" dir="ltr"></div>
-->
<div class="row">
    <div class="col-sm-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar-xs me-3">
                        <span class="avatar-title rounded-circle bg-primary-subtle text-primary font-size-18">
                            <i class="material-symbols-outlined font-size-18">legend_toggle</i>
                        </span>
                    </div>
                    <h5 class="font-size-14 mb-0">Average Pipeline Probability</h5>
                </div>
                <div class="text-muted mt-4">
                    <h4>{{$averagePipelineProbability}}% <i class="material-symbols-outlined text-success">keyboard_arrow_up</i></h4>
                </div>
            </div>
        </div>
    </div>

                            
    <div class="col-sm-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar-xs me-3">
                        <span class="avatar-title rounded-circle bg-primary-subtle text-primary font-size-18">
                            <i class="material-symbols-outlined font-size-18">add_business</i>
                        </span>
                    </div>
                    <h5 class="font-size-14 mb-0">New Transactions - Past 30 days
                    </h5>
                </div>
                <div class="text-muted mt-4">
                    <h4>{{$newDealsLast30Days}} <i class="material-symbols-outlined text-success">keyboard_arrow_up</i></h4>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar-xs me-3">
                        <span class="avatar-title rounded-circle bg-primary-subtle text-primary font-size-18">
                            <i class="material-symbols-outlined font-size-18">assignment_add</i>
                        </span>
                    </div>
                    <h5 class="font-size-14 mb-0">New Contacts - Past 30 days
                    </h5>
                </div>
                <div class="text-muted mt-4">
                    <h4>{{$newContactsLast30Days}} <i class="material-symbols-outlined text-success">keyboard_arrow_up</i></h4>
                </div>
            </div>
        </div>
    </div>              
</div>
                    
<div class="row">
    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Pipelines</h4>

                <div>
                    <div id="pipelines-chart" data-colors='["--bs-primary", "--bs-success", "--bs-danger"]' class="apex-charts"></div>
                </div>

                <div class="text-center text-muted">
                    <div class="row">
                        <div class="col-4">
                            <div class="mt-4">
                                <p class="mb-2 text-truncate d-flex align-items-center justify-content-center"><i class="material-symbols-outlined text-primary me-1 font-size-18 ">preliminary</i> Potential</p>
                                <h5>$ 12,320</h5>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mt-4">
                                <p class="mb-2 text-truncate d-flex align-items-center justify-content-center"><i class="material-symbols-outlined text-blue me-1 font-size-18">strategy</i> Pre-active</p>
                                <h5>$ 9,985</h5>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mt-4">
                                <p class="mb-2 text-truncate d-flex align-items-center justify-content-center"><i class="material-symbols-outlined text-success me-1 font-size-18">flash_on</i> Active</p>
                                <h5>$ 2,380</h5>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="border-top px-3 pt-3">
                            <div class="row">
                                <div class="col current-pipeline align-self-center">
                                    <div class="position-relative">
                                        <p class="mb-1 text-left">Current Pipeline</p>
                                        <h4>${{$progress}}</h4>
                                    </div>
                                </div>
                                <div class="col-auto align-self-center">
                                    <button class="btn btn-primary w-md waves-effect waves-light"><span class="d-none d-sm-inline-block">Manage Pipelines</span> </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">My Pipeline - Monthly Comparison</h4>
                <div class="row">
                    
                    <div class="col">
                        <div id="pipelines_chart" class="apex-charts" dir="ltr"></div> 

                    </div>
                </div>
            </div>
        </div><!--end card-->
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Recent Added Leads</h4>
                <div data-simplebar style="max-height: 376px;">
                    <div class="vstack gap-4">
                        <div class="d-flex">
                            <div class="ms-2 flex-grow-1">
                                <h6 class="mb-1 font-size-15"><a href="job-details.html" class="text-body">lead Title</a></h6>
                                <p class="text-muted mb-0">Tampa, FL, USA - <b>53</b> sec ago</p>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-light" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="material-symbols-outlined">menu</i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <li><a class="dropdown-item" href="job-details.html">View Details</a></li>
                                    <li><a class="dropdown-item" href="#">Apply Now</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="d-flex">
                            
                            <div class="ms-2 flex-grow-1">
                                <h6 class="mb-1 font-size-15"><a href="job-details.html" class="text-body">lead Title</a></h6>
                                <p class="text-muted mb-0">Tampa, FL, USA - <b>53</b> sec ago</p>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-light" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="material-symbols-outlined">menu</i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <li><a class="dropdown-item" href="job-details.html">View Details</a></li>
                                    <li><a class="dropdown-item" href="#">Apply Now</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="ms-2 flex-grow-1">
                                <h6 class="mb-1 font-size-15"><a href="job-details.html" class="text-body">lead Title</a></h6>
                                <p class="text-muted mb-0">Tampa, FL, USA - <b>53</b> sec ago</p>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-light" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="material-symbols-outlined">menu</i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <li><a class="dropdown-item" href="job-details.html">View Details</a></li>
                                    <li><a class="dropdown-item" href="#">Apply Now</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="ms-2 flex-grow-1">
                                <h6 class="mb-1 font-size-15"><a href="job-details.html" class="text-body">lead Title</a></h6>
                                <p class="text-muted mb-0">Tampa, FL, USA - <b>53</b> sec ago</p>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-light" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="material-symbols-outlined">menu</i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <li><a class="dropdown-item" href="job-details.html">View Details</a></li>
                                    <li><a class="dropdown-item" href="#">Apply Now</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="ms-2 flex-grow-1">
                                <h6 class="mb-1 font-size-15"><a href="job-details.html" class="text-body">lead Title</a></h6>
                                <p class="text-muted mb-0">Tampa, FL, USA - <b>53</b> sec ago</p>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-light" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="material-symbols-outlined">menu</i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <li><a class="dropdown-item" href="job-details.html">View Details</a></li>
                                    <li><a class="dropdown-item" href="#">Apply Now</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="ms-2 flex-grow-1">
                                <h6 class="mb-1 font-size-15"><a href="job-details.html" class="text-body">lead Title</a></h6>
                                <p class="text-muted mb-0">Tampa, FL, USA - <b>53</b> sec ago</p>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-light" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="material-symbols-outlined">menu</i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <li><a class="dropdown-item" href="job-details.html">View Details</a></li>
                                    <li><a class="dropdown-item" href="#">Apply Now</a></li>
                                </ul>
                            </div>
                        </div>

                        
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top">
                    <div class="text-center">
                        <a href="javascript: void(0);" class="btn btn-primary waves-effect waves-light"> Manage Leads</a>
                    </div>
                </div>
            </div>
        </div><!--end card-->
    </div>
</div> 
                                                
<div class="row">
    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Notifications</h4>

                <ul class="list-group" data-simplebar style="max-height: 390px;">
                    <li class="list-group-item border-0">
                        <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-xs">
                                    <span class="avatar-title rounded-circle bg-light">
                                        <img src="assets/images/companies/img-1.png" alt="" height="18">
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="font-size-14">Donec vitae sapien ut</h5>
                                <p class="text-muted">If several languages coalesce, the grammar of the resulting language</p>

                                <div class="float-end">
                                    <p class="text-muted mb-0 d-flex align-items-center"><i class="material-symbols-outlined me-1 person-icon font-size-18">person</i> Jerry</p>                                                        </div>
                                <p class="text-muted mb-0">12 Mar, 2020</p>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item border-0">
                        <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-xs">
                                    <span class="avatar-title rounded-circle bg-light">
                                        <img src="assets/images/companies/img-2.png" alt="" height="18">
                                    </span>
                                </div>
                            </div>
                            
                            <div class="flex-grow-1">
                                <h5 class="font-size-14">Cras ultricies mi eu turpis</h5>
                                <p class="text-muted">To an English person, it will seem like simplified English, as a skeptical cambridge</p>

                                <div class="float-end">
                                    <p class="text-muted mb-0 d-flex align-items-center"><i class="material-symbols-outlined me-1 person-icon font-size-18">person</i> Jerry</p>
                                </div>
                                <p class="text-muted mb-0">13 Mar, 2020</p>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item border-0">
                        <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-xs">
                                    <span class="avatar-title rounded-circle bg-light">
                                        <img src="assets/images/companies/img-3.png" alt="" height="18">
                                    </span>
                                </div>
                            </div>
                            
                            <div class="flex-grow-1">
                                <h5 class="font-size-14">Duis arcu tortor suscipit</h5>
                                <p class="text-muted">It va esser tam simplic quam occidental in fact, it va esser occidental.</p>

                                <div class="float-end">
                                    <p class="text-muted mb-0 d-flex align-items-center"><i class="material-symbols-outlined me-1 person-icon font-size-18">person</i> Calvin</p>
                                </div>
                                <p class="text-muted mb-0">14 Mar, 2020</p>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item border-0">
                        <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-xs">
                                    <span class="avatar-title rounded-circle bg-light">
                                        <img src="assets/images/companies/img-1.png" alt="" height="18">
                                    </span>
                                </div>
                            </div>
                            
                            <div class="flex-grow-1">
                                <h5 class="font-size-14">Donec vitae sapien ut</h5>
                                <p class="text-muted">If several languages coalesce, the grammar of the resulting language</p>

                                <div class="float-end">
                                    <p class="text-muted mb-0 d-flex align-items-center"><i class="material-symbols-outlined me-1 person-icon font-size-18">person</i> Joseph</p>
                                </div>
                                <p class="text-muted mb-0">12 Mar, 2020</p>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Tasks</h4>

                <ul class="nav nav-pills bg-light rounded">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">In Process</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Upcoming</a>
                    </li>
                </ul>

                <div class="mt-4">
                    <div data-simplebar style="max-height: 250px;">
                    
                        <div class="table-responsive">
                            <table class="table table-nowrap align-middle table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th></th> <!-- For checkbox -->
                                        <th>Subject</th>
                                        <th>Due Date</th>
                                        <th>Related To</th>
                                        <th>Assigned To</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tasks['tasks'] as $task)
                                        <tr>
                                            <td><input type="checkbox" name="taskCompleted[]" value="{{ $task['id'] }}"></td>
                                            <td>{{ $task['Subject'] ?? 'N/A' }}</td>
                                            <td>{{ $task['Due_Date'] ? Carbon\Carbon::parse($task['Due_Date'])->format('m/d/Y') : 'N/A' }}</td>
                                            <td>{{ $task['Who_Id']['name'] ?? 'N/A' }}</td>
                                            <td>{{ $task['Owner']['name'] ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top">
                    <div class="text-center">
                        <a href="javascript: void(0);" class="btn btn-primary waves-effect waves-light"> Add new Task</a>
                        <a href="javascript: void(0);" class="btn btn-secondary waves-effect waves-light"> Manage Tasks</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Transaction Modal -->
<div class="modal fade transaction-detailModal" tabindex="-1" role="dialog" aria-labelledby="transaction-detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transaction-detailModalLabel">Order Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2">Product id: <span class="text-primary">#SK2540</span></p>
                <p class="mb-4">Billing Name: <span class="text-primary">Neal Matthews</span></p>

                <div class="table-responsive">
                    <table class="table align-middle table-nowrap">
                        <thead>
                            <tr>
                                <th scope="col">Product</th>
                                <th scope="col">Product Name</th>
                                <th scope="col">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">
                                    <div>
                                        <img src="{{ URL::asset('build/images/product/img-7.png') }}" alt="" class="avatar-sm">
                                    </div>
                                </th>
                                <td>
                                    <div>
                                        <h5 class="text-truncate font-size-14">Wireless Headphone (Black)</h5>
                                        <p class="text-muted mb-0">$ 225 x 1</p>
                                    </div>
                                </td>
                                <td>$ 255</td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <div>
                                        <img src="{{ URL::asset('build/images/product/img-4.png') }}" alt="" class="avatar-sm">
                                    </div>
                                </th>
                                <td>
                                    <div>
                                        <h5 class="text-truncate font-size-14">Phone patterned cases</h5>
                                        <p class="text-muted mb-0">$ 145 x 1</p>
                                    </div>
                                </td>
                                <td>$ 145</td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <h6 class="m-0 text-right">Sub Total:</h6>
                                </td>
                                <td>
                                    $ 400
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <h6 class="m-0 text-right">Shipping:</h6>
                                </td>
                                <td>
                                    Free
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <h6 class="m-0 text-right">Total:</h6>
                                </td>
                                <td>
                                    $ 400
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- end modal -->

<!-- subscribeModal -->
<div class="modal fade" id="subscribeModal" tabindex="-1" aria-labelledby="subscribeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="avatar-md mx-auto mb-4">
                        <div class="avatar-title bg-light rounded-circle text-primary h1">
                            <i class="mdi mdi-email-open"></i>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-xl-10">
                            <h4 class="text-primary">Subscribe !</h4>
                            <p class="text-muted font-size-14 mb-4">Subscribe our newletter and get notification to stay
                                update.</p>

                            <div class="input-group bg-light rounded">
                                <input type="email" class="form-control bg-transparent border-0" placeholder="Enter Email address" aria-label="Recipient's username" aria-describedby="button-addon2">

                                <button class="btn btn-primary" type="button" id="button-addon2">
                                    <i class="bx bxs-paper-plane"></i>
                                </button>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end modal -->

@endsection
@section('script')
<!-- apexcharts -->
<script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>

<!-- dashboard init -->
<script src="{{ URL::asset('build/js/pages/dashboard.init.js') }}"></script>

<!-- One Off Scripts -->
<script>
    var radialbarColors = getChartColorsArray("radialBar-chart");
if (radialbarColors) {
    var options = {
        chart: {
            height: 200,
            type: 'radialBar',
            offsetY: -10
        },
        plotOptions: {
            radialBar: {
                startAngle: -135,
                endAngle: 135,
                dataLabels: {
                    name: {
                        fontSize: '13px',
                        color: undefined,
                        offsetY: 60
                    },
                    value: {
                        offsetY: 22,
                        fontSize: '16px',
                        color: undefined,
                        formatter: function (val) {
                            return val + "%";
                        }
                    }
                }
            }
        },
        colors: radialbarColors,
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'dark',
                shadeIntensity: 0.15,
                inverseColors: false,
                opacityFrom: 1,
                opacityTo: 1,
                stops: [0, 50, 65, 91]
            },
        },
        stroke: {
            dashArray: 4,
        },
        series: [{{$progress}}],
        labels: ['Progress'],

    }

    var chart = new ApexCharts(
        document.querySelector("#radialBar-chart"),
        options
    );

    chart.render();
}
</script>

@endsection
