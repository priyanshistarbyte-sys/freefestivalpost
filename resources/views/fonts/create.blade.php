<form action="{{  route('fonts.store') }}" enctype="multipart/form-data" method="POST">
    @csrf
     <div class="modal-body">
         <div class="row">
            <div class="mb-3 col-md-12 form-group">
                <label for="font_file" class="form-label">Font File</label>
                <input type="file" class="form-control" id="font_file" name="font_name" accept=".ttf,.otf,.woff,.woff2" onchange="updateFontName()">
            </div>
         </div>
     </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        <button class="btn btn-primary" type="submit">{{__('Submit')}}</button>
    </div>
</form>

<script>
function updateFontName() {
    const fileInput = document.getElementById('font_file');
    if (fileInput.files.length > 0) {
        const fileName = fileInput.files[0].name;
        // The file name will be automatically stored when the form is submitted
    }
}
</script>