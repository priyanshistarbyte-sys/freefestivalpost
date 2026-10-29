@extends('layouts.main')
@section('title', 'Tamplet')
@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title">Tamplet List</h4>
            <div class="d-flex gap-2">
                <button type="button" id="bulk-delete-btn" class="btn btn-danger" disabled>
                    <i class="fa fa-trash me-2"></i>Delete Selected (<span id="selected-count">0</span>)
                </button>
                <a href="{{ route('tamplet.create') }}" class="btn btn-primary"><i class="fa fa-plus me-2"></i>Add</a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-container">
                <table class="table" id="tamplet-table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" class="form-check-input" id="select-all"></th>
                            <th>Id</th>
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
    <script src="{{ asset('assets/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/js/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/js/buttons.print.min.js') }}"></script>
<script>
    $(document).ready(function() {
        const table = $('#tamplet-table').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            scrollX: false,
            responsive: true,
            ajax: '{{ route('tamplet.index') }}',
            order: [[1, 'desc']],
            dom: '<"d-flex justify-content-between align-items-center mb-3"<"d-flex align-items-center gap-2"Bl><f>>rtip',
            lengthMenu: [[10, 25, 50, 100, 500, 1000], [10, 25, 50, 100, 500, 1000]],
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: 'Excel',
                        title: 'Tamplet',
                        className: 'btn btn-success btn-sm',
                        exportOptions: {
                            columns: [1,2,5,6,7]
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        title: 'Customframe',
                        className: 'btn btn-info btn-sm',
                        exportOptions: {
                            columns: [1,2,5,6,7]
                        }
                    }
                ],
            columns: [
                { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
                { data: 'id', name: 'id'},
                { data: 'event_date', name: 'event_date'},
                { data: 'image', name: 'image'},
                { data: 'mask', name: 'mask'},
                { data: 'category', name: 'category'},
                { data: 'free_paid', name: 'free_paid'},
                { data: 'language', name: 'language'},
                { data: 'actions', name: 'actions', orderable: false, searchable: false },
            ]
        });

        function updateBulkState() {
            const total = $('.row-checkbox').length;
            const checked = $('.row-checkbox:checked').length;
            $('#selected-count').text(checked);
            $('#bulk-delete-btn').prop('disabled', checked === 0);
            $('#select-all').prop('checked', total > 0 && checked === total);
        }

        // Selection is per page: reset whenever the table redraws (page, search, length change)
        table.on('draw', function() {
            $('#select-all').prop('checked', false);
            updateBulkState();
        });

        $('#select-all').on('click', function(e) {
            e.stopPropagation();
            $('.row-checkbox').prop('checked', this.checked);
            updateBulkState();
        });

        $(document).on('change', '.row-checkbox', updateBulkState);

        $('#bulk-delete-btn').on('click', function() {
            const ids = $('.row-checkbox:checked').map(function() { return $(this).val(); }).get();
            if (ids.length === 0) return;

            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to delete ' + ids.length + ' selected record(s)?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Continue',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('tamplet.bulkDestroy') }}',
                        type: 'POST',
                        data: { _token: '{{ csrf_token() }}', ids: ids },
                        success: function(res) {
                            Swal.fire({ icon: 'success', title: 'Deleted', text: res.message, timer: 1500, showConfirmButton: false });
                            table.ajax.reload(null, false);
                        },
                        error: function(xhr) {
                            const msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Something went wrong.';
                            Swal.fire({ icon: 'error', title: 'Error', text: msg });
                        }
                    });
                }
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
    });
</script>
@endpush
