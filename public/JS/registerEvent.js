$(document).ready(function() {
    // Open the registration form modal
    $('#openForm').on('click', function() {
        $('#alertBox').hide();
        $("#registrationForm")[0].reset();
        $('#bookEvent').modal('show');
        $('.errorMsg').remove();
    });

    // Handle form submission
    $('#registeration').on('click', function(event) {
        event.preventDefault();
        $('.errorMsg').remove();

        // Validate the form before submitting
        if ($("#registrationForm").valid()) {
            let dataObj = new FormData($("#registrationForm")[0]);
            $('#pageLoader').show();
            $('#pageLoader').addClass('d-flex justify-content-center');

            $.ajax({
                url: SUBSCRIBE_AJAX_URL,
                type: "POST",
                data: dataObj,
                contentType: false,
                processData: false,
                success: function(response) {
                    $('#pageLoader').hide();
                    $('#pageLoader').removeClass('d-flex justify-content-center');

                    if (response.status === 'duplicate') {
                        $('#alertBox').addClass('alert-danger');
                        $('#alertMessage').text(response.message);
                        $('#alertBox').show(200);
                    } else if (response.status === 'success') {
                        $("#registrationForm")[0].reset();
                        $('#bookEvent').modal('hide');
                        toastr.success(response.message, 'Success');
                    } else if (response.status === 'error') {

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
                    } else if (response.status === 'limit') {
                        toastr.error(toastr.success(response.message, 'Error'));
                    } else if(response.status == 'spam') {
                        toastr.error(toastr.success(response.message, 'Error'));
                    }

                }
            });
        }
    });

    // Reset form data
    $('#resetData').on('click', function(event) {
        event.preventDefault();
        $("#registrationForm")[0].reset();
        $('#alertBox').hide(200);
        $('#pageLoader').hide();
        $('#pageLoader').removeClass('d-flex justify-content-center');
        $('.errorMsg').remove();
    });

});