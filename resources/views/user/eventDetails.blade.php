@extends('user.layout.comman')

@section('title', 'Events Details')

@section('main')
    <div class="loader-overlay" id="pageLoader">
        <div class="spinner-grow" style="width: 5rem; height: 5rem;" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>


    <div class="container my-2">


        <div class="row mb-3">
            <div class="col-md-6">
                <h1 class="card-title mb-2">{{ $event->event_title }}</h1>
            </div>
            <div class="col-md-6 mb-2 d-flex justify-content-end">
                <button class="btn btn-primary" id="openForm">Register Now</button>
            </div>
        </div>
        <div>
            <img src="{{ 'http://localhost/event_manager/public/event_images/' . $event->event_image }}"
                class="img-fluid rounded shadow-sm" alt="{{ $event->event_title }}"
                style="max-height: 500px; width: 100%; object-fit: cover;">
        </div>
        <div class="row my-4 ">
            <div class="col-md-6">
                <p class="mb-2"><strong>Start Date:</strong> {{ date('d M, Y', strtotime($event->start_date)) }}</p>
            </div>
            <div class="col-md-6 d-flex justify-content-end">
                <p class="mb-2"><strong>End Date:</strong> {{ date('d M, Y', strtotime($event->end_date)) }}</p>
            </div>
        </div>

        <div>
            <p class="mb-2">{{ $event->description }}</p>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <p class="mb-2"><strong>Price:</strong> {{ $event->price }}</p>
            </div>
            <div class="col-md-6">
                @if ($event->document)
                    <p class="d-flex justify-content-end me-5">
                        <a href="{{ route('download', ['id' => $event->id]) }}" class="text-decoration-none">
                            <i class="fa fa-download" aria-hidden="true"></i> Download detail itinerary
                        </a>
                    </p>
                @endif
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="bookEvent" tabindex="-1" aria-labelledby="bookEvent" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="bookEventLable">Register your self</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="container">
                            <div class="alert alert-dismissible fade show" role="alert" id="alertBox">
                                <span id="alertMessage"></span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        </div>

                        <form id="registrationForm">
                            @csrf
                            <input type="hidden" name="event_id" id="event_id" value="{{ $event->id }}">
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" id="name" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" id="phone" class="form-control">
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" name="tearmsconditions"
                                    id="tearmsconditions">
                                <label class="form-check-label" id="tc" for="tearmsconditions">
                                    I accept <a class="text-decoration-none" href="{{ route('terms-conditions') }}">terms
                                        &amp; conditions</a>
                                </label>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-lg-6">
                                    <button id="registeration" class="btn btn-primary w-100">Save Details</button>
                                </div>
                                <div class="col-lg-6">
                                    <button id="resetData" class="btn btn-danger w-100">Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
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
                        url: "{{ route('registraionEvent') }}",
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
                                // $('#alertBox').addClass('alert-success');
                                // $('#alertMessage').text(response.message);
                                // $('#alertBox').show(200);
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

            // Frontend validation rules
            $('#registrationForm').validate({
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
                    tearmsconditions: {
                        required: true
                    }
                },
                messages: {
                    name: {
                        required: "Please enter your name",
                        minlength: "Name must be at least 3 characters",
                        maxlength: "Name must not exceed 70 characters",
                    },
                    email: {
                        required: "Please enter your email",
                        email: "Please enter a valid email address"
                    },
                    phone: {
                        required: "Please enter your phone number",
                        digits: "Phone number must contain only digits",
                        minlength: "Phone number must be at least 10 digits",
                        maxlength: "Phone number must not exceed 15 digits"
                    },
                    tearmsconditions: {
                        required: "You must accept the terms and conditions"
                    }
                },
                highlight: function(element) {
                    if ($(element).is(':checkbox')) {
                        $('label[for="' + $(element).attr('id') + '"]').addClass('text-danger');
                    } else {
                        $(element).addClass('is-invalid');
                    }
                },
                unhighlight: function(element) {
                    if ($(element).is(':checkbox')) {
                        $('label[for="' + $(element).attr('id') + '"]').removeClass('text-danger');
                    } else {
                        $(element).removeClass('is-invalid');
                    }
                },
                errorPlacement: function(error, element) {
                    error.addClass('text-danger d-block mt-1');
                    if (element.is(':checkbox') || element.attr("name") === "tearmsconditions") {
                        $('#tc').next('span.error, p.error').remove();
                        error.insertAfter($('#tc'));
                    } else if (element.parent('.input-group').length) {
                        error.insertAfter(element.parent());
                    } else {
                        error.insertAfter(element);
                    }
                }
            });
        });
    </script>

@endsection
