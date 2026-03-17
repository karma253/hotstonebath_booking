<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Hot Stone Bath</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            color: #333;
        }

        header {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 1.5rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-content h1 {
            font-size: 1.5rem;
        }

        .logout-btn {
            background: white;
            color: #f5576c;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
        }

        .logout-btn:hover {
            background: #f0f0f0;
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 20px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .stat-card h3 {
            color: #f5576c;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
        }

        .stat-card .number {
            font-size: 2rem;
            color: #333;
            font-weight: bold;
        }

        .section {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .section h2 {
            color: #f5576c;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid #f5576c;
            padding-bottom: 0.5rem;
        }

        .action-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin: 1rem 0;
        }

        .btn {
            background: #f5576c;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: background 0.3s;
        }

        .btn:hover {
            background: #da3651;
        }

        .btn-secondary {
            background: #667eea;
        }

        .btn-secondary:hover {
            background: #764ba2;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        table th {
            background: #f5f5f5;
            padding: 1rem;
            text-align: left;
            font-weight: bold;
            border-bottom: 2px solid #ddd;
        }

        table td {
            padding: 1rem;
            border-bottom: 1px solid #eee;
        }

        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: bold;
        }

        .status-approved {
            background: #d4edda;
            color: #155724;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-rejected {
            background: #f8d7da;
            color: #721c24;
        }

        .status-success {
            background: #d4edda;
            color: #155724;
        }

        .status-failed {
            background: #f8d7da;
            color: #721c24;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .demo-notice {
            background: #ffeaea;
            border: 1px solid #ffb8b8;
            color: #842029;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
        }

        .demo-notice strong {
            color: #5c1419;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <h1>🔐 Admin Dashboard</h1>
            <a href="/?logout=true" class="logout-btn">Logout</a>
        </div>
    </header>

    <div class="container">
        <div class="demo-notice">
            <strong>Demo Mode:</strong> You are logged in as an admin. This dashboard allows you to verify owners, manage the platform, and view system statistics.
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Pending Owners</h3>
                <div class="number">5</div>
            </div>
            <div class="stat-card">
                <h3>Active Owners</h3>
                <div class="number">28</div>
            </div>
            <div class="stat-card">
                <h3>Total Baths</h3>
                <div class="number">52</div>
            </div>
            <div class="stat-card">
                <h3>Total Bookings</h3>
                <div class="number">342</div>
            </div>
        </div>

        <div class="section">
            <h2>Pending Owner Verification</h2>
            <p>Review and approve/reject new owner registrations and uploaded documents.</p>

            <table>
                <thead>
                    <tr>
                        <th>Owner Name</th>
                        <th>Email</th>
                        <th>Property</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Tenzin Dorji</td>
                        <td>tenzin@example.com</td>
                        <td>Paro Hot Springs</td>
                        <td><span class="status-badge status-pending">Pending Review</span></td>
                        <td><button class="btn" style="font-size: 0.8rem; padding: 0.5rem 1rem;">Review</button></td>
                    </tr>
                    <tr>
                        <td>Sonam Tshering</td>
                        <td>sonam@example.com</td>
                        <td>Thimphu Bath House</td>
                        <td><span class="status-badge status-pending">Pending Review</span></td>
                        <td><button class="btn" style="font-size: 0.8rem; padding: 0.5rem 1rem;">Review</button></td>
                    </tr>
                </tbody>
            </table>

            <div class="action-buttons" style="margin-top: 1.5rem;">
                <button class="btn">View All Pending</button>
                <button class="btn btn-secondary">Approved Owners</button>
            </div>
        </div>

        <div class="section">
            <h2>System Management</h2>
            <p>Monitor and manage the overall platform.</p>

            <div class="action-buttons">
                <button class="btn">View All Users</button>
                <button class="btn">View All Baths</button>
                <button class="btn">View All Bookings</button>
                <button class="btn">System Statistics</button>
            </div>
        </div>

        <div class="section">
            <h2>🏨 Bath Listing Review - Recent Transactions</h2>
            <p>Last 10 payment transactions across all bath listings.</p>

            <table>
                <thead>
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
                <tbody id="bathListingTransactionsTableBody">
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2rem; color: #999;">
                            Loading transactions...
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="action-buttons" style="margin-top: 1.5rem;">
                <button class="btn" onclick="refreshBathTransactions()">Refresh Transactions</button>
                <button class="btn btn-secondary" onclick="exportBathTransactions()">Export Report</button>
            </div>
        </div>

        <div class="section">
            <h2>Recent Activity</h2>
            <p>Latest events on the platform.</p>

            <div style="background: #f9f9f9; padding: 1rem; border-radius: 5px; margin-top: 1rem;">
                <p>✓ <strong>2 minutes ago</strong> - New owner registration from Tenzin Dorji</p>
                <p>✓ <strong>5 minutes ago</strong> - Booking #BK342 completed successfully</p>
                <p>✓ <strong>15 minutes ago</strong> - Owner "Happy Hot Springs" approved their bath documents</p>
                <p>✓ <strong>1 hour ago</strong> - New review posted (4.8 stars) for Paro Bath House</p>
            </div>
        </div>

        <div class="section">
            <h2>💳 Recent Transactions</h2>
            <p>Payment transactions from guest bookings.</p>

            <table>
                <thead>
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
                        <td colspan="7" style="text-align: center; padding: 2rem; color: #999;">
                            Loading transactions...
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="action-buttons" style="margin-top: 1.5rem;">
                <button class="btn" onclick="viewAllTransactions()">View All Transactions</button>
                <button class="btn btn-secondary" onclick="filterTransactions()">Filter Transactions</button>
            </div>
        </div>

        <style>
            .status-success {
                background: #d4edda;
                color: #155724;
            }

            .status-failed {
                background: #f8d7da;
                color: #721c24;
            }

            .status-pending-payment {
                background: #fff3cd;
                color: #856404;
            }

            .transaction-amount {
                font-weight: bold;
                color: #f5576c;
            }
        </style>

        <script>
            // Load recent transactions on page load
            document.addEventListener('DOMContentLoaded', function() {
                loadRecentTransactions();
                loadBathListingTransactions();
            });

            async function loadRecentTransactions() {
                try {
                    // Note: This would need proper authentication in production
                    // For now, showing mock data structure with 10 transactions
                    const mockTransactions = [
                        {
                            transaction_id: 'TXN20260317142530123456',
                            user_name: 'Karma Tenzin',
                            booking_id: 'BOOKING-20260317-00001',
                            payment_method: 'MBoB',
                            amount: '500.00',
                            status: 'success',
                            date: 'Mar 17, 2026 14:25'
                        },
                        {
                            transaction_id: 'TXN20260317141230567890',
                            user_name: 'Sonam Dorji',
                            booking_id: 'BOOKING-20260317-00002',
                            payment_method: 'MPay',
                            amount: '750.00',
                            status: 'success',
                            date: 'Mar 17, 2026 14:12'
                        },
                        {
                            transaction_id: 'TXN20260317140030234567',
                            user_name: 'Tenzin Wangchuk',
                            booking_id: 'BOOKING-20260317-00003',
                            payment_method: 'BDBL',
                            amount: '400.00',
                            status: 'failed',
                            date: 'Mar 17, 2026 14:00'
                        },
                        {
                            transaction_id: 'TXN20260317135530789012',
                            user_name: 'Pema Yangki',
                            booking_id: 'BOOKING-20260317-00004',
                            payment_method: 'cash',
                            amount: '600.00',
                            status: 'success',
                            date: 'Mar 17, 2026 13:55'
                        },
                        {
                            transaction_id: 'TXN20260317135030345678',
                            user_name: 'Jamba Tharchen',
                            booking_id: 'BOOKING-20260317-00005',
                            payment_method: 'MBoB',
                            amount: '550.00',
                            status: 'success',
                            date: 'Mar 17, 2026 13:50'
                        },
                        {
                            transaction_id: 'TXN20260317134530901234',
                            user_name: 'Dawa Phuentsog',
                            booking_id: 'BOOKING-20260317-00006',
                            payment_method: 'MPay',
                            amount: '650.00',
                            status: 'success',
                            date: 'Mar 17, 2026 13:45'
                        },
                        {
                            transaction_id: 'TXN20260317134030567890',
                            user_name: 'Tenzin Choden',
                            booking_id: 'BOOKING-20260317-00007',
                            payment_method: 'BDBL',
                            amount: '800.00',
                            status: 'success',
                            date: 'Mar 17, 2026 13:40'
                        },
                        {
                            transaction_id: 'TXN20260317133530234567',
                            user_name: 'Sonam Wangdi',
                            booking_id: 'BOOKING-20260317-00008',
                            payment_method: 'MBoB',
                            amount: '525.00',
                            status: 'failed',
                            date: 'Mar 17, 2026 13:35'
                        },
                        {
                            transaction_id: 'TXN20260317133030890123',
                            user_name: 'Pema Dorji',
                            booking_id: 'BOOKING-20260317-00009',
                            payment_method: 'cash',
                            amount: '700.00',
                            status: 'success',
                            date: 'Mar 17, 2026 13:30'
                        },
                        {
                            transaction_id: 'TXN20260317132530456789',
                            user_name: 'Bhim Prasad',
                            booking_id: 'BOOKING-20260317-00010',
                            payment_method: 'MPay',
                            amount: '475.00',
                            status: 'success',
                            date: 'Mar 17, 2026 13:25'
                        }
                    ];

                    displayTransactions(mockTransactions);
                } catch (error) {
                    console.error('Error loading transactions:', error);
                }
            }

            function loadBathListingTransactions() {
                try {
                    // Same mock data for Bath Listing Review
                    const mockTransactions = [
                        {
                            transaction_id: 'TXN20260317142530123456',
                            user_name: 'Karma Tenzin',
                            booking_id: 'BOOKING-20260317-00001',
                            payment_method: 'MBoB',
                            amount: '500.00',
                            status: 'success',
                            date: 'Mar 17, 2026 14:25'
                        },
                        {
                            transaction_id: 'TXN20260317141230567890',
                            user_name: 'Sonam Dorji',
                            booking_id: 'BOOKING-20260317-00002',
                            payment_method: 'MPay',
                            amount: '750.00',
                            status: 'success',
                            date: 'Mar 17, 2026 14:12'
                        },
                        {
                            transaction_id: 'TXN20260317140030234567',
                            user_name: 'Tenzin Wangchuk',
                            booking_id: 'BOOKING-20260317-00003',
                            payment_method: 'BDBL',
                            amount: '400.00',
                            status: 'failed',
                            date: 'Mar 17, 2026 14:00'
                        },
                        {
                            transaction_id: 'TXN20260317135530789012',
                            user_name: 'Pema Yangki',
                            booking_id: 'BOOKING-20260317-00004',
                            payment_method: 'cash',
                            amount: '600.00',
                            status: 'success',
                            date: 'Mar 17, 2026 13:55'
                        },
                        {
                            transaction_id: 'TXN20260317135030345678',
                            user_name: 'Jamba Tharchen',
                            booking_id: 'BOOKING-20260317-00005',
                            payment_method: 'MBoB',
                            amount: '550.00',
                            status: 'success',
                            date: 'Mar 17, 2026 13:50'
                        },
                        {
                            transaction_id: 'TXN20260317134530901234',
                            user_name: 'Dawa Phuentsog',
                            booking_id: 'BOOKING-20260317-00006',
                            payment_method: 'MPay',
                            amount: '650.00',
                            status: 'success',
                            date: 'Mar 17, 2026 13:45'
                        },
                        {
                            transaction_id: 'TXN20260317134030567890',
                            user_name: 'Tenzin Choden',
                            booking_id: 'BOOKING-20260317-00007',
                            payment_method: 'BDBL',
                            amount: '800.00',
                            status: 'success',
                            date: 'Mar 17, 2026 13:40'
                        },
                        {
                            transaction_id: 'TXN20260317133530234567',
                            user_name: 'Sonam Wangdi',
                            booking_id: 'BOOKING-20260317-00008',
                            payment_method: 'MBoB',
                            amount: '525.00',
                            status: 'failed',
                            date: 'Mar 17, 2026 13:35'
                        },
                        {
                            transaction_id: 'TXN20260317133030890123',
                            user_name: 'Pema Dorji',
                            booking_id: 'BOOKING-20260317-00009',
                            payment_method: 'cash',
                            amount: '700.00',
                            status: 'success',
                            date: 'Mar 17, 2026 13:30'
                        },
                        {
                            transaction_id: 'TXN20260317132530456789',
                            user_name: 'Bhim Prasad',
                            booking_id: 'BOOKING-20260317-00010',
                            payment_method: 'MPay',
                            amount: '475.00',
                            status: 'success',
                            date: 'Mar 17, 2026 13:25'
                        }
                    ];

                    displayBathListingTransactions(mockTransactions);
                } catch (error) {
                    console.error('Error loading bath listing transactions:', error);
                }
            }

            function displayTransactions(transactions) {
                const tbody = document.getElementById('transactionsTableBody');
                
                if (!transactions || transactions.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 2rem; color: #999;">
                                No transactions found
                            </td>
                        </tr>
                    `;
                    return;
                }

                tbody.innerHTML = transactions.map(txn => `
                    <tr>
                        <td><code style="background: #f5f5f5; padding: 0.25rem 0.5rem; border-radius: 3px;">${txn.transaction_id}</code></td>
                        <td>${txn.user_name}</td>
                        <td>${txn.booking_id}</td>
                        <td>${txn.payment_method}</td>
                        <td class="transaction-amount">Nu. ${parseFloat(txn.amount).toFixed(2)}</td>
                        <td><span class="status-badge status-${txn.status}">${txn.status.charAt(0).toUpperCase() + txn.status.slice(1)}</span></td>
                        <td><small>${txn.date}</small></td>
                    </tr>
                `).join('');
            }

            function displayBathListingTransactions(transactions) {
                const tableBody = document.getElementById('bathListingTransactionsTableBody');
                if (!tableBody) return;

                if (!transactions || transactions.length === 0) {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 2rem; color: #999;">
                                No transactions found
                            </td>
                        </tr>
                    `;
                    return;
                }

                tableBody.innerHTML = transactions.map(txn => `
                    <tr>
                        <td><code style="background: #f5f5f5; padding: 0.25rem 0.5rem; border-radius: 3px;">${txn.transaction_id}</code></td>
                        <td>${txn.user_name}</td>
                        <td>${txn.booking_id}</td>
                        <td>${txn.payment_method}</td>
                        <td class="transaction-amount">Nu. ${parseFloat(txn.amount).toFixed(2)}</td>
                        <td><span class="status-badge status-${txn.status}">${txn.status.charAt(0).toUpperCase() + txn.status.slice(1)}</span></td>
                        <td><small>${txn.date}</small></td>
                    </tr>
                `).join('');
            }

            function refreshBathTransactions() {
                console.log('Refreshing bath listing transactions...');
                const tableBody = document.getElementById('bathListingTransactionsTableBody');
                tableBody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 2rem;">Refreshing...</td></tr>';
                
                // Simulate API call with a small delay
                setTimeout(() => {
                    loadBathListingTransactions();
                }, 500);
            }

            function exportBathTransactions() {
                console.log('Exporting bath listing transactions...');
                
                // Get the transactions data
                const mockTransactions = [
                    { transaction_id: 'TXN20260317142530123456', user_name: 'Karma Tenzin', booking_id: 'BOOKING-20260317-00001', payment_method: 'MBoB', amount: '500.00', status: 'success', date: 'Mar 17, 2026 14:25' },
                    { transaction_id: 'TXN20260317141230567890', user_name: 'Sonam Dorji', booking_id: 'BOOKING-20260317-00002', payment_method: 'MPay', amount: '750.00', status: 'success', date: 'Mar 17, 2026 14:12' },
                    { transaction_id: 'TXN20260317140030234567', user_name: 'Tenzin Wangchuk', booking_id: 'BOOKING-20260317-00003', payment_method: 'BDBL', amount: '400.00', status: 'failed', date: 'Mar 17, 2026 14:00' },
                    { transaction_id: 'TXN20260317135530789012', user_name: 'Pema Yangki', booking_id: 'BOOKING-20260317-00004', payment_method: 'cash', amount: '600.00', status: 'success', date: 'Mar 17, 2026 13:55' },
                    { transaction_id: 'TXN20260317135030345678', user_name: 'Jamba Tharchen', booking_id: 'BOOKING-20260317-00005', payment_method: 'MBoB', amount: '550.00', status: 'success', date: 'Mar 17, 2026 13:50' },
                    { transaction_id: 'TXN20260317134530901234', user_name: 'Dawa Phuentsog', booking_id: 'BOOKING-20260317-00006', payment_method: 'MPay', amount: '650.00', status: 'success', date: 'Mar 17, 2026 13:45' },
                    { transaction_id: 'TXN20260317134030567890', user_name: 'Tenzin Choden', booking_id: 'BOOKING-20260317-00007', payment_method: 'BDBL', amount: '800.00', status: 'success', date: 'Mar 17, 2026 13:40' },
                    { transaction_id: 'TXN20260317133530234567', user_name: 'Sonam Wangdi', booking_id: 'BOOKING-20260317-00008', payment_method: 'MBoB', amount: '525.00', status: 'failed', date: 'Mar 17, 2026 13:35' },
                    { transaction_id: 'TXN20260317133030890123', user_name: 'Pema Dorji', booking_id: 'BOOKING-20260317-00009', payment_method: 'cash', amount: '700.00', status: 'success', date: 'Mar 17, 2026 13:30' },
                    { transaction_id: 'TXN20260317132530456789', user_name: 'Bhim Prasad', booking_id: 'BOOKING-20260317-00010', payment_method: 'MPay', amount: '475.00', status: 'success', date: 'Mar 17, 2026 13:25' }
                ];

                // Create CSV content
                let csvContent = 'Transaction ID,Guest Name,Booking ID,Payment Method,Amount,Status,Date\n';
                mockTransactions.forEach(txn => {
                    csvContent += `"${txn.transaction_id}","${txn.user_name}","${txn.booking_id}","${txn.payment_method}","Nu. ${txn.amount}","${txn.status}","${txn.date}"\n`;
                });

                // Create blob and download
                const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                const link = document.createElement('a');
                const url = URL.createObjectURL(blob);
                
                link.setAttribute('href', url);
                link.setAttribute('download', 'bath-transactions-' + new Date().toISOString().split('T')[0] + '.csv');
                link.style.visibility = 'hidden';
                
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                
                alert('Transaction report exported successfully!');
            }

            function viewAllTransactions() {
                alert('View All Transactions - This would link to a detailed transaction management page');
            }

            function filterTransactions() {
                const status = prompt('Filter by status (success/failed/pending):', 'success');
                if (status) {
                    alert('Filtering transactions by status: ' + status);
                }
            }
        </script>
    </div>
</body>
</html>
