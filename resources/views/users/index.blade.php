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
            <div class="row mb-4">
                <div class="col-md-3">
                    <label>Version Code</label>
                    <input type="text" id="filter_version" class="form-control" placeholder="Enter Version Code">
                </div>

                <div class="col-md-3">
                    <label>Select Plan</label>
                    <select id="filter_plan" class="form-select">
                        <option value="">All</option>
                        <option value="free">Free</option>
                        <option value="trial">Trial</option>
                        <option value="premium">Premium</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label>Start Date</label>
                    <input type="date" id="filter_start" class="form-control">
                </div>

                <div class="col-md-3">
                    <label>End Date</label>
                    <input type="date" id="filter_end" class="form-control">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12 text-end">
                    <button type="button" id="search-btn" class="btn btn-primary me-2">Filter</button>
                    <button type="button" id="reset-btn" class="btn btn-danger">Reset</button>
                </div>
            </div>
            <hr class="mb-5">
            <div class="table-container">
                <table class="table" id="users-table">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Reg. Date</th>
                            {{-- <th>App Version</th> --}}
                            <th>Logo</th>
                            <th>Business Name</th>
                            <th>Mobile</th>
                            <th>Status</th>
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
    <script>
        $(document).ready(function() {
            var table = $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('user.index') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'photo',
                        name: 'photo'
                    },
                    {
                        data: 'business_name',
                        name: 'business_name'
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
                        data: 'otp',
                        name: 'otp'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ]
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

        $('#reset-btn').click(function() {
            $('#filter_version').val('');
            $('#filter_plan').val('');
            $('#filter_start').val('');
            $('#filter_end').val('');
            table.ajax.reload();
        });

        $('#search-btn').click(function() {
            table.ajax.reload();
        });
    </script>
@endpush
