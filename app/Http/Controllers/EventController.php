<?php
namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{

    // display all events
    public function events(Request $request)
    {
        if (!request()->ajax()) {
            return view('admin.events');
        }
        $events = Event::select('id', 'event_title', 'category', 'start_date', 'end_date', 'event_image', 'document', 'status')->get();

        return datatables()->of($events)
            ->addColumn('event_title', function ($row) {
                return $row->event_title;
            })
            ->addColumn('category', function ($row) {
                return $row->category;
            })
            ->addColumn('start_date', function ($row) {
                return $row->start_date;
            })
            ->addColumn('end_date', function ($row) {
                return $row->end_date;
            })
            ->addColumn('event_image', function ($row) {

                $image = '';
                $image .= '<img src="' . asset('event_images/' . $row->event_image) . '"
                alt="" style="width:50px; height:50px;">';

                return $image;
            })
            ->addColumn('document', function ($row) {
                $document = '';
                $document .= '<a href="' . route('admin-download-document', ['id' => $row->id]) . '">
                <i class="fa fa-download" aria-hidden="true"></i>
            </a>';
                return $document;
            })
            ->addColumn('status', function ($row) {
                $status = '<div class="form-check form-switch">
              <input class="form-check-input eventStatus" type="checkbox" role="switch"
                  id="' . $row->id . '" ' . ($row->status == 'Y' ? 'checked' : '') . '>
           </div>';
                return $status;
            })
            ->addColumn('action', function ($row) {
                $actionBtn = '';
                $actionBtn .= '<div class="text-center text-nowrap">';
                $actionBtn .= '<a href="' . route('admin-editEvent', ['id' => $row->id]) . '" class="btn btn-link p-0 mx-1" title="Edit" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Edit"> <i class="fa fa-pencil-square-o" aria-hidden="true"></i> </a>';

                $actionBtn .= '<a href="' . route('admin-deleteEvent', ['id' => $row->id]) . '" datatableId="events_datatable" class="deleteRecord btn btn-link text-danger p-0 ms-2" title="Delete">
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                            </a>';

                $actionBtn .= '</div">';

                return $actionBtn;
            })
            ->rawColumns(['event_title', 'category', 'start_date', 'end_date', 'event_image', 'document', 'status', 'action'])
            ->addIndexColumn()
            ->make(true);
    }

    public function addEvent()
    {
        return view('admin.add_event');
    }

    public function saveEvent(Request $request)
    {

        $validate = Validator::make($request->all(), [
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
            
            return redirect()->route('admin-events')->with('success', 'event added successfully');
        }

    }

    public function changeStatus(Request $request)
    {
        if (isset($request->eventId) && !empty($request->eventId)) {
            $event = Event::find($request->eventId);
            if (!empty($event)) {
                $event->status = $request->status;
                $event->save();

                $message = '';
                if ($request->status == 'Y') {
                    $message = 'Event publish successfully.';
                } else {
                    $message = 'Event unpublish successfully.';
                }
                return response()->json([
                    'status' => 'success',
                    'message' => $message,
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Record not found.!',
                ]);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong.!',
            ]);
        }
    }

    public function allBookingData()
    {

        $bookingdata = Booking::all();
        return view('admin.bookedEvents', ['bookingdata' => $bookingdata]);

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

            return redirect()->route('admin-events')->with('success', 'event added successfully');

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
