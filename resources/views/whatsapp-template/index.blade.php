@extends('layouts.main')
@section('title', 'WhatsApp Template List')

@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title">WhatsApp Template List</h4>
            <a href="#" class="btn btn-primary" data-ajax-popup="true" data-size="lg"
                data-title="{{ __('Create WhatsApp Template') }}" data-url="{{ route('whatsapp-template.create') }}" data-bs-toggle="tooltip"
                data-bs-original-title="{{ __('Create') }}"><i class="fa fa-plus me-2"></i>Add</a>
        </div>
    </div>
      <div class="card">
        <div class="card-body">
            <div class="table-container">
                <table class="table" id="whatsapp-template-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Template Details</th>
                            <th>Note</th>
                            <th>Status</th>
                            <th>Bulk Status</th>
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
    <script src="{{ asset('assets/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/js/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/js/buttons.print.min.js') }}"></script>
 <script>
        $(document).ready(function() {
            $('#whatsapp-template-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                scrollX: false,
                responsive: true,
                pageLength: 100,
                ajax: '{{ route('whatsapp-template.index') }}',
                dom: '<"d-flex justify-content-between align-items-center mb-3"<"d-flex align-items-center gap-2"Bl><f>>rtip',
                lengthMenu: [[10, 25, 50, 100, 500, 1000], [10, 25, 50, 100, 500, 1000]],
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: 'Excel',
                        title: 'whatsapp template',
                        className: 'btn btn-success btn-sm',
                        exportOptions: {
                            columns: [0,1,2,4]
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        title: 'whatsapp template',
                        className: 'btn btn-info btn-sm',
                        exportOptions: {
                            columns: [0,1,2,4]
                        }
                    }
                ],
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'template_details',
                        name: 'template_details'
                    },
                    {
                        data: 'note',
                        name: 'note'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'bulk_status',
                        name: 'bulk_status',
                        orderable: false,
                        searchable: false
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
        $(document).on('change', '.status-toggle', function () {
            let status = $(this).is(':checked') ? 1 : 0;
            let id = $(this).data('id');

            $.ajax({
                url: "{{ route('whatsapp-template.updateStatus') }}",
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
        $(document).on('change', '.bulk-status-toggle', function () {
            let bulk_status = $(this).is(':checked') ? 1 : 0;
            let id = $(this).data('id');

            $.ajax({
                url: "{{ route('whatsapp-template.updateBulkStatus') }}",
                type: "POST",
                data: {
                    id: id,
                    bulk_status: bulk_status,
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    if (response.success) {
                        toastr.success('Bulk Status on whatsapp template updated successfully');
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


          