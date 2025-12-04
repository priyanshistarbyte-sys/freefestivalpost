@extends('layouts.main')
@section('title', 'Frames')
@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title">All Frames List</h4>
            <a href="#" class="btn btn-primary" data-ajax-popup="true" data-size="md"
                data-title="{{ __('Create Frame') }}" data-url="{{ route('frame.create') }}"
                data-bs-toggle="tooltip" data-bs-original-title="{{ __('Create') }}"><i class="fa fa-plus me-2"></i>Add</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-container">
                <table class="table" id="frame-table">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Photo</th>
                            <th>Frame Name</th>
                            <th>Free/Paid</th>
                            <th>Status</th>
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
            $('#frame-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('frame.index') }}',
                columns: [{
                        data: 'id',
                        name: 'id',
                    },
                    {
                        data: 'image',
                        name: 'image',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'frame_name',
                        name: 'frame_name'
                    },
                    {
                        data: 'free_paid',
                        name: 'free_paid'
                    },
                    {
                        data: 'status',
                        name: 'status'
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
                url: "{{ route('frame.updateStatus') }}",
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
        $(document).on('change', '.free-paid-toggle', function () {
            let free_paid = $(this).is(':checked') ? 1 : 0;
            let id = $(this).data('id');

            $.ajax({
                url: "{{ route('frame.updateFreePaid') }}",
                type: "POST",
                data: {
                    id: id,
                    free_paid: free_paid,
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