<form action="{{ route('videogif.update', $Videogif->id) }}" enctype="multipart/form-data" method="POST">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="row">
            <div class="mb-3 col-md-12 form-group">
                <label for="sub_category_id" class="form-label">Sub Category</label>
                <select class="form-select" name="sub_category_id" id="sub_category_id" required>
                    <option value="">Select Category</option>
                    @foreach ($subcategories ?? [] as $subcategory)
                        <option value="{{ $subcategory->id }}" {{ $Videogif->sub_category_id == $subcategory->id ? 'selected' : '' }}>{{ $subcategory->mtitle }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3 col-md-6 form-group">
                <label for="free_paid" class="form-label">Paid / Free (0-Free, 1-Paid)</label>
                <div class="radio-group">
                    <label class="radio-container">Free
                        <input type="radio" name="free_paid" value="0" {{ $Videogif->free_paid == 0 ? 'checked' : '' }}>
                        <span class="radio-checkmark"></span>
                    </label>
                    <label class="radio-container">Paid
                        <input type="radio" name="free_paid" value="1" {{ $Videogif->free_paid == 1 ? 'checked' : '' }}>
                        <span class="radio-checkmark"></span>
                    </label>
                </div>
            </div>
            <div class="mb-3 col-md-6 form-group">
                <label for="type" class="form-label">Tamplate Type (0-Gif, 1-Video)</label>
                <div class="radio-group">
                    <label class="radio-container">GIF
                        <input type="radio" name="type" value="0" {{ $Videogif->type == 0 ? 'checked' : '' }}>
                        <span class="radio-checkmark"></span>
                    </label>
                    <label class="radio-container">Video
                        <input type="radio" name="type" value="1" {{ $Videogif->type == 1 ? 'checked' : '' }}>
                        <span class="radio-checkmark"></span>
                    </label>
                </div>
            </div>
            <div class="mb-3 col-md-6 form-group">
                <label for="video" class="form-label">Video File</label>
                @if($Videogif->path)
                    <div class="mb-2">
                        <video width="200" height="150" controls>
                            <source src="http://localhost/freefestivalpost/storage/app/public/{{ $Videogif->path }}" type="video/mp4">
                        </video>
                    </div>
                @endif
                <input type="file" name="video" id="video" class="form-control" accept="video/*">
            </div>
            <div class="mb-3 col-md-6 form-group">
                <label for="lablebg" class="form-label">Status (0-off, 1-on)</label>
                <div class="radio-group">
                    <label class="radio-container">Off
                        <input type="radio" name="status" value="0" {{ $Videogif->status == 0 ? 'checked' : '' }}>
                        <span class="radio-checkmark"></span>
                    </label>
                    <label class="radio-container">On
                        <input type="radio" name="status" value="1" {{ $Videogif->status == 1 ? 'checked' : '' }}>
                        <span class="radio-checkmark"></span>
                    </label>
                </div>
            </div>
            <div class="mb-3 col-md-6 form-group">
                <label for="lable" class="form-label">Lable :</label>
                <input type="text" placeholder="Enter Lable" class="form-control" name="lable" value="{{ $Videogif->lable }}" required>
            </div>
            <div class="mb-3 col-md-6 form-group">
                <label for="lablebg" class="form-label">Lable BG :</label>
                <input type="text" placeholder="Enter Lable BG" class="form-control" name="lablebg" value="{{ $Videogif->lablebg }}" required>
            </div>
        </div>  
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        <button class="btn btn-primary" type="submit">{{ __('Update') }}</button>
    </div>
</form>