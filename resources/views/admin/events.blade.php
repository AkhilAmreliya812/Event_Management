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
        <table class="table table-bordered align-middle" id="events">
            <thead>
                <th>Event Title</th>
                <th>Category</th>
                <th>Start Date</th>
                <th>End Date</th>
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
    $(document).ready(function() {        
        $('#events').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin-events') }}",
            columns: [{
                    data: 'event_title',
                    name: 'event_title',
                    orderable: true,
                    searchable: true,
                    className: 'text-start'
                },
                {
                    data: 'category',
                    name: 'category',
                    orderable: false,
                    searchable: false,
                    className: 'text-start'
                },
                {
                    data: 'start_date',
                    name: 'start_date',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                },
                {
                    data: 'end_date',
                    name: 'end_date',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                },
                {
                    data: 'event_image',
                    name: 'event_image',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                },
                {
                    data: 'document',
                    name: 'document',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                },
                {
                    data: 'status',
                    name: 'status',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                },
            ]
        });


        //Publish/Unpublish Event.
        $('body').on('change','.eventStatus', function() {

            $.ajax({
                url: "{{ route('admin-eventStatus') }}",
                type: 'POST',
                data: {
                    'eventId': $(this).attr('id'),
                    'status': $(this).is(':checked') ? 'Y' : 'N',
                    '_token': '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.status == "success") {
                        $('#events').DataTable();
                        toastr.success('', response.message, {
                            timeOut: 3500
                        })
                    } else if (response.status == "error") {
                        toastr.error('', response.message, {
                            timeOut: 3500
                        })
                    }
                },
            });
        });
     });

    $("#errorAlert").delay(3000).fadeOut(500, function() {
        $(this).remove();
    });

    $("#successAlert").delay(3000).fadeOut(100, function() {
        $(this).remove();
    });
</script>
@endsection
