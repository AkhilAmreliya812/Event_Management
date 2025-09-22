@extends('user.layout.comman')
@section('title', 'Events')
@section('main')
    <div class="m-1">
        <h1 class="text-center">Events</h1>
        <div class="row">
            @foreach ($events as $event)
                <div class="card m-3" style="width: 18rem;">
                    <img class="card-img-top bg-secondary"
                        src="{{ 'http://localhost/event_manager/public/event_images/' . $event->event_image }}"
                        alt="Card image cap"    >
                    <div class="card-body">
                        <h5 class="card-title text-primary">{{ $event->event_title }}</h5>
                        <div>
                            <p class="my-1">{{ 'From ₹' . $event->price . '/-' }}</p>
                            <p class="my-1 mb-2">{{ 'Start Date : ' . $event->start_date }}</p>
                            <p class="my-1 mb-2">{{ 'End Date : ' . $event->end_date }}</p>
                        </div>

                        <a href="{{ route('eventDetails', ['id' => $event->id]) }}" class="btn btn-primary">View</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
