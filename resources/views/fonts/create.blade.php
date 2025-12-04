<form action="{{  route('fonts.store') }}" enctype="multipart/form-data" method="POST">
    @csrf
     <div class="modal-body">
         <div class="row">
         </div>
     </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        <button class="btn btn-primary" type="submit">{{__('Submit')}}</button>
    </div>
</form>