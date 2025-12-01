<form action="{{  route('photo.store') }}" enctype="multipart/form-data" method="POST">
    @csrf
     <div class="modal-body">
        <div class="row">
            <div class="mb-3 col-md-12 form-group">
                <label for="title" class="form-label">Photo Status</label>
                <select class="form-select" name="photo_status_id" id="photo_status_id" required>
                        @foreach ($photo_status ?? [] as $status)
                            <option value="{{ $status->id }}">{{ $status->title }}</option>
                        @endforeach
                </select>
            </div>
            <div class="mb-3 col-md-12 form-group">
                <label for="photo" class="form-label">Photo</label>
                <div class="file-input-wrapper">
                    <input type="file" name="photo" id="photo" class="file-input" accept="image/*" onchange="previewImage(this, 'photo-preview')">
                    <label for="photo" class="file-input-label">
                        <img id="photo-preview" class="file-preview" alt="Photo preview">
                        <i class="fas fa-cloud-upload-alt file-input-icon"></i>
                        <span class="file-input-text">Choose photo file or drag and drop</span>
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        <button class="btn btn-primary" type="submit">{{__('Submit')}}</button>
    </div>
</form>