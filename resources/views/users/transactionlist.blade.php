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
            $('#transaction-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('users.transactions.list') }}',
                dom: 'Bfrtip',
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
                    { data: 'date', name: 'date' },                 // Trial Date
                    { data: 'amount', name: 'amount' },
                    { data: 'plan_name', name: 'plan_name' },
                    { data: 'transactionid', name: 'transactionid' },
                    { data: 'payment_status', name: 'payment_status' },
                    { data: 'ispaid', name: 'ispaid', orderable: false, searchable: false },
                    { data: 'created_at', name: 'created_at' },
                ]
            });
        });
    </script>
@endpush
