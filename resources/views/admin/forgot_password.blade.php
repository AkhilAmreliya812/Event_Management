@extends('admin.layout.login_register_comman')
@section('title', 'Forgot password - Event Manager')
@section('main')
@section('form-title','Forgot Password')
    <form action="{{ route('admin-sentResetLink') }}" method="POST" id="forgotPasswordForm">
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
        
        <div class="d-grid mb-4">
            <button type="submit" class="btn btn-primary btn-lg">Send Reset Link</button>
        </div>

        <p class="text-center mt-3"><a href={{ route('admin-login') }} class="text-decoration-none text-primary">Back Login</a></p>
    </form>

@endsection
@section('script')
    <script src="{{ asset('JS/validation.js') }}"></script>
@endsection