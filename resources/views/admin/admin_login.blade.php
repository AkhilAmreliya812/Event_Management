@extends('admin.layout.login_register_comman')
@section('title', 'Login - Event Manager')
@section('main')
@section('form-title', 'Login')
<div>
    <form action="{{ route('admin-authenticate') }}" method="POST" id="loginForm">
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
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script>
    var $j = jQuery.noConflict();
    $j(document).ready(function() {
        $j('#loginForm').validate({
            errorClass: "error",
            errorElement: "span",
            rules: {
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    minlength: 6
                },
            },
            messages: {
                email: {
                    required: "Please enter email",
                    email: "Please enter a valid email address"
                },
                password: {
                    required: "Please enter password",
                    minlength: "Password must be at least 6 characters"
                },
            },
            highlight: function(element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid');
            },
            errorPlacement: function(error, element) {
                error.addClass('text-danger d-block mt-1');
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            }
        });
    });
</script>

@endsection
