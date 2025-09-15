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
    <script>
        $(document).ready(function() {
            $('#openForm').on('click', function() {
                $('#alertBox').hide();
                $("#registrationForm")[0].reset();
                $('#bookEvent').modal('show');
                $('.errorMsg').remove();
            });
            $('#registeration').on('click', function() {
                $('.errorMsg').remove();
                event.preventDefault();
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
                        if (response.status === 'duplicate') {
                            $('#alertBox').addClass('alert-danger');
                            $('#alertMessage').text(response.message);
                            $('#alertBox').show(200);
                            $('#pageLoader').hide();
                            $('#pageLoader').removeClass('d-flex justify-content-center');
                            $('#registeration').prop('disabled', false);
                        } else if (response.status === 'success') {
                            $("#registrationForm")[0].reset();
                            $('#alertBox').addClass('alert-success');
                            $('#alertMessage').text(response.message);
                            $('#alertBox').show(200);
                            $('#pageLoader').hide();
                            $('#pageLoader').removeClass('d-flex justify-content-center');
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
                            $('#pageLoader').hide();
                            $('#pageLoader').removeClass('d-flex justify-content-center');
                        }
                    }
                });
            });

            $('#resetData').on('click', function() {
                event.preventDefault();
                $("#registrationForm")[0].reset();
                $('#alertBox').hide(200);
                $('#pageLoader').hide();
                $('#pageLoader').removeClass('d-flex justify-content-center');
                $('.errorMsg').remove();
            });
        });
    </script>

@endsection
