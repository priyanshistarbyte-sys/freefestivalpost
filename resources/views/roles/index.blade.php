@extends('layouts.main')
@section('title', 'Roles')
@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title">All Roles</h4>
            <a href="#" class="btn btn-primary" data-ajax-popup="true" data-size="md"
                data-title="{{ __('Create Role') }}" data-url="{{ route('roles.create') }}" data-bs-toggle="tooltip"
                data-bs-original-title="{{ __('Create') }}"><i class="fa fa-plus me-2"></i>Add</a>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body">
            <div class="table-container">
                <table class="table" id="role-permission-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
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
            $('#role-permission-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('roles.index') }}',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'name', name: 'name'},
                    { data: 'actions', name: 'actions', orderable: false, searchable: false },
                ]
            });
        });
    </script>
@endpush