@extends('layouts.main')
@section('title', 'Tamplet')
@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title">All Tamplet List</h4>
            <a href="{{ route('tamplet.create') }}" class="btn btn-primary"><i class="fa fa-plus me-2"></i>Add</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-container">
                <table class="table" id="tamplet-table">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Image</th>
                            <th>Mask</th>
                            <th>Category</th>
                            <th>Free/Paid</th>
                            <th>Lang</th>
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
        $('#tamplet-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('tamplet.index') }}',
            columns: [
                { data: 'id', name: 'id'},
                { data: 'type', name: 'type'},
                { data: 'event_date', name: 'event_date'},
                { data: 'image', name: 'image'},
                { data: 'mask', name: 'mask'},
                { data: 'category', name: 'category'},
                { data: 'free_paid', name: 'free_paid'},
                { data: 'language', name: 'language'},
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
</script>
@endpush