@extends('layouts.main')

@section('page-title', '')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
             <h4 class="card-title mb-0">Application Page</h4>
            <a href="{{ route('application.index') }}" class="btn btn-secondary btn-sm">Back to List</a>
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
             <form action="{{ route('application.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                 <div class="row">
                    <div class="mb-3 col-md-4 form-group">
                        <label for="app_name" class="form-label">Application Name<span class="text-danger">*</span></label>
                        <input type="text" name="app_name" class="form-control" placeholder="Enter Application Name" required>
                    </div>
                    <div class="mb-3 col-md-4 form-group">
                        <label for="app_package_name" class="form-label">Application Package Name<span class="text-danger">*</span></label>
                        <input type="text" name="app_package_name" class="form-control" placeholder="Enter Application Package Name" required>
                    </div>
                    <div class="mb-3 col-md-4 form-group">
                        <label for="adclick" class="form-label">OnClick Count Ads Display</label>
                        <input type="text" name="adclick" class="form-control" placeholder="Enter OnClick Count Ads Display" maxlength="1" minlength="1"  value="3">
                    </div>
                    <div class="mb-3 col-md-3 form-group">
                        <label class="form-label" for="mode">Ads Mode (0-Test, 1- Live)<span class="text-danger">*</span></label>
                        <div class="radio-group">
                            <label class="radio-container">Test
                                <input type="radio" name="mode" value="0" checked>
                                <span class="radio-checkmark"></span>
                            </label>
                            <label class="radio-container">Live
                                <input type="radio" name="mode" value="1">
                                <span class="radio-checkmark"></span>
                            </label>
                        </div>
                    </div>
                     <div class="mb-3 col-md-3 form-group">
                        <label for="status" class="form-label">Ads Platform</label>
                        <select name="status" id="status" class="form-select">
                            <option value="0">Off</option>
                            <option value="1">Google</option>
                            <option value="2">Facebook</option>
                        </select>
                     </div>
                 </div>

                <div class="row">
                    <div class="col-md-12 text-center">
                        <br />
                        <h3 class="heading-bg"><b>Update Dailog Data</b></h3>
                        <br />
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection
