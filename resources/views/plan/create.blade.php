@extends('layouts.main')

@section('page-title', 'Create Subscription Plan')

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
            <form action="{{ route('plan.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3 col-md-12 form-group">
                            <label for="plan_name" class="form-label">Name</label>
                            <input type="text" name="plan_name" id="plan_name" class="form-control"
                                placeholder="Enter Name" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3 col-md-12 form-group">
                            <label for="special_title" class="form-label">Special Title</label>
                            <input type="text" name="special_title" id="special_title" class="form-control"
                                placeholder="Enter Special Title">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3 col-md-12 form-group">
                            <label for="duration_type" class="form-label">Duration Type</label>
                            <select name="duration_type" id="duration_type" class="form-select"
                                placeholder="Select Duration Type" required>
                                <option value="day">Day</option>
                                <option value="month">Month</option>
                                <option value="year">Year</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3 col-md-12 form-group">
                            <label for="duration" class="form-label">Duration</label>
                            <input type="number" name="duration" id="duration" class="form-control" min="1"
                                value="1" placeholder="Enter Duration" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3 col-md-12 form-group">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" step="0.01" name="price" id="price" class="form-control"
                                placeholder="Enter Price" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3 col-md-12 form-group">
                            <label for="discount" class="form-label">Discount</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    %
                                </span>
                                <input type="number" step="0.01" name="discount" id="discount" class="form-control"
                                    placeholder="Enter Discount">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3 col-md-12 form-group">
                            <label for="discount_price" class="form-label">Discount Price</label>
                            <input type="number" step="0.01" name="discount_price" id="discount_price"
                                class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3 col-md-12 form-group">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="1">On</option>
                                <option value="0">Off</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3 col-md-12 form-group">
                            <label for="sequence" class="form-label">sequence</label> 
                            <input type="number" name="sequence" id="sequence" class="form-control" min="0"  value="0" placeholder="Enter Sequence" required>
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
                                    <tr class="item-row">
                                        <td><input type="text" class="form-control" name="items[0][title]" required>
                                        </td>
                                        <td>
                                            <div class="radio-group">
                                                <label class="radio-container"><i class="fa fa-times fa-2x text-primary"
                                                        aria-hidden="true"></i>
                                                    <input type="radio" name="items[0][sign]" value="0" >
                                                    <span class="radio-checkmark"></span>
                                                </label>
                                                <label class="radio-container"><i class="fa fa-check fa-2x text-success"
                                                        aria-hidden="true"></i>
                                                    <input type="radio" name="items[0][sign]" value="1" checked>
                                                    <span class="radio-checkmark"></span>
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">Create</button>
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
            let itemIndex = 1;

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
                        e.target.closest('tr').remove();
                    }
                }
            });
        });
    </script>
@endpush
