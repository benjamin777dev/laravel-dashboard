@extends('layouts.master')

@section('title', 'zPortal | Dashboard')
@section('content')
    @vite(['resources/css/dashboard.css'])
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <div id="loader" style="display: none;">
        <img src="{{ URL::asset('/images/Spinner-5.gif') }}" alt="Loading...">
    </div>
    <div class="container-fluid">
        <div class="loader" id="loaderfor" style="display: none;"></div>
        <div class="loader-overlay" id="loaderOverlay" style="display: none;"></div>
        <div class="row mt-4 text-center">
            <div class="col-lg-3 col-md-3 text-start dcontactbtns-div">
                <div class="row g-1">
                    <div>
                        <div class="input-group-text dcontactBtns" id="btnGroupAddon" onclick="createContact();">
                            <i class="fas fa-plus plusicon"></i> New Contact
                        </div>
                    </div>
                    <div>
                        <div class="input-group-text dcontactBtns" id="btnGroupAddon" data-bs-toggle="modal"
                            data-bs-target="#" onclick="createTransaction({{ $userContact }})">
                            <i class="fas fa-plus plusicon"></i> New Transaction
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-ld-9 col-md-9 col-sm-12">
                <div class="row dashboard-cards-resp">
                    @foreach ($stageData as $stage => $data)
                        <div class="col-lg-3 col-md-3 col-sm-6 text-center dCardsCols" data-stage="{{ $stage }}">
                            <div class="card dash-card">
                                <div class="card-body dash-front-cards">
                                    <h5 class="card-title dTitle mb-0">{{ $stage }}</h5>
                                    <p class="dSumValue">${{ $data['sum'] }}</p>
                                    <p class="card-text dcountText">{{ $data['count'] }} Transactions</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="row dmain-Container">
            <div class="row dgraphdiv">
                <div class="col-md-8">
                    <div class="row dspeedn-month-camaparison">
                        <div class="col-md-4 dspeedometersection">
                            <p class="dpipetext">My Pipeline</p>
                            <div id="canvas-holder" style="width:100%">
                                <canvas id="chart" width="100%" height="100%"></canvas>
                            </div>
                            <p class="dFont13 dMb5 dRangeText">{{ '$' . $totalGciForDah . ' of 250,000 Goal' }}</p>
                        </div>
                        <div class="col-md-8 graphp-dash">
                            <div class="container dgraphpstackContainer">
                                <p class="dcamptext">Monthly Pipeline Comparison</p>
                                <div class="stacked-bar-chart w-100 stacked-contain">
                                    @php
                                        $gcis = array_column($allMonths, 'gci');
                                        $maxGCI = max($gcis);
                                    @endphp
                                    @foreach ($allMonths as $month => $data)
                                        @php
                                            $widthPercentage = $maxGCI != 0 ? ($data['gci'] / $maxGCI) * 91 : 0;
                                        @endphp
                                        <div class="row">
                                            <div class="col-md-1 align-self-center dmonth-design">
                                                {{ Carbon\Carbon::parse($month)->format('M') }}</div>
                                            <div class="col-md-11 dashchartImg">
                                                <div class="row dgraph-strip">
                                                    @php
                                                        $formattedGCI = str_replace(['$', ','], '', number_format($data['gci'], 0));
                                                    @endphp
                                                    <div class="col-md-10 text-end bar-a" style="width: {{ $formattedGCI < 1000 ? 'auto' : $widthPercentage . '%' }}">
                                                        {{ '$' . number_format($data['gci'], 0) }}
                                                    </div>
                                                    <div class="col-md-1">
                                                        <p class="dtransactions-des">{{ $data['deal_count'] }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @include('common.notes.view', [
                    'notesInfo' => $notesInfo,
                    'retrieveModuleData' => $retrieveModuleData,
                ])
            </div>
            <div class="col-sm-12 dtasksection">
                <div class="d-flex justify-content-between">
                    <p class="dFont800 dFont15">Tasks</p>
                    <div class="input-group-text text-white justify-content-center taskbtn dFont400 dFont13"
                        id="btnGroupAddon" data-bs-toggle="modal" data-bs-target="#staticBackdropforTask">
                        <i class="fas fa-plus plusicon"></i> New Task
                    </div>
                </div>
                <div class="row">
                    <nav class="dtabs">
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link dtabsbtn active" id="nav-home-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-home" data-tab='In Progress' type="button" role="tab"
                                aria-controls="nav-home" aria-selected="true" onclick="fetchData('In Progress')">In
                                Progress</button>
                            <button class="nav-link dtabsbtn" data-tab='Not Started' id="nav-profile-tab"
                                data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab"
                                aria-controls="nav-profile" aria-selected="false"
                                onclick="fetchData('Not Started')">Upcoming</button>
                            <button class="nav-link dtabsbtn" data-tab='Completed' id="nav-contact-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact"
                                aria-selected="false" onclick="fetchData('Completed')">Overdue</button>
                        </div>
                    </nav>
                    <div class="task-container">
                        @include('common.tasks', ['tasks' => $tasks])
                    </div>
                </div>
            </div>

            <div class="table-responsive dtranstiontable mt-2">
                <p class="fw-bold">Bad Dates</p>
                <div class="dtabletranstion dtableHeader">
                    <div>Transaction Name</div>
                    <div>Client Name</div>
                    <div>Stage</div>
                    <div>Representing</div>
                    <div>Sale Price</div>
                    <div>Closing Date</div>
                    <div>Commission</div>
                    <div>Potential GCI</div>
                    <div>Probability</div>
                    <div>Probable GCI</div>
                </div>
                @if (count($closedDeals) === 0)
                    <div>
                        <p class="text-center mt-4" colspan="12">No records found</p>
                    </div>
                @else
                    @foreach ($closedDeals as $deal)
                        <div class="dtabletranstion row-card" data-id="{{ $deal['id'] }}">
                            <div data-type="deal_name" data-value="{{ $deal['deal_name'] }}">
                                <div class="dTContactName">{{ $deal['deal_name'] }} {{ $deal['address'] }}</div>
                            </div>
                            <div data-type="client_name_primary" data-value="{{ $deal->client_name_primary ?? 'N/A' }}">
                                <div class="dTContactName">
                                    <img src="{{ URL::asset('/images/account_box.svg') }}" alt="R">
                                    {{ $deal->client_name_primary ?? 'N/A' }}
                                </div>
                            </div>
                            <div data-type="stage" data-value="{{ $deal['stage'] }}">
                                <div class="dTContactName">
                                    <img src="{{ URL::asset('/images/account_box.svg') }}" alt="R">
                                    {{ $deal['stage'] }}
                                </div>
                            </div>
                            <div data-type="representing" data-value="{{ $deal['representing'] }}">
                                <div class="dTContactName">{{ $deal['representing'] }}</div>
                            </div>
                            <div data-type="sale_price" data-value="{{ $deal['sale_price'] ?? 0 }}">
                                <div class="dTContactName">{{ number_format($deal['sale_price'] ?? 0, 0, '.', ',') }}</div>
                            </div>
                            <div>
                                <input type="date" onchange="updateDeal('{{ $deal['zoho_deal_id'] }}', '{{ $deal['id'] }}', this.closest('.row-card'))"
                                    id="closing_date{{ $deal['zoho_deal_id'] }}" value="{{ $deal['closing_date'] ? \Carbon\Carbon::parse($deal['closing_date'])->format('Y-m-d') : '' }}">
                            </div>
                            <div data-type="commission" data-value="{{ $deal['commission'] ?? 0 }}">
                                <div class="dTContactName">{{ number_format($deal['commission'] ?? 0, 2) }}%</div>
                            </div>
                            <div data-type="potential_gci" data-value="{{ $deal['potential_gci'] ?? 0 }}">
                                <div class="dTContactName">${{ number_format($deal['potential_gci'] ?? 0, 0, '.', ',') }}</div>
                            </div>
                            <div data-type="pipeline_probability" data-value="{{ $deal['pipeline_probability'] ?? 0 }}">
                                <div class="dTContactName">{{ number_format($deal['pipeline_probability'] ?? 0, 2) }}%</div>
                            </div>
                            <div data-type="probable_gci" data-value="{{ ($deal['sale_price'] ?? 0) * (($deal['commission'] ?? 0) / 100) * (($deal['pipeline_probability'] ?? 0) / 100) }}">
                                <div class="dTContactName">${{ number_format(($deal['sale_price'] ?? 0) * (($deal['commission'] ?? 0) / 100) * (($deal['pipeline_probability'] ?? 0) / 100), 0, '.', ',') }}</div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    <div class="dnotesBottomIcon" type="button" data-bs-toggle="modal" data-bs-target="#staticBackdropforNote">
        <div class="tooltip-wrapper">
            <img src="{{ URL::asset('/images/notesIcon.svg') }}" alt="Notes icon">
            <span class="tooltiptext">Add Notes</span>
        </div>
    </div>
    {{-- Modals --}}
    @include('common.tasks.create')
    @include('common.notes.create')
@endsection

@section('bladeScripts')
    @vite(['resources/js/dashboard.js'])
@endsection
