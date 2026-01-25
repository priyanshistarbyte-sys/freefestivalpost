@extends('layouts.main')
@section('title', 'App Notification')
@section('content')
   
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title">Notification List</h4>
            <div>
                <a href="{{ route('notification.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus me-2"></i>Add
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-container">
                <table class="table" id="notification-table">
                    <thead>
                        <tr>
                            <th width="8%">ID</th>
                            <th width="7%">Image</th>
                            <th width="20%">Title</th>
                            <th width="25%">Message</th>
                            <th width="15%">URL</th>
                            <th width="15%">Created</th>
                            <th width="10%">Action</th>
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
            $('#notification-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                scrollX: false,
                responsive: true,
                ajax: '{{ route('notification.index') }}',
                dom: '<"d-flex justify-content-between align-items-center mb-3"<"d-flex align-items-center gap-2"Bl><f>>rtip',
                lengthMenu: [[10, 25, 50, 100, 500, 1000], [10, 25, 50, 100, 500, 1000]],
                buttons: [
                        {
                            extend: 'excelHtml5',
                            text: 'Excel',
                            title: 'Notification',
                            className: 'btn btn-success btn-sm',
                            exportOptions: {
                                columns: [0,1,2,3,4,5]
                            }
                        },
                        {
                            extend: 'print',
                            text: 'Print',
                            title: 'Notification',
                            className: 'btn btn-info btn-sm',
                            exportOptions: {
                                columns: [0,1,2,3,4,5]
                            }
                        }
                ],
                columns: [
                    { data: 'id', name: 'id'},
                    { data: 'image', name: 'image'},
                    { data: 'title', name: 'title'},
                    { data: 'message', name: 'message'},
                    { data: 'url', name: 'url'},
                    { data: 'created_at', name: 'created_at'},
                    { data: 'actions', name: 'actions'},
                ]
            });
        });
    </script>
@endpush