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
            enctype="multipart/form-data">
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
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script> --}}

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery UI JS -->
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#startDate').datepicker({
                minDate: 0, 
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true
            });

            $('#endDate').datepicker({
                minDate: 0, 
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true
            });


        });
    </script>

@endsection
