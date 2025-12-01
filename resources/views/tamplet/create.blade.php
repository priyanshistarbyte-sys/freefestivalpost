@extends('layouts.main')

@section('page-title', 'Create Tamplet')

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ route('tamplet.index') }}" class="btn btn-secondary btn-sm float-end">Back to List</a>
        </div>
        <div class="card-body">
            <form action="{{ route('tamplet.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="sub_category_id" class="form-label">Category</label>
                        <select class="form-select" name="sub_category_id" id="sub_category_id" required>
                            @foreach ($categories ?? [] as $category)
                                <option value="{{ $category->id }}">{{ $category->mtitle }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="type" class="form-label">Event Date:</label>
                        <input type="date" name="event_date" class="form-control" placeholder="Enter Event Date">
                    </div>
                    <div class="col-md-6 mb-3 form-group">
                        <label for="font_type" class="form-label">Font</label>
                        <select class="form-select" name="font_type" id="font_type" required>
                            @foreach ($fonts ?? [] as $font)
                                <option value="{{ $font->id }}">{{ $font->font_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="image" class="form-label">Image</label></br>
                        <input type="file" name="image[]" id="image" multiple accept="image/*">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="language" class="form-label">Select Language</label>
                        <select class="form-select" name="language" id="language" required>
                            <option value="English">English</option>
                            <option value="Hindi">Hindi</option>
                            <option value="Gujarati">Gujarati</option>
                            <option value="Marathi">Marathi</option>
                            <option value="Telugu">Telugu</option>
                            <option value="Malayalam">Malayalam</option>
                            <option value="Tamil">Tamil</option>
                            <option value="Banagali">Banagali</option>
                            <option value="Panjabi">Panjabi</option>
                            <option value="Odia">Odia</option>
                            <option value="Kannad">Kannad</option>
                            <option value="URDU">URDU</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="font_size" class="form-label">Font Size</label>
                        <input type="number" name="font_size" class="form-control" placeholder="Enter Font Size"
                            value="17" min="17">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="font_color" class="form-label">Font Color</label></br>
                        <input type="color" name="font_color" id="font_color">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="font_size" class="form-label">Lable : (New)</label>
                        <input type="text" placeholder="Enter Label" name="label" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="lable_bg" class="form-label">Lable BG :(#000000)</label>
                        <input type="text" placeholder="Enter Lable BG" name="lable_bg" class="form-control"
                            value="#000000">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label" for="has_mask">Has Mask</label></br>
                        <label class="custom-switch">
                            <input type="checkbox" name="has_mask" id="has_mask" value="1">
                            <span class="switch-slider"></span>
                        </label>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="mask" class="form-label">Mask</label></br>
                        <input type="file" name="mask" id="mask" accept="image" disabled>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="free_paid" class="form-label">Free / Paid</label></br>
                        <label class="custom-switch">
                            <input type="checkbox" name="free_paid" id="free_paid" value="1">
                            <span class="switch-slider"></span>
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">{{ __('Submit') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const hasMaskCheckbox = document.getElementById('has_mask');
    const maskInput = document.getElementById('mask');

    hasMaskCheckbox.addEventListener('change', function() {
        if (this.checked) {
            maskInput.removeAttribute('disabled');
        } else {
            maskInput.setAttribute('disabled', 'disabled');
        }
    });
});
</script>
@endpush
