@extends('layouts.main')
@section('title', 'WhatsApp Bulk')

@section('content')
  <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0">Send WhatsApp Bulk</h4>
        </div>
        <div class="card-body">
              @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('whatsapp-bulk.send') }}" enctype="multipart/form-data" method="POST" id="whatsappBulkForm">
                @csrf
                <div class="row">
                    <div class="mb-3 col-md-12 form-group">
                        <label class="form-label" for="typeoffilter">Types of Filter</label>
                        <div class="radio-group">
                            <label class="radio-container">Filter
                                <input type="radio" name="typeoffilter" checked value="filter" id="filter">
                                <span class="radio-checkmark"></span>
                            </label>

                            <label class="radio-container">Bulk (CSV)
                                <input type="radio" name="typeoffilter" value="bulk" id="bulk">
                                <span class="radio-checkmark"></span>
                            </label>

                            <label class="radio-container">Manually
                                <input type="radio" name="typeoffilter" value="manually"  id="manually">
                                <span class="radio-checkmark"></span>
                            </label>

                             <label class="radio-container">Retarget
                                <input type="radio" name="typeoffilter" value="retarget" id="retarget">
                                <span class="radio-checkmark"></span>
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-4 form-group">
                            <label for="cam_title" class="form-label">Camping Name<span class="text-danger">*</span></label>
                            <input type="text" name="cam_title" id="cam_title" class="form-control" placeholder="Enter Camping Name" required>
                        </div>
                        <div class="mb-3 col-md-4 form-group">
                            <label for="temp_list" class="form-label">Select Template</label>
                            <select class="form-select" name="temp_list" id="temp_list">
                                @foreach ($whatsapp_templates ?? [] as $whatsapp_template)
                                    <option value="{{ $whatsapp_template->id }}">{{ $whatsapp_template->template }}--{{ $whatsapp_template->tamp_name }}--{{ $whatsapp_template->type }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row block_filter">
                        <div class="mb-3 col-md-4 form-group">
                            <label for="template" class="form-label">Select Filter Type</label>
                            <select class="form-select" name="filter_type" id="filter_type">
                                    <option value="11">Test - 8140331370</option>
                                    <option value="1">Free User</option>
                                    <option value="8">Payment Fail</option>
                                    <option value="5">Trial Expried User</option>
                                    <option value="3">Plan Expried User</option>
                                    <option value="10">Defult - Active Session - Free User</option>
                                    <option value="4">Trial Active User</option>
                                    <option value="2">Plan Active User</option>
                                    <option value="7">Last Login - All Free User</option>
                            </select>
                        </div>
                        <div class="mb-3 col-md-4 form-group">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date"  value="{{ date('Y-m-d') }}" name="start_date" id="start_date" class="form-control" min="{{ date('Y-m-d') }}">
                        </div>
                        <div class="mb-3 col-md-4 form-group">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date"  value="{{ date('Y-m-d') }}" name="end_date" id="end_date" class="form-control" min="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="mb-3 col-md-4 form-group block_bulk" style="display: none">
                        <label for="image" class="form-label">File Upload (Only CSV) </label>
                        <input type="file" name="image" id="image" accept="image">
                    </div>
                    <div class="mb-3 col-md-4 form-group block_menually" style="display: none">
                          <label for="mobile" class="form-label">Phone Number(s)</label>
                          <textarea rows="15" name="numbers_menually" id="numbers_menually" placeholder="Ex. 8140331370,8140331370" class="form-control"></textarea>
                    </div>
                    <div class="mb-3 col-md-4 form-group block_retarget" style="display: none">
                          <label for="previus_camping" class="form-label">Select Previous Camping</label>
                          <select class="form-select" name="previus_camping" id="previus_camping">
                                @foreach ($camping_lists as $camping)
                                    <option value="{{ $camping->id.'<->'.$camping->title }}">
                                        {{ ($camping->countTime['status'] ? '=>' : '') }}
                                        {{ $camping->title }} -- {{ \Carbon\Carbon::parse($camping->date)->format('d/m/Y') }}
                                    </option>
                                @endforeach
                         </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">{{ __('Submit') }}</button>
                </div>
            </form>
        </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('input[name="typeoffilter"]').on('change', function() {
        const value = $(this).val();
        
        $('.block_filter, .block_bulk, .block_menually, .block_retarget').hide();
        
        if(value === 'filter') {
            $('.block_filter').show();
        } else if(value === 'bulk') {
            $('.block_bulk').show();
        } else if(value === 'manually') {
            $('.block_menually').show();
        } else if(value === 'retarget') {
            $('.block_retarget').show();
        }
    });

    $('#whatsappBulkForm').on('submit', function(e) {
        e.preventDefault();
        
        if(!$('#cam_title').val()) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Camping Name is required'
            });
            return false;
        }
        
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to send this campaign?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, send it!'
        }).then((result) => {
            if (result.isConfirmed) {
                const submitBtn = $(this).find('button[type="submit"]');
                submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Sending...');
                
                const formData = new FormData(this);
                
                $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'),
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        submitBtn.prop('disabled', false).html('Submit');
                        
                        if(response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 2000
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr) {
                        submitBtn.prop('disabled', false).html('Submit');
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message || 'Something went wrong'
                        });
                    }
                });
            }
        });
    });
});
</script>
@endpush
