<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function dashbord() {
        return view('admin.dashbord');
    }

    public function events() {
        return view('admin.events');
    }

    public function contactLead() {
        return view('admin.contactLead');
    }

    public function register() {
        return view('admin.admin_register');
    }

    function imageUpload(Request $request) {
       
        $file = $request->file('profile_photo');
        $fileName = uniqid() . date('Ymd') . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('profile_photos'),$fileName);
        $profile_photo = "profile_photos/".$fileName;
        
        return $profile_photo;  
    }

    public function saveDetails(Request $request) {


        $validate = Validator::make($request->all(),
            [
                'name' => 'required|max:70|min:2',
                'email' => 'required|email',
                'phone' => 'required|max:15',
                'password' => 'required|min:8|max:20|confirmed',
                'password_confirmation' => 'required',
                'dob' => 'required',
                'profile_photo' => 'required'
            ]
        );

        if($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        } else {
            
            $user = new User();

            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = $request->password;
            $user->dob = $request->dob;
            $user->profile_photo = $this->imageUpload($request); 
            
            $userExist = User::where('email',$request->email)->first();

            if($userExist) {
                return redirect()->back()->with('error',$request->email . ' is alredy exist')->withInput();
            } else {
                $user->save();
            }

            return redirect()->route('admin-login')->with('success','User Registered Successfully');
        
        }
        

    }

    

    
}
