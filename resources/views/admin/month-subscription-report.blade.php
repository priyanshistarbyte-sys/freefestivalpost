@extends('layouts.main')
@section('title', 'Monthly Subscription Report')
@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title">Monthly Subscription Report List</h4>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-container">
                <table class="table" id="monthlySubscriptionList">
                    <thead>
                        <tr>
                            <th width="10%">Month</th>
                            <th width="10%">1 Monthly</th>
                            <th width="10%">3 Monthly</th>
                            <th width="10%">6 Monthly</th>
                            <th width="5%">Yearly</th>
                            <th width="5%">Exclusive</th>
                            <th width="10%">Trail</th>
                            <th width="10%">Total Refund</th>
                            <th width="10%">Total Refund Amount</th>
                            <th width="10%">Total Paid Subscription</th>
                            <th width="10%">Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $monthlyTotal = $monthly3Total = $monthly6Total = 0;
                            $yearlyTotal = $exclusiveTotal = $trailTotal = 0;
                            $totalPaid = $totalAmount = $totalRefund = $totalRefundAmount = 0;
                        @endphp

                        @foreach ($rows as $row)
                        @php
                            $monthlyTotal += $row->monthly_total;
                            $monthly3Total += $row->monthly_3_total;
                            $monthly6Total += $row->monthly_6_total;
                            $yearlyTotal += $row->yearly_total;
                            $exclusiveTotal += $row->total_exclusive;
                            $trailTotal += $row->trail_total;
                            $totalPaid += $row->total_paid;
                            $totalAmount += $row->total_amount;
                            $totalRefund += $row->total_refund;
                            $totalRefundAmount += $row->total_refund_amount;
                        @endphp
                        <tr>
                            <td>{{ $row->month }}</td>
                            <td>{{ $row->monthly_total }}</td>
                            <td>{{ $row->monthly_3_total }}</td>
                            <td>{{ $row->monthly_6_total }}</td>
                            <td>{{ $row->yearly_total }}</td>
                            <td>{{ $row->total_exclusive }}</td>
                            <td>{{ $row->trail_total }}</td>
                            <td>{{ $row->total_refund }}</td>
                            <td>{{ $row->total_refund_amount }}</td>
                            <td>{{ $row->total_paid }}</td>
                            <td>₹ {{ number_format($row->total_amount, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Total</th>
                            <th>{{ $monthlyTotal }}</th>
                            <th>{{ $monthly3Total }}</th>
                            <th>{{ $monthly6Total }}</th>
                            <th>{{ $yearlyTotal }}</th>
                            <th>{{ $exclusiveTotal }}</th>
                            <th>{{ $trailTotal }}</th>
                            <th>{{ $totalRefund }}</th>
                            <th>{{ $totalRefundAmount }}</th>
                            <th>{{ $totalPaid }}</th>
                            <th>₹ {{ number_format($totalAmount, 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection