@extends('admin.layout.login_register_comman')
@section('title', 'Reset Password - Event Manager')
@section('main')
@section('form-title','Reset Password')
    <form action="{{ route('admin-submitResetPassword') }}" method="POST" id="resetPasswordForm">
        @csrf

        <input type="hidden" name="token" value="{{ $resetToken }}">

        <div class="mb-4">
            <label for="password" class="form-label">New Password <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                <input type="password" class="form-control" name="password" id="password" placeholder="Enter password">
            </div>
            @error('password')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-4">
            <label for="confirmPassword" class="form-label">Re-enter Password <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation"
                    placeholder="Confirm password">
            </div>
            @error('password_confirmation')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-lg">Reset Password</button>
        </div>
        <p class="text-center mt-3"><a href={{ route('admin-login') }} class="text-decoration-none text-primary">Back Login</a></p>
    </form>
@endsection
@section('script')
    <script src="{{ asset('JS/validation.js') }}"></script>
@endsection
