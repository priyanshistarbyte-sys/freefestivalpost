@extends('layouts.main')
@section('title', 'Feedback')
@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title">All Feedback List</h4>
        </div>
    </div>
  <div class="card">
        <div class="card-body">
            <div class="table-container">
                <table class="table" id="feedback-table">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Business Name</th>
                            <th>Mobile</th>
                            <th>Subject</th>
                            <th>Message</th>
                            <th>Created At</th>
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
            $('#feedback-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('feedback.list') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
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
                        data: 'subject',
                        name: 'subject'
                    },
                    {
                        data: 'message',
                        name: 'message'
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
                    }
                ]
            });
        });
    </script>
@endpush
