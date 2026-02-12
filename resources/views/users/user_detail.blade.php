<div class="modal-body">
    <div class="row mb-3">
            <div class="col-md-3">
            <img src="{{ $userData['photo'] }}" alt="Profile" class="img-fluid rounded" style="max-width: 80px;">
            </div>
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td><strong>Mobile</strong></td>
                                    <td>{{ $userData['mobile'] ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email</strong></td>
                                    <td>{{ $userData['email'] ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Business Name</strong></td>
                                    <td>{{ $userData['business_name'] ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Business Email</strong></td>
                                    <td>{{ $userData['b_email'] ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Referral Code</strong></td>
                                    <td>{{ $userData['referral_code'] ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Referral Used Count</strong></td>
                                    <td>{{ \App\Models\Admin::where('used_referral_code', $userData['referral_code'])->whereNotNull('used_referral_code')->count() }}</td>
                                </tr>
                            </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td><strong>Business Mobile</strong></td>
                                <td>{{ $userData['b_mobile2'] ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Website</strong></td>
                                <td>{{ $userData['b_website'] ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Address</strong></td>
                                <td>{{ $userData['address'] ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Is Paid</strong></td>
                                <td>
                                    @if($userData['ispaid'] == 1)
                                        <i class="fa fa-check-circle text-success"></i>
                                    @else
                                        <i class="fa fa-times-circle text-danger"></i>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Used Rreferral Code</strong></td>
                                <td>{{ $userData['used_referral_code'] ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Status</strong></td>
                                <td>
                                    @php
                                        $activePayment = collect($userData['payments'])->where('status', '1')->where('end_date', '>=', now()->format('Y-m-d'))->first();
                                    @endphp
                                    @if($activePayment)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-4"><small><strong>Last Login:</strong> {{ $userData['last_login'] }}</small></div>
                    <div class="col-md-4"><small><strong>Created:</strong> {{ $userData['created_at'] }}</small></div>
                    <div class="col-md-4"><small><strong>Updated:</strong> {{ $userData['updated_at'] }}</small></div>
                </div>
            </div>
    </div>
    <!-- Payment Information Section -->
    <div class="card mb-3">
        <div class="card-header bg-info py-2">
            <h6 class="mb-0">Payment Information</h6>
        </div>
        <div class="card-body p-2">
            @if(count($userData['payments']) > 0)
                <div class="table-responsive">
                    <table class="table table-sm table-striped">
                        <thead>
                            <tr>
                                <th>Plan</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Amount</th>
                                <th>Tran ID</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($userData['payments'] as $payment)
                            <tr>
                                <td>{{ $payment->plan_name ?? '-' }}</td>
                                <td>{{ $payment->start_date ? \Carbon\Carbon::parse($payment->start_date)->format('d/m/Y') : '-' }}</td>
                                <td>{{ $payment->end_date ? \Carbon\Carbon::parse($payment->end_date)->format('d/m/Y') : '-' }}</td>
                                <td>₹{{ number_format($payment->price, 2) }}</td>
                                <td>{{ $payment->transactionid ?? '-' }}</td>
                                <td>
                                    @if($payment->status == '0')
                                        <span class="badge bg-danger">Expire</span>
                                    @elseif($payment->status == '1')
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-warning" style="color:black">Upcoming</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="mb-0">No payment information available.</p>
            @endif
        </div>
    </div>
    <!-- Device Information Section -->
    <div class="card mb-3">
        <div class="card-header bg-info py-2">
            <h6 class="mb-0">Device Information</h6>
        </div>
        <div class="card-body p-2">
            <div class="table-responsive">
                <table class="table table-sm table-striped">
                    <thead>
                        <tr>
                            <th>Device ID</th>
                            <th>App Version</th>
                            <th>OS</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($userData['deviceInfo'] as $device)
                        <tr>
                            <td>{{ substr($device->device_id, 0, 15) }}...</td>
                            <td>{{ $device->app_version }}</td>
                            <td>{{ $device->oprating_system }}</td>
                            <td>
                                <button class="btn btn-sm btn-danger delete-btn"
                                    data-url={{ route('user-notification.delete',$device->id) }}>
                                    <i class="fa fa-times"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Custom Frames & Payment Links -->
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-header bg-info  py-2">
                    <h6 class="mb-0">Custom Frames: {{ $userData['totalCustomFrame'] }}</h6>
                </div>
            </div>
        </div>
    </div>
    <!-- Send Payment Link Form -->
    <div class="card">
        <div class="card-header bg-success text-white py-2">
            <h6 class="mb-0">Send Payment Link</h6>
        </div>
        <div class="card-body p-2">
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label">Select Package</label>
                    <select class="form-select form-select-sm" id="packageSelect" onchange="setAmount()">
                        <option value="">-- Select Package--</option>
                        @foreach($userData['packageList'] as $package)
                            <option value="{{ $package->id }}" data-price="{{ $package->price }}" data-discount="{{ $package->discount }}">{{ $package->plan_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Enter Amount</label>
                    <input type="number" class="form-control form-control-sm" id="amountField" placeholder="Enter Amount" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label><br>
                    <button type="button" class="btn btn-success btn-sm" onclick="sendPaymentLink()">Send Payment Link</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function sendPaymentLink() {
    const packageSelect = document.getElementById('packageSelect');
    const amountField = document.getElementById('amountField');
    
    if (!packageSelect.value || !amountField.value) {
        alert('Please select package and amount');
        return;
    }
    
    $.ajax({
        url: '{{ route("user.sendPaymentLink") }}',
        type: 'POST',
        data: {
            user_id: '{{ $userData["id"] }}',
            amount: amountField.value,
            packageid: packageSelect.value,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.status === 'success') {
                alert(response.message);
            } else {
                alert(response.message);
            }
        },
        error: function() {
            alert('Error occurred while sending payment link');
        }
    });
}

function setAmount() {
    const select = document.getElementById('packageSelect');
    
    const amountField = document.getElementById('amountField');
    const selectedOption = select.options[select.selectedIndex];
    
    if (selectedOption.value) {
        const price = parseFloat(selectedOption.getAttribute('data-price'));
        const discount = parseFloat(selectedOption.getAttribute('data-discount')) || 0;
        const finalPrice = price - (price * discount / 100);
        amountField.value = finalPrice.toFixed(2);
    } else {
        amountField.value = '';
    }
}

    $(document).on('click', '.delete-btn', function(e) {
                e.preventDefault();
                const deleteUrl = $(this).attr('data-url');

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You want to delete this record?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Continue',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = $('<form>', {
                            'method': 'POST',
                            'action': deleteUrl
                        });
                        form.append($('<input>', {
                            'type': 'hidden',
                            'name': '_token',
                            'value': '{{ csrf_token() }}'
                        }));
                        form.append($('<input>', {
                            'type': 'hidden',
                            'name': '_method',
                            'value': 'DELETE'
                        }));
                        $('body').append(form);
                        form.submit();
                    }
                });
    });
</script>