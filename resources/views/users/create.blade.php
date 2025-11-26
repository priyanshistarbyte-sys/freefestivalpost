@extends('layouts.main')

@section('page-title', 'Create User')

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
            <form action="{{ route('user.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="business_name" class="form-label">Business Name</label>
                        <input type="text" class="form-control" id="business_name" name="business_name"
                            placeholder="Enter Business Name">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                            placeholder="example@gmail.com">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Password <span class="rerq">*</span></label>
                        <input type="password" class="form-control" id="password" name="password" required
                            placeholder="Enter Password">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password <span
                                class="rerq">*</span></label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                            required placeholder="Enter Confirm Password">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="password_confirmation" class="form-label">Business Logo <span>(250px X
                                100px)</span></label>
                        <input type="file" class="form-control" id="business_logo" name="business_logo" accept="image/*">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="note" class="form-label">Business Note</label>
                        <input type="text" class="form-control" id="note" name="note"
                            placeholder="Enter Business Note">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="mobile" class="form-label">Business Mobile 1 <span class="rerq">*</span></label>
                        <input type="number" class="form-control" id="mobile" name="mobile" required
                            placeholder="8888888888">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="b_mobile2" class="form-label">Business Mobile 2</label>
                        <input type="number" class="form-control" id="b_mobile2" name="b_mobile2" placeholder="8888888888">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="b_email" class="form-label">Business Email</label>
                        <input type="email" class="form-control" id="b_email" name="b_email"
                            placeholder="example@gmail.com">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="b_website" class="form-label">Business Website</label>
                        <input type="text" class="form-control" id="b_website" name="b_website"
                            placeholder="www.example.com">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="address" class="form-label">Business Address</label>
                        <textarea rows="4" name="address" id="address" placeholder="Enter Business Address" class="form-control"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label" for="status">Active / Deactive</label></br>
                            <label class="custom-switch">
                                <input type="checkbox" name="status" value="1" checked>
                                <span class="switch-slider"></span>
                            </label>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label" for="ispaid">Paid / Unpaid</label></br>
                            <label class="custom-switch">
                                <input type="checkbox" name="ispaid" value="1">
                                <span class="switch-slider"></span>
                            </label>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label" for="gender">Gender</label></br>
                            <div class="radio-group">
                                <label class="radio-container">Male
                                    <input type="radio" name="gender" value="0" checked>
                                    <span class="radio-checkmark"></span>
                                </label>

                                <label class="radio-container">Female
                                    <input type="radio" name="gender" value="1">
                                    <span class="radio-checkmark"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
