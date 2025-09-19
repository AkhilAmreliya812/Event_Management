@extends('user.layout.comman')

@section('title', 'Contact us')

@section('main')

    <style>
        body {
            background-color: #f8f9fa;
        }

        .contact-form {
            max-width: 600px;
            margin: 0 auto;
            padding: 2rem;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
    </head>

    <body>
        <div class="container my-5">
            <div class="contact-form">
                <h1 class="header-title text-center mb-4">Contact Us</h1>
                <p class="text-center text-muted mb-4">Fill out the form below, and we'll get back to you soon.</p>
                <div class="container mt-4">
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
                <form action="{{ route('contactRequest') }}" method="POST" id="contactUsForm" novalidate>
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label required">Name</label>
                        <input type="text" class="form-control" name="name" id="name"
                            placeholder="Enter your full name" required>
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label required">Email</label>
                        <input type="email" class="form-control" name="email" id="email"
                            placeholder="Enter your email" required>
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="tel" class="form-control" name="phone" id="phone"
                            placeholder="Enter your phone number">
                        @error('phone')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" name="address" id="address" rows="3" placeholder="Enter your address"></textarea>
                        @error('address')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label required">Description</label>
                        <textarea class="form-control" name="description" id="description" rows="5"
                            placeholder="Enter your message or inquiry" required></textarea>
                        @error('description')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="text-center">
                        <button class="btn btn-primary btn-lg">Send Request</button>
                    </div>
                </form>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
        <script>
            $(document).ready(function() {
                // Custom validation rule for letters and whitespaces
                $.validator.addMethod("letterswhitespace", function(value, element) {
                    return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
                }, "Name must contain only letters and whitespaces");

                // Success alert fade out
                $("#successAlert").delay(3000).fadeOut(500, function() {
                    $(this).remove();
                });

                // Form validation
                $('#contactUsForm').validate({
                    errorClass: "error",
                    errorElement: "span",
                    rules: {
                        name: {
                            required: true,
                            minlength: 3,
                            maxlength: 70,
                            letterswhitespace: true
                        },
                        email:{
                            required: true,
                            email: true
                        },
                        phone: {
                            required: true,
                            minlength: 10,
                            maxlength: 10,
                            digits: true
                        },
                        address:{
                            required:true,
                            maxlength: 255
                        }
                      
                    },
                    messages: {
                        name: {
                            required: "Please enter your name",
                            minlength: "Name must be at least 3 characters long",
                            maxlength: "Name must not exceed 70 characters",
                            letterswhitespace: "Name must contain only letters and whitespaces"
                        },
                        phone: {
                            required: "Please enter your phone number",
                            minlength: "Phone number must be exactly 10 digits",
                            maxlength: "Phone number must be exactly 10 digits",
                            digits: "Phone number must contain only numbers"
                        },
                        email: {
                            required: "Please enter your email",
                            email: "Please enter a valid email address"
                        },
                        address:{
                            required: "Please enter your address",
                            maxlength: "Address must not exceed 255 characters"
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
