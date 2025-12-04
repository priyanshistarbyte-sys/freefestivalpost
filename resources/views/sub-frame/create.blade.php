<form action="{{  route('sub-frame.store') }}" enctype="multipart/form-data" method="POST">
    @csrf
     <div class="modal-body">
        <div class="row">
             <div class="mb-3">
                <div class="form-group">
                    <label for="frame_id" class="form-label">Select Frame</label>
                    <select class="form-select" name="frame_id" id="frame_id" required>
                      @foreach ($frames ?? [] as $frame)
                            <option value="{{ $frame->id }}">{{ $frame->frame_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mb-3 col-md-12 form-group">
                <label for="image" class="form-label">Frame Image</label>
                <div class="file-input-wrapper">
                    <input type="file" name="image" id="image" class="file-input" accept="image/*" onchange="previewImage(this, 'image-preview')">
                    <label for="image" class="file-input-label">
                        <img id="image-preview" class="file-preview" alt="image preview">
                        <i class="fas fa-cloud-upload-alt file-input-icon"></i>
                        <span class="file-input-text">Choose Image file or drag and drop</span>
                    </label>
                </div>
            </div>
            <div class="mb-3 col-md-4 form-group">
                <label class="form-label" for="status">Status</label></br>
                <label class="custom-switch">
                    <input type="checkbox" name="status" value="1" checked>
                    <span class="switch-slider"></span>
                </label>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        <button class="btn btn-primary" type="submit">{{__('Submit')}}</button>
    </div>
</form>