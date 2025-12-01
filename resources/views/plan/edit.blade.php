@extends('layouts.main')

@section('page-title', 'Edit Subscription Plan')

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ route('plan.index') }}" class="btn btn-secondary btn-sm float-end">Back to List</a>
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
            <form action="{{ route('plan.update', $subscriptionPlan->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3 col-md-12 form-group">
                            <label for="plan_name" class="form-label">Name</label>
                            <input type="text" name="plan_name" id="plan_name" class="form-control"
                                placeholder="Enter Name" value="{{ $subscriptionPlan->plan_name }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3 col-md-12 form-group">
                            <label for="special_title" class="form-label">Special Title</label>
                            <input type="text" name="special_title" id="special_title" class="form-control"
                                placeholder="Enter Special Title" value="{{ $subscriptionPlan->special_title }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3 col-md-12 form-group">
                            <label for="duration_type" class="form-label">Duration Type</label>
                            <select name="duration_type" id="duration_type" class="form-select" required>
                                <option value="day" {{ $subscriptionPlan->duration_type == 'day' ? 'selected' : '' }}>Day</option>
                                <option value="month" {{ $subscriptionPlan->duration_type == 'month' ? 'selected' : '' }}>Month</option>
                                <option value="year" {{ $subscriptionPlan->duration_type == 'year' ? 'selected' : '' }}>Year</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3 col-md-12 form-group">
                            <label for="duration" class="form-label">Duration</label>
                            <input type="number" name="duration" id="duration" class="form-control" min="1"
                                value="{{ $subscriptionPlan->duration }}" placeholder="Enter Duration" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3 col-md-12 form-group">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" step="0.01" name="price" id="price" class="form-control"
                                placeholder="Enter Price" value="{{ $subscriptionPlan->price }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3 col-md-12 form-group">
                            <label for="discount" class="form-label">Discount</label>
                            <div class="input-group">
                                <span class="input-group-text">%</span>
                                <input type="number" step="0.01" name="discount" id="discount" class="form-control"
                                    placeholder="Enter Discount" value="{{ $subscriptionPlan->discount }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3 col-md-12 form-group">
                            <label for="discount_price" class="form-label">Discount Price</label>
                            <input type="number" step="0.01" name="discount_price" id="discount_price"
                                class="form-control" value="{{ $subscriptionPlan->discount_price }}" >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3 col-md-12 form-group">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="1" {{ $subscriptionPlan->status == 1 ? 'selected' : '' }}>On</option>
                                <option value="0" {{ $subscriptionPlan->status == 0 ? 'selected' : '' }}>Off</option>
                            </select>
                        </div>
                    </div>
                   
                    <div class="col-md-4">
                        <div class="mb-3 col-md-12 form-group">
                            <label for="sequence" class="form-label">sequence</label> 
                            <input type="number" name="sequence" id="sequence" class="form-control" min="0"
                                value="{{ $subscriptionPlan->sequence }}" placeholder="Enter Sequence" required>
                        </div>
                    </div>
                     <div class="col-md-4">
                        <div class="mb-3 col-md-12 form-group">
                            <label for="is_free" class="form-label">Is Free</label> 
                            <div class="radio-group">
                                <label class="radio-container">Free
                                    <input type="radio" name="is_free" value="0" {{ $subscriptionPlan->is_free == 0 ? 'checked' : '' }}>
                                    <span class="radio-checkmark"></span>
                                </label>
                                <label class="radio-container">Paid
                                    <input type="radio" name="is_free" value="1" {{ $subscriptionPlan->is_free == 1 ? 'checked' : '' }}>
                                    <span class="radio-checkmark"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Description Section -->
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0"></h5>
                            <button type="button" class="btn btn-success" id="addItem">Add</button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="itemsTable">
                                <thead>
                                    <tr>
                                        <th>Discription Title</th>
                                        <th>Select Sign (0-False, 1-True)</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="itemsBody">
                                    @foreach($subscriptionPlan->descriptionsItem as $index => $description)
                                    <tr class="item-row">
                                        <td><input type="text" class="form-control" name="items[{{ $index }}][title]" 
                                            value="{{ $description->title }}" required></td>
                                        <td>
                                            <div class="radio-group">
                                                <label class="radio-container"><i class="fa fa-times fa-2x text-primary" aria-hidden="true"></i>
                                                    <input type="radio" name="items[{{ $index }}][sign]" value="0" 
                                                        {{ $description->sign == 0 ? 'checked' : '' }}>
                                                    <span class="radio-checkmark"></span>
                                                </label>
                                                <label class="radio-container"><i class="fa fa-check fa-2x text-success" aria-hidden="true"></i>
                                                    <input type="radio" name="items[{{ $index }}][sign]" value="1" 
                                                        {{ $description->sign == 1 ? 'checked' : '' }}>
                                                    <span class="radio-checkmark"></span>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            @if($index > 0)
                                                <button type="button" class="btn btn-danger btn-sm remove-item" data-item-id="{{ $description->id }}">Remove</button>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            function calculateDiscountPrice() {
                const price = parseFloat($('#price').val()) || 0;
                const discount = parseFloat($('#discount').val()) || 0;

                if (price > 0 && discount >= 0) {
                    const discountAmount = (price * discount) / 100;
                    const discountPrice = price - discountAmount;
                    $('#discount_price').val(discountPrice.toFixed(2));
                } else {
                    $('#discount_price').val('');
                }
            }

            $('#price, #discount').on('input', calculateDiscountPrice);
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let itemIndex = {{ count($subscriptionPlan->descriptionsItem) }};

            // Add new item row
            document.getElementById('addItem').addEventListener('click', function() {
                const tbody = document.getElementById('itemsBody');
                const newRow = `
            <tr class="item-row">
                <td><input type="text" class="form-control" name="items[${itemIndex}][title]" required></td>
                <td>
                    <div class="radio-group">
                        <label class="radio-container"><i class="fa fa-times fa-2x text-primary" aria-hidden="true"></i>
                            <input type="radio" name="items[${itemIndex}][sign]" value="0">
                            <span class="radio-checkmark"></span>
                        </label>
                        <label class="radio-container"><i class="fa fa-check fa-2x text-success" aria-hidden="true"></i>
                            <input type="radio" name="items[${itemIndex}][sign]" value="1" checked>
                            <span class="radio-checkmark"></span>
                        </label>
                    </div>
                </td>
                <td><button type="button" class="btn btn-danger btn-sm remove-item">Remove</button></td>
            </tr>
        `;
                tbody.insertAdjacentHTML('beforeend', newRow);
                itemIndex++;
            });

            // Remove item row
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-item')) {
                    if (document.querySelectorAll('.item-row').length > 1) {
                        const itemId = e.target.getAttribute('data-item-id');
                        const row = e.target.closest('tr');
                        
                        if (itemId) {
                            // Show SweetAlert confirmation for existing items
                            Swal.fire({
                                title: 'Are you sure?',
                                text: 'You want to delete this item?',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#dc3545',
                                cancelButtonColor: '#6c757d',
                                confirmButtonText: 'Continue',
                                cancelButtonText: 'Cancel'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    fetch(`{{ url('/plan/item') }}/${itemId}`, {
                                        method: 'DELETE',
                                        headers: {
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                            'Content-Type': 'application/json'
                                        }
                                    })
                                    .then(response => {
                                        if (response.ok) {
                                            return response.json();
                                        }
                                        throw new Error('Network response was not ok');
                                    })
                                    .then(data => {
                                        if (data.success) {
                                            row.remove();
                                            calculateSummary();
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                    });
                                }
                            });
                        } else {
                            // Directly remove new items
                            row.remove();
                            calculateSummary();
                        }
                    }
                }
            });
        });
    </script>
@endpush