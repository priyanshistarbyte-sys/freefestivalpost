

<form action="{{  route('roles.store') }}" method="POST">
    @csrf
     <div class="modal-body">
        <div class="row">
            <div class="mb-3">
                <div class="form-group">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Enter Name" required>
                </div>
            </div>
            <div class="mb-3">
                <div class="form-group">
                    <label for="permission" class="form-label">Permission</label>
                    <br/>
                    @foreach($permission as $value)
                        <label><input type="checkbox" name="permission[{{$value->id}}]" value="{{$value->id}}" class="name">
                        {{ $value->name }}</label>
                    <br/>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        <button class="btn btn-primary" type="submit">{{__('Submit')}}</button>
    </div>
</form>