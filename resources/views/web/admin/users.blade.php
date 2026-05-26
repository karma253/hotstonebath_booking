@extends('web.layouts.app')

@section('title', 'User Management - Admin')

@section('content')
<div class="container-fluid mt-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-1">👥 User Management</h1>
            <p class="text-muted">Approve, reject, or manage user accounts</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

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

    <!-- Filters -->
    <div class="card card-shadow rounded-4 mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">🔍 Filters</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.users') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Search (Name/Email)</label>
                    <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request()->input('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select">
                        <option value="">All Roles</option>
                        <option value="guest" {{ request()->input('role') === 'guest' ? 'selected' : '' }}>Guest/Customer</option>
                        <option value="owner" {{ request()->input('role') === 'owner' ? 'selected' : '' }}>Owner</option>
                        <option value="admin" {{ request()->input('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="pending_verification" {{ request()->input('status') === 'pending_verification' ? 'selected' : '' }}>Pending</option>
                        <option value="active" {{ request()->input('status') === 'active' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request()->input('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card card-shadow rounded-4">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Users List ({{ $users->total() }} total)</h5>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Registered</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    <strong>{{ $user->name }}</strong>
                                </td>
                                <td>
                                    <small>{{ $user->email }}</small>
                                </td>
                                <td>{{ $user->phone }}</td>
                                <td>
                                    @if($user->role === 'admin')
                                        <span class="badge bg-danger">
                                            <i class="fas fa-crown"></i> Admin
                                        </span>
                                    @elseif($user->role === 'owner')
                                        <span class="badge bg-info">
                                            <i class="fas fa-user-tie"></i> Owner
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-user"></i> Guest
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->status === 'pending_verification')
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-hourglass-half"></i> Pending
                                        </span>
                                    @elseif($user->status === 'active' || $user->status === 'approved')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle"></i> Approved
                                        </span>
                                    @elseif($user->status === 'rejected')
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times-circle"></i> Rejected
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">{{ $user->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ $user->created_at->format('M d, Y') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        @if($user->status === 'pending_verification')
                                            <form method="POST" action="{{ route('admin.user.approve', $user) }}" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm" title="Approve">
                                                    <i class="fas fa-check"></i> Approve
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-danger btn-sm" title="Reject" onclick="showRejectModal({{ $user->id }}, '{{ $user->name }}')">
                                                <i class="fas fa-times"></i> Reject
                                            </button>
                                        @elseif($user->status === 'rejected')
                                            <form method="POST" action="{{ route('admin.user.approve', $user) }}" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm" title="Approve">
                                                    <i class="fas fa-check"></i> Re-approve
                                                </button>
                                            </form>
                                        @else
                                            <button type="button" class="btn btn-danger btn-sm" title="Reject" onclick="showRejectModal({{ $user->id }}, '{{ $user->name }}')">
                                                <i class="fas fa-times"></i> Reject
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox" style="font-size: 2rem;"></i>
                                    <p>No users found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
</div>

<style>
    .card-shadow {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .rounded-4 {
        border-radius: 12px;
    }

    .btn-group-sm .btn {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }
</style>

<script>
    function showRejectModal(userId, userName) {
        const reason = prompt(`Enter rejection reason for ${userName}:`, '');
        if (reason !== null && reason.trim() !== '') {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/users/${userId}/reject`;
            form.innerHTML = `
                @csrf
                <input type="hidden" name="reason" value="${reason}">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endsection
