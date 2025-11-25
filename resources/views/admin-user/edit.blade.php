<form action="{{ route('admin-user.update', $admin->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="modal-body">
        <div class="row">

            <div class="mb-3 col-md-6 form-group">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control"
                       value="{{ $admin->name }}" placeholder="Enter Name" required>
            </div>

            <div class="mb-3 col-md-6 form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control"
                       value="{{ $admin->email }}" placeholder="Enter Email" required>
            </div>

            <div class="mb-3 col-md-6 form-group">
                <label class="form-label">Password (Leave blank to keep same)</label>
                <input type="password" name="password" class="form-control" placeholder="Enter New Password">
            </div>

            <div class="mb-3 col-md-6 form-group">
                <label class="form-label">Mobile</label>
                <input type="number" name="mobile" class="form-control"
                       value="{{ $admin->mobile }}" placeholder="Enter Mobile" required>
            </div>

            <div class="mb-3 col-md-6 form-group">
                <label class="form-label">Promo Code</label>
                <input type="text" name="note" class="form-control"
                       value="{{ $admin->note }}" placeholder="Enter Promo Code">
            </div>

            <div class="mb-3 col-md-6 form-group">
                <label class="form-label">Role</label>
                <select name="role" class="form-control" required>
                    @foreach ($roles as $value => $label)
                        <option value="{{ $value }}"
                            {{ $admin->getRoleNames()->first() == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3 col-md-3 form-group">
                <label class="form-label">Status</label><br>
                <label class="custom-switch">
                    <input type="checkbox" name="status" value="1"
                        {{ $admin->status == 1 ? 'checked' : '' }}>
                    <span class="switch-slider"></span>
                </label>
            </div>

        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary" type="submit">Update</button>
    </div>

</form>
