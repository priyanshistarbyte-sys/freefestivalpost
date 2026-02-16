
<form method="POST" action="{{ route('whatsapp-media.update', $whatsappMedia->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
     <div class="modal-body">
        <div class="row">
            <div class="mb-3 col-md-12 form-group">
                <label for="title" class="form-label">Title</label>
                <input type="text" name="title" id="title" class="form-control" placeholder="Enter Title"  value="{{ $whatsappMedia->title }}" required>
            </div>
            <div class="mb-3 col-md-12 form-group">
                <label for="image" class="form-label">Image</label>
                <div class="file-input-wrapper">
                    <input type="file" name="image" id="image" class="file-input" accept="image/*"
                        onchange="previewImage(this, 'image-preview')">
                    <label for="image" class="file-input-label {{ $whatsappMedia->image ? 'has-file' : '' }}">
                        <img id="image-preview" class="file-preview"
                            src="{{ $whatsappMedia->image ? asset('storage/' . $whatsappMedia->image) : '' }}"
                            alt="Image preview"
                            style="{{ $whatsappMedia->image ? 'display: block;' : 'display: none;' }}">
                        <i class="fas fa-cloud-upload-alt file-input-icon"></i>
                        <span class="file-input-text">Choose image file or drag and drop</span>
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