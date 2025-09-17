@extends('admin.layout.comman')
@section('title', isset($event) ? 'Edit Event' : 'Add Event')

@section('main')

    <div class="container mt-2" style="width:700px">
        <div class="container mt-2">
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
        <h3 class="text-center mb-4">{{ isset($event) ? 'Edit Event' : 'Add Event' }}</h3>
        <form action="{{ isset($event) ? route('admin-updateEvent') : route('admin-saveEvent') }}" method="POST"
            enctype="multipart/form-data" id="eventForm">
            @csrf
            <input type="hidden" name="id" id="id" value="{{ isset($event) ? $event->id : '' }}">
            <input type="hidden" name="existing_image" value="{{ isset($event) ? $event->event_image : '' }}">
            <input type="hidden" name="existing_document" value="{{ isset($event) ? $event->document : '' }}">
            <div class="mb-3">
                <label for="eventTitle" class="form-label">Event Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="event_title" id="eventTitle"
                    placeholder="Enter event title"
                    value="{{ old('event_title', isset($event) ? $event->event_title : '') }}">
                @error('event_title')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="startDate" class="form-label">Start Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="start_date" id="startDate"
                        value="{{ old('start_date', isset($event) ? $event->start_date : '') }}" onkeydown="return false;">
                    @error('start_date')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="endDate" class="form-label">End Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="end_date" id="endDate"
                        value="{{ old('end_date', isset($event) ? $event->end_date : '') }}" onkeydown="return false;">
                    @error('end_date')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="price" id="price"
                    value="{{ old('price', isset($event) ? $event->price : '') }}">
                @error('price')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                <select class="form-select" name="category" id="category">
                    <option value="" disabled
                        {{ old('category', isset($event) ? $event->category : '') == '' ? 'selected' : '' }}>
                        Select a category
                    </option>
                    <option value="School Camp"
                        {{ old('category', isset($event) ? $event->category : '') == 'School Camp' ? 'selected' : '' }}>
                        School Camp
                    </option>
                    <option value="Collage Camp"
                        {{ old('category', isset($event) ? $event->category : '') == 'Collage Camp' ? 'selected' : '' }}>
                        Collage Camp
                    </option>
                    <option value="Group Camp"
                        {{ old('category', isset($event) ? $event->category : '') == 'Group Camp' ? 'selected' : '' }}>
                        Group Camp
                    </option>
                </select>

                @error('category')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Event Image <span class="text-danger">*</span></label>
                <input type="file" class="form-control" name="event_image" id="image">
                <div>{{ isset($event) ? $event->event_image : '' }}</div>
                @error('event_image')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="document" class="form-label">Document</label>
                <input type="file" class="form-control" name="document" id="document" value="">
                <div>
                    <div>{{ isset($event) ? $event->document : '' }}</div>
                </div>
                @error('document')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" name="description" id="description" rows="4"
                    placeholder="Describe your event">{{ old('description', isset($event) ? $event->description : '') }}</textarea>
                @error('description')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="d-grid">
                <button class="btn btn-primary btn-lg">Submit</button>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

    <script>
        $(function() {
            // --- register custom validators BEFORE calling .validate() ---

            // greaterThan: end date must be >= start date (change to > if you want strictly after)
            $.validator.addMethod("greaterThan", function(value, element, param) {
                var start = $(param).val();
                if (!start || !value) return true; // let 'required' handle empties
                // parse yyyy-mm-dd safely
                var s = start.split('-');
                var e = value.split('-');
                if (s.length !== 3 || e.length !== 3) return false;
                var startTs = Date.UTC(+s[0], +s[1] - 1, +s[2]);
                var endTs = Date.UTC(+e[0], +e[1] - 1, +e[2]);
                // allow same-day end date: use (endTs >= startTs)
                return this.optional(element) || (endTs >= startTs);
            }, "End date must be on/after start date");

            // simple extension check (so you don't need additional-methods.js)
            $.validator.addMethod("fileExtension", function(value, element, allowed) {
                if (!value)
                    return true; // no file selected -> valid (use required rule if you want mandatory)
                var ext = value.split('.').pop().toLowerCase();
                var allowedArr = allowed.split('|').map(function(s) {
                    return s.toLowerCase();
                });
                return allowedArr.indexOf(ext) !== -1;
            }, "Invalid file type");

            // --- now initialize validator ---
            $('#eventForm').validate({
                errorClass: "error",
                errorElement: "span",
                rules: {
                    event_title: {
                        required: true,
                        maxlength: 100
                    },
                    start_date: {
                        required: true,
                        date: true
                    },
                    end_date: {
                        required: true,
                        date: true,
                        greaterThan: "#startDate"
                    },
                    price: {
                        required: true,
                        number: true,
                        min: 0
                    },
                    category: {
                        required: true
                    },
                    // make event_image required only for new events (id empty)
                    event_image: {
                        required: function() {
                            return $('#id').val() === "" || typeof $('#id').val() === 'undefined';
                        },
                        fileExtension: "jpg|jpeg|png|gif"
                    },
                    document: {
                        required: function() {
                            return $('#id').val() === "" || typeof $('#id').val() === 'undefined';
                        },
                        fileExtension: "pdf|doc|docx"
                    },
                    description: {
                        required: true,
                        minlength: 10,
                    }
                },
                messages: {
                    event_title: {
                        required: "Please enter the event title",
                        maxlength: "Event title must not exceed 100 characters"
                    },
                    start_date: {
                        required: "Please select the start date",
                        date: "Please enter a valid date"
                    },
                    end_date: {
                        required: "Please select the end date",
                        date: "Please enter a valid date",
                        greaterThan: "End date must be on or after the start date"
                    },
                    price: {
                        required: "Please enter the price",
                        number: "Please enter a valid number",
                        min: "Price must be a positive number"
                    },
                    category: {
                        required: "Please select a category"
                    },
                    event_image: {
                        required: "Please upload an event image",
                        fileExtension: "Allowed file types: jpg, jpeg, png, gif"
                    },
                    document: {
                        required: "Please upload a document",
                        fileExtension: "Allowed file types: pdf, doc, docx"
                    },
                    description: {
                        required: "Please provide a description",
                        minlength: "Description must be at least 10 characters long",
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
