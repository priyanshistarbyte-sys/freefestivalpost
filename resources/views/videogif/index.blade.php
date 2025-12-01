@extends('layouts.main')
@section('title', 'Videogif')

@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title">All Videogif</h4>
            <a href="#" class="btn btn-primary" data-ajax-popup="true" data-size="lg"
                data-title="{{ __('Create Videogif') }}" data-url="{{ route('videogif.create') }}" data-bs-toggle="tooltip"
                data-bs-original-title="{{ __('Create') }}"><i class="fa fa-plus me-2"></i>Add</a>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-container">
                <table class="table" id="videogif-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Sub Category</th>
                            <th>Type</th>
                            <th>Free/Paid</th>
                            <th>Video</th>
                            <th>Thum</th>
                            <th>Status</th>
                            <th>Lable</th>
                            <th>Created</th>                            	
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
        function previewVideo(input) {
            const file = input.files[0];
            const preview = document.getElementById('videoPreview');
            const source = preview.getElementsByTagName('source')[0];

            if (file) {
                const url = URL.createObjectURL(file);
                source.src = url;
                preview.load();  // Reload the video element to reflect the new source
            }
        }
    </script>
    <script>
        $(document).ready(function() {
            $('#videogif-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('videogif.index') }}',
                columns: [
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'category',
                        name: 'category'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                     {
                        data: 'free_paid',
                        name: 'free_paid'
                    },
                    {
                        data: 'video',
                        name: 'video',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'thumb',
                        name: 'thumb',
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
                        data: 'lable',
                        name: 'lable'
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
        $(document).on('change', '.status-toggle', function () {
            let status = $(this).is(':checked') ? 1 : 0;
            let id = $(this).data('id');

            $.ajax({
                url: "{{ route('videogif.updateStatus') }}",
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
    </script>
@endpush