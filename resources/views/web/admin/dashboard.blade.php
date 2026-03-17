@extends('web.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Admin Dashboard</h1>
    <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button class="btn btn-dark">Logout</button>
    </form>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card card-shadow rounded-4"><div class="card-body"><div class="text-muted">Customers</div><div class="h4 mb-0">{{ $stats['customers'] }}</div></div></div>
    </div>
    <div class="col-md-3">
        <div class="card card-shadow rounded-4"><div class="card-body"><div class="text-muted">Owners</div><div class="h4 mb-0">{{ $stats['owners'] }}</div></div></div>
    </div>
    <div class="col-md-3">
        <div class="card card-shadow rounded-4"><div class="card-body"><div class="text-muted">Bookings</div><div class="h4 mb-0">{{ $stats['bookings'] }}</div></div></div>
    </div>
    <div class="col-md-3">
        <div class="card card-shadow rounded-4"><div class="card-body"><div class="text-muted">Active Listings</div><div class="h4 mb-0">{{ $stats['active_listings'] }}</div></div></div>
    </div>
</div>

<div class="card card-shadow rounded-4 mb-4">
    <div class="card-header bg-white"><h2 class="h5 mb-0">Owner Verification</h2></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light"><tr><th>Owner</th><th>Email</th><th>Bath</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                @forelse($pendingOwners as $owner)
                    <tr>
                        <td>{{ $owner->name }}</td>
                        <td>{{ $owner->email }}</td>
                        <td>{{ optional($owner->baths->first())->name ?? 'N/A' }}</td>
                        <td><span class="badge bg-warning text-dark">PENDING</span></td>
                        <td>
                            <div class="d-flex gap-2 flex-wrap">
                                <form method="POST" action="{{ route('admin.owner.approve', $owner) }}">@csrf<button class="btn btn-sm btn-success">Approve</button></form>
                                <form method="POST" action="{{ route('admin.owner.reject', $owner) }}" class="d-flex gap-1">
                                    @csrf
                                    <input type="text" name="reason" class="form-control form-control-sm" placeholder="Reason" required>
                                    <button class="btn btn-sm btn-danger">Reject</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-4 text-muted">No pending owner requests.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card card-shadow rounded-4">
    <div class="card-header bg-white"><h2 class="h5 mb-0">Bath Listing Review</h2></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light"><tr><th>Bath Name</th><th>Owner</th><th>Location</th><th>Price</th><th>Actions</th></tr></thead>
                <tbody>
                @forelse($pendingListings as $listing)
                    <tr>
                        <td>{{ $listing->name }}</td>
                        <td>{{ optional($listing->owner)->name }}</td>
                        <td>{{ optional($listing->dzongkhag)->name }}</td>
                        <td>Nu. {{ number_format((float)($listing->price_per_session ?? $listing->price_per_hour), 2) }}</td>
                        <td>
                            <div class="d-flex gap-2 flex-wrap">
                                <form method="POST" action="{{ route('admin.listing.status', $listing) }}" class="d-flex gap-1">
                                    @csrf
                                    <input type="hidden" name="status" value="active">
                                    <button class="btn btn-sm btn-success">Approve</button>
                                </form>
                                <form method="POST" action="{{ route('admin.listing.status', $listing) }}" class="d-flex gap-1">
                                    @csrf
                                    <input type="hidden" name="status" value="suspended">
                                    <input type="text" name="notes" class="form-control form-control-sm" placeholder="Rejection notes">
                                    <button class="btn btn-sm btn-danger">Reject</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-4 text-muted">No pending bath listings.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card card-shadow rounded-4 mt-4">
    <div class="card-header bg-white"><h2 class="h5 mb-0">💳 Recent Transactions - Last 10</h2></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Transaction ID</th>
                        <th>Guest Name</th>
                        <th>Booking ID</th>
                        <th>Payment Method</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody id="transactionsTableBody">
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Loading transactions...</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white">
            <button class="btn btn-sm btn-primary" onclick="refreshTransactions()">Refresh Transactions</button>
            <button class="btn btn-sm btn-secondary" onclick="exportTransactionsCSV()">Export Report</button>
        </div>
    </div>
</div>

<style>
    .status-success {
        background-color: #d4edda;
        color: #155724;
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-weight: bold;
        font-size: 0.85rem;
    }

    .status-failed {
        background-color: #f8d7da;
        color: #721c24;
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-weight: bold;
        font-size: 0.85rem;
    }

    .status-pending {
        background-color: #fff3cd;
        color: #856404;
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-weight: bold;
        font-size: 0.85rem;
    }

    .transaction-id {
        font-family: 'Courier New', monospace;
        background-color: #f5f5f5;
        padding: 0.25rem 0.5rem;
        border-radius: 3px;
        font-size: 0.9rem;
    }
</style>

<script>
    let allTransactions = [];
    let autoRefreshInterval = null;
    const serverTransactions = @json($transactions ?? []);

    // Load transactions on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Display server transactions immediately (even if empty)
        if (serverTransactions && serverTransactions.length > 0) {
            console.log('Displaying server transactions:', serverTransactions.length);
            displayTransactions(serverTransactions);
        } else {
            console.log('No server transactions, showing "No transactions" message');
            // Show "No transactions" message while loading
            const tableBody = document.getElementById('transactionsTableBody');
            tableBody.innerHTML = '<tr><td colspan="7" class="text-center py-4 text-muted">No transactions found in the system yet.</td></tr>';
        }
        
        // Then fetch fresh data from API
        loadTransactions();
        
        // Setup auto-refresh every 30 seconds
        startAutoRefresh();
    });

    function loadTransactions() {
        // Fetch transactions from web endpoint
        fetch('/admin/transactions/recent', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Transaction data received:', data);
            if (data && data.success && data.data && Array.isArray(data.data)) {
                allTransactions = data.data;
                displayTransactions(allTransactions);
            } else {
                console.warn('Unexpected data format:', data);
                if (serverTransactions && serverTransactions.length > 0) {
                    displayTransactions(serverTransactions);
                } else {
                    displayTransactions([]);
                }
            }
        })
        .catch(error => {
            console.error('Error fetching transactions:', error);
            // Use server-passed transactions on error
            if (serverTransactions && serverTransactions.length > 0) {
                displayTransactions(serverTransactions);
            } else {
                displayTransactions([]);
            }
        });
    }

    function displayTransactions(transactions) {
        const tableBody = document.getElementById('transactionsTableBody');
        
        if (!transactions || transactions.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="7" class="text-center py-4 text-muted">No transactions found in the system yet.</td></tr>';
            return;
        }

        tableBody.innerHTML = transactions.map(txn => {
            // Use date formatted by controller or format here
            const displayDate = txn.date || new Date(txn.created_at).toLocaleDateString('en-US', { 
                month: 'short', 
                day: 'numeric', 
                year: 'numeric'
            });
            
            return `
                <tr>
                    <td><span class="transaction-id">${txn.transaction_id}</span></td>
                    <td>${txn.user_name || 'N/A'}</td>
                    <td>${txn.booking_id || 'N/A'}</td>
                    <td>${txn.payment_method}</td>
                    <td><strong>Nu. ${parseFloat(txn.amount).toFixed(2)}</strong></td>
                    <td><span class="status-${txn.status}">${txn.status.charAt(0).toUpperCase() + txn.status.slice(1)}</span></td>
                    <td><small>${displayDate}</small></td>
                </tr>
            `;
        }).join('');
    }

    function refreshTransactions() {
        const tableBody = document.getElementById('transactionsTableBody');
        tableBody.innerHTML = '<tr><td colspan="7" class="text-center py-4 text-muted">Refreshing...</td></tr>';
        
        setTimeout(() => {
            loadTransactions();
        }, 800);
    }

    function startAutoRefresh() {
        // Auto-refresh transactions every 30 seconds
        if (autoRefreshInterval) {
            clearInterval(autoRefreshInterval);
        }
        autoRefreshInterval = setInterval(() => {
            loadTransactions();
        }, 30000); // 30 seconds
    }

    function stopAutoRefresh() {
        if (autoRefreshInterval) {
            clearInterval(autoRefreshInterval);
            autoRefreshInterval = null;
        }
    }

    // Stop auto-refresh when user leaves page
    window.addEventListener('beforeunload', () => {
        stopAutoRefresh();
    });

    function exportTransactionsCSV() {
        if (!allTransactions || allTransactions.length === 0) {
            alert('No transactions to export');
            return;
        }

        let csvContent = 'Transaction ID,Guest Name,Booking ID,Payment Method,Amount,Status,Date\n';
        allTransactions.forEach(txn => {
            const displayDate = txn.date || new Date(txn.created_at).toLocaleDateString('en-US', { 
                month: 'short', 
                day: 'numeric', 
                year: 'numeric'
            });
            
            csvContent += `"${txn.transaction_id}","${txn.user_name || 'N/A'}","${txn.booking_id || 'N/A'}","${txn.payment_method}","Nu. ${txn.amount}","${txn.status}","${displayDate}"\n`;
        });

        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        
        link.setAttribute('href', url);
        link.setAttribute('download', 'transactions-' + new Date().toISOString().split('T')[0] + '.csv');
        link.style.visibility = 'hidden';
        
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        alert('Transaction report exported successfully! Total: ' + allTransactions.length);
    }
</script>
@endsection
