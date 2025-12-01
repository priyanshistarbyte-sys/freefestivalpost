@extends('layouts.main')

@section('page-title', 'Edit User')

@section('content')
<div class="card">
    <div class="card-header">
        <a href="{{ route('user.index') }}" class="btn btn-secondary btn-sm float-end">Back to List</a>
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

        <form action="{{ route('user.update', $user->id) }}" enctype="multipart/form-data" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Business Name</label>
                    <input type="text" class="form-control" name="business_name" 
                        value="{{ old('business_name', $user->business_name) }}" placeholder="Enter Business Name">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email"
                        value="{{ old('email', $user->email) }}" placeholder="example@gmail.com">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Business Logo <span>(250px X 100px)</span></label>
                    <input type="file" class="form-control" name="business_logo" accept="image/*">
                    
                    @if ($user->business_logo)
                        <img src="{{ asset('uploads/business_logo/'.$user->business_logo) }}" 
                             alt="Logo" class="img-thumbnail mt-2" width="150">
                    @endif
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Business Note</label>
                    <input type="text" class="form-control" name="note"
                        value="{{ old('note', $user->note) }}" placeholder="Enter Business Note">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Business Mobile 1</label>
                    <input type="number" class="form-control" name="mobile"
                        value="{{ old('mobile', $user->mobile) }}" placeholder="8888888888">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Business Mobile 2</label>
                    <input type="number" class="form-control" name="b_mobile2"
                        value="{{ old('b_mobile2', $user->b_mobile2) }}" placeholder="8888888888">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Business Email</label>
                    <input type="email" class="form-control" name="b_email"
                        value="{{ old('b_email', $user->b_email) }}" placeholder="example@gmail.com">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Business Website</label>
                    <input type="text" class="form-control" name="b_website"
                        value="{{ old('b_website', $user->b_website) }}" placeholder="www.example.com">
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label">Business Address</label>
                    <textarea rows="4" name="address" class="form-control" placeholder="Enter Business Address">{{ old('address', $user->address) }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Active / Deactive</label><br>
                        <label class="custom-switch">
                            <input type="checkbox" name="status" value="1" {{ $user->status ? 'checked' : '' }}>
                            <span class="switch-slider"></span>
                        </label>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Paid / Unpaid</label><br>
                        <label class="custom-switch">
                            <input type="checkbox" name="ispaid" value="1" {{ $user->ispaid ? 'checked' : '' }}>
                            <span class="switch-slider"></span>
                        </label>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Gender</label><br>
                        <div class="radio-group">
                            <label class="radio-container">Male
                                <input type="radio" name="gender" value="0" {{ $user->gender == 0 ? 'checked' : '' }}>
                                <span class="radio-checkmark"></span>
                            </label>

                            <label class="radio-container">Female
                                <input type="radio" name="gender" value="1" {{ $user->gender == 1 ? 'checked' : '' }}>
                                <span class="radio-checkmark"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
