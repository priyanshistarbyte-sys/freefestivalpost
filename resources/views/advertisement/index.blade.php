@extends('layouts.main')
@section('title', 'Advertisement')
@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="card-title">Advertisement List</h3>
            <a href="#" class="btn btn-primary" data-ajax-popup="true" data-size="md"
                data-title="{{ __('Create Advertisement') }}" data-url="{{ route('advertisement.create') }}" data-bs-toggle="tooltip"
                data-bs-original-title="{{ __('Create') }}"><i class="fa fa-plus me-2"></i>Add</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-container">
                <table class="table" id="ads-api-table">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Type</th>
                            <th>App</th>
                            <th>Title</th>
                            <th>Ads ID</th>
                            <th>Action</th>
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
            $('#ads-api-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('advertisement.index') }}',
                columns: [
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'ads_type',
                        name: 'ads_type'
                    },
                    {
                        data: 'ads_app',
                        name: 'ads_app'
                    },
                    {
                        data: 'ads_title',
                        name: 'ads_title'
                    },
                    {
                        data: 'ads_id',
                        name: 'ads_id'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    },
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
    </script>
@endpush