@extends('layouts.main')
@section('title', 'Dashboard')
@section('content')
    <!-- Today Festival Post List -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Today Festival Post List</h5>
            <div class="card-tools">
                {{-- <button class="btn btn-sm btn-outline-secondary" onclick="$('#todayPostsTable').DataTable().ajax.reload()">
                    <i class="fas fa-sync"></i>
                </button> --}}
                <button class="btn btn-sm btn-outline-secondary" onclick="toggleCard(this)">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-container">
                <table id="todayPostsTable" class="table">
                    <thead>
                        <tr>
                            <th width="15%">No</th>
                            <th width="15%">Date</th>
                            <th width="20%">Cat ID</th>
                            <th width="40%">Title</th>
                            <th width="15%">Image</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Upcoming Festival Post List -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">UpComing Festival Post List</h5>
            <div class="card-tools">
                <button class="btn btn-sm btn-outline-secondary" onclick="toggleCard(this)">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-container">
                <table id="upcomingPostsTable" class="table">
                    <thead>
                        <tr>
                            <th width="15%">No</th>
                            <th width="15%">Date</th>
                            <th width="20%">Cat ID</th>
                            <th width="40%">Title</th>
                            <th width="15%">Image</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- UpComing Festival List -->
    {{-- <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">UpComing Festival List</h5>
            <div class="card-tools">
                <button class="btn btn-sm btn-outline-secondary" onclick="toggleCard(this)">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-container">
                <table id="upcomingFestivalsTable" class="table">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="10%">Date</th>
                            <th width="5%">Cat ID</th>
                            <th width="40%">Title</th>
                            <th width="5%">Plan/Auto</th>
                            <th width="20%">Image</th>
                            <th width="5%">Template</th>
                            <th width="5%">Plan</th>
                            <th width="3%">Paid</th>
                            <th width="3%">Videos</th>
                            <th width="3%">Paid</th>
                            <th width="3%">Banner</th>
                            <th width="3%">Quote</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div> --}}

    <div class="row">
        <!-- App Version Count -->
        <div class="col-xs-3 col-md-3">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">App Version Count</h5>
                    <div class="card-tools">
                        <button class="btn btn-sm btn-outline-secondary" onclick="toggleCard(this)">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-container">
                        <table id="appVersionTable" class="table">
                            <thead>
                                <tr>
                                    <th>Version Code</th>
                                    <th>Total User</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalVersionWiseUser = 0;
                                @endphp

                                @forelse($versionwiseUserCount as $versionUser)
                                    <tr>
                                        <td>{{ $versionUser->app_version ?? '-' }}</td>
                                        <td>{{ $versionUser->totalUser ?? 0 }}</td>
                                    </tr>

                                    @php
                                        $totalVersionWiseUser += $versionUser->totalUser ?? 0;
                                    @endphp
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center">No data found</td>
                                    </tr>
                                @endforelse
                            </tbody>

                            @if (count($versionwiseUserCount))
                                <tfoot>
                                    <tr>
                                        <th>Total</th>
                                        <th><b>{{ $totalVersionWiseUser }}</b></th>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Videos Analytics Last - 7 Days -->
        <div class="col-xs-3 col-md-3">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Videos Analytics Last - 7 Days</h5>
                    <div class="card-tools">
                        <button class="btn btn-sm btn-outline-secondary" onclick="toggleCard(this)">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-container">
                        <table id="appVersionTable" class="table">
                            <thead>
                                <tr>
                                    <th width="10%">No</th>
                                    <th width="40%">Date</th>
                                    <th width="50%">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($videoanalyticsLast7Days as $key => $value)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $value->date ?? '-' }}</td>
                                        <td>{{ $value->count ?? 0 }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">No data found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Daily Crone Job Report -->
        <div class="col-xs-6 col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Daily Crone Job Report</h5>
                    <div class="card-tools">
                        <button class="btn btn-sm btn-outline-secondary" onclick="toggleCard(this)">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-container">
                        <table id="appVersionTable" class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Function</th>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Count</th>
                                    <th>Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($croneReportFetch as $key => $value)
                                    <tr>
                                        <td>{{ $value->id ?? '-' }}</td>
                                        <td>{{ $value->funcation ?? '-' }}</td>
                                        <td>{{ $value->title ?? '-' }}</td>
                                        <td>{{ $value->type ?? '-' }}</td>
                                        <td>{{ $value->count ?? '-' }}</td>
                                        <td>{{ $value->created_at ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No data found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Category Wise Tamplate Count -->
        <div class="col-xs-6 col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Category Wise Tamplate Count</h5>
                    <div class="card-tools">
                        <button class="btn btn-sm btn-outline-secondary" onclick="toggleCard(this)">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-container">
                        <table id="categoryTemplateTable" class="table">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="5%">Date</th>
                                    <th width="60%">Sub Category</th>
                                    <th width="30%">Total Post</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Category Wise Photos Count -->
        <div class="col-xs-6 col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Category Wise Photos Count</h5>
                    <div class="card-tools">
                        <button class="btn btn-sm btn-outline-secondary" onclick="toggleCard(this)">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-container">
                        <table id="categoryPhotoTable" class="table">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="65%">Category</th>
                                    <th width="30%">Total Photo</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
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
        // Toggle card collapse function
        function toggleCard(button) {
            const card = $(button).closest('.card');
            const cardBody = card.find('.card-body');
            const icon = $(button).find('i');

            cardBody.slideToggle();
            icon.toggleClass('fa-minus fa-plus');
        }
        $(document).ready(function() {
            $('#todayPostsTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 10,
                ajax: '{{ route('dashboard.today-festival-posts') }}',
                dom: '<"d-flex justify-content-between align-items-center"<"d-flex align-items-center gap-2"Bl><f>>rtip',
                lengthMenu: [
                    [10, 25, 50, 100, 500, 1000],
                    [10, 25, 50, 100, 500, 1000]
                ],
                buttons: [{
                        extend: 'excelHtml5',
                        text: 'Excel',
                        title: 'Home-Category',
                        className: 'btn btn-success btn-sm',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        title: 'Customframe',
                        className: 'btn btn-info btn-sm',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    }
                ],
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'event_date',
                        name: 'event_date'
                    },
                    {
                        data: 'category_id',
                        name: 'category_id'
                    },
                    {
                        data: 'mtitle',
                        name: 'mtitle'
                    },
                    {
                        data: 'image',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
            $('#upcomingPostsTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 10,
                ajax: '{{ route('dashboard.upcoming-festival-posts') }}',
                dom: '<"d-flex justify-content-between align-items-center"<"d-flex align-items-center gap-2"Bl><f>>rtip',
                lengthMenu: [
                    [10, 25, 50, 100, 500, 1000],
                    [10, 25, 50, 100, 500, 1000]
                ],
                buttons: [{
                        extend: 'excelHtml5',
                        text: 'Excel',
                        title: 'Home-Category',
                        className: 'btn btn-success btn-sm',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        title: 'Customframe',
                        className: 'btn btn-info btn-sm',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    }
                ],
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'event_date',
                        name: 'event_date'
                    },
                    {
                        data: 'category_id',
                        name: 'category_id'
                    },
                    {
                        data: 'mtitle',
                        name: 'mtitle'
                    },
                    {
                        data: 'image',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
            // $('#upcomingFestivalsTable').DataTable({
            //     processing: true,
            //     serverSide: true,
            //     pageLength: 10,
            //     ajax: '{{ route('dashboard.upcoming-festivals') }}',
            //     dom: '<"d-flex justify-content-between align-items-center"<"d-flex align-items-center gap-2"Bl><f>>rtip',
            //     lengthMenu: [
            //         [10, 25, 50, 100, 500, 1000],
            //         [10, 25, 50, 100, 500, 1000]
            //     ],
            //     buttons: [{
            //             extend: 'excelHtml5',
            //             text: 'Excel',
            //             title: 'Home-Category',
            //             className: 'btn btn-success btn-sm',
            //             exportOptions: {
            //                 columns: [0, 1, 2, 3, 4]
            //             }
            //         },
            //         {
            //             extend: 'print',
            //             text: 'Print',
            //             title: 'Customframe',
            //             className: 'btn btn-info btn-sm',
            //             exportOptions: {
            //                 columns: [0, 1, 2, 3, 4]
            //             }
            //         }
            //     ],
            //     columns: [{
            //             data: 'DT_RowIndex',
            //             orderable: false,
            //             searchable: false
            //         },
            //         {
            //             data: 'event_date',
            //             name: 'event_date'
            //         },
            //         {
            //             data: 'category_id',
            //             name: 'category_id'
            //         },
            //         {
            //             data: 'mtitle',
            //             name: 'mtitle'
            //         },
            //         {
            //             data: 'plan_auto',
            //             name: 'plan_auto'
            //         },
            //         {
            //             data: 'image',
            //             orderable: false,
            //             searchable: false
            //         },
            //     ]
            // });
            $('#categoryTemplateTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 10,
                ajax: '{{ route('dashboard.category-template-count') }}',
                dom: '<"d-flex justify-content-between align-items-center"<"d-flex align-items-center gap-2"Bl><f>>rtip',
                lengthMenu: [
                    [10, 25, 50, 100, 500, 1000],
                    [10, 25, 50, 100, 500, 1000]
                ],
                buttons: [{
                        extend: 'excelHtml5',
                        text: 'Excel',
                        title: 'Home-Category',
                        className: 'btn btn-success btn-sm',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        title: 'Customframe',
                        className: 'btn btn-info btn-sm',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    }
                ],
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'event_date',
                        name: 'event_date'
                    },
                    {
                        data: 'mtitle',
                        name: 'mtitle'
                    },
                    {
                        data: 'totalTemplate',
                        name: 'totalTemplate',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
            $('#categoryPhotoTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 10,
                ajax: '{{ route('dashboard.category-photo-count') }}',
                dom: '<"d-flex justify-content-between align-items-center"<"d-flex align-items-center gap-2"Bl><f>>rtip',
                lengthMenu: [
                    [10, 25, 50, 100, 500, 1000],
                    [10, 25, 50, 100, 500, 1000]
                ],
                buttons: [{
                        extend: 'excelHtml5',
                        text: 'Excel',
                        title: 'Category-Photo',
                        className: 'btn btn-success btn-sm',
                        exportOptions: {
                            columns: [0, 1, 2]
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        title: 'Customframe',
                        className: 'btn btn-info btn-sm',
                        exportOptions: {
                            columns: [0, 1, 2]
                        }
                    }
                ],
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'title', name: 'title' },
                    { data: 'totalPhoto', name: 'totalPhoto',orderable: false, searchable: false }
                ]
            });
        });
    </script>
@endpush
