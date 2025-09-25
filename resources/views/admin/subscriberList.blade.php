@extends('admin.layout.comman')

@section('title', 'Subscribers')
@section('main')
    <h1>Subscribers</h1>

    <div class="m-4">
        <table class="table table-bordered table-hover align-middle" id="subscribers" style="font-size: 15px;">
            <thead>
                <th>Event Title</th>
                <th>Name</th>
                <th>Subscribe Date</th>
                <th>Email</th>
                <th>Phone</th>
            </thead>
        </table>
    </div>

    
@endsection
@section('script')
<script src="{{ asset('JS/subscribers.js') }}"></script>
    <script>       
        var SUBSCRIBERS_AJAX_URL = "{{ route('subscriberList') }}";
    </script>
@endsection