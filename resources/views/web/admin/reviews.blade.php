@extends('layouts.admin-layout')

@section('title', 'Manage Reviews')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="mb-4">
        <h1 class="h3 mb-0">⭐ Reviews & Feedback Management</h1>
    </div>

    <!-- Filters -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reviews') }}" class="row g-3">
                <div class="col-md-3">
                    <select name="bath_id" class="form-select">
                        <option value="">All Baths</option>
                        @foreach($baths as $bath)
                            <option value="{{ $bath->id }}" {{ request('bath_id') == $bath->id ? 'selected' : '' }}>
                                {{ $bath->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="rating" class="form-select">
                        <option value="">All Ratings</option>
                        @foreach([5, 4, 3, 2, 1] as $rating)
                            <option value="{{ $rating }}" {{ request('rating') == $rating ? 'selected' : '' }}>
                                {{ $rating }} {{ str_repeat('⭐', $rating) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="approval_status" class="form-select">
                        <option value="">All Status</option>
                        <option value="1" {{ request('approval_status') === '1' ? 'selected' : '' }}>Approved</option>
                        <option value="0" {{ request('approval_status') === '0' ? 'selected' : '' }}>Pending Review</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Total Reviews</div>
                    <h4 class="mb-0">{{ \App\Models\Review::count() }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Approved</div>
                    <h4 class="mb-0">{{ \App\Models\Review::query()->where('is_approved', 1)->count() }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Pending Review</div>
                    <h4 class="mb-0">{{ \App\Models\Review::query()->where('is_approved', 0)->count() }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Average Rating</div>
                    <h4 class="mb-0">{{ number_format(\App\Models\Review::query()->avg('rating') ?? 0, 1) }} ⭐</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews Table -->
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Bath Name</th>
                        <th>Guest</th>
                        <th>Rating</th>
                        <th>Review</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                        <tr>
                            <td class="fw-500">{{ $review->bath->name ?? 'N/A' }}</td>
                            <td>{{ $review->guest->name ?? 'Anonymous' }}</td>
                            <td>
                                <div class="text-warning">
                                    {{ str_repeat('⭐', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                                    <span class="text-muted">({{ $review->rating }}/5)</span>
                                </div>
                            </td>
                            <td>
                                <small>{{ Str::limit($review->comment, 50) }}</small>
                            </td>
                            <td>
                                <small class="text-muted">{{ $review->created_at->format('M d, Y') }}</small>
                            </td>
                            <td>
                                @if($review->is_approved)
                                    <span class="badge bg-success">Approved</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" 
                                        data-bs-target="#reviewModal{{ $review->id }}" title="View">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @if(!$review->is_approved)
                                    <form action="{{ route('admin.review.approve', $review) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" title="Approve">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" 
                                        data-bs-target="#rejectModal{{ $review->id }}" title="Remove">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox"></i> No reviews found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $reviews->links() }}
    </div>
</div>

<!-- View Review Modal -->
@foreach($reviews as $review)
    <div class="modal fade" id="reviewModal{{ $review->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0">
                <div class="modal-header border-0">
                    <h5 class="modal-title">Review Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <h6 class="mb-1">{{ $review->bath->name ?? 'N/A' }}</h6>
                        <small class="text-muted">by {{ $review->guest->name ?? 'Anonymous' }}</small>
                    </div>

                    <div class="mb-3">
                        <div class="text-warning fs-5">
                            {{ str_repeat('⭐', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                            <span class="text-muted">({{ $review->rating }}/5)</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <p>{{ $review->comment }}</p>
                    </div>

                    <hr>

                    <div class="row text-center">
                        <div class="col-6">
                            <small class="text-muted">Date</small>
                            <div>{{ $review->created_at->format('M d, Y') }}</div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Status</small>
                            <div>
                                @if($review->is_approved)
                                    <span class="badge bg-success">Approved</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Review Modal -->
    <div class="modal fade" id="rejectModal{{ $review->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0">
                <div class="modal-header border-0">
                    <h5 class="modal-title">Remove Review</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to remove this review?</p>
                    <blockquote class="blockquote mb-0">
                        <p>{{ Str::limit($review->comment, 100) }}</p>
                        <footer class="blockquote-footer">{{ $review->guest->name ?? 'Anonymous' }}</footer>
                    </blockquote>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('admin.review.reject', $review) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-danger">Remove Review</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

@endsection
