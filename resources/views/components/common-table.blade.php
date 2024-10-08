@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    @vite(['resources/css/custom.css'])
@endsection
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table id="{{ $id }}" class="table table-bordered table-responsive nowrap w-100 min-mobile-p">
                    @if ($id==="datatable_transaction")
                        @if ($needsNewDate['count'] > 0)
                        <p class="fw-bold">Bad Dates | <span class="text-danger bad_date_count">{{$needsNewDate['count']}}</span></p>
                        @else
                            <p class="fw-bold">Bad Dates</p>
                        @endif
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
    <script src="{{ URL::asset('build/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/pdfmakebuild/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/pdfmakebuild/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
    
    <!-- Datatable init js -->
    @routes
    <script src="{{ URL::asset('build/js/pages/datatables.init.js') }}"></script>
    @vite(['resources/js/dropdown.js'])
    @vite(['resources/js/datatable.js'])
 @endsection
    
