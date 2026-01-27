@extends('layouts.main')
@section('title', 'User')
@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title">Users List</h4>
            <a href="{{ route('user.create') }}" class="btn btn-primary"><i class="fa fa-plus me-2"></i>Add</a>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <form id="payment_filter" class="w-100">
                    <div class="row mb-3 align-items-end">
                        <div class="col-md-3">
                            <label for="version" class="form-label">Version Code</label>
                            <input type="text" name="version" id="version" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label for="type" class="form-label">User Type</label>
                            <select id="type" class="form-select">
                                <option value="">--- Select ---</option>
                                <option value="1">New User</option>
                                <option value="2">Total Package Paid User</option>
                                <option value="6">Total Package Expired User</option>
                                <option value="3">Trial Plan Active User</option>
                                <option value="5">Trial Plan Expired User</option>
                                <option value="4">Without Logo</option>
                                <option value="8">Total Free User</option>
                                <option value="9">Free Plan Expired User</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control">
                        </div>
                    </div>
                    <!-- Buttons column -->
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
                <table class="table" id="users-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Business Name</th>
                            <th>Mobile</th>
                            <th>Reg. Date</th>
                            <th>App Version</th>
                            <th>Post</th>
                            <th>Logo</th>
                            <th>Status</th>
                            <th>Is Paid</th>
                            <th>Expiry</th>
                            <th>OTP</th>
                            <th>Actions</th>
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
            var table = $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                scrollX: false,
                responsive: true,
                ajax: {
                    url: '{{ route("user.index") }}',
                    data: function (d) {
                        d.version    = $('#version').val();
                        d.start_date = $('#start_date').val();
                        d.end_date   = $('#end_date').val();
                        d.type       = $('#type').val();
                    }
                },
                dom: '<"d-flex justify-content-between align-items-center mb-3"<"d-flex align-items-center gap-2"Bl><f>>rtip',
                lengthMenu: [[10, 25, 50, 100, 500, 1000], [10, 25, 50, 100, 500, 1000]],
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: 'Excel',
                        title: 'Users',
                        className: 'btn btn-success btn-sm',
                        exportOptions: {
                           columns: [0, 1, 2, 3, 4, 5, 6, 7, 9, 10]
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        title: 'Users',
                        className: 'btn btn-info btn-sm',
                        exportOptions: {
                           columns: [0, 1, 2, 3, 4, 5, 6, 7, 9, 10]
                        }
                    }
                ],
                columns: [
                    { data: 'id', name: 'a.id', width: "5%" },
                    { data: 'business_name', name: 'a.business_name' },
                    { data: 'mobile', name: 'a.mobile' },
                    { data: 'created_at', name: 'a.created_at' },
                    { data: 'app_version', name: 'n.app_version' },
                    { data: 'post', name: 'post', searchable: false }, // computed
                    { data: 'photo', name: 'photo', searchable: false, orderable: false },
                    { data: 'status', name: 'status', searchable: false, orderable: false },
                    { data: 'ispaid', name: 'a.ispaid' },
                    { data: 'expdate', name: 'a.expdate' },
                    { data: 'otp', name: 'a.otp' },
                    {
                        data: 'actions',
                        name: 'actions',
                        searchable: false,
                        orderable: false,
                        width: "13%"
                    }
                ]
            });

            $('#filter-btn').click(function() {
                table.ajax.reload();
            });

            $('#reset-btn').click(function() {
                $('#version').val('');
                $('#type').val('');
                $('#start_date').val('');
                $('#end_date').val('');
                table.ajax.reload();
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
        $(document).on('change', '.status-toggle', function() {
            let status = $(this).is(':checked') ? 1 : 0;
            let id = $(this).data('id');

            $.ajax({
                url: "{{ route('admin.updateStatus') }}",
                type: "POST",
                data: {
                    id: id,
                    status: status,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success('Status updated successfully');
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('Something went wrong!');
                }
            });
        });

      
    </script>
@endpush
