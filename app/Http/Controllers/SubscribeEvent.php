<?php
namespace App\Http\Controllers;

use App\Mail\RegisterConfirmation;
use App\Models\Event;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class SubscribeEvent extends Controller
{

    public function subscribeEvent(Request $request)
    {

        $rules = [
            'name' => 'required|string|max:100',
            'email' => 'required|email',
            'phone' => 'required|string|max:20|',
            'tearmsconditions' => 'required',
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

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ]);
        } else {
            $is_duplicate = Subscription::where('event_id', $request->event_id)->where('email', $request->email)->first();

            if ($is_duplicate) {
                return response()->json([
                    'status' => 'duplicate',
                    'message' => 'You are alreday subscribe this event',
                ]);
            } else {

                $subscribeEvent = Subscription::create([
                    'event_id' => $request->event_id,
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                ]);

                $name = $request->name;
                $id = $request->event_id;
                $event = Event::find($id);
                $event_title = $event->event_title;
                $start_date = $event->start_date;
                $end_date = $event->end_date;
                $registration_id = $subscribeEvent->id . date('Ymd');

                Mail::to($request->email)->send(new RegisterConfirmation($name, $event_title, $start_date, $end_date, $registration_id));

                return response()->json([
                    'status' => 'success',
                    'message' => 'You have successfully registerd',
                ]);
            }
        }
    }

    public function termsConditions()
    {
        return view('user.termsConditions');
    }

    public function subscriberList()
    {

        if (request()->ajax()) {

            $subscribers = DB::table('subscriptions')
                ->join('events', 'subscriptions.event_id', '=', 'events.id')
                ->select('subscriptions.name', 'subscriptions.email', 'subscriptions.phone',
                    'events.event_title','subscriptions.created_at')->get();

            return datatables()->of($subscribers)
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('email', function ($row) {
                    return $row->email;
                })
                ->addColumn('phone', function ($row) {
                    return $row->phone;
                })
                ->addColumn('event_title', function ($row) {
                    return $row->event_title;
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? with(new \Illuminate\Support\Carbon($row->created_at))->format('d M, Y') : '';
                })
                ->rawColumns(['name', 'email', 'phone', 'event_title','created_at'])
                ->addIndexColumn()
                ->make(true);
        } else {
            return view('admin.subscriberList');
        }
    }
}
