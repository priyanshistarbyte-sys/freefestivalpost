<form method="POST" action="{{ route('photo-status.update', $photoStatus->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="row">
            <div class="mb-3 col-md-12 form-group">
                <label for="title" class="form-label">Title</label>
                <input type="text" value="{{ $photoStatus->title }}" name="title" id="title" class="form-control"
                    placeholder="Enter Title" required>
            </div>
            <div class="mb-3 col-md-12 form-group">
                <label for="image" class="form-label">Image</label>
                <div class="file-input-wrapper">
                    <input type="file" name="image" id="image" class="file-input" accept="image/*"
                        onchange="previewImage(this, 'image-preview')">
                    <label for="image" class="file-input-label {{ $photoStatus->image ? 'has-file' : '' }}">
                        <img id="image-preview" class="file-preview"
                            src="{{ $photoStatus->image ? asset('storage/' . $photoStatus->image) : '' }}"
                            alt="Image preview"
                            style="{{ $photoStatus->image ? 'display: block;' : 'display: none;' }}">
                        <i class="fas fa-cloud-upload-alt file-input-icon"></i>
                        <span class="file-input-text">Choose image file or drag and drop</span>
                    </label>
                </div>
            </div>
            <div class="mb-3 col-md-12 form-group">
                <label for="lable" class="form-label">Lable</label> 
                <input type="text" name="lable" id="lable" class="form-control" value="{{ $photoStatus->lable }}"  placeholder="Enter Lable">
            </div>
            <div class="mb-3 col-md-3 form-group">
                <label for="lablebg" class="form-label">Lable BG</label> <br>
                <input type="color" name="lablebg" id="lablebg" class="form-control" value="{{ $photoStatus->lablebg }}"  placeholder="Enter Lable BG">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        <button class="btn btn-primary" type="submit">{{ __('Update') }}</button>
    </div>
</form>
