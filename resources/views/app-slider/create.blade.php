@extends('layouts.main')

@section('page-title', '')

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ route('app-slider.index') }}" class="btn btn-secondary btn-sm float-end">Back to List</a>
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
             <form action="{{ route('app-slider.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="row">
                     <div class="col-md-6 mb-3">
                        <label for="title" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="title" name="title"
                            placeholder="Enter Category Name">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="mid" class="form-label">MID</label>
                        <input type="text" class="form-control" id="mid" name="mid"
                            placeholder="Enter MID">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="festivalDate" class="form-label">Festival Date</label>
                        <input type="date" class="form-control" id="festivalDate" name="festivalDate"
                            placeholder="Enter Festival Date">
                    </div>
                    <div class="col-md-6 mb-3">
                          <label for="sort" class="form-label">Sequence</label>
                          <input type="number" class="form-control" id="sort" name="sort"
                            placeholder="Enter Sequence">
                     </div>
                     <div class="col-md-6 mb-3">
                         <label for="sub" class="form-label">Sub (""-Not click, 0-All image list, 1-Sub category list, 2-Plan Details page, 3-Other Url redirect)</label>
                          <input type="text" class="form-control" id="sub" name="sub" placeholder="Enter Sub" value="0">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="url" class="form-label">Url (sub = 3 hoy to url ma link apvi)</label>
                        <input type="text" class="form-control" id="url" name="url" placeholder="Enter Url">
                    </div>
                    <div class="col-md-6 mb-3">
                         <label for="image" class="form-label">Images</label>
                         <input type="file" class="form-control" id="image" name="image">
                    </div>
                    <div class="col-md-6 mb-3">
                          <label class="form-label" for="status">Status</label></br>
                          <label class="custom-switch">
                            <input type="checkbox" name="status" value="1" checked>
                            <span class="switch-slider"></span>
                         </label>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </div>
             </form>
        </div>
    </div>
@endsection