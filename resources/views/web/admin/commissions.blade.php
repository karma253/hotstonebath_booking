@extends('layouts.admin-layout')

@section('title', 'Commission Management')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">💰 Commission Management</h1>
        <a href="{{ route('admin.commission.report') }}" class="btn btn-outline-primary btn-sm">
            <i class="fas fa-chart-bar"></i> View Reports
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-white-50 small">Total Revenue</div>
                            <h3 class="mb-0">Nu. {{ number_format($totalRevenue, 2) }}</h3>
                        </div>
                        <i class="fas fa-coins fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-white-50 small">System Commission (15%)</div>
                            <h3 class="mb-0">Nu. {{ number_format($totalCommission, 2) }}</h3>
                        </div>
                        <i class="fas fa-percent fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-white-50 small">Owner Earnings (85%)</div>
                            <h3 class="mb-0">Nu. {{ number_format($totalRevenue - $totalCommission, 2) }}</h3>
                        </div>
                        <i class="fas fa-user-tie fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Commission Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-0">
            <h6 class="mb-0 py-2">Commission Breakdown by Owner</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Owner Name</th>
                        <th>Email</th>
                        <th>Bookings</th>
                        <th>Total Revenue</th>
                        <th>System Commission (15%)</th>
                        <th>Owner Earnings (85%)</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($commissionData as $data)
                        <tr>
                            <td class="fw-500">{{ $data['owner_name'] }}</td>
                            <td><small>{{ $data['email'] }}</small></td>
                            <td>
                                <span class="badge bg-light text-dark">{{ $data['total_bookings'] }}</span>
                            </td>
                            <td class="text-primary fw-500">Nu. {{ number_format($data['total_revenue'], 2) }}</td>
                            <td class="text-info">Nu. {{ number_format($data['commission'], 2) }}</td>
                            <td class="text-success fw-500">Nu. {{ number_format($data['owner_earnings'], 2) }}</td>
                            <td>
                                <span class="badge bg-success">Pending Payout</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox"></i> No commission data
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Commission Statistics -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title mb-3">Top 5 Earning Owners</h6>
                    <div class="list-group list-group-flush">
                        @foreach($commissionData->take(5) as $data)
                            <div class="list-group-item px-0 py-3 d-flex justify-content-between">
                                <div>
                                    <h6 class="mb-1">{{ $data['owner_name'] }}</h6>
                                    <small class="text-muted">{{ $data['total_bookings'] }} bookings</small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold">Nu. {{ number_format($data['owner_earnings'], 2) }}</div>
                                    <small class="text-success">+{{ number_format($data['total_revenue'], 2) }} revenue</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title mb-3">Commission Summary</h6>
                    <div class="row text-center">
                        <div class="col-6 py-3 border-end">
                            <div class="text-muted small">Commission Rate</div>
                            <h4 class="mb-0">{{ $commissionRate * 100 }}%</h4>
                        </div>
                        <div class="col-6 py-3">
                            <div class="text-muted small">Total Owners</div>
                            <h4 class="mb-0">{{ $commissionData->count() }}</h4>
                        </div>
                    </div>
                    <hr>
                    <div class="text-center">
                        <div class="text-muted small mb-2">This Month</div>
                        <div class="text-success fw-bold">
                            Nu. {{ number_format($totalRevenue * $commissionRate, 2) }} in commission
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
