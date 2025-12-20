@extends('layouts.main')
@section('title', 'Trial Subscription')
@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title">Trial Subscription List</h4>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-container">
                <table class="table" id="trial-subscription-table">
                    <thead>
                         <tr>
                            <th>ID</th>
                            <th>Mobile</th>
                            <th>U ID-Name</th>
                            <th>Trans ID</th>
                            <th>Status</th>
                            <th>PKG</th>
                            <th>Price</th>
                            <th>Month</th>
                            <th>IsPaid</th>
                            <th>Exp Date</th>
                            <th>Created</th>
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
            $('#trial-subscription-table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 10,
                ajax: '{{ route('payment.trial-subscription') }}',
                dom: '<"d-flex justify-content-between align-items-center"<"d-flex align-items-center gap-2"Bl><f>>rtip',
                lengthMenu: [
                    [10, 25, 50, 100, 500, 1000],
                    [10, 25, 50, 100, 500, 1000]
                ],
                buttons: [{
                        extend: 'excelHtml5',
                        text: 'Excel',
                        title: 'Trial-Subscription',
                        className: 'btn btn-success btn-sm',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        title: 'Trial-Subscription',
                        className: 'btn btn-info btn-sm',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                        }
                    }
                ],
                columns: [
                    { data: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'mobile', name: 'u.mobile' },
                    { data: 'business_name', name: 'u.business_name' },
                    { data: 'transactionid', name: 'p.transactionid' },
                    { data: 'status', name: 'p.status' },
                    { data: 'packageid', name: 'p.packageid' },
                    { data: 'price', name: 'p.price' },
                    { data: 'month', name: 'p.month' },
                    { data: 'ispaid', name: 'u.ispaid' },
                    { data: 'expdate', name: 'u.expdate' },
                    { data: 'created_at', name: 'p.created_at' }
                ]
            });
        });
    </script>
@endpush
