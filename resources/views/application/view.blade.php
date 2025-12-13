@extends('layouts.main')

@section('page-title', '')

@section('content')

    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title">{{ $applicationAdd->app_name ?? '' }} - {{ $applicationAdd->app_package_name ?? '' }}
                ({{ $totaladvertisements }})</h4>
            <a href="{{ route('application.index') }}" class="btn btn-secondary btn-lg ">Back to List</a>
        </div>
    </div>

    {{-- Application Info --}}
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Application Info</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>App Name</th>
                                <th>Package Name</th>
                                <th>Ads Click</th>
                                <th>Mode</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $applicationAdd->app_name ?? '' }}</td>
                                <td>{{ $applicationAdd->app_package_name ?? '' }}</td>
                                <td>{{ $applicationAdd->adclick ?? '' }}</td>
                                <td>
                                    @if ($applicationAdd->mode == 1)
                                        <a href="javascript:void(0);">
                                            <button type="button" class="btn btn-sm btn btn-success" data-toggle="tooltip"
                                                title="Live">
                                                <i class="fa fa-circle" aria-hidden="true"></i>
                                            </button>
                                        </a>
                                    @else
                                        Test
                                    @endif
                                </td>
                                <td>
                                    @if ($applicationAdd->status == 0)
                                        <a href="javascript:void(0);">
                                            <button type="button" class="btn btn-sm btn btn-light" data-toggle="tooltip"
                                                title="Off">
                                                <i class="fa fa-power-off"></i>
                                            </button>
                                        </a>
                                    @elseif($applicationAdd->status == 1)
                                        <a href="javascript:void(0);">
                                            <button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip"
                                                title="Google">
                                                <i class="fa fa-google"></i>
                                            </button>
                                        </a>
                                    @else
                                        <a href="javascript:void(0);">
                                            <button type="button" class="btn btn-sm btn-primary" data-toggle="tooltip"
                                                title="Facebook">
                                                <i class="fa fa-facebook"></i>
                                            </button>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Dailog Info --}}
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Dailog Info</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>IsDisplay</th>
                                <th>IsDisplay Other</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Button 1</th>
                                <th>Button 2</th>
                                <th>Link</th>
                                <th>Image</th>
                                <th>Version</th>
                                <th>Update Force</th>
                                <th>Other Force</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $dailogs->d_isDisplay == 1 ? 'On' : 'Off' }}</td>
                                <td>{{ $dailogs->d_other_isDisplay == 1 ? 'On' : 'Off' }}</td>
                                <td>{{ $dailogs->title ?? '' }}</td>
                                <td>
                                    <div style="max-width: 400px; white-space: normal; word-wrap: break-word;">
                                        {{ strlen($dailogs->description) > 150 ? substr($dailogs->description, 0, 150) . '...' : $dailogs->description }}
                                    </div>
                                </td>
                                <td>
                                    @if (!empty($dailogs->button1))
                                        <span class="badge bg-success">{{ $dailogs->button1 }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if (!empty($dailogs->button2))
                                        <span class="badge bg-danger">{{ $dailogs->button2 }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ $dailogs->link ?? '#' }}" target="_blank">Link</a>
                                </td>
                                <td>
                                    <a class="image-popup-no-margins"
                                        href="{{ $dailogs && $dailogs->image ? asset('storage/' . $dailogs->image) : asset('assets/images/default.jpg') }}">
                                        <img class="img-responsive"
                                            src="{{ $dailogs && $dailogs->image ? asset('storage/' . $dailogs->image) : asset('assets/images/default.jpg') }}"
                                            alt="Icon" class="dataTable-app-img rounded"
                                            style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;">
                                    </a>
                                </td>
                                <td>{{ $dailogs->appversion ?? '' }}</td>
                                <td>{{ $dailogs->forcefully == 1 ? 'On' : 'Off' }}</td>
                                <td>{{ $dailogs->other_forcefully == 1 ? 'On' : 'Off' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

   {{-- 7 Dayas Analytics --}}
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">7 Days User Analytics</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>New</th>
                                <th>Active</th>
                                <th>Impression</th>
                                <th>Updated Date</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($analytics as $analytic)
                                <tr>
                                    <td>
                                        {{ $analytic->c_date != '0000-00-00' ? date('d/m/Y', strtotime($analytic->c_date)) : '' }}
                                    </td>

                                    <td>{{ $analytic->new ?? '-' }}</td>
                                    <td>{{ $analytic->active ?? '-' }}</td>
                                    <td>{{ $analytic->impression ?? '-' }}</td>

                                    <td>
                                        {{ !empty($analytic->updated_at) ? date('d/m/Y H:i', strtotime($analytic->updated_at)) : '-' }}
                                    </td>
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

    {{-- Live  Analytics --}}
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Live User Analytics</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>New</th>
                                <th>Active</th>
                                <th>Impression</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($liveanalytics as $liveanalytic)
                                <tr>
                                    <td>
                                        {{ $liveanalytic->d_date != '0000-00-00' ? date('d/m/Y', strtotime($liveanalytic->d_date)) : '' }}
                                    </td>
                                    <td>{{ $liveanalytic->totalNew ?? '-' }}</td>
                                    <td>{{ $liveanalytic->totalActive ?? '-' }}</td>
                                    <td>{{ $liveanalytic->totalImpression ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">No record found!..</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Application Unite Id --}}
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                    <h5 class="card-title mb-0">Application Unite Id</h5>
                </div>
            <div class="card-body">
                <div class="table-container">
                    <table class="table" id="ads-api-table">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Type</th>
                                    <th>App</th>
                                    <th>Title</th>
                                    <th>Ads ID</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
      <script>
        $(document).ready(function() {
            $('#ads-api-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('application.show',Crypt::encrypt($applicationAdd->id)) }}',
                columns: [
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'ads_type',
                        name: 'ads_type'
                    },
                    {
                        data: 'ads_app',
                        name: 'ads_app'
                    },
                    {
                        data: 'ads_title',
                        name: 'ads_title'
                    },
                    {
                        data: 'ads_id',
                        name: 'ads_id'
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
    </script>
@endpush