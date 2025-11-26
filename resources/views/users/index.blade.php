@extends('layouts.main')
@section('title', 'User')
@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title">All Users List</h4>
            <a href="{{ route('user.create') }}" class="btn btn-primary"><i class="fa fa-plus me-2"></i>Add</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-container">
                <table class="table" id="users-table">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Reg. Date</th>
                            <th>Version</th>
                            <th>Logo</th>
                            <th>Name</th>
                            <th>Mobile</th>
                            <th>IsPaid</th>
                            <th>Status</th>
                            <th>OTP</th>
                            <th>Expiry</th>
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
            $('#users-table').DataTable({
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
                        data: 'app_version',
                        name: 'app_version'
                    },
                    {
                        data: 'logo',
                        name: 'logo'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'mobile',
                        name: 'mobile'
                    },
                    {
                        data: 'is_paid',
                        name: 'is_paid'
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
                        data: 'otp_expiry',
                        name: 'otp_expiry'
                    }
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>
    
@endpush
