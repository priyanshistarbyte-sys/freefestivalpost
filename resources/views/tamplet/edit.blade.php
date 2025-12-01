@extends('layouts.main')

@section('page-title', 'Edit Tamplet')

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ route('tamplet.index') }}" class="btn btn-secondary btn-sm float-end">Back to List</a>
        </div>
        <div class="card-body">
            <form action="{{ route('tamplet.update', $tamplet->id) }}" enctype="multipart/form-data" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="type" class="form-label">Category</label>
                        <select class="form-select" name="type" id="type" required>
                            @foreach ($categories ?? [] as $category)
                                <option value="{{ $category->id }}" {{ $tamplet->sub_category_id == $category->id ? 'selected' : '' }}>{{ $category->mtitle }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="type" class="form-label">Event Date:</label>
                        <input type="date" name="event_date" class="form-control" value="{{ $tamplet->event_date }}">
                    </div>
                    <div class="col-md-6 mb-3 form-group">
                        <label for="font_type" class="form-label">Font</label>
                        <select class="form-select" name="font_type" id="font_type" required>
                            @foreach ($fonts ?? [] as $font)
                                <option value="{{ $font->id }}" {{ $tamplet->font_type == $font->id ? 'selected' : '' }}>{{ $font->font_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="image" class="form-label">Image</label></br>
                        <input type="file" name="image[]" id="image" multiple accept="image/*">
                        @if($tamplet->path)
                            <div class="mt-2">
                                @foreach(json_decode($tamplet->path) as $img)
                                    <img src="{{ asset('storage/' . $img) }}" width="50" height="50" class="me-2">
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="ln_post" class="form-label">Select Language</label>
                        <select class="form-select" name="ln_post" id="ln_post" required>
                            <option value="English" {{ $tamplet->language == 'English' ? 'selected' : '' }}>English</option>
                            <option value="Hindi" {{ $tamplet->language == 'Hindi' ? 'selected' : '' }}>Hindi</option>
                            <option value="Gujarati" {{ $tamplet->language == 'Gujarati' ? 'selected' : '' }}>Gujarati</option>
                            <option value="Marathi" {{ $tamplet->language == 'Marathi' ? 'selected' : '' }}>Marathi</option>
                            <option value="Telugu" {{ $tamplet->language == 'Telugu' ? 'selected' : '' }}>Telugu</option>
                            <option value="Malayalam" {{ $tamplet->language == 'Malayalam' ? 'selected' : '' }}>Malayalam</option>
                            <option value="Tamil" {{ $tamplet->language == 'Tamil' ? 'selected' : '' }}>Tamil</option>
                            <option value="Banagali" {{ $tamplet->language == 'Banagali' ? 'selected' : '' }}>Banagali</option>
                            <option value="Panjabi" {{ $tamplet->language == 'Panjabi' ? 'selected' : '' }}>Panjabi</option>
                            <option value="Odia" {{ $tamplet->language == 'Odia' ? 'selected' : '' }}>Odia</option>
                            <option value="Kannad" {{ $tamplet->language == 'Kannad' ? 'selected' : '' }}>Kannad</option>
                            <option value="URDU" {{ $tamplet->language == 'URDU' ? 'selected' : '' }}>URDU</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="font_size" class="form-label">Font Size</label>
                        <input type="number" name="font_size" class="form-control" value="{{ $tamplet->font_size }}" min="17">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="font_color" class="form-label">Font Color</label></br>
                        <input type="color" name="font_color" id="font_color" value="{{ $tamplet->font_color }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="font_size" class="form-label">Lable : (New)</label>
                        <input type="text" placeholder="Enter Label" name="label_new" class="form-control" value="{{ $tamplet->lable }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="font_size" class="form-label">Lable BG :(#000000)</label>
                        <input type="text" placeholder="Enter Lable BG" name="lablebg" class="form-control" value="{{ $tamplet->lablebg }}">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label" for="has_mask">Has Mask</label></br>
                        <label class="custom-switch">
                            <input type="checkbox" name="has_mask" id="has_mask" value="1" {{ $tamplet->planImgName ? 'checked' : '' }}>
                            <span class="switch-slider"></span>
                        </label>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="mask" class="form-label">Mask</label></br>
                        <input type="file" name="mask" id="mask" accept="image" {{ !$tamplet->planImgName ? 'disabled' : '' }}>
                        @if($tamplet->planImgName)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $tamplet->planImgName) }}" width="50" height="50">
                            </div>
                        @endif
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="free_paid" class="form-label">Free / Paid</label></br>
                        <label class="custom-switch">
                            <input type="checkbox" name="free_paid" id="free_paid" value="1" {{ $tamplet->free_paid ? 'checked' : '' }}>
                            <span class="switch-slider"></span>
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">{{ __('Update') }}</button>
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