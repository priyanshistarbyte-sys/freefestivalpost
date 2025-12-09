<form method="POST" action="{{ route('compain.reply.store', $complain->id) }}">
    @csrf
     <div class="modal-body">
        <div class="row">
            <div class="mb-3 col-md-12 form-group">
                <label for="reply" class="form-label">Reply Message</label>
                <textarea rows="8" name="reply" id="reply" placeholder="Enter Reply Message" class="form-control">{{ old('question', $complain->reply) }}</textarea>
            </div>
            <div class="mb-3 col-md-12 form-group">
                 <label for="status" class="form-label">Status</label>
                  <select class="form-select" name="status" id="status" required>
                        <option value="0" {{ $complain->status == '0' ? 'selected' : '' }}>Pending</option>
                        <option value="1" {{ $complain->status == '1' ? 'selected' : '' }}>On Progress</option>
                        <option value="2" {{ $complain->status == '2' ? 'selected' : '' }}>Hold</option>
                        <option value="3" {{ $complain->status == '3' ? 'selected' : '' }}>Solved</option>
                 </select>
            </div>
        </div>
     </div>
     <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        <button class="btn btn-primary" type="submit">{{__('Update')}}</button>
    </div>
</form>