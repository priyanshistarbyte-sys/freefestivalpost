@extends('layouts.main')
@section('title', 'Settings')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0">Site Setting</h4>
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
            <form action="{{ route('settings.update') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="row">
                    <div class="mb-3 col-md-6 form-group">
                        <label for="sitename" class="form-label">Site Name<span class="text-danger">*</span></label>
                        <input type="text" name="sitename" class="form-control" placeholder="Enter Application Name"
                            value="{{ $settings['sitename'] }}">
                    </div>
                    <div class="mb-3 col-md-6 form-group">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" placeholder="Enter Title"
                            value="{{ $settings['title'] }}">
                    </div>

                    <div class="col-md-6">
                        <div class="row">
                            <div class="mb-3 col-md-12 form-group">
                                <label for="site_email" class="form-label">Email</label>
                                <input type="email" name="site_email" class="form-control" placeholder="example@gmail.com"
                                    value="{{ $settings['site_email'] }}">
                            </div>
                            <div class="mb-3 col-md-12 form-group">
                                <label for="site_logo" class="form-label">Site Logo</label>
                                <input type="file" class="form-control" id="site_logo" name="site_logo">
                                <div class="mt-2">
                                    <img src="{{ $settings['site_logo'] ? asset('storage/' . $settings['site_logo']) : asset('assets/images/default.jpg') }}"
                                        alt="Current Image"
                                        style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3 col-md-12 form-group">
                            <label for="address" class="form-label">Address</label>
                            <textarea rows="4" name="address" id="address" placeholder="Enter Address" class="form-control">{{ $settings['address'] }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <br />
                        <h3 class="heading-bg"><b>Application Settings</b></h3>
                        <br />
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3 form-group">
                            <label for="support_call" class="form-label">Call Number</label>
                            <input type="text" name="support_call" class="form-control" placeholder="+91 8888888888"
                                value="{{ $settings['support_call'] }}">
                        </div>
                        <div class="mb-3 form-group">
                            <label for="whatsappNumber" class="form-label">WhatsApp Number</label>
                            <input type="text" name="whatsappNumber" class="form-control" placeholder="+91 8888888888"
                                value="{{ $settings['whatsappNumber'] }}">
                        </div>
                        <div class="mb-3 form-group">
                            <label for="sharingLink" class="form-label">Sharing Link</label>
                            <input type="text" name="sharingLink" class="form-control" placeholder="Enter payment key 2"
                                value="{{ $settings['sharingLink'] }}">
                        </div>
                        <div class="mb-3 form-group">
                            <label for="sharingBanner" class="form-label">Sharing Banner</label>
                            <input type="file" class="form-control" id="sharingBanner" name="sharingBanner">
                            <div class="mt-2">
                                <img src="{{ $settings['sharingBanner'] ? asset('storage/' . $settings['sharingBanner']) : asset('assets/images/default.jpg') }}"
                                    alt="Current Image"
                                    style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;">
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-6 form-group">
                                <label for="totalpost" class="form-label">Total User Post</label>
                                <h3 style="margin-top: 0px;">{{ $settings['totalpost'] }}</h3>
                            </div>
                            <div class="mb-3 col-md-6 form-group">
                                <label class="form-label">App Active / Deactive</label><br>
                                <label class="custom-switch">
                                    <input type="hidden" name="active" value="0">
                                    <input type="checkbox" name="active" value="1"
                                        {{ $settings['active'] == 1 ? 'checked' : '' }}>
                                    <span class="switch-slider"></span>
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-4 form-group">
                                <label for="festival_name" class="form-label">Whatsapp Festival Name</label>
                                <input type="text" name="festival_name" class="form-control"
                                    placeholder="Enter Festival Name" value="{{ $settings['festival_name'] }}">
                            </div>
                            <div class="mb-3 col-md-4 form-group">
                                <label for="mobile81" class="form-label">8141631381 <span
                                        class="text-danger">auto/bulk</span></label>
                                <input type="text" name="mobile81" class="form-control" placeholder="Enter 1 or 2"
                                    value="{{ $settings['mobile81'] }}">
                            </div>
                            <div class="mb-3 col-md-4 form-group">
                                <label for="mobile75" class="form-label">8141631375 <span
                                        class="text-danger">auto/bulk</span></label>
                                <input type="text" name="mobile75" class="form-control" placeholder="Enter 1 or 2"
                                    value="{{ $settings['mobile75'] }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3 form-group">
                            <label for="aboutUs" class="form-label">About Us</label>
                            <textarea rows="8" name="aboutUs" id="aboutUs" placeholder="Enter Abou tUs" class="form-control">{{ $settings['aboutUs'] }}</textarea>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-4 form-group">
                                <label class="form-label">Forcefully all Logout</label><br>
                                <label class="custom-switch">
                                    <input type="hidden" name="forceFullyLogout" value="0">
                                    <input type="checkbox" name="forceFullyLogout" value="1"
                                        {{ $settings['forceFullyLogout'] == 1 ? 'checked' : '' }}>
                                    <span class="switch-slider"></span>
                                </label>
                            </div>
                            <div class="mb-3 col-md-4 form-group">
                                <label class="form-label">Help & Support</label><br>
                                <label class="custom-switch">
                                    <input type="hidden" name="help-support" value="0">
                                    <input type="checkbox" name="help-support" value="1"
                                        {{ $settings['help-support'] == 1 ? 'checked' : '' }}>
                                    <span class="switch-slider"></span>
                                </label>
                            </div>
                            <div class="mb-3 col-md-4 form-group">
                                <label class="form-label">Feedback & Suggestion</label><br>
                                <label class="custom-switch">
                                    <input type="hidden" name="feedback-suggestion" value="0">
                                    <input type="checkbox" name="feedback-suggestion" value="1"
                                        {{ $settings['feedback-suggestion'] == 1 ? 'checked' : '' }}>
                                    <span class="switch-slider"></span>
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-4 form-group">
                                <label class="form-label">Premium Subscription</label><br>
                                <label class="custom-switch">
                                    <input type="hidden" name="premium-subscription" value="0">
                                    <input type="checkbox" name="premium-subscription" value="1"
                                        {{ $settings['premium-subscription'] == 1 ? 'checked' : '' }}>
                                    <span class="switch-slider"></span>
                                </label>
                            </div>
                            <div class="mb-3 col-md-4 form-group">
                                <label class="form-label">Refer & Earn</label><br>
                                <label class="custom-switch">
                                    <input type="hidden" name="refer-earn" value="0">
                                    <input type="checkbox" name="refer-earn" value="1"
                                        {{ $settings['refer-earn'] == 1 ? 'checked' : '' }}>
                                    <span class="switch-slider"></span>
                                </label>
                            </div>
                            <div class="mb-3 col-md-4 form-group">
                                <label class="form-label">Complaint Menu</label><br>
                                <label class="custom-switch">
                                    <input type="checkbox" name="complaint_menu" value="1"
                                        {{ $settings['complaint_menu'] == 1 ? 'checked' : '' }}>
                                    <span class="switch-slider"></span>
                                </label>
                            </div>
                        </div>
                        <div class="mb-3 col-md-3 form-group">
                            <label for="sms_gateway_type" class="form-label">SMS Gateway</label>
                            <select name="sms_gateway_type" id="sms_gateway_type" class="form-select">
                                <option value="windex" {{ $settings['sms_gateway_type'] == 'windex' ? 'selected' : '' }}>Off
                                </option>
                                <option value="bulksms"{{ $settings['sms_gateway_type'] == 'bulksms' ? 'selected' : '' }}>
                                    Google</option>
                                <option value="msg91" {{ $settings['sms_gateway_type'] == 'msg91' ? 'selected' : '' }}>
                                    Facebook</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <button type="submit" class="btn btn-primary btn-lg">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
