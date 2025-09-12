<?php
namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    public function addEvent()
    {return view('admin.add_event');}

    public function saveEvent(Request $request)
    {

        $data = $request->only('event_title', 'start_date', 'end_date', 'price', 'category', 'event_image', 'document', 'description');

        $validate = Validator::make($data,[
                'event_title' => 'required|min:3|max:50',
                'start_date' => 'required',
                'end_date' => 'required',
                'price' => 'required',
                'category' => 'required',
                'event_image' => 'required',
                'document' => 'required',
                'description' => 'required',
            ]
        );

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        } else {
            $event = new Event();
            $event->event_title = $request->event_title;
            $event->start_date = $request->start_date;
            $event->end_date = $request->end_date;
            $event->price = $request->price;
            $event->category = $request->category;
            $event->event_image = $this->eventImage($request);
            $event->document = $this->document($request);
            $event->description = $request->description;
            $event->save();

            return redirect()->route('admin-events')->with('success','event added successfully');
        }

    }

    public function eventImage(Request $request)
    {
        $inputImage = $request->file('event_image');
        $inputImage->move(public_path('event_images'), $inputImage->getClientOriginalName());
        return $inputImage->getClientOriginalName();
    }

    public function document(Request $request)
    {
        $inputDocument = $request->file('document');
        $inputDocument->move(public_path('documents'), $inputDocument->getClientOriginalName());
        return $inputDocument->getClientOriginalName();
    }

    public function eventList()
    {
        $events = Event::all();
        return view('admin.events', ['events' => $events]);
    }

    public function editEvent($id)
    {
        $event = Event::find($id);
        return view('admin.add_event', ['event' => $event]);
    }

    public function updateEvent(Request $request)
    {
        $validate = Validator::make($request->all(),
            [
                'event_title' => 'required|min:3|max:50',
                'start_date' => 'required',
                'end_date' => 'required',
                'price' => 'required',
                'category' => 'required',
                'description' => 'required',
            ]
        );

        if ($validate->passes()) {
            $event = Event::find($request->id);

            
            $event->event_title = $request->event_title;
            $event->start_date = $request->start_date;
            $event->end_date = $request->end_date;
            $event->price = $request->price;
            $event->category = $request->category;
            $event->event_image = $request->event_image == null ? $event->event_image : $this->eventImage($request);
            $event->document = $request->document == null ? $event->document : $this->document($request);
            $event->description = $request->description;
            $event->save();

            return redirect()->route('admin-events')->with('success','event added successfully');

        } else {
            return redirect()->back()->withErrors($validate)->withInput();
        }
    }

    public function deleteEvent($id)
    {

        $deleteEvent = Event::find($id)->delete();

        if ($deleteEvent) {
            return redirect()->back()->with('success', 'Event deleted successfully');
        } else {
            return redirect()->back()->with('erro', 'Event not deleted');
        }
    }

    public function downloadDocument($id)
    {
        $event = Event::find($id);
       
        $documentPath = public_path('documents/' . $event->document);
        return response()->download($documentPath, $event->document, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    
}
