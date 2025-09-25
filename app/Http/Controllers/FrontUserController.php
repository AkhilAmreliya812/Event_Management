<?php
namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Event;
use DateTime;
use Illuminate\Http\Request;
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
        $events = Event::where('status', 'Y')
            ->whereDate('end_date', '>=', $currentDate->format('Y-m-d'))
            ->whereDate('start_date', '<=', $currentDate->format('Y-m-d'))
            ->orderBy('orderBy', 'ASC')
            ->get();

        return view('user.events', ['events' => $events]);
    }

    public function contactRequest(Request $request)
    {

        $rules = [
            'name' => ['required', 'min:3', 'max:70', 'regex:/^[a-zA-Z\s]+$/'],
            'email' => ['required', 'email'],
            'phone' => ['required', 'digits:10'],
            'address' => ['required', 'max:255'],
            'description' =>['required', 'min:10' ,'max:255']
        ];

        $messages = [
            'name.required' => 'Please enter your name',
            'name.min' => 'Name must be at least 3 characters long',
            'name.max' => 'Name must not exceed 70 characters',
            'name.regex' => 'Name must contain only letters and whitespaces',

            'email.required' => 'Please enter your email',
            'email.email' => 'Please enter a valid email address',

            'phone.required' => 'Please enter your phone number',
            'phone.digits' => 'Phone number must be exactly 10 digits',
            
            'address.required' => 'Please enter your address',
            'address.max' => 'Address must not exceed 255 characters',

            'description.required' => 'Please enter your description',
            'description.max' => 'Description must be at least 10 characters long',
            'description.max' => 'Description must not exceed 255 characters',
        ];

        $validate = Validator::make($request->all(), $rules, $messages);
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

    public function termsConditions()
    {
        return view('user.termsConditions');
    }

}
