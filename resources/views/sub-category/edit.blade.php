@extends('layouts.main')

@section('page-title', 'Edit Sub Category')

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

            <form action="{{ route('sub-category.update', $subCategory->id) }}" enctype="multipart/form-data" method="POST">
                @csrf
                @method('PUT')

                <div class="row">

                    {{-- ------------- LEFT COLUMN ------------- --}}
                    <div class="col-md-4">

                        {{-- Category --}}
                        <div class="mb-3 form-group">
                            <label for="category_id" class="form-label">Category</label>
                            <select class="form-select" name="category_id" id="category_id" required>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ $subCategory->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 col-md-12 form-group">
                            <label for="is_parent" class="form-label">Want to choose a parent category?</label>
                            <select name="is_parent" id="is_parent" class="form-select">
                                <option value="0" {{ $subCategory->is_parent == 0 ? 'selected' : '' }}>No (Main)
                                </option>
                                <option value="1" {{ $subCategory->is_parent == 1 ? 'selected' : '' }}>Yes (Child)
                                </option>
                            </select>
                        </div>
                        <div class="mb-3 col-md-12 form-group"  id="parent_category_box" style="{{ $subCategory->is_parent ? '' : 'display:none;' }}">
                            <label class="form-label">Parent Category</label>
                            <select name="parent_category" id="parent_category" class="form-select">
                                <option value="">Select Parent</option>

                                @foreach ($parentSubs as $parent)
                                    <option value="{{ $parent->id }}"
                                        {{ $subCategory->parent_category == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->mtitle }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        

                        {{-- Name --}}
                        <div class="mb-3 col-md-12 form-group">
                            <label for="mtitle" class="form-label">Name</label>
                            <input type="text" name="mtitle" class="form-control" value="{{ $subCategory->mtitle }}"
                                required>
                        </div>

                        {{-- Status + Plan/Auto --}}
                        <div class="row">
                            <div class="mb-3 col-md-6 form-group">
                                <label class="form-label">Status</label><br>
                                <label class="custom-switch">
                                    <input type="checkbox" name="status" value="1"
                                        {{ $subCategory->status == 1 ? 'checked' : '' }}>
                                    <span class="switch-slider"></span>
                                </label>
                            </div>

                            <div class="mb-3 col-md-6 form-group">
                                <label class="form-label">Plan / Auto</label>
                                <div class="radio-group">

                                    <label class="radio-container">
                                        Only Plan
                                        <input type="radio" name="plan_auto" value="1"
                                            {{ $subCategory->plan_auto == 1 ? 'checked' : '' }}>
                                        <span class="radio-checkmark"></span>
                                    </label>

                                    <label class="radio-container">
                                        Both (Plan/Auto)
                                        <input type="radio" name="plan_auto" value="0"
                                            {{ $subCategory->plan_auto == 0 ? 'checked' : '' }}>
                                        <span class="radio-checkmark"></span>
                                    </label>

                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ------------- idDLE COLUMN ------------- --}}
                    <div class="col-md-4">
                        {{-- Event Date --}}
                        <div class="mb-3 form-group">
                            <label for="event_date" class="form-label">Event Date</label>
                            <input type="date" name="event_date" class="form-control"
                                value="{{ $subCategory->event_date }}">
                        </div>
                        {{-- Label --}}
                        <div class="mb-3 form-group">
                            <label for="label" class="form-label">Label</label>
                            <input type="text" name="label" id="label" class="form-control"
                                placeholder="Enter Label" value="{{ $subCategory->lable }}">
                        </div>

                        {{-- Label BG --}}
                        <div class="mb-3 form-group">
                            <label for="label_bg" class="form-label">Label Background Color</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-paint-brush"></i></span>
                                <input type="text" name="label_bg" id="label_bg" class="form-control"
                                    placeholder="Enter Label BG" value="{{ $subCategory->lablebg }}">
                            </div>
                        </div>

                        {{-- Thumbnail Preview --}}
                        <div class="mb-3 form-group">
                            <label for="image" class="form-label">Category Thumbnail</label>
                            <div class="file-input-wrapper">

                                <input type="file" name="image" id="image" class="file-input" accept="image/*"
                                    onchange="previewImage(this, 'image-preview')">

                                <label for="image" class="file-input-label">
                                    <img id="image-preview" class="file-preview"
                                        src="{{ asset('storage/' . $subCategory->image) }}" style="display:block;">
                                    <i class="fas fa-cloud-upload-alt file-input-image"></i>
                                    <span class="file-input-text">Choose image file or drag and drop</span>
                                </label>

                            </div>
                        </div>

                    </div>

                    {{-- ------------- RIGHT COLUMN ------------- --}}
                    <div class="col-md-4">

                        {{-- Notification Quote --}}
                        <div class="mb-3 form-group">
                            <label for="noti_quote" class="form-label">Notification Quote</label>
                            <textarea rows="5" name="noti_quote" class="form-control">{{ $subCategory->noti_quote }}</textarea>
                        </div>

                        {{-- Notification Banner --}}
                        <div class="mb-3 form-group">
                            <label for="noti_banner" class="form-label">Notification Banner</label>

                            <div class="file-input-wrapper">

                                <input type="file" name="noti_banner" id="noti_banner" class="file-input"
                                    accept="image/*" onchange="previewImage(this, 'noti-banner-preview')">

                                <label for="noti_banner" class="file-input-label">
                                    <img id="noti-banner-preview" class="file-preview"
                                        src="{{ $subCategory->noti_banner ? asset('storage/' . $subCategory->noti_banner) : '' }}"
                                        style="{{ $subCategory->noti_banner ? 'display:block;' : 'display:none;' }}">
                                    <i class="fas fa-cloud-upload-alt file-input-image"></i>
                                    <span class="file-input-text">Choose noti banner file or drag and drop</span>
                                </label>

                            </div>
                        </div>
                    </div>

                </div>

                {{-- Submit --}}
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>

            </form>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let subcategoryRoute = "{{ route('subcategories.Category', ['cid' => ':cid']) }}";

        function loadParentSubcategories(categoryId, selectedParent = null) {
            let url = subcategoryRoute.replace(':cid', categoryId);

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    let select = document.getElementById('parent_category');
                    select.innerHTML = '<option value="">Select Parent</option>';

                    data.forEach(sub => {
                        let selected = selectedParent == sub.id ? "selected" : "";
                        select.innerHTML += `<option value="${sub.id}" ${selected}>${sub.mtitle}</option>`;
                    });
                });
        }

        // Load when category changes
        document.getElementById('category_id').addEventListener('change', function() {
            let categoryId = this.value;

            if (document.getElementById('is_parent').value == 1) {
                loadParentSubcategories(categoryId);
            }
        });

        // Show dropdown if parent selected
        document.getElementById('is_parent').addEventListener('change', function() {
            let box = document.getElementById('parent_category_box');

            if (this.value == 1) {
                box.style.display = 'block';

                let categoryId = document.getElementById('category_id').value;
                let selectedParent = "{{ $subCategory->parent_category }}";
                loadParentSubcategories(categoryId, selectedParent);
            } else {
                box.style.display = 'none';
            }
        });

        // Auto-load on page load (edit mode)
        @if ($subCategory->is_parent == 1)
            loadParentSubcategories("{{ $subCategory->category_id }}", "{{ $subCategory->parent_category }}");
        @endif
    </script>
    <script>
        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush
