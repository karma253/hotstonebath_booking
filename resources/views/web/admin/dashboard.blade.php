@extends('layouts.admin-layout')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard Overview')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-chart-line"></i> Admin Dashboard</h1>
    <p>System overview and management controls</p>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-card-icon primary">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-card-label">Total Users</div>
            <div class="stat-card-value">{{ $stats['customers'] ?? 0 }}</div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-card-icon success">
                <i class="fas fa-user-tie"></i>
            </div>
            <div class="stat-card-label">Total Owners</div>
            <div class="stat-card-value">{{ $stats['owners'] ?? 0 }}</div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-card-icon warning">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="stat-card-label">Total Bookings</div>
            <div class="stat-card-value">{{ $stats['bookings'] ?? 0 }}</div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-card-icon danger">
                <i class="fas fa-coins"></i>
            </div>
            <div class="stat-card-label">Total Revenue</div>
            <div class="stat-card-value">Nu. {{ number_format($totalRevenue ?? 0, 0) }}</div>
        </div>
    </div>
</div>

<!-- Pending Approvals Section -->
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-hourglass-half"></i> Pending Approvals</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <!-- Pending Owners -->
                    <div class="col-md-6">
                        <div class="border rounded p-3">
                            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                                <div style="width: 50px; height: 50px; border-radius: 8px; background: rgba(245, 158, 11, 0.1); display: flex; align-items: center; justify-content: center; color: #f59e0b; font-size: 1.5rem;">
                                    <i class="fas fa-user-clock"></i>
                                </div>
                                <div>
                                    <div style="font-size: 1.5rem; font-weight: 700; color: #1e293b;">{{ $pendingOwners->count() }}</div>
                                    <div style="color: #64748b; font-size: 0.85rem;">Pending Owner Approvals</div>
                                </div>
                            </div>
                            @if($pendingOwners->count() > 0)
                                <a href="{{ route('admin.users', ['role' => 'owner', 'status' => 'pending_verification']) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> Review Owners
                                </a>
                            @else
                                <p class="text-muted mb-0 small">No pending approvals</p>
                            @endif
                        </div>
                    </div>

                    <!-- Pending Listings -->
                    <div class="col-md-6">
                        <div class="border rounded p-3">
                            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                                <div style="width: 50px; height: 50px; border-radius: 8px; background: rgba(16, 185, 129, 0.1); display: flex; align-items: center; justify-content: center; color: #10b981; font-size: 1.5rem;">
                                    <i class="fas fa-list-check"></i>
                                </div>
                                <div>
                                    <div style="font-size: 1.5rem; font-weight: 700; color: #1e293b;">{{ $pendingListings->count() }}</div>
                                    <div style="color: #64748b; font-size: 0.85rem;">Pending Bath Listings</div>
                                </div>
                            </div>
                            @if($pendingListings->count() > 0)
                                <a href="#" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> Review Listings
                                </a>
                            @else
                                <p class="text-muted mb-0 small">All listings approved</p>
                            @endif
                        </div>
                    </div>

                    <!-- Pending Services -->
                    <div class="col-md-6">
                        <div class="border rounded p-3">
                            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                                <div style="width: 50px; height: 50px; border-radius: 8px; background: rgba(239, 68, 68, 0.1); display: flex; align-items: center; justify-content: center; color: #ef4444; font-size: 1.5rem;">
                                    <i class="fas fa-spa"></i>
                                </div>
                                <div>
                                    <div style="font-size: 1.5rem; font-weight: 700; color: #1e293b;">{{ $pendingServices->count() }}</div>
                                    <div style="color: #64748b; font-size: 0.85rem;">Pending Services</div>
                                </div>
                            </div>
                            @if($pendingServices->count() > 0)
                                <a href="#" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> Review Services
                                </a>
                            @else
                                <p class="text-muted mb-0 small">All services approved</p>
                            @endif
                        </div>
                    </div>

                    <!-- Commission -->
                    <div class="col-md-6">
                        <div class="border rounded p-3">
                            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                                <div style="width: 50px; height: 50px; border-radius: 8px; background: rgba(37, 99, 235, 0.1); display: flex; align-items: center; justify-content: center; color: #2563eb; font-size: 1.5rem;">
                                    <i class="fas fa-percentage"></i>
                                </div>
                                <div>
                                    <div style="font-size: 1.5rem; font-weight: 700; color: #1e293b;">Nu. {{ number_format($monthlyCommission ?? 0, 0) }}</div>
                                    <div style="color: #64748b; font-size: 0.85rem;">Monthly Commission</div>
                                </div>
                            </div>
                            <small class="text-muted">(15% of monthly revenue)</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Quick Stats</h5>
            </div>
            <div class="card-body">
                <div class="mb-3 pb-3" style="border-bottom: 1px solid #e2e8f0;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: #64748b;"><i class="fas fa-check-circle text-success"></i> Active Listings</span>
                        <span style="font-weight: 700; font-size: 1.25rem;">{{ $stats['active_listings'] ?? 0 }}</span>
                    </div>
                </div>
                <div class="mb-3 pb-3" style="border-bottom: 1px solid #e2e8f0;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: #64748b;"><i class="fas fa-money-bill-wave"></i> Total Revenue</span>
                        <span style="font-weight: 700; font-size: 1.25rem;">Nu. {{ number_format($totalRevenue ?? 0, 0) }}</span>
                    </div>
                </div>
                <div class="mb-3">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: #64748b;"><i class="fas fa-percentage text-primary"></i> Commission</span>
                        <span style="font-weight: 700; font-size: 1.25rem; color: #2563eb;">Nu. {{ number_format($totalCommission ?? 0, 0) }}</span>
                    </div>
                </div>
                <a href="{{ route('admin.revenue') }}" class="btn btn-sm btn-primary w-100">
                    <i class="fas fa-chart-bar"></i> View Revenue Report
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Owner Verification Table -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user-check"></i> Owner Verification</h5>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Owner Name</th>
                            <th>Email</th>
                            <th>Bath</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingOwners as $owner)
                            <tr>
                                <td><strong>{{ $owner->name }}</strong></td>
                                <td>{{ $owner->email }}</td>
                                <td>{{ optional($owner->baths->first())->name ?? 'N/A' }}</td>
                                <td><span class="badge bg-warning text-dark">PENDING</span></td>
                                <td>
                                    <div class="d-flex gap-2" style="flex-wrap: wrap;">
                                        <form method="POST" action="{{ route('admin.owner.approve', $owner) }}" style="display: inline;">
                                            @csrf
                                            <button class="btn btn-sm btn-success"><i class="fas fa-check"></i> Approve</button>
                                        </form>
                                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $owner->id }}">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    </div>

                                    <!-- Reject Modal -->
                                    <div class="modal fade" id="rejectModal{{ $owner->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Reject Owner</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST" action="{{ route('admin.owner.reject', $owner) }}">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Rejection Reason</label>
                                                            <textarea name="reason" class="form-control" rows="4" required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger">Reject Owner</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="fas fa-check-circle"></i> No pending owner approvals
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Bath Listings Table -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-bath"></i> Bath Listing Verification</h5>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Bath Name</th>
                            <th>Owner</th>
                            <th>Location</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingListings as $listing)
                            <tr>
                                <td><strong>{{ $listing->name }}</strong></td>
                                <td>{{ optional($listing->owner)->name }}</td>
                                <td>{{ optional($listing->dzongkhag)->name }}</td>
                                <td>Nu. {{ number_format((float)($listing->price_per_session ?? $listing->price_per_hour), 2) }}</td>
                                <td><span class="badge bg-warning text-dark">PENDING</span></td>
                                <td>
                                    <div class="d-flex gap-2">
                                       <form method="POST" action="{{ route('admin.listing.status', $listing) }}" style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="status" value="active">
                                            <button class="btn btn-sm btn-success"><i class="fas fa-check"></i> Approve</button>
                                        </form>
                                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectListingModal{{ $listing->id }}">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    </div>

                                    <!-- Reject Modal -->
                                    <div class="modal fade" id="rejectListingModal{{ $listing->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Reject Listing</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST" action="{{ route('admin.listing.status', $listing) }}">
                                                    @csrf
                                                    <input type="hidden" name="status" value="suspended">
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Notes</label>
                                                            <textarea name="notes" class="form-control" rows="4"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger">Reject Listing</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="fas fa-check-circle"></i> No pending bath listings
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Recent Transactions -->
<div class="row g-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                <h5 class="mb-0"><i class="fas fa-receipt"></i> Recent Transactions</h5>
                <button class="btn btn-sm btn-primary" onclick="refreshTransactions()">
                    <i class="fas fa-sync"></i> Refresh
                </button>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>User</th>
                            <th>Booking ID</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody id="transactionsTableBody">
                        @forelse($transactions as $transaction)
                            <tr>
                                <td><code style="font-size: 0.8rem; background: #f1f5f9; padding: 0.25rem 0.5rem; border-radius: 3px;">{{ substr($transaction['transaction_id'], 0, 12) }}...</code></td>
                                <td>{{ $transaction['user_name'] }}</td>
                                <td>#{{ $transaction['booking_id'] }}</td>
                                <td><strong>Nu. {{ number_format($transaction['amount'], 2) }}</strong></td>
                                <td>{{ ucfirst(str_replace('_', ' ', $transaction['payment_method'])) }}</td>
                                <td>
                                    @if($transaction['status'] === 'completed')
                                        <span class="badge bg-success">Completed</span>
                                    @elseif($transaction['status'] === 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @else
                                        <span class="badge bg-danger">{{ ucfirst($transaction['status']) }}</span>
                                    @endif
                                </td>
                                <td><small>{{ $transaction['date'] }}</small></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox"></i> No transactions found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function refreshTransactions() {
        location.reload();
    }
</script>
@endsection
