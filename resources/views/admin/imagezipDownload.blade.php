@extends('layouts.main')
@section('title', '')
@section('content')
     <div class="card">
        <div class="card-body">
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
             </div>
        </div>
     </div>
@endsection