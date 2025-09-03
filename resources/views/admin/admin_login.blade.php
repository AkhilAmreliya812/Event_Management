@extends('admin.layout.login_register_comman')
@section('title', 'Login - Event Manager')
@section('main')
@section('form-title','Login')
    <form action="{{ route('admin-authenticate') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="email" class="form-label">Email address</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                <input type="text" class="form-control" name="email" id="email" placeholder="Enter email">
            </div>
            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-4">
            <label for="password" class="form-label">Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                <input type="password" class="form-control" name="password" id="password" placeholder="Enter password">
            </div>
            @error('password')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="d-flex justify-content-between mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="rememberMe">
                <label class="form-check-label" for="rememberMe">Remember me</label>
            </div>
            <a href="{{ route('admin-forgotPassword') }}" class="text-decoration-none text-primary">Forgot password?</a>
        </div>
        <div class="d-grid mb-4">
            <button type="submit" class="btn btn-primary btn-lg">Sign In</button>
        </div>

        <p class="text-center mt-3">Don't have an account? <a href={{ route('admin-register') }}
                class="text-decoration-none text-primary">Sign up</a></p>
    </form>

@endsection
