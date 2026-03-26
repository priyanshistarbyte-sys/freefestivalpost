@extends('layouts.main')
@section('title', 'Category')
@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="card-title">Category List</h3>
            <a href="#" class="btn btn-primary" data-ajax-popup="true" data-size="md"
                data-title="{{ __('Create Category') }}" data-url="{{ route('category.create') }}" data-bs-toggle="tooltip"
                data-bs-original-title="{{ __('Create') }}"><i class="fa fa-plus me-2"></i>Add</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-container">
                <table class="table" id="category-table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>No</th>
                            <th>Icon</th>
                            <th>Name</th>
                            <th>Thumbnail</th>
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

        function getSubCategoryRows(data) {
            if (!data.length) {
                return '<tr><td colspan="5" class="text-center text-muted">No sub categories found</td></tr>';
            }
            let rows = '';
            data.forEach(function(sub, index) {
                rows += `
                    <tr style="background:#f9f9f9">
                        <td></td>
                        <td>${index + 1}</td>
                        <td><img src="${sub.image}" width="30" height="30" class="rounded"></td>
                        <td>${sub.mtitle}</td>
                        <td>
                            <a href="${sub.edit_url}" class="btn btn-sm"><i class="fa fa-edit me-1"></i></a>
                            <button type="button" class="btn btn-sm delete-btn" data-url="${sub.delete_url}"><i class="fa fa-trash me-1"></i></button>
                        </td>
                    </tr>`;
            });
            return rows;
        }

        $(document).ready(function() {
            var table = $('#category-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                scrollX: false,
                responsive: true,
                pageLength: 100,
                ajax: '{{ route('category.index') }}',
                dom: '<"d-flex justify-content-between align-items-center mb-3"<"d-flex align-items-center gap-2"Bl><f>>rtip',
                lengthMenu: [[10, 25, 50, 100, 500, 1000], [10, 25, 50, 100, 500, 1000]],
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: 'Excel',
                        title: 'Category',
                        className: 'btn btn-success btn-sm',
                        exportOptions: { columns: [1, 3] }
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        title: 'Category',
                        className: 'btn btn-info btn-sm',
                        exportOptions: { columns: [1, 3] }
                    }
                ],
                columns: [
                    {
                        className: 'dt-control',
                        orderable: false,
                        searchable: false,
                        data: null,
                        defaultContent: '<i class="fa fa-chevron-right" style="cursor:pointer"></i>'
                    },
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'icon', name: 'icon', orderable: false, searchable: false },
                    { data: 'title', name: 'title' },
                    { data: 'thumb', name: 'thumb', orderable: false, searchable: false },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false },
                ]
            });

            $('#category-table tbody').on('click', 'td.dt-control', function() {
                var tr = $(this).closest('tr');
                var row = table.row(tr);
                var icon = $(this).find('i');

                if (tr.next('.sub-category-row').length) {
                    tr.next('.sub-category-row').remove();
                    icon.removeClass('fa-chevron-down').addClass('fa-chevron-right');
                } else {
                    var categoryId = row.data().id;
                    $.get('{{ url('admin/category') }}/' + categoryId + '/subcategories', function(data) {
                        tr.after(
                            '<tr class="sub-category-row"><td colspan="5" class="p-0">' +
                            '<table class="table table-sm mb-0"><thead><tr><th></th><th>#</th><th>Image</th><th>Name</th><th>Actions</th></tr></thead>' +
                            '<tbody>' + getSubCategoryRows(data) + '</tbody></table></td></tr>'
                        );
                        icon.removeClass('fa-chevron-right').addClass('fa-chevron-down');
                    });
                }
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
                        const form = $('<form>', { 'method': 'POST', 'action': deleteUrl });
                        form.append($('<input>', { 'type': 'hidden', 'name': '_token', 'value': '{{ csrf_token() }}' }));
                        form.append($('<input>', { 'type': 'hidden', 'name': '_method', 'value': 'DELETE' }));
                        $('body').append(form);
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
