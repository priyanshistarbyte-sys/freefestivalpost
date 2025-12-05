<form action="{{ route('fonts.update', $font->id) }}" enctype="multipart/form-data" method="POST">
    @csrf
    @method('PUT')
     <div class="modal-body">
         <div class="row">
            <div class="mb-3 col-md-12 form-group">
                <label for="font_name" class="form-label">Current Font</label>
                <input type="text" class="form-control" value="{{ $font->font_name }}" readonly>
            </div>
            <div class="mb-3 col-md-12 form-group">
                <label for="font_file" class="form-label">New Font File (Optional)</label>
                <input type="file" class="form-control" id="font_file" name="font_name" accept=".ttf,.otf,.woff,.woff2">
                <small class="text-muted">Leave empty to keep current font</small>
            </div>
         </div>
     </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        <button class="btn btn-primary" type="submit">{{__('Update')}}</button>
    </div>
</form>