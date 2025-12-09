@extends('layouts.main')
@section('title', 'Complain')
@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="card-title">Complain List</h3>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <form id="complain_filter">
                    <div class="row mb-4">
                        <div class="col-md-2">
                        
                        </div>
                        <div class="col-md-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>Select Status</label>
                            <select class="form-select" name="status" id="status">
                                <option value="">Select Status</option>
                                <option value="0">Pending</option>
                                <option value="1">On Progress</option>
                                <option value="2">Hold</option>
                                <option value="3">Solved</option>
                            </select>
                        </div>
                        <div class="col-md-1" style=" padding: 20px;">
                            <button type="button" id="filter-btn" class="btn  btn-primary">
                                <i class="ti ti-search"></i> Filter
                            </button>
                            <button type="button" id="reset-btn" class="btn  btn-danger">
                                <i class="ti ti-trash-off"></i> Reset
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            {{-- <hr class="my-4"> --}}
            <div class="table-container">
                <table class="table" id="complain-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Complain Id</th>
                            <th>Message</th>
                            <th>Reply</th>
                            <th>Status</th>
                            <th>Date</th>
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
            var table = $('#complain-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route("complain.list") }}',
                    data: function (d) {
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                        d.status = $('#status').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'business_name', name: 'business_name' },
                    { data: 'complain_id', name: 'complain_id' },
                    { data: 'message', name: 'message' },
                    { data: 'reply', name: 'reply' },
                    { data: 'status', name: 'status' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ]
            });

            $('#filter-btn').click(function() {
                table.ajax.reload();
            });

            $('#reset-btn').click(function() {
                $('#start_date').val('');
                $('#end_date').val('');
                $('#status').val('');
                table.ajax.reload();
            });
        });
    </script>
@endpush
