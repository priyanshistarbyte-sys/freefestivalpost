@extends('layouts.main')

@section('page-title', '')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0">Application Page</h4>
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
            <form action="{{ route('application.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="row">
                    <div class="mb-3 col-md-4 form-group">
                        <label for="app_name" class="form-label">Application Name<span class="text-danger">*</span></label>
                        <input type="text" name="app_name" class="form-control" placeholder="Enter Application Name"
                            required>
                    </div>
                    <div class="mb-3 col-md-4 form-group">
                        <label for="app_package_name" class="form-label">Application Package Name<span class="text-danger">*</span></label>
                        <input type="text" name="app_package_name" class="form-control"
                            placeholder="Enter Application Package Name" required>
                    </div>
                    <div class="mb-3 col-md-4 form-group">
                        <label for="adclick" class="form-label">OnClick Count Ads Display</label>
                        <input type="text" name="adclick" class="form-control"
                            placeholder="Enter OnClick Count Ads Display" maxlength="1" minlength="1" value="3">
                    </div>
                    <div class="mb-3 col-md-3 form-group">
                        <label class="form-label" for="mode">Ads Mode (0-Test, 1- Live)<span
                                class="text-danger">*</span></label>
                        <div class="radio-group">
                            <label class="radio-container">Test
                                <input type="radio" name="mode" value="0" checked>
                                <span class="radio-checkmark"></span>
                            </label>
                            <label class="radio-container">Live
                                <input type="radio" name="mode" value="1">
                                <span class="radio-checkmark"></span>
                            </label>
                        </div>
                    </div>
                    <div class="mb-3 col-md-3 form-group">
                        <label for="status" class="form-label">Ads Platform</label>
                        <select name="status" id="status" class="form-select">
                            <option value="0">Off</option>
                            <option value="1">Google</option>
                            <option value="2">Facebook</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 text-center">
                        <br />
                        <h3 class="heading-bg"><b>Dialog Data</b></h3>
                        <br />
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3 form-group">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" id="title" name="title" class="form-control"
                                placeholder="Enter Dialog Title">
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-6 form-group">
                                <label for="button1" class="form-label">Button 1</label>
                                <input type="text" id="button1" name="button1" class="form-control"
                                    placeholder="Enter Button 1 Title">
                            </div>
                            <div class="mb-3 col-md-6 form-group">
                                <label for="button2" class="form-label">Button 2</label>
                                <input type="text" id="button2" name="button2" class="form-control"
                                    placeholder="Enter Button 2 Title">
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-6 form-group">
                                <label for="link" class="form-label">Link URL</label>
                                <input type="text" id="link" name="link" class="form-control"
                                    placeholder="Enter Link">
                            </div>
                            <div class="mb-3 col-md-6 form-group">
                                <label for="appversion" class="form-label">App Version</label>
                                <input type="text" id="appversion" name="appversion" class="form-control"
                                    placeholder="Enter App Version">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3 form-group">
                            <label for="description" class="form-label">Description</label>
                            <textarea rows="5" name="description" id="description" placeholder="Enter Description"
                                class="form-control"></textarea>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-6 form-group">
                                <label class="form-label" for="isDisplay">Update - Is Display (0 - Off, 1 - On)</label>
                                <div class="radio-group">
                                    <label class="radio-container">Off
                                        <input type="radio" name="isDisplay" value="0" checked>
                                        <span class="radio-checkmark"></span>
                                    </label>
                                    <label class="radio-container">On
                                        <input type="radio" name="isDisplay" value="1">
                                        <span class="radio-checkmark"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="mb-3 col-md-6 form-group">
                                <label class="form-label" for="forcefully">Update - Forcefully Update (0-Off, 1-
                                    On)</label>
                                <div class="radio-group">
                                    <label class="radio-container">Off
                                        <input type="radio" name="forcefully" value="0" checked>
                                        <span class="radio-checkmark"></span>
                                    </label>
                                    <label class="radio-container">On
                                        <input type="radio" name="forcefully" value="1">
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
                                <option value="view">View</option>
                                <option value="click">Click</option>
                                <option value="today">Today</option>
                                <option value="plan">Plan</option>
                                <option value="upcoming">Upcoming</option>
                            </select>
                        </div>
                        <div class="mb-3 col-md-4 form-group">
                            <label for="o_link" class="form-label">Offer Link URL</label>
                            <input type="text" id="o_link" name="o_link" class="form-control"
                                placeholder="Enter Offer Banner Link">
                        </div>

                        <div class="mb-3 col-md-4 form-group">
                            <label for="image" class="form-label">Images</label>
                            <input type="file" class="form-control" id="image" name="image">
                        </div>
                        <div class="mb-3 col-md-4 form-group">
                            <label class="form-label" for="other_isDisplay">Offer - Is Display (0 - Off, 1 - On)</label>
                            <div class="radio-group">
                                <label class="radio-container">Off
                                    <input type="radio" name="other_isDisplay" value="0" checked>
                                    <span class="radio-checkmark"></span>
                                </label>
                                <label class="radio-container">On
                                    <input type="radio" name="other_isDisplay" value="1">
                                    <span class="radio-checkmark"></span>
                                </label>
                            </div>
                        </div>
                        <div class="mb-3 col-md-4 form-group">
                            <label class="form-label" for="other_forcefully">Offer - Forcefully Display (0 - Off, 1 - On)</label>
                            <div class="radio-group">
                                <label class="radio-container">Off
                                    <input type="radio" name="other_forcefully" value="0" checked>
                                    <span class="radio-checkmark"></span>
                                </label>
                                <label class="radio-container">On
                                    <input type="radio" name="other_forcefully" value="1">
                                    <span class="radio-checkmark"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <button type="submit" class="btn btn-primary btn-lg">Create</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
