$(document).ready(function () {
    $("#forgotPasswordForm").validate({
        rules: {
            email: {
                required: true,
                email: true,
            },
        },
        messages: {
            email: {
                required: "Please enter your email.",
                email: "Please enter a valid email address.",
            },
        },
        errorElement: "div",
        errorClass: "text-danger",
        errorPlacement: function (error, element) {
            // Place error message below the input group
            error.insertAfter(element.closest(".input-group"));
        },
        highlight: function (element) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element) {
            $(element).removeClass("is-invalid");
        },
        submitHandler: function (form) {
            form.submit(); // Submit the form if validation passes
        },
    });

    $("#changePasswordForm").validate({
        rules: {
            old_password: {
                required: true,
                minlength: 8,
            },
            password: {
                required: true,
                minlength: 8,
                maxlength: 20,
            },
            password_confirmation: {
                required: true,
                equalTo: "#password",
            },
        },
        messages: {
            old_password: {
                required: "Please enter your old password.",
                minlength:
                    "Your old password must be at least 8 characters long.",
            },
            password: {
                required: "Please enter a new password.",
                minlength:
                    "Your new password must be at least 8 characters long.",
                maxlength: "Your new password must not exceed 20 characters.",
            },
            password_confirmation: {
                required: "Please confirm your new password.",
                equalTo: "Passwords do not match.",
            },
        },
        errorElement: "div",
        errorClass: "text-danger",
        errorPlacement: function (error, element) {
            // Place error message below the input group
            error.insertAfter(element.closest(".input-group"));
        },
        highlight: function (element) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element) {
            $(element).removeClass("is-invalid");
        },
        submitHandler: function (form) {
            form.submit(); // Submit the form if validation passes
        },
    });

    // Custom validation rule for letters and whitespaces
    $.validator.addMethod(
        "letterswhitespace",
        function (value, element) {
            return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
        },
        "Name must contain only letters and whitespaces"
    );

    // Success alert fade out
    $("#successAlert")
        .delay(3000)
        .fadeOut(500, function () {
            $(this).remove();
        });

    // Form validation
    $("#contactUsForm").validate({
        errorClass: "error",
        errorElement: "span",
        rules: {
            name: {
                required: true,
                minlength: 3,
                maxlength: 70,
                letterswhitespace: true,
            },
            email: {
                required: true,
                email: true,
            },
            phone: {
                required: true,
                minlength: 10,
                maxlength: 10,
                digits: true,
            },
            address: {
                required: true,
                maxlength: 255,
            },
            description: {
                required: true,
                minlength: 10,
                maxlength: 255,
            },
        },
        messages: {
            name: {
                required: "Please enter your name",
                minlength: "Name must be at least 3 characters long",
                maxlength: "Name must not exceed 70 characters",
                letterswhitespace:
                    "Name must contain only letters and whitespaces",
            },
            phone: {
                required: "Please enter your phone number",
                minlength: "Phone number must be exactly 10 digits",
                maxlength: "Phone number must be exactly 10 digits",
                digits: "Phone number must contain only numbers",
            },
            email: {
                required: "Please enter your email",
                email: "Please enter a valid email address",
            },
            address: {
                required: "Please enter your address",
                maxlength: "Address must not exceed 255 characters",
            },
            description: {
                required: "Please enter description",
                minlength: "Description must be at least 10 characters long",
                maxlength: "Description must not exceed 255 characters",
            },
        },
        highlight: function (element) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element) {
            $(element).removeClass("is-invalid");
        },
        errorPlacement: function (error, element) {
            error.addClass("text-danger d-block mt-1");
            if (element.parent(".input-group").length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
    });

    const today = new Date().toISOString().split("T")[0];
    $("#startDate").attr("min", today);

    $("#startDate").on("change", function () {
        const startDate = $(this).val();
        $("#endDate").attr("min", startDate);
        if ($("#endDate").val() && $("#endDate").val() < startDate) {
            $("#endDate").val("");
        }
    });

    $.validator.addMethod(
        "greaterThan",
        function (value, element, param) {
            if (!value || !$(param).val()) return true;
            return new Date(value) >= new Date($(param).val());
        },
        "End date must be on or after the start date"
    );

    $.validator.addMethod(
        "minToday",
        function (value, element) {
            if (!value) return true;
            return new Date(value) >= new Date(today);
        },
        "Start date must be today or later"
    );

    $.validator.addMethod(
        "fileExtension",
        function (value, element, allowed) {
            if (!value) return true;
            var ext = value.split(".").pop().toLowerCase();
            var allowedArr = allowed.split("|").map(function (s) {
                return s.toLowerCase();
            });
            return allowedArr.indexOf(ext) !== -1;
        },
        "Invalid file type"
    );

    jQuery.validator.addMethod(
        "notOnlyWhitespace",
        function (value, element) {
            return this.optional(element) || $.trim(value).length > 0;
        },
        "This field cannot contain only whitespace."
    );

    jQuery.validator.addMethod(
        "latterswithspace",
        function (value, element) {
            return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
        },
        "Please enter only letters and spaces."
    );

    // --- now initialize validator ---
    $("#eventForm").validate({
        errorClass: "error",
        errorElement: "span",
        rules: {
            event_title: {
                required: true,
                maxlength: 100,
                notOnlyWhitespace: true,
                latterswithspace: true,
            },
            start_date: {
                required: true,
                date: true,
            },
            end_date: {
                required: true,
                date: true,
                greaterThan: "#startDate",
            },
            price: {
                required: true,
                number: true,
                min: 0,
                notOnlyWhitespace: true,
            },
            category: {
                required: true,
            },
            event_image: {
                required: function () {
                    return (
                        $("#id").val() === "" ||
                        typeof $("#id").val() === "undefined"
                    );
                },
                fileExtension: "jpg|jpeg|png|gif",
            },
            document: {
                required: function () {
                    return (
                        $("#id").val() === "" ||
                        typeof $("#id").val() === "undefined"
                    );
                },
                fileExtension: "pdf|doc|docx",
            },
            description: {
                required: true,
                minlength: 10,
                notOnlyWhitespace: true,
            },
        },
        messages: {
            event_title: {
                required: "Please enter the event title",
                maxlength: "Event title must not exceed 100 characters",
            },
            start_date: {
                required: "Please select the start date",
                date: "Please enter a valid date",
            },
            end_date: {
                required: "Please select the end date",
                date: "Please enter a valid date",
                greaterThan: "End date must be after the start date",
            },
            price: {
                required: "Please enter the price",
                number: "Please enter a valid number",
                min: "Price must be a positive number",
                notOnlyWhitespace: "Price cannot contain only whitespace.",
            },
            category: {
                required: "Please select a category",
            },
            event_image: {
                required: "Please upload an event image",
                fileExtension: "Allowed file types: jpg, jpeg, png, gif",
            },
            document: {
                required: "Please upload a document",
                fileExtension: "Allowed file types: pdf, doc, docx",
            },
            description: {
                required: "Please provide a description",
                minlength: "Description must be at least 10 characters long",
            },
        },
        highlight: function (element) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element) {
            $(element).removeClass("is-invalid");
        },
        errorPlacement: function (error, element) {
            error.addClass("text-danger d-block mt-1");
            if (element.parent(".input-group").length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
    });

    $("#registerForm").validate({
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
                email: true,
            },
            phone: {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 15,
            },
            password: {
                required: true,
                minlength: 6,
            },
            password_confirmation: {
                required: true,
                equalTo: "#password",
            },
            dob: {
                required: true,
                date: true,
            },
            profile_photo: {
                required: true,
                extension: "jpg|jpeg|png|gif",
            },
        },
        messages: {
            name: {
                required: "Please enter name",
                minlength: "Name must be at least 3 characters",
                maxlength: "Name must not be greater than 70 characters",
            },
            email: {
                required: "Please enter email",
                email: "Please enter a valid email address",
            },
            phone: {
                required: "Please enter phone number",
                digits: "Phone number must contain only digits",
                minlength: "Phone number must be at least 10 digits",
                maxlength: "Phone number must not exceed 15 digits",
            },
            password: {
                required: "Please enter password",
                minlength: "Password must be at least 6 characters",
            },
            password_confirmation: {
                required: "Please confirm your password",
                equalTo: "Passwords do not match",
            },
            dob: {
                required: "Please select your birth date",
                date: "Please enter a valid date",
            },
            profile_photo: {
                required: "Please upload a profile photo",
                extension: "Only JPG, JPEG, PNG, and GIF files are allowed",
            },
        },

        highlight: function (element) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element) {
            $(element).removeClass("is-invalid");
        },
        errorPlacement: function (error, element) {
            error.addClass("text-danger d-block mt-1");
            if (element.parent(".input-group").length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
    });

    $("#loginForm").validate({
        errorClass: "error",
        errorElement: "span",
        rules: {
            email: {
                required: true,
                email: true,
            },
            password: {
                required: true,
                minlength: 6,
            },
        },
        messages: {
            email: {
                required: "Please enter email",
                email: "Please enter a valid email address",
            },
            password: {
                required: "Please enter password",
                minlength: "Password must be at least 6 characters",
            },
        },
        highlight: function (element) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element) {
            $(element).removeClass("is-invalid");
        },
        errorPlacement: function (error, element) {
            error.addClass("text-danger d-block mt-1");
            if (element.parent(".input-group").length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
    });

    // Custom validation rule for letters and whitespaces
    $.validator.addMethod(
        "letterswhitespace",
        function (value, element) {
            return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
        },
        "Name must contain only letters and whitespaces"
    );

    // Success alert fade out
    $("#successAlert")
        .delay(3000)
        .fadeOut(500, function () {
            $(this).remove();
        });

    // Form validation
    $("#profileForm").validate({
        errorClass: "error",
        errorElement: "span",
        rules: {
            name: {
                required: true,
                minlength: 3,
                maxlength: 70,
                letterswhitespace: true,
            },
            phone: {
                required: true,
                minlength: 10,
                maxlength: 10,
                digits: true,
            },
            dob: {
                required: true,
                date: true,
            },
        },
        messages: {
            name: {
                required: "Please enter your name",
                minlength: "Name must be at least 3 characters long",
                maxlength: "Name must not exceed 70 characters",
                letterswhitespace:
                    "Name must contain only letters and whitespaces",
            },
            phone: {
                required: "Please enter your phone number",
                minlength: "Phone number must be exactly 10 digits",
                maxlength: "Phone number must be exactly 10 digits",
                digits: "Phone number must contain only numbers",
            },
            dob: {
                required: "Please enter your date of birth",
                date: "Please enter a valid date",
            },
        },
        highlight: function (element) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element) {
            $(element).removeClass("is-invalid");
        },
        errorPlacement: function (error, element) {
            error.addClass("text-danger d-block mt-1");
            if (element.parent(".input-group").length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
    });

    $(document).ready(function () {
        $("#resetPasswordForm").validate({
            rules: {
                password: {
                    required: true,
                    minlength: 8,
                    maxlength: 20,
                },
                password_confirmation: {
                    required: true,
                    equalTo: "#password",
                },
            },
            messages: {
                password: {
                    required: "Please enter a new password.",
                    minlength:
                        "Your password must be at least 8 characters long.",
                    maxlength: "Your password must not exceed 20 characters.",
                },
                password_confirmation: {
                    required: "Please confirm your new password.",
                    equalTo: "Passwords do not match.",
                },
            },
            errorElement: "div",
            errorClass: "text-danger",
            errorPlacement: function (error, element) {
                // Place error message below the input group
                error.insertAfter(element.closest(".input-group"));
            },
            highlight: function (element) {
                $(element).addClass("is-invalid");
            },
            unhighlight: function (element) {
                $(element).removeClass("is-invalid");
            },
            submitHandler: function (form) {
                form.submit(); // Submit the form if validation passes
            },
        });
    });

    $("#otpVerificationForm").validate({
        rules: {
            otp: {
                required: true,
                digits: true,
                minlength: 6,
                maxlength: 6,
            },
        },
        messages: {
            otp: {
                required: "Please enter the OTP.",
                digits: "OTP must contain only digits.",
                minlength: "OTP must be exactly 6 digits.",
                maxlength: "OTP must be exactly 6 digits.",
            },
        },
        errorElement: "div",
        errorClass: "text-danger",
        errorPlacement: function (error, element) {
            // Place error message below the input group
            error.insertAfter(element.closest(".input-group"));
        },
        highlight: function (element) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element) {
            $(element).removeClass("is-invalid");
        },
        submitHandler: function (form) {
            form.submit(); // Submit the form if validation passes
        },
    });
});
