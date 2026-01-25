@extends('layouts.main')
@section('title', 'Payments List')
@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title">Payments List</h4>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            {{-- <a href="{{ route('payment.paid-subscription') }}" class="btn btn-secondary btn-lg float-end ">Back to List</a> --}}
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('payment.manually') }}" method="POST">
                @csrf
                 <div class="row">
                    <div class="mb-3 col-md-3 form-group">
                        <label for="mobile" class="form-label">Fetch User</label>
                        <input type="text" id="mobile" name="mobile" placeholder="Enter Mobile Number" class="form-control mobile" required>
                        <input type="hidden" id="userid" name="userid" value="">
                    </div>
                    <div class="mb-3 col-md-1 form-group" style="padding-top: 25px;">
                        <button type="button" class="getMobileData btn btn-primary fetch">
                            Fetch
                        </button>
                    </div>
                    <div class="mb-3 col-md-4 form-group">
                        <label for="mobile" class="form-label">Transaction ID</label>
                        <input type="text" id="transationid" name="transationid" placeholder="Enter Transation ID" class="form-control" required>
                    </div>
                    <div class="mb-3 col-md-4 form-group">
                         <label for="mobile" class="form-label">Select Plan</label>
                          <select class="form-select" name="select_plan" id="select_plan" required>
                            <option value="">-- Select Plan --</option>
                            <option value="1">Free Trial</option>
                            @foreach ($plans ?? [] as $plan)
                                <option value="{{ $plan->id }}">{{ $plan->plan_name }}</option>
                            @endforeach
                         </select>
                    </div>
                    <div class="mb-3 col-md-4 form-group">
                            <!---->
                    </div>
                    <div class="mb-3 col-md-4 form-group">
                        <label class="form-label">Select Date</label>
                        <input type="date" class="form-control" id="buyDate" name="buyDate" value="{{ old('buyDate', date('Y-m-d')) }}" placeholder="Enter Date" required>
                    </div>
                    <div class="mb-3 col-md-4 form-group">
                        <label for="freeDays" class="form-label">Free Trial Days</label>
                        <input type="text" id="freeDays" name="freeDays" placeholder="Enter Free Trial Days" class="form-control">
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="submit" class="btn btn-primary" disabled="disabled">Send</button>
                    </div>
                 </div>
            </form>
         
        </div>
        <div class="col-md-12">
            <table class="table">
                <tr>
                    <th width="10%">ID</th>
                    <th width="10%">Mobile</th>
                    <th width="15%">Name</th>
                    <th width="15%">Email</th>
                    <th width="10%">isPaid</th>
                    <th width="15%">Exp Date</th>
                    <th width="10%">Status</th>
                    <th width="15%">Last Login</th>
                </tr>
                <tr class="insertRow">

                </tr>
            </table>
        </div>
    </div>

    <!-- Other Number Payment Pay -->
    <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Other Number Payment Pay</h5>
                <div class="card-tools">
                    <button class="btn btn-sm btn-outline-secondary" onclick="toggleCard(this)">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table id="othernumberpaymentTable" class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Event</th>
                                <th>Transaction</th>
                                <th>Amount</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
    </div>
    <!-- Paid Payments Active List -->
    <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Paid Payments Active List</h5>
                <div class="card-tools">
                    <button class="btn btn-sm btn-outline-secondary" onclick="toggleCard(this)">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table id="paymentactiveTable" class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Mobile</th>
                                <th>U ID-Name</th>
                                <th>Trans ID</th>
                                <th>Status</th>
                                <th>Package</th>
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
    <!--  Paid Payments But Expired List -->
      <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Paid Payments But Expired List</h5>
                <div class="card-tools">
                    <button class="btn btn-sm btn-outline-secondary" onclick="toggleCard(this)">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table id="paymentexpireTable" class="table">
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
        // Toggle card collapse function
        function toggleCard(button) {
            const card = $(button).closest('.card');
            const cardBody = card.find('.card-body');
            const icon = $(button).find('i');

            cardBody.slideToggle();
            icon.toggleClass('fa-minus fa-plus');
        }
        $(document).ready(function() {
            $('#othernumberpaymentTable').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                scrollX: false,
                responsive: true,
                pageLength: 10,
                ajax: '{{ route('payment.othernumberpayment') }}',
                dom: '<"d-flex justify-content-between align-items-center mb-3"<"d-flex align-items-center gap-2"Bl><f>>rtip',
                lengthMenu: [
                    [10, 25, 50, 100, 500, 1000],
                    [10, 25, 50, 100, 500, 1000]
                ],
                buttons: [{
                        extend: 'excelHtml5',
                        text: 'Excel',
                        title: 'Payment',
                        className: 'btn btn-success btn-sm',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        title: 'Payment',
                        className: 'btn btn-info btn-sm',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                        }
                    }
                ],
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'event',
                        name: 'event'
                    },
                    {
                        data: 'transaction_id',
                        name: 'transaction_id'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'mobile',
                        name: 'mobile'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false 
                    },
                ]
            });
            $('#paymentactiveTable').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                scrollX: false,
                responsive: true,
                pageLength: 10,
                ajax: '{{ route('payment.active') }}',
                dom: '<"d-flex justify-content-between align-items-center mb-3"<"d-flex align-items-center gap-2"Bl><f>>rtip',
                lengthMenu: [
                    [10, 25, 50, 100, 500, 1000],
                    [10, 25, 50, 100, 500, 1000]
                ],
                buttons: [{
                        extend: 'excelHtml5',
                        text: 'Excel',
                        title: 'Payment-Active',
                        className: 'btn btn-success btn-sm',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        title: 'Payment-Active',
                        className: 'btn btn-info btn-sm',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                        }
                    }
                ],
             columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'mobile',        name: 'u.mobile' },
                { data: 'business_name', name: 'u.business_name' },
                { data: 'transactionid', name: 'p.transactionid' },
                { data: 'status',        name: 'p.status' },
                { data: 'packageid',     name: 'p.packageid' },
                { data: 'price',         name: 'p.price' },
                { data: 'month',         name: 'p.month' },
                { data: 'ispaid',        name: 'u.ispaid' },
                { data: 'expdate',       name: 'u.expdate' },
                { data: 'created_at',    name: 'p.created_at' },
            ]
            });
            $('#paymentexpireTable').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                scrollX: false,
                responsive: true,
                pageLength: 10,
                ajax: '{{ route('payment.deactive') }}',
                dom: '<"d-flex justify-content-between align-items-center mb-3"<"d-flex align-items-center gap-2"Bl><f>>rtip',
                lengthMenu: [
                    [10, 25, 50, 100, 500, 1000],
                    [10, 25, 50, 100, 500, 1000]
                ],
                buttons: [{
                        extend: 'excelHtml5',
                        text: 'Excel',
                        title: 'Payment-Active',
                        className: 'btn btn-success btn-sm',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        title: 'Payment-Active',
                        className: 'btn btn-info btn-sm',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                        }
                    }
                ],
                columns: [
                    { data: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'mobile',        name: 'u.mobile' },
                    { data: 'business_name', name: 'u.business_name' },
                    { data: 'transactionid', name: 'p.transactionid' },
                    { data: 'status',        name: 'p.status' },
                    { data: 'packageid',     name: 'p.packageid' },
                    { data: 'price',         name: 'p.price' },
                    { data: 'month',         name: 'p.month' },
                    { data: 'ispaid',        name: 'u.ispaid' },
                    { data: 'expdate',       name: 'u.expdate' },
                    { data: 'created_at',    name: 'p.created_at' },
                ]
            });

        });
        $(document).on('click', '.delete-btn', function(e) {
            e.preventDefault();
            const deleteUrl = $(this).attr('data-url');
            
            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to delete this record?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Continue',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = $('<form>', {
                        'method': 'POST',
                        'action': deleteUrl
                    });
                    form.append($('<input>', {
                        'type': 'hidden',
                        'name': '_token',
                        'value': '{{ csrf_token() }}'
                    }));
                    form.append($('<input>', {
                        'type': 'hidden',
                        'name': '_method',
                        'value': 'DELETE'
                    }));
                    $('body').append(form);
                    form.submit();
                }
            });
        });
    </script>
    <script>
        $('.getMobileData').click(function () {

            let mobile = $('.mobile').val();
            $('#transationid').val('');

            $.ajax({
                type: 'POST',
                url: "{{ route('payment.getUserData') }}",
                data: {
                    mobile: mobile,
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {

                  if (response.status === 'success') {

                    $(':submit').prop('disabled', false);
                    $('#userid').val(response.data.id);

                    let rowData = `
                        <td>${response.data.id}</td>
                        <td>${response.data.mobile}</td>
                        <td>${response.data.business_name ?? ''}</td>
                        <td>${response.data.b_email ?? ''}</td>
                        <td>${response.data.payment_status}</td>
                        <td>${response.data.expdate ?? ''}</td>
                        <td>${response.data.admin_status}</td>
                        <td>${response.data.last_login ?? ''}</td>
                    `;

                    $('.insertRow').html(rowData);
                    toastr.success(response.message);

                } else {
                    $(':submit').prop('disabled', true);
                    $('#userid').val('');
                    $('.insertRow').html('');
                    toastr.error(response.message);
                }
                },
                error: function(xhr, status, error) {
                    toastr.error('Failed to fetch user data. Please try again.');
                }
            });
        });
    </script>

@endpush