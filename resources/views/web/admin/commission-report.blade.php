@extends('layouts.admin-layout')

@section('title', 'Commission Report - ' . ucfirst($period))

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="mb-4">
        <a href="{{ route('admin.commissions') }}" class="btn btn-secondary btn-sm mb-3">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <h1 class="h3 mb-0">📊 Commission Report - {{ ucfirst($period) }}</h1>
    </div>

    <!-- Period Selector -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body">
            <div class="btn-group" role="group">
                <a href="{{ route('admin.commission.report', ['period' => 'daily']) }}" 
                   class="btn btn-sm {{ $period === 'daily' ? 'btn-primary' : 'btn-outline-primary' }}">
                    <i class="fas fa-calendar-day"></i> Daily
                </a>
                <a href="{{ route('admin.commission.report', ['period' => 'weekly']) }}" 
                   class="btn btn-sm {{ $period === 'weekly' ? 'btn-primary' : 'btn-outline-primary' }}">
                    <i class="fas fa-calendar-week"></i> Weekly
                </a>
                <a href="{{ route('admin.commission.report', ['period' => 'monthly']) }}" 
                   class="btn btn-sm {{ $period === 'monthly' ? 'btn-primary' : 'btn-outline-primary' }}">
                    <i class="fas fa-calendar"></i> Monthly
                </a>
            </div>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Total Revenue</div>
                    <h3 class="mb-0 text-primary">Nu. {{ number_format($totalRevenue, 2) }}</h3>
                    <small class="text-muted">{{ $transactions->count() }} transactions</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">System Commission</div>
                    <h3 class="mb-0 text-info">Nu. {{ number_format($totalCommission, 2) }}</h3>
                    <small class="text-muted">{{ $commissionRate * 100 }}% commission</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Owner Earnings</div>
                    <h3 class="mb-0 text-success">Nu. {{ number_format($ownerEarnings, 2) }}</h3>
                    <small class="text-muted">Distributed to owners</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Average Transaction</div>
                    <h3 class="mb-0">Nu. {{ $transactions->count() > 0 ? number_format($totalRevenue / $transactions->count(), 2) : '0.00' }}</h3>
                    <small class="text-muted">Per transaction</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-0">
            <h6 class="mb-0 py-2">Transaction Details</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0 small">
                <thead class="table-light">
                    <tr>
                        <th>Transaction ID</th>
                        <th>Owner</th>
                        <th>Booking ID</th>
                        <th>Amount</th>
                        <th>Commission (15%)</th>
                        <th>Owner Earnings</th>
                        <th>Payment Method</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr>
                            <td><code>{{ substr($transaction->transaction_id, 0, 12) }}...</code></td>
                            <td>{{ $transaction->user->name ?? 'N/A' }}</td>
                            <td>{{ $transaction->booking->booking_id ?? 'N/A' }}</td>
                            <td class="fw-500">Nu. {{ number_format($transaction->amount, 2) }}</td>
                            <td class="text-info">Nu. {{ number_format($transaction->amount * $commissionRate, 2) }}</td>
                            <td class="text-success fw-500">Nu. {{ number_format($transaction->amount * (1 - $commissionRate), 2) }}</td>
                            <td>
                                <span class="badge bg-light text-dark">{{ ucfirst($transaction->payment_method) }}</span>
                            </td>
                            <td>
                                <small>{{ $transaction->created_at->format('M d, Y H:i') }}</small>
                            </td>
                            <td>
                                @if($transaction->status === 'success')
                                    <span class="badge bg-success">Success</span>
                                @elseif($transaction->status === 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @else
                                    <span class="badge bg-danger">Failed</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox"></i> No transactions for this period
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Export Button -->
    <div class="mt-4 text-center">
        <button class="btn btn-outline-secondary" onclick="window.print()">
            <i class="fas fa-download"></i> Download/Print Report
        </button>
    </div>
</div>

@endsection
