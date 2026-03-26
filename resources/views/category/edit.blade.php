<form method="POST" action="{{ route('category.update', $category->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
     <div class="modal-body">
        <div class="row">
            <div class="mb-3">
                <div class="form-group">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" value="{{ $category->title }}" name="title" id="title" class="form-control" placeholder="Enter Title" required>
                </div>
            </div>
             <div class="mb-3">
                <div class="form-group">
                    <label for="icon" class="form-label">Icon</label>
                    <div class="file-input-wrapper">
                        <input type="file" name="icon" id="icon" class="file-input" accept="image/*" onchange="previewImage(this, 'icon-preview')">
                        <label for="icon" class="file-input-label {{ $category->icon ? 'has-file' : '' }}">
                            <img id="icon-preview" class="file-preview" src="{{ $category->icon ? asset('storage/' . $category->icon) : '' }}" alt="Icon preview" style="{{ $category->icon ? 'display: block;' : 'display: none;' }}">
                            <i class="fas fa-cloud-upload-alt file-input-icon"></i>
                            <span class="file-input-text">Choose icon file or drag and drop</span>
                        </label>
                    </div>
                </div>
            </div>
              <div class="mb-3">
                <div class="form-group">
                    <label for="thumb" class="form-label">Thumb</label>
                    <div class="file-input-wrapper">
                       <input type="file" name="thumb" id="thumb" class="file-input" accept="image/*" onchange="previewImage(this, 'thumb-preview')">
                        <label for="icon" class="file-input-label {{ $category->thumb ? 'has-file' : '' }}">
                            <img id="thumb-preview" class="file-preview" src="{{ $category->thumb ? asset('storage/' . $category->thumb) : '' }}" alt="Thumb preview" style="{{ $category->thumb ? 'display: block;' : 'display: none;' }}">
                            <i class="fas fa-cloud-upload-alt file-input-icon"></i>
                            <span class="file-input-text">Choose icon file or drag and drop</span>
                        </label>
                    </div>
                </div>
            </div>
             <div class="mb-3">
                <div class="form-group">
                    <label for="sort" class="form-label">Sort</label>
                    <input type="number" value="{{ $category->sort }}" name="sort" id="sort" class="form-control" min="0" placeholder="Enter Sort Order" required>
                </div>
            </div>
              <div class="mb-3 col-md-4 form-group">
                <label class="form-label" for="status">Status</label></br>
                <label class="custom-switch">
                    <input type="checkbox" name="status" value="1" {{ $category->status ? 'checked' : '' }}>
                    <span class="switch-slider"></span>
                </label>
            </div>
            <div class="mb-3 col-md-4 form-group">
                <label class="form-label" for="is_show_on_home">Show on Home</label></br>
                <label class="custom-switch">
                    <input type="checkbox" name="is_show_on_home" value="1" {{ $category->is_show_on_home ? 'checked' : '' }}>
                    <span class="switch-slider"></span>
                </label>
            </div>
             <div class="mb-3 col-md-4 form-group">
                <label class="form-label" for="is_new">New</label></br>
                <label class="custom-switch">
                    <input type="checkbox" name="is_new" value="1" {{ $category->is_new ? 'checked' : '' }}>
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