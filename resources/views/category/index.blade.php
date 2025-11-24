@extends('layouts.main')
@section('title', 'Category')
@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title">All Category</h4>
            <a href="#" class="btn btn-sm btn-success btn-icon action-btn" data-ajax-popup="true" data-size="md"
                data-title="{{ __('Create Category') }}" data-url="{{ route('category.create') }}" data-bs-toggle="tooltip"
                data-bs-original-title="{{ __('Create') }}">Add New </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-container">
                <table class="table" id="category-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Icon</th>
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
    function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    const label = input.nextElementSibling;
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                label.classList.add('has-file');
            };
            
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.style.display = 'none';
            label.classList.remove('has-file');
        }
    }
</script>
<script>
    $(document).ready(function() {
        $('#category-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('category.index') }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'icon', name: 'icon', orderable: false, searchable: false },
                { data: 'title', name: 'title'},
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