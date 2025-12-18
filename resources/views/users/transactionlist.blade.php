@extends('layouts.main')
@section('title', 'User Transaction')
@section('content')
   <div class="page-header">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title">User Transaction List</h4>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <form id="payment_filter">
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
                <table class="table" id="transaction-table">
                    <thead>
                        <tr>
                            <th>User Id</th>
                            <th>Business Name</th>
                            <th>Mobile</th>
                            <th>Trail Date</th>
                            <th>Amount</th>
                            <th>Package Name</th>
                            <th>Transaction No</th>
                            <th>Status</th>
                            <th>IsPaid</th>
                            <th>Created At</th>
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
            var table = $('#transaction-table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 100,
                ajax: {
                    url: '{{ route("users.transactions.list") }}',
                    data: function (d) {
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                    }
                },
                dom: '<"d-flex justify-content-between align-items-center"<"d-flex align-items-center gap-2"Bl><f>>rtip',
                lengthMenu: [[10, 25, 50, 100, 500, 1000], [10, 25, 50, 100, 500, 1000]],
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: 'Excel',
                        title: 'Transaction',
                        className: 'btn btn-success btn-sm',
                        exportOptions: {
                            columns: [0,1,2,3,4,5,6,7,8,9,10]
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        title: 'Customframe',
                        className: 'btn btn-info btn-sm',
                        exportOptions: {
                            columns: [0,1,2,3,4,5,6,7,8,9,10]
                        }
                    }
                ],
                columns: [
                    { data: 'user_id', name: 'user_id' },
                    { data: 'business_name', name: 'business_name' },
                    { data: 'mobile', name: 'mobile' },
                    { data: 'date', name: 'date' }, // Trial Date
                    { data: 'amount', name: 'amount' },
                    { data: 'plan_name', name: 'plan_name' },
                    { data: 'transactionid', name: 'transactionid' },
                    { data: 'payment_status', name: 'payment_status' },
                    { data: 'ispaid', name: 'ispaid', orderable: false, searchable: false },
                    { data: 'created_at', name: 'created_at' },
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
