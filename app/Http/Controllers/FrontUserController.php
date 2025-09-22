<?php
namespace App\Http\Controllers;

use App\Mail\RegisterConfirmation;
use App\Models\Booking;
use App\Models\Contact;
use App\Models\Event;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class FrontUserController extends Controller
{
    public function home()
    {return view('user.home');}

    public function about()
    {return view('user.about');}

    public function contact()
    {return view('user.contact');}

    public function events()
    {
        $currentDate = new DateTime();
        $events = Event::where('status', 'Y')->whereDate('end_date', '>=', $currentDate->format('Y-m-d'))->get();

        return view('user.events', ['events' => $events]);
    }

    public function contactRequest(Request $request)
    {

        $rules =  [
            'name' => 'required|min:2|max:70',
            'email' => 'required|min:2|max:70|email',
            'phone' => 'required|max:15',
            'address' => 'required',
            'description' => 'required',
        ];

        $messages = [
            'name.required' => 'The name is required.',
            'name.min' => 'The name must be at least 2 characters long.',
            'name.max' => 'The name cannot exceed 70 characters.',
            
            'email.required' => 'The email address is required.',
            'email.min' => 'The email address must be at least 2 characters long.',
            'email.max' => 'The email address cannot exceed 70 characters.',
            'email.email' => 'The email address must be a valid email format.',
            
            'phone.required' => 'The phone number is required.',
            'phone.max' => 'The phone number cannot exceed 15 characters.',
            
            'address.required' => 'The address is required.',
            
            'description.required' => 'The description is required.',
        ];

        $validate = Validator::make($request->all(),$rules, $messages);
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate);
        } else {
            $exist = Contact::where('email', $request->email)->first();
            if ($exist) {
                return redirect()->back()->with('error', 'We alredy got your response will get back to you soon');
            } else {
                Contact::create($request->all());
                return redirect()->back()->with('success', 'We got your response will get back to you soon');
            }
        }
    }

    public function eventDetails($id)
    {
        if ($id) {
            $event = Event::find($id);
            return view('user.eventDetails', ['event' => $event]);
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

    public function termsConditions() {
        return view('user.termsConditions');
    }

    public function registraionEvent(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:100',
            'email' => 'required|email',
            'phone' => 'required|string|max:20|',
            'tearmsconditions' => 'required'
        ];

        $messages = [
            'name.required' => 'The name is required.',
            'name.string' => 'The name must be a valid string.',
            'name.max' => 'The name cannot exceed 100 characters.',
            
            'email.required' => 'The email address is required.',
            'email.email' => 'The email address must be a valid email format.',
            
            'phone.required' => 'The phone number is required.',
            'phone.string' => 'The phone number must be a valid string.',
            'phone.max' => 'The phone number cannot exceed 20 characters.',
            
            'tearmsconditions.required' => 'You must accept the terms and conditions.',
        ];

        $validator = Validator::make($request->all(),$rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ]);
        } else {

            $is_duplicate = Booking::where('event_id',$request->event_id)->where('email',$request->email)->first();

            if($is_duplicate) {
                return response()->json([
                    'status' => 'duplicate',
                    'message' => 'You are alreday registerd in this event'
                ]);
            } else {
                $register = Booking::create($request->all()); // Saves to bookings_name table

                $name = $request->name;
                $id = $request->event_id;
                $event = Event::find($id);
                $event_title = $event->event_title;
                $start_date = $event->start_date;
                $end_date = $event->end_date;
                $registration_id = $register->id . date('Ymd');
                
                Mail::to($request->email)->send(new RegisterConfirmation($name, $event_title, $start_date, $end_date, $registration_id ));
    
                return response()->json([
                    'status' => 'success',
                    'message' => 'You have successfully registerd'
                ]);
            }  
        }
    }
}
