<form action="{{  route('home-category.store') }}" enctype="multipart/form-data" method="POST">
    @csrf
     <div class="modal-body">
        <div class="row">
            <div class="mb-3">
                <div class="form-group">
                    <label for="sub_category_id" class="form-label">Category</label>
                    <select class="form-select" name="sub_category_id" id="sub_category_id" required>
                        @foreach ($categories ?? [] as $category)
                            <option value="{{ $category->id }}">{{ $category->mtitle }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <div class="form-group">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" name="title" id="title" class="form-control" placeholder="Enter Title" required>
                </div>
            </div>
            <div class="mb-3">
                <div class="form-group">
                    <label for="sequence" class="form-label">Sequence</label>
                    <input type="number" name="sequence" id="sequence" class="form-control" placeholder="Enter Sequence" required>
                </div>
            </div>
            <div class="mb-3 col-md-4 form-group">
                <label class="form-label" for="status">Status</label></br>
                <label class="custom-switch">
                    <input type="checkbox" name="status" value="1" checked>
                    <span class="switch-slider"></span>
                </label>
            </div>
            <div class="mb-3 col-md-4 form-group">
                <label class="form-label" for="is_show_on_home">Show on Home</label></br>
                <label class="custom-switch">
                    <input type="checkbox" name="is_show_on_home" value="1" checked>
                    <span class="switch-slider"></span>
                </label>
            </div>
             <div class="mb-3 col-md-4 form-group">
                <label class="form-label" for="is_new">New</label></br>
                <label class="custom-switch">
                    <input type="checkbox" name="is_new" value="1" checked>
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