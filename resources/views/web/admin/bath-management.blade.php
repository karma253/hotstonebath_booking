@extends('layouts.admin-layout')

@section('title', 'Bath Management')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">🛁 Bath Management</h1>
    </div>

    <!-- Filters -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.baths') }}" class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Search by name..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="type" class="form-select">
                        <option value="">All Types</option>
                        @foreach(['menchu' => 'Menchu', 'dotsho' => 'Dotsho', 'tshachu' => 'Tshachu'] as $value => $label)
                            <option value="{{ $value }}" {{ request('type') === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="pending_verification" {{ request('status') === 'pending_verification' ? 'selected' : '' }}>Pending</option>
                        <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="owner_id" class="form-select">
                        <option value="">All Owners</option>
                        @foreach($owners as $owner)
                            <option value="{{ $owner->id }}" {{ request('owner_id') == $owner->id ? 'selected' : '' }}>{{ $owner->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="dzongkhag_id" class="form-select">
                        <option value="">All Locations</option>
                        @foreach($dzongkhags as $dzongkhag)
                            <option value="{{ $dzongkhag->id }}" {{ request('dzongkhag_id') == $dzongkhag->id ? 'selected' : '' }}>{{ $dzongkhag->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">Search</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Total Baths</div>
                    <h4 class="mb-0">{{ $baths->total() }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Active</div>
                    <h4 class="mb-0">{{ \App\Models\Bath::where('status', 'active')->count() }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Pending Verification</div>
                    <h4 class="mb-0">{{ \App\Models\Bath::where('status', 'pending_verification')->count() }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Suspended</div>
                    <h4 class="mb-0">{{ \App\Models\Bath::where('status', 'suspended')->count() }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Baths Table -->
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Bath Name</th>
                        <th>Owner</th>
                        <th>Type</th>
                        <th>Location</th>
                        <th>Price/Session</th>
                        <th>Max Guests</th>
                        <th>Status</th>
                        <th>Services</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($baths as $bath)
                        <tr>
                            <td class="fw-500">{{ $bath->name }}</td>
                            <td>{{ $bath->owner->name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-info">{{ ucfirst($bath->bath_type) }}</span>
                            </td>
                            <td>{{ $bath->dzongkhag->name ?? 'N/A' }}</td>
                            <td>Nu. {{ number_format($bath->price_per_session, 2) }}</td>
                            <td>{{ $bath->max_guests }}</td>
                            <td>
                                @if($bath->status === 'active')
                                    <span class="badge bg-success">Active</span>
                                @elseif($bath->status === 'pending_verification')
                                    <span class="badge bg-warning">Pending</span>
                                @else
                                    <span class="badge bg-danger">{{ ucfirst(str_replace('_', ' ', $bath->status)) }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $bath->services->count() }} services</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.baths.edit', $bath) }}" class="btn btn-sm btn-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $bath->id }}" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox"></i> No baths found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $baths->links() }}
    </div>
</div>

<!-- Delete Modals -->
@foreach($baths as $bath)
    <div class="modal fade" id="deleteModal{{ $bath->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0">
                <div class="modal-header border-0">
                    <h5 class="modal-title">Delete Bath</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete <strong>{{ $bath->name }}</strong>? This action cannot be undone.</p>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('admin.baths.delete', $bath) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

@endsection
