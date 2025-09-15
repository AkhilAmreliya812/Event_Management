@extends('admin.layout.comman')

@section('title', 'Events')
@section('main')
    <h1>Events</h1>

    <div class="container mt-4">
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
    <a class="btn btn-secondary m-3" href="{{ route('admin-add_event') }}">Add New Event</a>

    <div class="m-4">
        <table class="table align-middle" id="events">
            <thead>
                <th>Title</th>
                <th>Category</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Event Image</th>
                <th>Document</th>
                <th>Publish</th>
                <th>Actions</th>
            </thead>
            <tbody>
                @foreach ($events as $event)
                    {{-- @dd($event->document); --}}
                    <tr>
                        <td>{{ $event->event_title }}</td>
                        <td>{{ $event->category }}</td>
                        <td>{{ date('d-M-Y', strtotime($event->start_date)) }}</td>
                        <td>{{ date('d-M-Y', strtotime($event->end_date)) }}</td>
                        <td class="text-center">
                            <img src="{{ 'http://localhost/event_manager/public/event_images/' . $event->event_image }}"
                                alt="" height="50px" width="50px">
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin-download-document', ['id' => $event->id]) }}">
                                <i class="fa fa-download" aria-hidden="true"></i>
                            </a>
                        </td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="publishUnpublish">
                            </div>
                        </td>
                        <td>
                            <a class="btn btn-info" href="{{ route('admin-editEvent', ['id' => $event->id]) }}"><i
                                    class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                            <a class="btn btn-danger" href="{{ route('admin-deleteEvent', ['id' => $event->id]) }}"
                                onclick="return confirm('You want to delete event ?');"><i class="fa fa-trash-o"
                                    aria-hidden="true"></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
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
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    `
    <script src="https://cdn.datatables.net/2.3.3/js/dataTables.min.js"></script>
    <script>
        $(document).ready(function() {

            $('#events').DataTable();

           $('#publishUnpublish').on('change',function() {
               
           });

           


        });
    </script>

@endsection
