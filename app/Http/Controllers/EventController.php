<?php
namespace App\Http\Controllers;

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
        $events = Event::select('id', 'event_title', 'category', 'start_date', 'end_date', 'orderBy', 'event_image', 'document', 'status')
            ->orderBy('orderBy', 'ASC')->get();
        return datatables()->of($events)
            ->addColumn('event_title', function ($row) {
                return $row->event_title;
            })
            ->addColumn('category', function ($row) {
                return $row->category;
            })
            ->addColumn('start_date', function ($row) {
                return date('d M, Y', strtotime($row->start_date));
            })
            ->addColumn('end_date', function ($row) {
                return date('d M, Y', strtotime($row->end_date));
            })
            ->addColumn('orderBy', function ($row) {
                return $row->orderBy;
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

                $actionBtn .= '<button datatableId="events_datatable" id="' . $row->id . '" class="deleteRecord btn btn-link text-danger p-0 ms-2" title="Delete">
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                            </button>';

                $actionBtn .= '</div">';

                return $actionBtn;
            })
            ->rawColumns(['event_title', 'category', 'start_date', 'end_date', 'orderBy', 'event_image', 'document', 'status', 'action'])
            ->addIndexColumn()
            ->make(true);
    }

    public function addEvent()
    {
        return view('admin.add_event');
    }

    public function saveEvent(Request $request)
    {

        $rules = [
            'event_title' => 'required|string|max:100|regex:/^[A-Za-z\s]+$/',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'price' => 'required|numeric|min:0',
            'category' => 'required',
            'event_image' => 'required_without:id|file|mimes:jpg,jpeg,png,gif',
            'document' => 'required_without:id|file|mimes:pdf,doc,docx',
            'description' => 'required|string|min:10',
        ];

        $messages = [
            'event_title.required' => 'Please enter the event title.',
            'event_title.string' => 'Event title must be a string.',
            'event_title.max' => 'Event title must not exceed 100 characters.',
            'event_title.regex' => 'Event title must contain only letters and spaces and cannot be only whitespace.',

            'start_date.required' => 'Please select the start date.',
            'start_date.date' => 'Please enter a valid start date.',
            'start_date.after_or_equal' => 'Start date must be today or later.',

            'end_date.required' => 'Please select the end date.',
            'end_date.date' => 'Please enter a valid end date.',
            'end_date.after_or_equal' => 'End date must be the same as or after the start date.',

            'price.required' => 'Please enter the price.',
            'price.numeric' => 'Please enter a valid number for price.',
            'price.min' => 'Price must be a positive number.',

            'category.required' => 'Please select a category.',

            'event_image.required_without' => 'Please upload an event image (required when creating).',
            'event_image.file' => 'Event image must be a file.',
            'event_image.mimes' => 'Allowed image file types: jpg, jpeg, png, gif.',

            'document.required_without' => 'Please upload a document (required when creating).',
            'document.file' => 'Document must be a file.',
            'document.mimes' => 'Allowed document types: pdf, doc, docx.',

            'description.required' => 'Please provide a description.',
            'description.string' => 'Description must be text.',
            'description.min' => 'Description must be at least 10 characters long.',
        ];

        $validate = Validator::make($request->all(), $rules, $messages);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        } else {

            $event = new Event();

            $eventData = [
                'event_title' => $request->event_title,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'price' => $request->price,
                'category' => $request->category,
                'event_image' => $this->eventImage($request),
                'document' => $this->document($request),
                'description' => $request->description,

                // 'orderBy' => (int) $request->orderBy,
                'orderBy' => $this->swapOrderAdd((int) $request->orderBy),
            ];

            Event::saveEvent($eventData);

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

        $rules = [
            'event_title' => 'required|string|max:100|regex:/^[A-Za-z\s]+$/',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'price' => 'required|numeric|min:0',
            'category' => 'required',
            'event_image' => 'required_without:id|file|mimes:jpg,jpeg,png,gif',
            'document' => 'required_without:id|file|mimes:pdf,doc,docx',
            'description' => 'required|string|min:10',
        ];

        $messages = [
            'event_title.required' => 'Please enter the event title.',
            'event_title.string' => 'Event title must be a string.',
            'event_title.max' => 'Event title must not exceed 100 characters.',
            'event_title.regex' => 'Event title must contain only letters and spaces and cannot be only whitespace.',

            'start_date.required' => 'Please select the start date.',
            'start_date.date' => 'Please enter a valid start date.',
            'start_date.after_or_equal' => 'Start date must be today or later.',

            'end_date.required' => 'Please select the end date.',
            'end_date.date' => 'Please enter a valid end date.',
            'end_date.after_or_equal' => 'End date must be the same as or after the start date.',

            'price.required' => 'Please enter the price.',
            'price.numeric' => 'Please enter a valid number for price.',
            'price.min' => 'Price must be a positive number.',

            'category.required' => 'Please select a category.',

            'event_image.required_without' => 'Please upload an event image (required when creating).',
            'event_image.file' => 'Event image must be a file.',
            'event_image.mimes' => 'Allowed image file types: jpg, jpeg, png, gif.',

            'document.required_without' => 'Please upload a document (required when creating).',
            'document.file' => 'Document must be a file.',
            'document.mimes' => 'Allowed document types: pdf, doc, docx.',

            'description.required' => 'Please provide a description.',
            'description.string' => 'Description must be text.',
            'description.min' => 'Description must be at least 10 characters long.',
        ];

        $validate = Validator::make($request->all(), $rules, $messages);

        if ($validate->passes()) {
            $event = Event::find($request->id);

            $event->event_title = $request->event_title;
            $event->start_date = $request->start_date;
            $event->end_date = $request->end_date;
            $event->price = $request->price;
            $event->category = $request->category;
            $event->orderBy = $this->swapOrderUpdate($event->orderBy, (int) $request->orderBy, $event->id);
            $event->event_image = $request->event_image == null ? $event->event_image : $this->eventImage($request);
            $event->document = $request->document == null ? $event->document : $this->document($request);
            $event->description = $request->description;
            $event->save();

            return redirect()->route('admin-events')->with('success', 'event added successfully');

        } else {
            return redirect()->back()->withErrors($validate)->withInput();
        }
    }

    public function deleteEvent(Request $request)
    {
        $id = $request->id;
        $order = Event::where('id', $id)->value('orderBy');
        $this->swaapOrderDelete($order);

        $deleteEvent = Event::find($id)->delete();

        if ($deleteEvent) {
            return response()->json([
                'status' => 'success',
                'message' => 'Event deleted successfully',
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong.!',
            ]);
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

    public function swapOrderAdd($order)
    {
        $count = Event::count();
        if ($order > $count + 1) {
            $order = $count + 1;
        } else if ($order < 1) {
            $order = 1;
        }
        // Increment orderBy of existing events to make space for the new event
        Event::where('orderBy', '>=', $order)->increment('orderBy');
        return $order;
    }

    public function swapOrderUpdate($oldOrder, $newOrder, $eventId)
    {
        $count = Event::count();
        if ($newOrder > $count) {
            $newOrder = $count;
        } else if ($newOrder < 1) {
            $newOrder = 1;
        }

        if ($oldOrder < $newOrder) {
            // Move down: Decrement orderBy of events between old and new position
            Event::where('orderBy', '>', $oldOrder)
                ->where('orderBy', '<=', $newOrder)
                ->where('id', '!=', $eventId)
                ->decrement('orderBy');
        } else if ($oldOrder > $newOrder) {
            // Move up: Increment orderBy of events between new and old position
            Event::where('orderBy', '>=', $newOrder)
                ->where('orderBy', '<', $oldOrder)
                ->where('id', '!=', $eventId)
                ->increment('orderBy');
        }
        return $newOrder;
    }

    public function swaapOrderDelete($order)
    {
        Event::where('orderBy', '>', $order)->decrement('orderBy');
        return true;
    }
}
