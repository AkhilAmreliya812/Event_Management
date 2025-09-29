$(document).ready(function () {
    // Open the registration form modal
    $("#openForm").on("click", function () {
        $("#alertBox").hide();
        $("#registrationForm")[0].reset();
        $("#bookEvent").modal("show");
        $(".errorMsg").remove();
    });

    $("#registrationForm").validate({
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
                maxlength: 10,
            },
            tearmsconditions: {
                required: true,
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
                maxlength: "Phone number must not exceed 10 digits",
            },
            tearmsconditions: {
                required: "Please accept the terms and conditions. ",
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
            if (element.attr("name") === "tearmsconditions") {
                error.insertAfter(element.closest(".form-check"));
            } 
            else if (element.parent(".input-group").length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
    });

    // Handle form submission
    $("#registeration").on("click", function (event) {
        event.preventDefault();
        $(".errorMsg").remove();
        
        // Validate the form before submitting
        if ($("#registrationForm").valid()) {
            let dataObj = new FormData($("#registrationForm")[0]);
            $("#pageLoader").show();
            $("#pageLoader").addClass("d-flex justify-content-center");

            $.ajax({
                url: SUBSCRIBE_AJAX_URL,
                type: "POST",
                data: dataObj,
                contentType: false,
                processData: false,
                success: function (response) {
                    $("#pageLoader").hide();
                    $("#pageLoader").removeClass(
                        "d-flex justify-content-center"
                    );

                    if (response.status === "duplicate") {
                        $("#alertBox").addClass("alert-danger");
                        $("#alertMessage").text(response.message);
                        $("#alertBox").show(200);
                    } else if (response.status === "success") {
                        $("#registrationForm")[0].reset();
                        $("#bookEvent").modal("hide");
                        toastr.success(response.message, "Success");
                    } else if (response.status === "error") {
                        if (response.errors.name) {
                            $("#name").after(
                                `<p class="text-danger errorMsg">${response.errors.name}</p>`
                            );
                        }
                        if (response.errors.email) {
                            $("#email").after(
                                `<p class="text-danger errorMsg">${response.errors.email}</p>`
                            );
                        }
                        if (response.errors.phone) {
                            $("#phone").after(
                                `<p class="text-danger errorMsg">${response.errors.phone}</p>`
                            );
                        }
                        if (response.errors.tearmsconditions) {
                            $("#tc").after(
                                `<p class="text-danger errorMsg">${response.errors.tearmsconditions}</p>`
                            );
                        }
                    } else if (response.status === "limit") {
                        toastr.error(toastr.success(response.message, "Error"));
                    } else if (response.status == "spam") {
                        toastr.error(toastr.success(response.message, "Error"));
                    }
                },
            });
        }
    });

    // Reset form data
    $("#resetData").on("click", function (event) {
        event.preventDefault();
        $("#registrationForm")[0].reset();
        $("#alertBox").hide(200);
        $("#pageLoader").hide();
        $("#pageLoader").removeClass("d-flex justify-content-center");
        $(".errorMsg").remove();
    });
});
