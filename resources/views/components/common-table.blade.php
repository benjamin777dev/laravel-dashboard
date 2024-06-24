@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ URL::asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="{{ URL::asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
@endsection
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table id="{{ $id }}" class="table table-bordered dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            @foreach ($th as $thname)
                                <th>{{ $thname }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    @if ($type === 'dash-transaction')
                        <tbody>
                            @foreach ($commonArr as $deal)
                                <tr class="parent-tr" data-id="{{ $deal['id'] }}"
                                    data-zid="{{ $deal['zoho_deal_id'] }}">
                                    <td data-type="deal_name" data-value="{{ $deal['deal_name'] }}">
                                        {{ $deal['deal_name'] }}
                                        {{-- {{ $deal['address'] }} --}}
                                    </td>
                                    <td data-type="client_name_primary"
                                        data-value="{{ $deal->client_name_primary ?? 'N/A' }}">
                                        {{ $deal->client_name_primary ?? 'N/A' }}
                                    </td>
                                    <td data-type="stage" data-value="{{ $deal['stage'] }}">
                                        {{ $deal['stage'] ?? 'N/A' }}
                                    </td>
                                    <td data-type="representing" data-value="{{ $deal['representing'] }}">
                                        {{ $deal['representing'] ?? 'N/A' }}
                                    </td>
                                    <td data-type="sale_price" data-value="{{ $deal['sale_price'] ?? 0 }}">
                                        ${{ number_format($deal['sale_price'] ?? 0, 0, '.', ',') }}
                                    </td>
                                    <td> <input type="date" class="badDateInput"
                                            onchange="updateDeal('{{ $deal['zoho_deal_id'] }}', '{{ $deal['id'] }}', this.closest('.parent-tr'))"
                                            id="closing_date{{ $deal['zoho_deal_id'] }}"
                                            value="{{ $deal['closing_date'] ? \Carbon\Carbon::parse($deal['closing_date'])->format('Y-m-d') : '' }}">
                                    </td>
                                    <td> {{ number_format($deal['commission'] ?? 0, 2) ?? 'N/A' }}%
                                    </td>
                                    <td>${{ number_format($deal['potential_gci'] ?? 0, 0, '.', ',') ?? 'N/A' }}</td>
                                    <td>
                                        {{ number_format($deal['pipeline_probability'] ?? 0, 2) ?? 'N/A' }}%</td>
                                    <td>
                                        ${{ number_format(($deal['sale_price'] ?? 0) * (($deal['commission'] ?? 0) / 100) * (($deal['pipeline_probability'] ?? 0) / 100), 0, '.', ',') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    @endif

                    @if ($type === 'dash-pipe-transaction')
                    @endif
                    @if ($type === 'contact')
                    @endif
                </table>

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

@section('script')
    <!-- Required datatable js -->
    <script src="{{ URL::asset('build/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Buttons examples -->
    <script src="{{ URL::asset('build/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/pdfmakebuild/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/pdfmakebuild/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>

    <!-- Responsive examples -->
    <script src="{{ URL::asset('build/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
    <!-- Datatable init js -->
    <script src="{{ URL::asset('build/js/pages/datatables.init.js') }}"></script>
@endsection
