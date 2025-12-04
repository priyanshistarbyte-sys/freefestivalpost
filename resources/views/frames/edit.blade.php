<form action="{{ route('frame.update', $frame->id) }}" enctype="multipart/form-data" method="POST">
    @csrf
    @method('PUT')

    <div class="modal-body">
        <div class="row">
            {{-- Frame Name --}}
            <div class="mb-3">
                <div class="form-group">
                    <label for="frame_name" class="form-label">Frame Name</label>
                    <input type="text" name="frame_name" id="frame_name" class="form-control"
                        value="{{ old('frame_name', $frame->frame_name) }}" required>
                </div>
            </div>
            {{-- Frame Code --}}
            <div class="mb-3">
                <div class="form-group">
                    <label for="data" class="form-label">Frame Code</label>
                    <textarea rows="8" name="data" id="data" class="form-control" placeholder="Enter Frame Source Code">{{ old('data', $frame->data) }}</textarea>
                </div>
            </div>
            {{-- Logo Code --}}
            <div class="mb-3">
                <div class="form-group">
                    <label for="logosection" class="form-label">Logo Code</label>
                    <textarea rows="8" name="logosection" id="logosection" class="form-control" placeholder="Enter Logo Code">{{ old('logosection', $frame->logosection) }}</textarea>
                </div>
            </div>
            {{-- Status --}}
            <div class="mb-3 col-md-4 form-group">
                <label class="form-label" for="status">Status</label><br>
                <label class="custom-switch">
                    <input type="checkbox" name="status" value="1"
                        {{ old('status', $frame->status) == 1 ? 'checked' : '' }}>
                    <span class="switch-slider"></span>
                </label>
            </div>
            {{-- Free / Paid --}}
            <div class="mb-3 col-md-4 form-group">
                <label class="form-label" for="free_paid">Free / Paid</label><br>
                <label class="custom-switch">
                    <input type="checkbox" name="free_paid" value="1"
                        {{ old('free_paid', $frame->free_paid) == 1 ? 'checked' : '' }}>
                    <span class="switch-slider"></span>
                </label>
            </div>
            {{-- Image Upload --}}
            <div class="mb-3 col-md-12 form-group">
                <label for="image" class="form-label">Frame Image</label>

                 <div class="file-input-wrapper">
                    <input type="file" name="image" id="image" class="file-input" accept="image/*"
                        onchange="previewImage(this, 'image-preview')">

                    <label for="image" class="file-input-label">
                        <img id="image-preview" class="file-preview"
                            src="{{ $frame->image ? asset('storage/' . $frame->image) : '' }}"  style="display:block;">
                        <i class="fas fa-cloud-upload-alt file-input-image"></i>
                        <span class="file-input-text">Choose image file or drag and drop</span>
                    </label>
                </div>

             
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary" type="submit">Update</button>
    </div>
</form>
