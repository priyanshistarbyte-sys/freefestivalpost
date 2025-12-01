<form action="{{  route('photo-status.store') }}" enctype="multipart/form-data" method="POST">
    @csrf
     <div class="modal-body">
        <div class="row">
            <div class="mb-3 col-md-12 form-group">
                <label for="title" class="form-label">Title</label>
                <input type="text" name="title" id="title" class="form-control" placeholder="Enter Title" required>
            </div>
            <div class="mb-3 col-md-12 form-group">
                <label for="image" class="form-label">Image</label>
                <div class="file-input-wrapper">
                    <input type="file" name="image" id="image" class="file-input" accept="image/*" onchange="previewImage(this, 'image-preview')">
                    <label for="image" class="file-input-label">
                        <img id="image-preview" class="file-preview" alt="Image preview">
                        <i class="fas fa-cloud-upload-alt file-input-icon"></i>
                        <span class="file-input-text">Choose image file or drag and drop</span>
                    </label>
                </div>
            </div>
            <div class="mb-3 col-md-12 form-group">
                <label for="lable" class="form-label">Lable</label> 
                <input type="text" name="lable" id="lable" class="form-control" placeholder="Enter Lable">
            </div>
            <div class="mb-3 col-md-3 form-group">
                <label for="lablebg" class="form-label">Lable BG</label> <br>
                <input type="color" name="lablebg" id="lablebg" class="form-control" placeholder="Enter Lable">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        <button class="btn btn-primary" type="submit">{{__('Submit')}}</button>
    </div>
</form>