@extends('layouts.main')
@section('title', 'Payment Failed')
@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title">Payments Failed List</h4>
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
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Payments Failed List</h5>
            <div class="card-tools">
                <button class="btn btn-sm btn-outline-secondary" onclick="toggleCard(this)">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-container">
                <table class="table" id="payment-failed-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Failed Date</th>
                            <th>Mobile</th>
                            <th>Transaction Id</th>
                            <th>Amount</th>
                            <th>Email</th>
                            <th>Created</th>
                            <th>Updated</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Order Create List</h5>
            <div class="card-tools">
                <button class="btn btn-sm btn-outline-secondary" onclick="toggleCard(this)">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-container">
                <table class="table" id="order-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID</th>
                            <th>Entity</th>
                            <th>Amount</th>
                            <th>Receipt</th>
                            <th>Status</th>
                            <th>Attempts</th>
                            <th>Created At</th>
                        </tr>
                        <tbody>
                            @forelse($ordersList as $key => $item)
                                @php
                                    $mobile = getUserMobileNumber($item->user_id ?? null);
                                @endphp
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->entity }}</td>
                                <td>{{ $item->amount / 100 }}</td>
                                <td>
                                    @if($mobile)
                                        <a target="_blank"
                                        href="https://wa.me/91{{ $mobile }}">
                                            {{ $mobile }}
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $item->status }}</td>
                                <td>{{ $item->attempts }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y h:i:s') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">No data found</td>
                            </tr>
                            @endforelse
                        </tbody>
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
         // Toggle card collapse function
        function toggleCard(button) {
            const card = $(button).closest('.card');
            const cardBody = card.find('.card-body');
            const icon = $(button).find('i');

            cardBody.slideToggle();
            icon.toggleClass('fa-minus fa-plus');
        }
        $(document).ready(function() {
            var payment_table = $('#payment-failed-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                scrollX: false,
                responsive: true,
                ajax: {
                    url: '{{ route("payment.failed") }}',
                    data: function (d) {
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                    }
                },
                dom: '<"d-flex justify-content-between align-items-center mb-3"<"d-flex align-items-center gap-2"Bl><f>>rtip',
                lengthMenu: [[10, 25, 50, 100, 500, 1000], [10, 25, 50, 100, 500, 1000]],
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: 'Excel',
                        title: 'Complain',
                        className: 'btn btn-success btn-sm',
                        exportOptions: {
                            columns: [0,1,2,3,4,5,6,7]
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        title: 'Complain',
                        className: 'btn btn-info btn-sm',
                        exportOptions: {
                            columns: [0,1,2,3,4,5,6,7]
                        }
                    }
                ],
                columns: [
                    { data: 'id', name: 'id', orderable: false, searchable: false },
                    { data: 'date', name: 'date' },
                    { data: 'mobile', name: 'mobile'},
                    { data: 'transaction_id', name: 'transaction_id' },
                    { data: 'amount', name: 'amount' },
                    { data: 'email', name: 'email' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'updated_at', name: 'updated_at' },
                ]
            });

            $('#filter-btn').click(function() {
                payment_table.ajax.reload();
            });

            $('#reset-btn').click(function() {
                $('#start_date').val('');
                $('#end_date').val('');
                payment_table.ajax.reload();
            });

          
        });
    </script>
@endpush
