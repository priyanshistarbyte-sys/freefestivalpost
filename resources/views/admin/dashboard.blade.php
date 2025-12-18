@extends('layouts.main')
@section('title', 'Dashboard')
@section('content')
    
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-2">{{ $totalUser }}</h4>
                                <p class="mb-2">Total Users</p>
                                <div class="small">
                                    <span class="me-3">{{ $totalDeactiveUser }} Inactive</span>
                                    <span>{{ $totalTodayNewUser }} Today</span>
                                </div>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-2">{{ $totalUserPost }}</h4>
                                <p class="mb-2">Total User Post</p>
                                <div class="small">
                                    <span class="me-3">{{ $totalUserPostToday }} Today Total Post</span>
                                </div>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-images fa-2x "></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-2">{{ $videoanalytics }}</h4>
                                <p class="mb-2">Total User Videos</p>
                                <div class="small">
                                    <span class="me-3">{{ $videoanalyticsToday }} Today Total Videos</span>
                                </div>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-video-camera fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-2">{{ $totalTamplate }}</h4>
                                <p class="mb-2">Total Tamplate</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-images fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-2">{{ $totalCategory }}</h4>
                                <p class="mb-2">Total Category</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-images fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-2">{{ $totalSubCategory }}</h4>
                                <p class="mb-2">Total Sub Category</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-tags fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-2">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-2">{{ $totalPremiumUser }}</h4>
                                <p class="mb-2">Total Premium User</p>
                                <div class="small">
                                    <span class="me-3">{{ $totalActivePremiumUser }} Active</span>
                                    <span class="me-3">{{ $totalTodayPremiumUser }} Paid Today</span>
                                    <span class="me-3">{{ $totalExpiredTodayUser }} Expired Today</span>
                                    <span class="me-3">{{ $totalExpiredUser }} Total Expired</span>
                                </div>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-2">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-2">{{ $totalTrialUser }}</h4>
                                <p class="mb-2">Total Trial User</p>
                                <div class="small">
                                    <span class="me-3">{{ $totalActiveTrialUser }} Active</span>
                                    <span class="me-3">{{ $totalTodayTrialUser }} Trial Today</span>
                                    <span class="me-3">{{ $totalExpiredTodayTrialUser }} Expired Today</span>
                                    <span class="me-3">{{ $totalExpiredTrialUser }} Total Expired</span>
                                </div>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
   
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

        <div class="row">
            <!-- Paid User Last - 10 -->
            <div class="col-xs-6 col-md-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Paid User Last - 10</h5>
                        <div class="card-tools">
                            <button class="btn btn-sm btn-outline-secondary" onclick="toggleCard(this)">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-container">
                            <table id="paidUserTable" class="table">
                                <thead>
                                    <tr>
                                        <th width="5%">ID</th>
                                        <th width="15%">Date</th>
                                        <th width="20%">Mobile</th>
                                        <th width="30%">Transaction</th>
                                        <th width="15%">Price</th>
                                        <th width="15%">Month</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($todayPaidSubscriptionUser as $key => $value)
                                        <tr>
                                            <td>{{ $value->id }}</td>
                                            <td>{{ \Carbon\Carbon::parse($value->date)->format('d-m-Y') }}</td>
                                            <td>{{ $value->mobile ?? '-' }}</td>
                                            <td>{{ $value->transactionid ?? '-' }}</td>
                                            <td>₹{{ number_format($value->price, 2) }}</td>
                                            <td>{{ $value->month }}</td>
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
            <!-- Trial User Last - 10 -->
            <div class="col-xs-6 col-md-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Trial User Last - 10</h5>
                        <div class="card-tools">
                            <button class="btn btn-sm btn-outline-secondary" onclick="toggleCard(this)">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-container">
                            <table id="TrialUserTable" class="table">
                                <thead>
                                    <tr>
                                        <th width="5%">Id</th>
                                        <th width="15%">Date</th>
                                        <th width="20%">Mobile</th>
                                        <th width="30%">Transaction</th>
                                        <th width="15%">Price</th>
                                        <th width="15%">Month</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($todayTrialSubscriptionUser as $key => $value)
                                        <tr>
                                            <td>{{ $value->id }}</td>
                                            <td>{{ \Carbon\Carbon::parse($value->date)->format('d-m-Y') }}</td>
                                            <td>{{ $value->mobile ?? '-' }}</td>
                                            <td>{{ $value->transactionid ?? '-' }}</td>
                                            <td>₹{{ number_format($value->price, 2) }}</td>
                                            <td>{{ $value->month }}</td>
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
            <!-- Custom Report -->
            <div class="col-md-9 col-ms-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Custom Report</h5>
                        <div class="card-tools">
                            <button class="btn btn-sm btn-outline-secondary" onclick="toggleCard(this)">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-container">
                            <table id="customReportTable" class="table">
                                <thead>
                                    <tr>
                                        <th width="10%">No</th>
                                        <th width="15%">Date</th>
                                        <th width="10%">Post</th>
                                        <th width="10%">Video</th>
                                        <th width="10%">Register</th>
                                        <th width="10%">Paid</th>
                                        <th width="10%">Fail</th>
                                        <th width="10%">Trile</th>
                                        <th width="15%">Revenew</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        @forelse($customReport as $key => $row)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $row['date'] }}</td>
                                            <td>{{ $row['totalPost'] }}</td>
                                            <td>{{ $row['totalVideo'] }}</td>
                                            <td>{{ $row['totalRegister'] }}</td>
                                            <td>{{ $row['totalPaid'] }}</td>
                                            <td>{{ $row['totalFail'] }}</td>
                                            <td>{{ $row['totalTrial'] }}</td>
                                            <td>₹{{ number_format($row['totalRevenue'], 2) }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="9" class="text-center">No data found</td>
                                        </tr>
                                        @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- SMS Log -->
            <div class="col-md-3 col-ms-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">SMS Log</h5>
                        <div class="card-tools">
                            <button class="btn btn-sm btn-outline-secondary" onclick="toggleCard(this)">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-container">
                            <table id="smsLogTable" class="table">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="25%">Date</th>
                                            <th width="15%">Forgot</th>
                                            <th width="15%">Signup</th>
                                            <th width="10%">Unique Signup</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($smsReport as $key => $row)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $row->date }}</td>
                                            <td>{{ $row->total_forgot_sms }}</td>
                                            <td>{{ $row->total_signup_sms }}</td>
                                            <td>{{ $row->total_unique_signup_sms }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No data found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
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
                        title: 'Today-Festival-Post',
                        className: 'btn btn-success btn-sm',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        title: 'Today-Festival-Post',
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
                        title: 'Upcoming-Festival-Post',
                        className: 'btn btn-success btn-sm',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        title: 'Upcoming-Festival-Post',
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
                        title: 'Category-Template-Count',
                        className: 'btn btn-success btn-sm',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        title: 'Category-Template-Count',
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
                        title: 'Category-Photo',
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
