@extends('layouts.main')
@section('title', 'Day Wise User Register Report')
@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title">Day Wise User Register Report List</h4>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <form id="day_wise_filter">
                    <div class="row mb-3">
                        <div class="col-md-4">
                        </div>
                        <div class="col-md-4">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-1">
                        <button type="button" id="filter-btn" class="btn btn-primary">
                            <i class="ti ti-search"></i> Filter
                        </button>
                        <button type="button" id="reset-btn" class="btn btn-danger">
                            <i class="ti ti-trash-off"></i> Reset
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-container">
                <table class="table" id="dayWiseUserRegList">
                    <thead>
                        <tr>
                            <th width="5%">ID</th>
                            <th width="15%">Date</th>
                            <th width="10%">Total User</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/js/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/js/buttons.print.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            var table = $('#dayWiseUserRegList').DataTable({
                processing: true,
                serverSide: true,
                searching: false,   
                ordering: false,    
                pageLength: 10,
                ajax: {
                    url: '{{ route("report.daywiseRegister") }}',
                    data: function (d) {
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                    }
                },
              
                lengthMenu: [[10, 25, 50, 100, 500, 1000], [10, 25, 50, 100, 500, 1000]],
              
                columns: [
                    { data: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'date', name: 'date' },
                    { data: 'total_user', name: 'total_user' }
                ]
            });

            $('#filter-btn').click(function() {
                table.ajax.reload();
            });

            $('#reset-btn').click(function() {
                $('#start_date').val('');
                $('#end_date').val('');
                table.ajax.reload();
            });
        });
    </script>
@endpush