<form action="{{ route('sub-frame.update', $subFrame->id) }}" enctype="multipart/form-data" method="POST">
    @csrf
    @method('PUT')

    <div class="modal-body">
        <div class="row">
            <div class="mb-3">
                <div class="form-group">
                    <label for="frame_id" class="form-label">Select Frame</label>
                    <select class="form-select" name="frame_id" id="frame_id" required>
                        @foreach ($frames ?? [] as $frame)
                            <option value="{{ $frame->id }}" {{ $subFrame->frame_id == $frame->id ? 'selected' : '' }}>{{ $frame->frame_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            {{-- Image Upload --}}
            <div class="mb-3 col-md-12 form-group">
                <label for="image" class="form-label">Frame Image</label>
                 <div class="file-input-wrapper">
                    <input type="file" name="image" id="image" class="file-input" accept="image/*"
                        onchange="previewImage(this, 'image-preview')">

                    <label for="image" class="file-input-label">
                        <img id="image-preview" class="file-preview"
                            src="{{ $subFrame->image ? asset('storage/' . $subFrame->image) : '' }}"  style="display:block;">
                        <i class="fas fa-cloud-upload-alt file-input-image"></i>
                        <span class="file-input-text">Choose image file or drag and drop</span>
                    </label>
                </div>
            </div>
            {{-- Status --}}
            <div class="mb-3 col-md-4 form-group">
                <label class="form-label" for="status">Status</label><br>
                <label class="custom-switch">
                    <input type="checkbox" name="status" value="1"
                        {{ old('status', $subFrame->status) == 1 ? 'checked' : '' }}>
                    <span class="switch-slider"></span>
                </label>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary" type="submit">Update</button>
    </div>
</form>
