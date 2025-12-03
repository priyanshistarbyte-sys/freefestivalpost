<form action="{{ route('update.customframe', [$user->id, $frame->id]) }}" enctype="multipart/form-data" method="POST">
    @csrf
    @method('PUT')
     <div class="modal-body">
        <div class="row">
             <div class="mb-3 form-group">
                <label for="image" class="form-label">Image</label>
                <div class="file-input-wrapper">
                    <input type="file" name="image" id="image" class="file-input" accept="image/*" onchange="previewImage(this, 'image-preview')">
                    <label for="image" class="file-input-label">
                        <img id="image-preview" class="file-preview" src="{{ $frame->image ? asset('storage/' . $frame->image) : '' }}" alt="Image preview" style="{{ $frame->image ? 'display: block;' : '' }}">
                        <i class="fas fa-cloud-upload-alt file-input-icon"></i>
                        <span class="file-input-text">Choose image file or drag and drop</span>
                    </label>
                </div>
            </div>
            <div class="mb-3">
                <div class="form-group">
                    <label for="frame_name" class="form-label">Frame Name</label>
                    <input type="text" name="frame_name" id="frame_name" class="form-control" placeholder="Enter Frame Name" value="{{ $frame->frame_name }}" required>
                </div>
            </div>
         
            <div class="mb-3 col-md-4 form-group">
                <label class="form-label" for="status">Status</label></br>
                <label class="custom-switch">
                    <input type="checkbox" name="status" value="1" {{ $frame->status == 1 ? 'checked' : '' }}>
                    <span class="switch-slider"></span>
                </label>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        <button class="btn btn-primary" type="submit">{{__('Update')}}</button>
    </div>
</form>