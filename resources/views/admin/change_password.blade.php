@extends('admin.layout.comman')
@section('title', 'CHange Password')

@section('main')

    <div class="container mt-2" style="width:700px">
        <h3 class="text-center mb-4">Change Password</h3>
        <div class="container mt-2">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
        <form action="{{ route('admin-setNewPassword') }}" method="POST" id="changePasswordForm">
            @csrf
    
            <input type="hidden" name="email" value={{ auth()->user()->email }}>
    
            <div class="mb-4">
                <label for="password" class="form-label">Old Password <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" class="form-control" name="old_password" id="old_password" placeholder="Enter password">
                </div>
                @error('password')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
    
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
        </form>
    </div>
@endsection
@section('script') 
<script src="{{ asset('JS/validation.js') }}"></script>
@endsection