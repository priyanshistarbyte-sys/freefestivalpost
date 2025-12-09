<form action="{{ route('faqs.update', $faq->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="row">
            <div class="mb-3 col-md-12 form-group">
                <label for="question" class="form-label">Question</label>
                <textarea rows="8" name="question" id="question" placeholder="Enter Question" class="form-control">{{ old('question', $faq->question) }}</textarea>
            </div>
            <div class="mb-3 col-md-12 form-group">
                <label for="answer" class="form-label">Answer</label>
                <textarea rows="8" name="answer" id="answer" placeholder="Enter Answer" class="form-control">{{ old('answer', $faq->answer) }}</textarea>
            </div>
            <div class="mb-3 col-md-4 form-group">
                <label class="form-label" for="status">Status</label></br>
                <label class="custom-switch">
                    <input type="checkbox" name="status" value="1"
                        {{ old('status', $faq->status) == 1 ? 'checked' : '' }}>
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