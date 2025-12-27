@extends('layouts.main')
@section('title', 'Day Wise Subscription')
@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title">Day Wise Subscription List</h4>
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
                <table class="table" id="dayWiseSubscriptionList">
                    <thead>
                        <tr>
                            <th width="5%">ID</th>
                            <th width="10%">Date</th>
                            <th>Business Plan- 1 year (Best Value)</th>
                            <th>1 Month Plan</th>
                            <th>Business Plan - 6 Month</th>
                            <th>12 Month Plan</th>
                            <th>Advance Plan - 6 Month</th>
                            <th>Exclusive Plan</th>
                            <th>Navratri Festival</th>
                            <th>Premium Plan (Most Popular - Recommended)</th>
                            <th>Trail Total</th>
                            <th>Refund</th>
                            <th>Refund Amount</th>
                            <th>Total Paid</th>
                            <th>Total Amount</th>
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
            var table = $('#dayWiseSubscriptionList').DataTable({
                processing: true,
                serverSide: true,
                searching: false,   
                ordering: false,   
                pageLength: 10,
                ajax: {
                    url: '{{ route("report.dayWiseSubscription") }}',
                    data: function (d) {
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                    }
                },
              
                lengthMenu: [[10, 25, 50, 100, 500, 1000], [10, 25, 50, 100, 500, 1000]],
              
                columns: [
                        { data: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'date' },
                        { data: 'business_1_year' },
                        { data: 'one_month' },
                        { data: 'business_6_month' },
                        { data: 'twelve_month' },
                        { data: 'advance_6_month' },
                        { data: 'exclusive_plan' },
                        { data: 'navratri_plan' },
                        { data: 'premium_plan' },
                        { data: 'trial_total' },
                        { data: 'refund_count' },
                        { data: 'refund_amount' },
                        { data: 'total_paid' },
                        { data: 'total_amount' }
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