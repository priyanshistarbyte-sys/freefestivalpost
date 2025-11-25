@extends('layouts.main')
@section('title', 'Home Category')
@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title">All Home Category</h4>
            <a href="#" class="btn btn-primary" data-ajax-popup="true" data-size="md"
                data-title="{{ __('Create Home Category') }}" data-url="{{ route('home-category.create') }}" data-bs-toggle="tooltip"
                data-bs-original-title="{{ __('Create') }}"><i class="fa fa-plus me-2"></i>Add</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-container">
                <table class="table" id="home-category-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Sub Category</th>
                            <th>Seq</th>
                            <th>Status</th>
                            <th>Show on Home</th>
                            <th>New</th>                            	
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
        $('#home-category-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('home-category.index') }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'title', name: 'title'},
                { data: 'category', name: 'category'},
                { data: 'sequence', name: 'sequence'},
                { data: 'status', name: 'status'},
                { data: 'is_show_on_home', name: 'is_show_on_home'},
                { data: 'is_new', name: 'is_new'},
                { data: 'actions', name: 'actions', orderable: false, searchable: false },
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
     $(document).on('change', '.status-toggle', function () {
            let status = $(this).is(':checked') ? 1 : 0;
            let id = $(this).data('id');

            $.ajax({
                url: "{{ route('homecategory.updateStatus') }}",
                type: "POST",
                data: {
                    id: id,
                    status: status,
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    if (response.success) {
                        toastr.success('Status updated successfully');
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function () {
                    toastr.error('Something went wrong!');
                }
            });
        });
         $(document).on('change', '.home-status-toggle', function () {
            let is_show_on_home = $(this).is(':checked') ? 1 : 0;
            let id = $(this).data('id');

            $.ajax({
                url: "{{ route('homecategory.showHome') }}",
                type: "POST",
                data: {
                    id: id,
                    is_show_on_home: is_show_on_home,
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    if (response.success) {
                        toastr.success('Show on home updated successfully');
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function () {
                    toastr.error('Something went wrong!');
                }
            });
        });
</script>
@endpush