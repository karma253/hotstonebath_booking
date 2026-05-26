@extends('web.layouts.app')

@section('title', 'Revenue & Commission - Admin')

@section('content')
<div class="container-fluid mt-4">
    <!-- Success/Error Messages -->
    @if($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card card-shadow rounded-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1">Total Revenue</p>
                            <h3 class="mb-0">Nu. {{ number_format($stats['total_revenue'], 2) }}</h3>
                        </div>
                        <div style="font-size: 2.5rem; color: #667eea;">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-shadow rounded-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1">Admin Commission ({{ (int)($commissionRate * 100) }}%)</p>
                            <h3 class="mb-0">Nu. {{ number_format($stats['total_commission'], 2) }}</h3>
                        </div>
                        <div style="font-size: 2.5rem; color: #f5576c;">
                            <i class="fas fa-percentage"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-shadow rounded-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1">Owner Earnings</p>
                            <h3 class="mb-0">Nu. {{ number_format($stats['total_owner_earnings'], 2) }}</h3>
                        </div>
                        <div style="font-size: 2.5rem; color: #43e97b;">
                            <i class="fas fa-hand-holding-heart"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-shadow rounded-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1">Commission Rate</p>
                            <h3 class="mb-0">{{ (int)($commissionRate * 100) }}%</h3>
                        </div>
                        <div style="font-size: 2.5rem; color: #ffa502;">
                            <i class="fas fa-info-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Owner Revenue Table -->
    <div class="card card-shadow rounded-4">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">🏢 Owner Revenue Breakdown</h5>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Owner Name</th>
                            <th>Total Bookings</th>
                            <th>Total Revenue</th>
                            <th>Commission Paid ({{ (int)($commissionRate * 100) }}%)</th>
                            <th>Net Earnings</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ownerRevenue as $revenue)
                            <tr>
                                <td>
                                    <strong>{{ $revenue['owner']->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $revenue['owner']->email }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $revenue['total_bookings'] }} bookings</span>
                                </td>
                                <td>
                                    <strong>Nu. {{ number_format($revenue['total_revenue'], 2) }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-danger">
                                        Nu. {{ number_format($revenue['commission'], 2) }}
                                    </span>
                                </td>
                                <td>
                                    <strong style="color: #43e97b; font-size: 1.1rem;">
                                        Nu. {{ number_format($revenue['net_earnings'], 2) }}
                                    </strong>
                                </td>
                                <td>
                                    @if($revenue['owner']->status === 'active' || $revenue['owner']->status === 'approved')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle"></i> Active
                                        </span>
                                    @elseif($revenue['owner']->status === 'pending_verification')
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-hourglass-half"></i> Pending
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">{{ $revenue['owner']->status }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox" style="font-size: 2rem;"></i>
                                    <p>No owner data available</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Commission Details -->
    <div class="row g-3 mt-4">
        <div class="col-md-6">
            <div class="card card-shadow rounded-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">📊 Commission Calculation Formula</h5>
                </div>
                <div class="card-body">
                    <code style="display: block; background: #f8f9fa; padding: 1.5rem; border-radius: 8px; line-height: 1.8;">
                        <strong>Admin Commission:</strong> Total Amount × {{ (int)($commissionRate * 100) }}%<br>
                        <strong>Example:</strong> Nu. 10,000 × {{ (int)($commissionRate * 100) }}% = Nu. {{ number_format(10000 * $commissionRate, 2) }}<br>
                        <br>
                        <strong>Owner Earnings:</strong> Total Amount - Commission<br>
                        <strong>Example:</strong> Nu. 10,000 - Nu. {{ number_format(10000 * $commissionRate, 2) }} = Nu. {{ number_format(10000 * (1 - $commissionRate), 2) }}
                    </code>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-shadow rounded-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">💡 Revenue Summary</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled" style="line-height: 2;">
                        <li>
                            <strong>Total System Revenue:</strong>
                            <span style="float: right;">Nu. {{ number_format($stats['total_revenue'], 2) }}</span>
                        </li>
                        <li>
                            <strong>Admin Commission Earned:</strong>
                            <span style="float: right; color: #f5576c;">Nu. {{ number_format($stats['total_commission'], 2) }}</span>
                        </li>
                        <li>
                            <strong>Paid to Owners:</strong>
                            <span style="float: right; color: #43e97b;">Nu. {{ number_format($stats['total_owner_earnings'], 2) }}</span>
                        </li>
                        <li style="border-top: 2px solid #e0e0e0; padding-top: 1rem; margin-top: 1rem;">
                            <strong>Commission Rate:</strong>
                            <span style="float: right;">{{ (int)($commissionRate * 100) }}%</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card-shadow {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .rounded-4 {
        border-radius: 12px;
    }

    .card-header {
        border-bottom: 1px solid #e0e0e0;
    }
</style>
@endsection
