<form action="{{  route('whatsapp-template.store') }}" enctype="multipart/form-data" method="POST">
    @csrf
     <div class="modal-body">
        <div class="row">
            <div class="mb-3 col-md-12 form-group">
                <label for="tamp_name" class="form-label">Template Title</label>
                <input type="text" name="tamp_name" id="tamp_name" class="form-control" placeholder="Enter Template Title" required>
            </div>
            <div class="mb-3 col-md-12 form-group">
                <label for="template" class="form-label">Template Name</label>
                <input type="text" name="template" id="template" class="form-control" placeholder="Enter Template" required>
            </div>
            <div class="mb-3 col-md-6 form-group">
                <label for="type" class="form-label">Type</label>
                <select class="form-select" name="type" id="type" required>
                    <option value="Utility">Utility</option>
                    <option value="Marketing">Marketing</option>
                </select>
            </div>
            <div class="mb-3 col-md-6 form-group">
                <label for="param" class="form-label">Total Parameters</label>
                <input type="text" name="param" id="param" class="form-control" placeholder="Enter Total Parameters" required>
            </div>
            <div class="mb-3 col-md-6 form-group">
                <label for="lang" class="form-label">Language</label>
                <select class="form-select" name="lang" id="lang" required>
                    <option value="en">English</option>
                    <option value="hi">Hindi</option>
                </select>
            </div>
            <div class="mb-3 col-md-6 form-group">
                <label for="sort" class="form-label">Sort</label>
                <input type="number" name="sort" id="sort" class="form-control" placeholder="Enter Sorting Number" required>
            </div>
            <div class="mb-3 col-md-12 form-group">
                <label for="media" class="form-label">Media URL</label>
                <input type="text" name="media" id="media" class="form-control" placeholder="Enter Media URL" required>
            </div>
            <div class="mb-3 col-md-6 form-group">
                <label class="form-label" for="status">Status</label></br>
                <label class="custom-switch">
                    <input type="checkbox" name="status" value="1" checked>
                    <span class="switch-slider"></span>
                </label>
            </div>
            <div class="mb-3 col-md-6 form-group">
                <label class="form-label" for="bulk_status">Bulk Camping Listing</label></br>
                <label class="custom-switch">
                    <input type="checkbox" name="bulk_status" value="0" checked>
                    <span class="switch-slider"></span>
                </label>
            </div>
            <div class="mb-3 col-md-12 form-group">
                <label for="note" class="form-label">Note</label>
                <textarea rows="4" name="note" id="note" placeholder="Enter Note" class="form-control"></textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        <button class="btn btn-primary" type="submit">{{__('Submit')}}</button>
    </div>
</form>