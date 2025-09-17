@extends('admin.layout.login_register_comman')
@section('title', 'OTP Verification - Event Manager')
@section('main')
@section('form-title','OTP Verification')
    <form action="{{ route('admin-verifyOTP') }}" method="POST">
        @csrf

        <input type="hidden" name="email" id="email" value="{{ session('email') }}">
        <input type="hidden" name="password" id="password" value="{{ session('password') }}">
        
        <div class="mb-4">
            <label for="otp" class="form-label">Enter OTP</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                <input type="password" class="form-control" name="otp" id="otp"
                    placeholder="Enter OTP">
            </div>
            {{-- <div class="text-end mt-2">
                <a href="{{ route('admin-resendOTP') }}" class="text-decoration-none text-primary">Resend OTP</a>
            </div> --}}
            @error('password_confirmation')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-lg">Submit</button>
        </div>
        <p class="text-center mt-3"><a href="{{ route('admin-login') }}" class="text-decoration-none text-primary">Back Login</a></p>
    </form>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $("#errorAlert").delay(3000).fadeOut(500, function () {
            $(this).remove();
        });
    </script>
@endsection
