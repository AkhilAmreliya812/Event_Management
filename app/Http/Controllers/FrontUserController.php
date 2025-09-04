<?php
namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FrontUserController extends Controller
{
    public function home()
    {
        return view('user.home');
    }

    public function about()
    {
        return view('user.about');
    }

    public function contact()
    {
        return view('user.contact');
    }

    public function contactRequest(Request $request)
    {

        $validate = Validator::make($request->all(),
            [
                'name' => 'required|min:2|max:70',
                'email' => 'required|min:2|max:70|email',
                'phone' => 'required|max:15',
                'address' => 'required',
                'description' => 'required',
            ]
        );

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
}
