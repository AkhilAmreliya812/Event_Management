<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;



class AddEditAdminProfile extends Controller
{
    public function profile($id)
    {
        $user = User::find($id);
        return view('admin.profile', ['user' => $user]);
    }

    // update profile
    public function upddate_profile(Request $request)
    {
        $rules =  [
            'name' => 'required|min:3|max:70',
            'phone' => 'required|max:15',
            'dob' => 'required',
        ];

        $messages = [
            'name.required' => 'The name is required.',
            'name.min' => 'The name must be at least 3 characters long.',
            'name.max' => 'The name cannot exceed 70 characters.',
            
            'phone.required' => 'The phone number is required.',
            'phone.max' => 'The phone number cannot exceed 15 characters.',
            
            'dob.required' => 'The date of birth is required.',
        ];

        $validate = Validator::make($request->all(),$rules, $messages);
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        } else {
            $user = User::find($request->id);
            if ($user) {
                $user->name = $request->name;
                $user->phone = $request->phone;
                $user->dob = $request->dob;
                $user->email = $user->email;
                $user->profile_photo = $request->hasfile('profile_photo') ? $this->imageUpload($request->file('profile_photo')) : $user->profile_photo;
                $user->save();
                return redirect()->back()->with('success', 'Your profile updated successfully');
            } else {
                return redirect()->back()->with('error', 'User not found');
            }
        }
    }  


    // admin register form
    public function register()
    {
        return view('admin.admin_register');
    }

    // profile photo upload
    public function imageUpload($file)
    {
        $fileName = uniqid() . date('Ymd') . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('profile_photos'), $fileName);
        $profile_photo = "profile_photos/" . $fileName;
        return $profile_photo;
    }

    // save details
    public function saveDetails(Request $request)
    {
        $rules =  [
            'name' => 'required|max:70|min:2',
            'email' => 'required|email',
            'phone' => 'required|max:15',
            'password' => 'required|min:8|max:20|confirmed',
            'password_confirmation' => 'required',
            'dob' => 'required',
            'profile_photo' => 'required|mimes:jpg,jpeg,png,gif',
        ];

        $messages = [
            'name.required' => 'The name is required.',
            'name.min' => 'The name must be at least 2 characters long.',
            'name.max' => 'The name cannot exceed 70 characters.',
            
            'email.required' => 'The email address is required.',
            'email.email' => 'The email address must be a valid email format.',
            
            'phone.required' => 'The phone number is required.',
            'phone.max' => 'The phone number cannot exceed 15 characters.',
            
            'password.required' => 'The password is required.',
            'password.min' => 'The password must be at least 8 characters long.',
            'password.max' => 'The password cannot exceed 20 characters.',
            'password.confirmed' => 'The password confirmation does not match.',
            
            'password_confirmation.required' => 'The password confirmation is required.',
            
            'dob.required' => 'The date of birth is required.',
            
            'profile_photo.required' => 'The profile photo is required.',
            'profile_photo.mimes' => 'The profile photo must be a file of type: jpg, jpeg, png, or gif.',
        ];

        $validate = Validator::make($request->all(),$rules, $messages);
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        } else {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = $request->password;
            $user->dob = $request->dob;
            $user->profile_photo = $this->imageUpload($request->file('profile_photo'));
            $userExist = User::where('email', $request->email)->first();
            if ($userExist) {
                return redirect()->back()->with('error', $request->email . ' is alredy exist')->withInput();
            } else {
                $user->save();
            }
            return redirect()->route('admin-login')->with('success', 'User Registered Successfully');
        }
    }
}
