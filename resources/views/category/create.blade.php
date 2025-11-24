<form action="{{  route('category.store') }}" enctype="multipart/form-data" method="POST">
    @csrf
     <div class="modal-body">
        <div class="row">
            <div class="mb-3">
                <div class="form-group">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" name="title" id="title" class="form-control" placeholder="Enter Title" required>
                </div>
            </div>
            <div class="mb-3">
                <div class="form-group">
                    <label for="icon" class="form-label">Icon</label>
                    <div class="file-input-wrapper">
                        <input type="file" name="icon" id="icon" class="file-input" accept="image/*" onchange="previewImage(this, 'icon-preview')">
                        <label for="icon" class="file-input-label">
                            <img id="icon-preview" class="file-preview" alt="Icon preview">
                            <i class="fas fa-cloud-upload-alt file-input-icon"></i>
                            <span class="file-input-text">Choose icon file or drag and drop</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <div class="form-group">
                    <label for="sort" class="form-label">Sort</label> 
                    <input type="number" name="sort" id="sort" class="form-control" min="0"  value="0" placeholder="Enter Sort Order" required>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        <button class="btn btn-primary" type="submit">{{__('Submit')}}</button>
    </div>
  </form>