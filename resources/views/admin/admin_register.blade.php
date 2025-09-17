@extends('admin.layout.login_register_comman')
@section('main')
@section('form-title', 'Register')
<div class="">
    <form action="{{ route('admin-register') }}" method="POST" enctype="multipart/form-data" id="registerForm">
        @csrf
        <div class="mb-4">
            <label for="name" class="form-label">Full Name </label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                <input type="text" class="form-control" name="name" id="name" placeholder="Enter full name">
            </div>
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-4">
            <label for="email" class="form-label">Email address </label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                <input type="email" class="form-control" name="email" id="email" placeholder="Enter email">
            </div>
        </div>
        @error('email')
            <div class="text-danger">{{ $message }}</div>
        @enderror
        <div class="mb-4">
            <label for="phone" class="form-label">Phone Number </label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                <input type="tel" class="form-control" name="phone" id="phone"
                    placeholder="Enter phone number">
            </div>
            @error('phone')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-4">
            <label for="password" class="form-label">Password </label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                <input type="password" class="form-control" name="password" id="password" placeholder="Enter password">
            </div>
            @error('password')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-4">
            <label for="confirmPassword" class="form-label">Confirm Password </label>
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
            <label for="birthDate" class="form-label">Birth Date</label>
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
            <button class="btn btn-primary btn-lg">Sign Up</button>
        </div>
        <p class="text-center mt-3">Already have an account? <a href={{ route('admin-login') }}
                class="text-decoration-none text-primary">Sign in</a></p>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script>
    var $j = jQuery.noConflict();
    $j(document).ready(function() {
        $j('#registerForm').validate({
            errorClass: "error",
            errorElement: "span",
            rules: {
                name: {
                    required: true,
                    minlength: 3,
                    maxlength: 70,

                },
                email: {
                    required: true,
                    email: true
                },
                phone: {
                    required: true,
                    digits: true,
                    minlength: 10,
                    maxlength: 15
                },
                password: {
                    required: true,
                    minlength: 6
                },
                password_confirmation: {
                    required: true,
                    equalTo: "#password"
                },
                dob: {
                    required: true,
                    date: true
                },
                profile_photo: {
                    required: true,
                    extension: "jpg|jpeg|png|gif"
                }
            },
            messages: {
                name: {
                    required: "Please enter name",
                    minlength: "Name must be at least 3 characters",
                    maxlength: "Name must not be greater than 70 characters",

                },
                email: {
                    required: "Please enter email",
                    email: "Please enter a valid email address"
                },
                phone: {
                    required: "Please enter phone number",
                    digits: "Phone number must contain only digits",
                    minlength: "Phone number must be at least 10 digits",
                    maxlength: "Phone number must not exceed 15 digits"
                },
                password: {
                    required: "Please enter password",
                    minlength: "Password must be at least 6 characters"
                },
                password_confirmation: {
                    required: "Please confirm your password",
                    equalTo: "Passwords do not match"
                },
                dob: {
                    required: "Please select your birth date",
                    date: "Please enter a valid date"
                },
                profile_photo: {
                    required: "Please upload a profile photo",
                    extension: "Only JPG, JPEG, PNG, and GIF files are allowed"
                }
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
