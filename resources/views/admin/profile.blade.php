@extends('admin.layout.comman')

@section('title', 'profile')
@section('main')
    <h1>My Profile</h1>

    <div class="container mt-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" id="successAlert" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" id="errorsAlert" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <form action="{{ route('admin-update_profile') }}" method="POST" enctype="multipart/form-data" id="profileForm">
        @csrf
        <div class="row">
            <div class="text-center col-md-4" style="margin-top: 60px;">
                <div>
                    <img class="align-middle" src="http://localhost/event_manager/public/{{ $user->profile_photo }}"
                        alt="{{ $user->profile_photo }}" style="border-radius: 200px; width: 223px; height:223px;">
                </div>
                <div>
                    <div>
                        <label for="profilePhoto" class="form-label">Profile Photo</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-image-fill"></i></span>
                            <input type="file" class="form-control" name="profile_photo" id="profile_photo">
                        </div>

                        @error('profile_photo')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <input type="hidden" name="id" value="{{ $user->id }}">
                <input type="hidden" name="existing_profile_photo" value="{{ $user->profile_photo }}">
                <div class="mb-4">
                    <label for="name" class="form-label">Full Name </label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                        <input type="text" class="form-control" name="name" id="name"
                            value='{{ $user->name }}'>
                    </div>
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="email" class="form-label">Email address </label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                        <input type="email" class="form-control " name="email" id="email" value={{ $user->email }}
                            disabled>
                    </div>
                </div>
                @error('email')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
                <div class="mb-4">
                    <label for="phone" class="form-label">Phone Number </label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                        <input type="tel" class="form-control" name="phone" id="phone" value={{ $user->phone }}>
                    </div>
                    @error('phone')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="birthDate" class="form-label">Birth Date </label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-calendar-fill"></i></span>
                        <input type="date" class="form-control" name="dob" id="birthDate"
                            value="{{ $user->dob }}">
                    </div>
                    @error('dob')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>


            </div>
            <div class="d-grid mb-4">
                <button class="btn btn-primary btn-lg">Update Profile</button>
            </div>
        </div>
    </form>
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
            $('#profileForm').validate({
                errorClass: "error",
                errorElement: "span",
                rules: {
                    name: {
                        required: true,
                        minlength: 3,
                        maxlength: 70,
                        letterswhitespace: true
                    },
                    phone: {
                        required: true,
                        minlength: 10,
                        maxlength: 10,
                        digits: true
                    },
                    dob: {
                        required: true,
                        date: true
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
                    dob: {
                        required: "Please enter your date of birth",
                        date: "Please enter a valid date"
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
