@extends('layouts.main')

@section('page-title', 'Create Sub Category')

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ route('sub-category.index') }}" class="btn btn-secondary btn-sm float-end">Back to List</a>
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

            <form action="{{ route('sub-category.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="row">
                    <div class="mb-3 col-md-3 form-group">
                        <label for="category_id" class="form-label">Category</label>
                        <select class="form-select" name="category_id" id="category_id" required>
                            @foreach ($categories ?? [] as $category)
                                <option value="{{ $category->id }}">{{ $category->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-md-3 form-group">
                        <label for="mtitle" class="form-label">Name</label>
                        <input type="text" name="mtitle" class="form-control" placeholder="Enter Name" required>
                    </div>
                    <div class="mb-3 col-md-3 form-group">
                        <label for="is_parent" class="form-label">Want to choose a parent category?</label>
                        <select name="is_parent" id="is_parent" class="form-select">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                    <div class="mb-3 col-md-3 form-group" id="parent_category_box" style="display:none;">
                        <label for="parent_category" class="form-label">Select Parent Category</label>
                        <select name="parent_category" id="parent_category" class="form-select">
                            <option value="">Select parent</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-md-3 form-group">
                        <label for="event_date" class="form-label">Event Date</label>
                        <input type="date" name="event_date" class="form-control" placeholder="Enter Event Date">
                    </div>
                    <div class="mb-3 col-md-3 form-group">
                        <label for="label" class="form-label">Label</label>
                        <input type="text" name="label" id="label" class="form-control"
                            placeholder="Enter Label">
                    </div>
                    <div class="mb-3 col-md-3 form-group">
                        <label for="label_bg" class="form-label">Label Background Color</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fa fa-paint-brush"></i>
                            </span>
                            <input type="text" name="label_bg" id="label_bg" placeholder="Enter Label BG"
                                class="form-control" value="">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-md-4 form-group">
                        <label for="image" class="form-label">Category Thumbnail</label>
                        <div class="file-input-wrapper">
                            <input type="file" name="image" id="image" class="file-input" accept="image/*"
                                onchange="previewImage(this, 'image-preview')">
                            <label for="image" class="file-input-label">
                                <img id="image-preview" class="file-preview" alt="Image preview">
                                <i class="fas fa-cloud-upload-alt file-input-image"></i>
                                <span class="file-input-text">Choose image file or drag and drop</span>
                            </label>
                        </div>
                    </div>
                    <div class="mb-3 col-md-4 form-group">
                        <label for="noti_banner" class="form-label">Notification Banner</label>
                        <div class="file-input-wrapper">
                            <input type="file" name="noti_banner" id="noti_banner" class="file-input"
                                accept="image/*" onchange="previewImage(this, 'noti-banner-preview')">
                            <label for="noti_banner" class="file-input-label">
                                <img id="noti-banner-preview" class="file-preview" alt="Image preview">
                                <i class="fas fa-cloud-upload-alt file-input-noti-banner-preview"></i>
                                <span class="file-input-text">Choose noti banner file or drag and drop</span>
                            </label>
                        </div>
                    </div>
                    <div class="mb-3 col-md-4 form-group">
                        <label for="noti_quote" class="form-label">Notification Quote</label>
                        <textarea rows="4" name="noti_quote" id="noti_quote" placeholder="Enter Notification Quote"
                            class="form-control"></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-md-3 form-group">
                        <label class="form-label" for="status">Status</label></br>
                        <label class="custom-switch">
                            <input type="checkbox" name="status" value="1" checked>
                            <span class="switch-slider"></span>
                        </label>
                    </div>
                    <div class="mb-3 col-md-3 form-group">
                        <label class="form-label" for="plan_auto">Plan / Auto</label>
                        <div class="radio-group">
                            <label class="radio-container">Only Plan
                                <input type="radio" name="plan_auto" value="1">
                                <span class="radio-checkmark"></span>
                            </label>

                            <label class="radio-container">Both (Plan/Auto)
                                <input type="radio" name="plan_auto" value="0" checked>
                                <span class="radio-checkmark"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>
            <div class="mt-5">
                <hr>
                <h5 class="mb-3">Bulk Upload</h5>
                <form class="form-horizontal" id="import_form" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="bulk_file" class="form-label">Upload Excel File</label>
                                <input type="file" name="file" class="form-control" id="bulk_file"
                                    accept=".xls,.xlsx" required>
                                <div class="form-text">Upload Excel file with sub categories data</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Sample Template</label>
                                <div>
                                    <a href="#" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-download"></i> Download Sample Template
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-start">
                        <button type="submit" name="import" class="btn btn-success">
                            <i class="fas fa-upload"></i> Upload File
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.getElementById('is_parent').addEventListener('change', function() {
            let box = document.getElementById('parent_category_box');
            if (this.value == 1) {
                box.style.display = 'block';

                // When parent is selected, load subcategories based on category
                let categoryId = document.getElementById('category_id').value;

                fetch(`{{ url('/category') }}/${categoryId}/subcategories`)
                    .then(response => response.json())
                    .then(data => {
                        let select = document.getElementById('parent_category');
                        select.innerHTML = '<option value="">Select Parent</option>';

                        data.forEach(function(sub) {
                            select.innerHTML += `<option value="${sub.id}">${sub.mtitle}</option>`;
                        });
                    });

            } else {
                box.style.display = 'none';
                document.getElementById('parent_category').innerHTML = "";
            }
        });

        // If category changes, reload subcategories (when Is Parent = Yes)
        document.getElementById('category_id').addEventListener('change', function() {
            if (document.getElementById('is_parent').value == 1) {
                let categoryId = this.value;
                fetch(`{{ url('/category') }}/${categoryId}/subcategories`)
                    .then(response => response.json())
                    .then(data => {
                        let select = document.getElementById('parent_category');
                        select.innerHTML = '<option value="">Select Parent</option>';

                        data.forEach(function(sub) {
                            select.innerHTML += `<option value="${sub.id}">${sub.mtitle}</option>`;
                        });
                    });
            }
        });
    </script>
    <script>
        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };

                reader.readAsDataURL(input.files[0]);
            } else {
                preview.style.display = 'none';
            }
        }
    </script>
@endpush
