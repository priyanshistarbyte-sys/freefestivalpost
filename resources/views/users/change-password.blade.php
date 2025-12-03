<form action="{{ route('user.updatePassword', $user->id) }}" method="POST">
    @csrf
    <div class="modal-body">
        <div class="form-group mb-3">
            <label for="new_password">New Password</label>
            <input type="password" name="new_password" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label for="new_password_confirmation">Confirm New Password</label>
            <input type="password" name="new_password_confirmation" class="form-control" required>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Submit</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
    </div>
</form>