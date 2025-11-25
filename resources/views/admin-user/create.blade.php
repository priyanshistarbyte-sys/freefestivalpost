<form action="{{ route('admin-user.store') }}" method="POST">
    @csrf
    <div class="modal-body">
        <div class="row">
            <div class="mb-3 col-md-6 form-group">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Enter Name" required>
            </div>
            <div class="mb-3 col-md-6 form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email" required>
            </div>
            <div class="mb-3 col-md-6 form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Enter Password" required>
            </div>
            <div class="mb-3 col-md-6 form-group">
                <label for="mobile" class="form-label">Mobile</label>
                <input type="number" name="mobile" id="mobile" class="form-control" placeholder="Enter Mobile" required>
            </div>
            <div class="mb-3 col-md-6 form-group">
                <label for="note" class="form-label">Promo Code (Multiple Code separated by ,)</label>
                <input type="text" name="note" id="note" class="form-control" placeholder="Enter Promo Code" >
            </div>
            <div class="mb-3 col-md-6 form-group">
                <label for="note" class="form-label">Role</label>
                <select name="role" id="role" class="form-control" required>
                    @foreach ($roles as $value => $label)
                            <option value="{{ $value }}">
                                {{ $label }}
                            </option>
                    @endforeach
                </select>
             </div>
            <div class="mb-3 col-md-3 form-group">
                <label class="form-label" for="status">Status</label></br>
                <label class="custom-switch">
                    <input type="checkbox" name="status" value="1" checked>
                    <span class="switch-slider"></span>
                </label>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        <button class="btn btn-primary" type="submit">{{ __('Submit') }}</button>
    </div>
</form>
