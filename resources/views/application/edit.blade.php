@extends('layouts.main')

@section('page-title', '')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0">Edit Application</h4>
            <a href="{{ route('application.index') }}" class="btn btn-secondary btn-lg ">Back to List</a>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('application.update', $application->id) }}" enctype="multipart/form-data" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="mb-3 col-md-4 form-group">
                        <label for="app_name" class="form-label">Application Name<span class="text-danger">*</span></label>
                        <input type="text" name="app_name" class="form-control" placeholder="Enter Application Name"
                            value="{{ old('app_name', $application->app_name) }}" required>
                    </div>
                    <div class="mb-3 col-md-4 form-group">
                        <label for="app_package_name" class="form-label">Application Package Name<span class="text-danger">*</span></label>
                        <input type="text" name="app_package_name" class="form-control"
                            placeholder="Enter Application Package Name" value="{{ old('app_package_name', $application->app_package_name) }}" required>
                    </div>
                    <div class="mb-3 col-md-4 form-group">
                        <label for="adclick" class="form-label">OnClick Count Ads Display</label>
                        <input type="text" name="adclick" class="form-control"
                            placeholder="Enter OnClick Count Ads Display" maxlength="1" minlength="1" value="{{ old('adclick', $application->adclick) }}">
                    </div>
                    <div class="mb-3 col-md-3 form-group">
                        <label class="form-label" for="mode">Ads Mode (0-Test, 1- Live)<span
                                class="text-danger">*</span></label>
                        <div class="radio-group">
                            <label class="radio-container">Test
                                <input type="radio" name="mode" value="0" {{ old('mode', $application->mode) == '0' ? 'checked' : '' }}>
                                <span class="radio-checkmark"></span>
                            </label>
                            <label class="radio-container">Live
                                <input type="radio" name="mode" value="1" {{ old('mode', $application->mode) == '1' ? 'checked' : '' }}>
                                <span class="radio-checkmark"></span>
                            </label>
                        </div>
                    </div>
                    <div class="mb-3 col-md-3 form-group">
                        <label for="status" class="form-label">Ads Platform</label>
                        <select name="status" id="status" class="form-select">
                            <option value="0" {{ old('status', $application->status) == '0' ? 'selected' : '' }}>Off</option>
                            <option value="1" {{ old('status', $application->status) == '1' ? 'selected' : '' }}>Google</option>
                            <option value="2" {{ old('status', $application->status) == '2' ? 'selected' : '' }}>Facebook</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 text-center">
                        <br />
                        <h3 class="heading-bg"><b>Update Dialog Data</b></h3>
                        <br />
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3 form-group">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" id="title" name="title" class="form-control"
                                placeholder="Enter Dialog Title" value="{{ old('title', $dailog->title ?? '') }}">
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-6 form-group">
                                <label for="button1" class="form-label">Button 1</label>
                                <input type="text" id="button1" name="button1" class="form-control"
                                    placeholder="Enter Button 1 Title" value="{{ old('button1', $dailog->button1 ?? '') }}">
                            </div>
                            <div class="mb-3 col-md-6 form-group">
                                <label for="button2" class="form-label">Button 2</label>
                                <input type="text" id="button2" name="button2" class="form-control"
                                    placeholder="Enter Button 2 Title" value="{{ old('button2', $dailog->button2 ?? '') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-6 form-group">
                                <label for="link" class="form-label">Link URL</label>
                                <input type="text" id="link" name="link" class="form-control"
                                    placeholder="Enter Link" value="{{ old('link', $dailog->link ?? '') }}">
                            </div>
                            <div class="mb-3 col-md-6 form-group">
                                <label for="appversion" class="form-label">App Version</label>
                                <input type="text" id="appversion" name="appversion" class="form-control"
                                    placeholder="Enter App Version" value="{{ old('appversion', $dailog->appversion ?? '') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3 form-group">
                            <label for="description" class="form-label">Description</label>
                            <textarea rows="5" name="description" id="description" placeholder="Enter Description"
                                class="form-control">{{ old('description', $dailog->description ?? '') }}</textarea>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-6 form-group">
                                <label class="form-label" for="d_isDisplay">Update - Is Display (0 - Off, 1 - On)</label>
                                <div class="radio-group">
                                    <label class="radio-container">Off
                                        <input type="radio" name="d_isDisplay" value="0" {{ old('d_isDisplay', $dailog->d_isDisplay ?? '0') == '0' ? 'checked' : '' }}>
                                        <span class="radio-checkmark"></span>
                                    </label>
                                    <label class="radio-container">On
                                        <input type="radio" name="d_isDisplay" value="1" {{ old('d_isDisplay', $dailog->d_isDisplay ?? '0') == '1' ? 'checked' : '' }}>
                                        <span class="radio-checkmark"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="mb-3 col-md-6 form-group">
                                <label class="form-label" for="forcefully">Update - Forcefully Update (0-Off, 1-
                                    On)</label>
                                <div class="radio-group">
                                    <label class="radio-container">Off
                                        <input type="radio" name="forcefully" value="0" {{ old('forcefully', $dailog->forcefully ?? '0') == '0' ? 'checked' : '' }}>
                                        <span class="radio-checkmark"></span>
                                    </label>
                                    <label class="radio-container">On
                                        <input type="radio" name="forcefully" value="1" {{ old('forcefully', $dailog->forcefully ?? '0') == '1' ? 'checked' : '' }}>
                                        <span class="radio-checkmark"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 text-center">
                        <br />
                        <h3 class="heading-bg"><b>Offer Dailog Data</b></h3>
                        <br />
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-4 form-group">
                            <label for="o_type" class="form-label">Select Banner type</label>
                            <select name="o_type" id="o_type" class="form-select">
                                <option value="view" {{ old('o_type', $dailog->o_type ?? 'view') == 'view' ? 'selected' : '' }}>View</option>
                                <option value="click" {{ old('o_type', $dailog->o_type ?? 'view') == 'click' ? 'selected' : '' }}>Click</option>
                                <option value="today" {{ old('o_type', $dailog->o_type ?? 'view') == 'today' ? 'selected' : '' }}>Today</option>
                                <option value="plan" {{ old('o_type', $dailog->o_type ?? 'view') == 'plan' ? 'selected' : '' }}>Plan</option>
                                <option value="upcoming" {{ old('o_type', $dailog->o_type ?? 'view') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                            </select>
                        </div>
                        <div class="mb-3 col-md-4 form-group">
                            <label for="o_link" class="form-label">Offer Link URL</label>
                            <input type="text" id="o_link" name="o_link" class="form-control"
                                placeholder="Enter Offer Banner Link" value="{{ old('o_link', $dailog->o_link ?? '') }}">
                        </div>

                        <div class="mb-3 col-md-4 form-group">
                            <label for="image" class="form-label">Images</label>
                            <input type="file" class="form-control" id="image" name="image">
                            <div class="mt-2">
                                <img src="{{ ($dailog && $dailog->image) ? asset('storage/' . $dailog->image) : asset('assets/images/default.jpg') }}" 
                                     alt="Current Image" 
                                     style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;">
                            </div>
                        </div>
                        <div class="mb-3 col-md-4 form-group">
                            <label class="form-label" for="d_other_isDisplay">Offer - Is Display (0 - Off, 1 - On)</label>
                            <div class="radio-group">
                                <label class="radio-container">Off
                                    <input type="radio" name="d_other_isDisplay" value="0" {{ old('d_other_isDisplay', $dailog->d_other_isDisplay ?? '0') == '0' ? 'checked' : '' }}>
                                    <span class="radio-checkmark"></span>
                                </label>
                                <label class="radio-container">On
                                    <input type="radio" name="d_other_isDisplay" value="1" {{ old('d_other_isDisplay', $dailog->d_other_isDisplay ?? '0') == '1' ? 'checked' : '' }}>
                                    <span class="radio-checkmark"></span>
                                </label>
                            </div>
                        </div>
                        <div class="mb-3 col-md-4 form-group">
                            <label class="form-label" for="other_forcefully">Offer - Forcefully Display (0 - Off, 1 - On)</label>
                            <div class="radio-group">
                                <label class="radio-container">Off
                                    <input type="radio" name="other_forcefully" value="0" {{ old('other_forcefully', $dailog->other_forcefully ?? '0') == '0' ? 'checked' : '' }}>
                                    <span class="radio-checkmark"></span>
                                </label>
                                <label class="radio-container">On
                                    <input type="radio" name="other_forcefully" value="1" {{ old('other_forcefully', $dailog->other_forcefully ?? '0') == '1' ? 'checked' : '' }}>
                                    <span class="radio-checkmark"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <button type="submit" class="btn btn-primary btn-lg">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection