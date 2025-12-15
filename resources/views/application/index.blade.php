@extends('layouts.main')
@section('title', 'Application')
@section('content')

    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title">Application List</h4>
            <a href="{{ route('application.create') }}" class="btn btn-primary"><i class="fa fa-plus me-2"></i>Add</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-container">
                <table class="table" id="application-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>App Name</th>
                            <th>Package</th>
                            <th>Unite</th>
                            <th>Click</th>
                            <th>Mode</th>
                            <th>Platform</th>
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
            $('#application-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('application.index') }}',
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: 'Excel',
                        title: 'Application',
                        className: 'btn btn-success btn-sm',
                        exportOptions: {
                            columns: [0,1,2,3,4,5]
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        title: 'Application',
                        className: 'btn btn-info btn-sm',
                        exportOptions: {
                            columns: [0,1,2,3,4,5]
                        }
                    }
                ],
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'app_name',
                        name: 'app_name'
                    },
                    {
                        data: 'app_package_name',
                        name: 'app_package_name'
                    },
                    {
                        data: 'totalUnite',
                        name: 'totalUnite'
                    },
                    {
                        data: 'adclick',
                        name: 'adclick'
                    },
                    {
                        data: 'mode',
                        name: 'mode',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
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
