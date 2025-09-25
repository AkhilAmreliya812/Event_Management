@extends('admin.layout.comman')

@section('title', 'Events')
@section('main')
    <h1>Events</h1>

    <div class="container mt-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" id="successAlert" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" id="errorAlert" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>
    <a class="btn btn-secondary m-3" href="{{ route('admin-add_event') }}">Add New Event</a>

    <div class="m-4">
        <table class="table table-bordered table-hover align-middle" id="events" style="font-size: 15px;">
            <thead>
                <th>Event Title</th>
                <th>Category</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Order By</th>
                <th>Event Image</th>
                <th>Document</th>
                <th>Publish</th>
                <th>Action</th>
            </thead>
        </table>
    </div>

    <div class="modal fade" id="description" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Description</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="description_details"></p>
                </div>

            </div>
        </div>
    </div>

    
@endsection
@section('script')
    <script>       
        var EVENTS_STATUS_URL = "{{ route('admin-eventStatus') }}";
        var EVENTS_CSRF_TOKEN = "{{ csrf_token() }}";
        var EVENTS_AJAX_URL = "{{ route('admin-events') }}";
        EVENTS_DELETE_URL = "{{ route('admin-deleteEvent') }}";
    </script>
    <script src="{{ asset('JS/EventsScript.js') }}"></script>
@endsection