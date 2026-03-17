@extends('web.layouts.app')

@section('title', 'Owner Login')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card card-shadow rounded-4">
            <div class="card-body p-4 p-lg-5">
                <h1 class="h3 mb-3">Bath Owner Login</h1>
                <p class="text-muted mb-4">Login to manage listings, bookings, pricing, and availability.</p>

                <form method="POST" action="{{ route('owner.login.submit') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-dark w-100">Login</button>
                </form>

                <hr class="my-4">
                <p class="mb-0">New owner? <a href="{{ route('owner.register') }}">Register account</a></p>
            </div>
        </div>
    </div>
</div>
@endsection
