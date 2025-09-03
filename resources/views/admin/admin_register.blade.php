@extends('admin.layout.login_register_comman')
@section('main')
@section('form-title','Register')
    <form action="{{ route('admin-register') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                <input type="text" class="form-control" name="name" id="name" placeholder="Enter full name">
            </div>
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-4">
            <label for="email" class="form-label">Email address <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                <input type="email" class="form-control" name="email" id="email" placeholder="Enter email">
            </div>
        </div>
        @error('email')
            <div class="text-danger">{{ $message }}</div>
        @enderror
        <div class="mb-4">
            <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                <input type="tel" class="form-control" name="phone" id="phone" placeholder="Enter phone number">
            </div>
            @error('phone')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-4">
            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                <input type="password" class="form-control" name="password" id="password" placeholder="Enter password">
            </div>
            @error('password')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-4">
            <label for="confirmPassword" class="form-label">Confirm Password <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                <input type="password" class="form-control" name="password_confirmation" id="confirmPassword"
                    placeholder="Confirm password">
            </div>
            @error('password_confirmation')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-4">
            <label for="birthDate" class="form-label">Birth Date <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-calendar-fill"></i></span>
                <input type="date" class="form-control" name="dob" id="birthDate">
            </div>
            @error('dob')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-4">
            <label for="profilePhoto" class="form-label">Profile Photo</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-image-fill"></i></span>
                <input type="file" class="form-control" name="profile_photo" id="profile_photo">
            </div>
            @error('profile_photo')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="d-grid mb-4">
            <button type="submit" class="btn btn-primary btn-lg">Sign Up</button>
        </div>
        <p class="text-center mt-3">Already have an account? <a href={{ route('admin-login') }}
                class="text-decoration-none text-primary">Sign in</a></p>
    </form>
    
 @endsection
