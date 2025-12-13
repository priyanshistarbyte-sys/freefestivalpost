<form action="{{ route('advertisement.update', $advertisement->id) }}" method="POST">
    @csrf
    @method('PUT')
     <div class="modal-body">
        <div class="row">
            <div class="mb-3">
                <div class="form-group">
                    <label for="app_id" class="form-label">Select Application</label>
                    <select class="form-select" name="app_id" id="app_id" required>
                            @foreach ($applications ?? [] as $application)
                                <option value="{{ $application->id }}" {{ $advertisement->app_id == $application->id ? 'selected' : '' }}>{{ $application->app_name }}</option>
                            @endforeach
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <div class="form-group">
                    <label for="ads_title" class="form-label">Advertise Title <span class="text-danger">*</span></label>
                    <input type="text" name="ads_title" id="ads_title" class="form-control" placeholder="Enter Advertise Title" value="{{ $advertisement->ads_title }}" required>
                </div>
            </div>
            <div class="mb-3">
                <div class="form-group">
                    <label for="ads_id" class="form-label">Advertise ID</label>
                    <input type="text" name="ads_id" id="ads_id" class="form-control" placeholder="Enter Ads Id" value="{{ $advertisement->ads_id }}" required>
                </div>
            </div>
            <div class="mb-3">
                <div class="form-group">
                    <label for="ads_type" class="form-label">Select Ads Type (1-Admob, 2- Facebook)</label>
                   <div class="radio-group">
                            <label class="radio-container">Admob
                                <input type="radio" name="ads_type" value="1" {{ $advertisement->ads_type == 1 ? 'checked' : '' }}>
                                <span class="radio-checkmark"></span>
                            </label>
                            <label class="radio-container">Facebook
                                <input type="radio" name="ads_type" value="2" {{ $advertisement->ads_type == 2 ? 'checked' : '' }}>
                                <span class="radio-checkmark"></span>
                            </label>
                        </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        <button class="btn btn-primary" type="submit">{{__('Update')}}</button>
    </div>
</form>