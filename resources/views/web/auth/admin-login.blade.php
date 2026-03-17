@extends('web.layouts.app')

@section('title', 'Admin Login')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card card-shadow rounded-4">
            <div class="card-body p-4 p-lg-5">
                <h1 class="h3 mb-3">Admin Login</h1>
                <p class="text-muted mb-4">Review owner registrations, approve listings, and manage platform activity.</p>

                <form method="POST" action="{{ route('admin.login.submit') }}">
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
                <small class="text-muted">Demo admin bootstrap: admin@example.com / password</small>
            </div>
        </div>
    </div>
</div>
@endsection
