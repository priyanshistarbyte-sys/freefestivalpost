@extends('layouts.main')
@section('title', 'User')
@section('content')

    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title">All Users</h4>
            <a href="#" class="btn btn-primary" data-ajax-popup="true" data-size="lg"
                data-title="{{ __('Create User') }}" data-url="{{ route('admin-user.create') }}" data-bs-toggle="tooltip"
                data-bs-original-title="{{ __('Create') }}"><i class="fa fa-plus me-2"></i>Add</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-container">
                <table class="table" id="admin-user-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>User Id</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Role</th>
                            <th>Reg Date</th>
                            <th>Status</th>
                            <th>OTP</th>
                            <th>Promo</th>
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
            $('#admin-user-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin-user.index') }}',
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
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
                        data: 'role',
                        name: 'role'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'otp',
                        name: 'otp'
                    },
                    {
                        data: 'note',
                        name: 'note'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        });
    </script>
@endpush
