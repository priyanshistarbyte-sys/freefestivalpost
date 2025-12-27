@extends('layouts.main')
@section('title', 'Repeat Subscription Report')
@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title">Repeat Subscription Report List</h4>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
             <div class="table-container">
                <table class="table" id="repeatSubscriptionList">
                      <thead>
                            <tr>
                                <th width="5%">Name</th>
                                <th width="10%">Mobile</th>
                                <th width="10%">status</th>
                                <th width="5%">Total</th>
                                <th width="8%">First Date</th>
                                <th width="8%">Last Date</th>
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
            var table = $('#repeatSubscriptionList').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 10,
                ajax: {
                    url: '{{ route("report.repeatSubscription") }}',
                },
              
                lengthMenu: [[10, 25, 50, 100, 500, 1000], [10, 25, 50, 100, 500, 1000]],
               
                columns: [
                        { data: 'name', name: 'a.name' },
                        { data: 'mobile', name: 'a.mobile'},
                        { data: 'pstatus', name: 'p.status'},
                        { data: 'total', name: 'total', orderable: false, searchable: false},
                        { data: 'firstDate', name: 'firstDate', orderable: false, searchable: false},
                        { data: 'lastDate', name: 'lastDate', orderable: false, searchable: false}
                ]
            });
        });
    </script>
@endpush