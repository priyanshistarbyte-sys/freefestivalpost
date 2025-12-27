@extends('layouts.main')


@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
             <h4 class="card-title mb-0">Coupon Code </h4>
            <a href="{{ route('coupon-code.index') }}" class="btn btn-secondary btn-sm">Back to List</a>
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
            <form action="{{ route('coupon-code.update', $couponCode->id) }}" method="POST">
                @csrf
                @method('PUT')
                  <div class="row">
                    <div class="mb-3 col-md-4 form-group">
                        <label for="name" class="form-label">Name<span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ $couponCode->name }}" placeholder="Enter Coupon Name" required>
                    </div>
                    <div class="mb-3 col-md-4 form-group">
                        <label for="code" class="form-label">Coupon Code<span class="text-danger">*</span></label>
                        <input type="text" name="code" id="code"  class="form-control" value="{{ $couponCode->code }}" placeholder="Enter Coupon Code" required>
                    </div>
                    <div class="mb-3 col-md-4 form-group">
                        <label for="title" class="form-label">Coupon Title<span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control" value="{{ $couponCode->title }}" placeholder="Enter Coupon Title" required>
                    </div>
                    <div class="mb-3 col-md-6 form-group">
                        <label for="start_date" class="form-label">Start Date<span class="text-danger">*</span></label>
                        <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $couponCode->start_date }}" placeholder="Enter Start Date" required>
                    </div>
                    <div class="mb-3 col-md-6 form-group">
                        <label for="end_date" class="form-label">End Date<span class="text-danger">*</span></label>
                        <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $couponCode->end_date }}" placeholder="Enter End Date" required>
                    </div>
                    <div class="mb-3 col-md-6 form-group">
                        <label for="total_qty" class="form-label">Total QTY<span class="text-danger">*</span></label>
                        <input type="number" name="total_qty" id="total_qty"  class="form-control" value="{{ $couponCode->total_qty }}"  placeholder="Enter Total QTY" required>
                    </div>
                    <div class="mb-3 col-md-6 form-group">
                        <label for="total_days" class="form-label">Total Days<span class="text-danger">*</span></label>
                        <input type="number" name="total_days" id="total_days"  class="form-control" value="{{ $couponCode->total_days }}" placeholder="Enter Total Days" required>
                    </div>
                     <div class="mb-3 col-md-6 form-group">
                        <label for="note" class="form-label">Note</label>
                        <input type="text" name="note" id="note" class="form-control" value="{{ $couponCode->note }}" placeholder="Enter Note">
                    </div>
                    <div class="mb-3 col-md-6 form-group">
                        <label for="status" class="form-label">Status</label></br>
                        <label class="custom-switch">
                            <input type="checkbox" name="status" value="1" {{ $couponCode->status ? 'checked' : '' }}>
                            <span class="switch-slider"></span>
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">{{ __('Submit') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection