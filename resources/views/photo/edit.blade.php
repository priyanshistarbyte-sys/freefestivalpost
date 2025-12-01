<form method="POST" action="{{ route('photo.update', $photo->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="row">
            <div class="mb-3 col-md-12 form-group">
                <label for="photo_status_id" class="form-label">Photo Status</label>
                    <select class="form-select" name="photo_status_id" id="photo_status_id" required>
                        @foreach ($photo_status ?? [] as $status)
                            <option value="{{ $status->id }}" {{ $photo->photo_status_id == $status->id ? 'selected' : '' }}>{{ $status->title }}</option>
                        @endforeach
                    </select>
            </div>
            <div class="mb-3 col-md-12 form-group">
                <label for="photo" class="form-label">Photo</label>
                <div class="file-input-wrapper">
                    <input type="file" name="photo" id="photo" class="file-input" accept="image/*"
                        onchange="previewImage(this, 'photo-preview')">
                    <label for="photo" class="file-input-label {{ $photo->photo ? 'has-file' : '' }}">
                        <img id="photo-preview" class="file-preview"
                            src="{{ $photo->photo ? asset('storage/' . $photo->photo) : '' }}"
                            alt="Photo preview"
                            style="{{ $photo->photo ? 'display: block;' : 'display: none;' }}">
                        <i class="fas fa-cloud-upload-alt file-input-icon"></i>
                        <span class="file-input-text">Choose photo file or drag and drop</span>
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        <button class="btn btn-primary" type="submit">{{ __('Update') }}</button>
    </div>
</form>
