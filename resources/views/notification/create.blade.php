@extends('layouts.main')
@section('title', 'Notification Send List')
@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ route('notification.index') }}" class="btn btn-secondary btn-sm float-end">Back to List</a>
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
            <form action="{{ route('notification.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <p>1). cat_10 - go to category 10 id par page</p>
                                <p>2). plan_0 - go to plan page</p>
                            </div>
                            <div class="col-md-6">
                                <p>3). update_0 - go to play store</p>
                                <!-- <p>4). today_0 - go to today special page</p> -->
                                <p>4). general_0 - go to main page</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="row">

                            <!-- Topic / Token -->
                            <div class="col-md-4">
                                <div class="mb-3 form-group">
                                    <label class="form-label">Topic or Token?</label>
                                    <div class="radio-group">
                                        <label class="radio-container">
                                            Topic
                                            <input type="radio" name="topictoken" value="0">
                                            <span class="radio-checkmark"></span>
                                        </label>

                                        <label class="radio-container">
                                            Token
                                            <input type="radio" name="topictoken" value="1" checked>
                                            <span class="radio-checkmark"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Save or Not -->
                            <div class="col-md-4">
                                <div class="mb-3 form-group">
                                    <label class="form-label">Save?</label>
                                    <div class="radio-group">
                                        <label class="radio-container">
                                            No
                                            <input type="radio" name="savenote" value="0">
                                            <span class="radio-checkmark"></span>
                                        </label>

                                        <label class="radio-container">
                                            Yes
                                            <input type="radio" name="savenote" value="1" checked>
                                            <span class="radio-checkmark"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Image Send -->
                            <div class="col-md-4">
                                <div class="mb-3 form-group">
                                    <label class="form-label">Send Image?</label>
                                    <div class="radio-group">
                                        <label class="radio-container">
                                            No
                                            <input type="radio" name="imgsend" value="0">
                                            <span class="radio-checkmark"></span>
                                        </label>

                                        <label class="radio-container">
                                            Yes
                                            <input type="radio" name="imgsend" value="1" checked>
                                            <span class="radio-checkmark"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="title" class="form-label">User Filter</label>
                        <select name="userFilter" id="userFilter" class="form-control">
                            <option value="">-- Select User Filter --</option>
                            <option value="1">New User</option>
                            <option value="2">Total Package Paid User</option>
                            <option value="6">Total Package Expried User</option>
                            <option value="3">Trial Plan Active User</option>
                            <option value="5">Trial Plan Expried User</option>
                            <option value="4">Without Logo</option>
                            <option value="8">Total Free User</option>
                            <option value="7">My Testing Device - Sandip</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="title" class="form-label">Select Category</label>
                        <select name="category_data" id="category_data" class="form-control">
                            <option value="">-- Select Category --</option>
                            @foreach ($categories ?? [] as $category)
                                @php
                                    $festivalDate = (!empty($category->event_date) && $category->event_date !== '0000-00-00') ? ' || ' . \Carbon\Carbon::parse($category->event_date)->format('d/m/Y') : '';
                                @endphp

                                <option value="{{ $category->id }}">
                                    {{ $category->mtitle }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="url" class="form-label">URL* (cat_categoryID / plan_0 / update_0 / general_0)</label>
                        <input type="text" class="form-control" id="url" name="url" placeholder="Enter ID">
                            
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="Enter Title">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="image" class="form-label">Notification Banner</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                     </div>
                     <div class="col-md-6 mb-3">
                         <label for="message" class="form-label">Message*</label>
                         <textarea rows="4" name="message" id="message" placeholder="Enter Message"
                            class="form-control"></textarea>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">Send</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
<script>
$(document).ready(function () {

    /* CATEGORY CHANGE → AUTO FILL */
    $('#category_data').on('change', function () {

        let cat_id = $(this).val();

        if (!cat_id) return;

        $.ajax({
            url: "{{ route('notification.getCategoryDataById') }}",
            type: "POST",
            data: {
                id: cat_id,
                _token: "{{ csrf_token() }}"
            },
            success: function (res) {
                if (res.status === 'success') {

                    $('#title').val(res.data.mtitle);

                    let message = res.data.noti_quote
                        ? res.data.noti_quote + ' - Make your own business post'
                        : res.data.mtitle + ' - Make your own business post';

                    $('#message').val(message);
                    $('#url').val('cat_' + res.data.id);

                } else {
                    $('#title').val('');
                    $('#message').val('');
                    $('#url').val('');
                }
            }
        });
    });

    /* TOPIC SELECT → RESET OPTIONS */
    $('input[type=radio][name=topictoken]').change(function () {
        if (this.value == '0') {
            $('input[name="imgsend"][value="0"]').prop('checked', true);
            $('#userFilter').val('');
        }
    });

});
</script>
@endpush