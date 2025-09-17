<?php
namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Mail\OtpVerificationMailer;
use Illuminate\Support\Carbon;
use App\Mail\ForgotPasswordMailer;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    // open login form
    public function login()
    {
        return view('admin.admin_login');
    }

    // login user
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        } else {
            $user = User::where('email', $request->only('email'))->first();
            if ($user && Hash::check($credentials['password'], $user->password)) {
                if ($user->email_verified == 'N') {
                    // Genrate Otp
                    $otp = mt_rand(100000, 999999);
                    User::where('email', $request->email)->update(['verification_code' => $otp]);
                    // Email verification mail sent
                    Mail::to($request->email)->send(new OtpVerificationMailer($user->name, $request->email, $otp));
                    // email and password store in session
                    $request->session()->put('email', $request->email);
                    $request->session()->put('password', $request->password);
                    return redirect()->route('admin-otpForm')->with('success', 'We sent you 6 digit verification code on your email please check your mailbox');
                } else {
                    // Authenticate user
                    if (Auth::attempt($credentials)) {
                        return redirect()->route('admin-dashbord');
                    } else {
                        return redirect()->back()->with('error', 'Invalid Credentials');
                    }
                }
            } else {
                return redirect()->back()->with('error', 'Invalid Credentials');
            }
        }
    }

    // otp form
    public function getOTP()
    {
        return view('admin.otp_verification');
    }

    // otp check
    public function verifyOTP(Request $request)
    {
        $validate = Validator::make($request->all(), ['otp' => 'required']);
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate);
        } else {
            $user = User::where('email', $request->only('email'))->first();
            if (Carbon::parse($user->updated_at)->diffInMinutes(Carbon::now()) > 10) {
                return redirect()->back()->with('error', 'OTP has been expired');
            }
            if ($request->otp == $user->verification_code) {
                
                User::where('email', $request->email)->update(['email_verified' => 'Y', 'verification_code' => null]);
                $credentials = $request->only('email', 'password');
                if (Auth::attempt(($credentials))) {
                    return redirect()->route('admin-dashbord');
                } else {
                    return redirect()->back()->with('error', 'Something went wrong try again');
                }
            } else {
                return redirect()->back()->with('error', 'Invalid OTP');
            }
        }
    }

    // Chanhe password after login
    public function changePassword()
    {
        return view('admin.change_password');
    }

    // crate new password
    public function setNewPassword(Request $request)
    {
        $validate = Validator::make($request->all(),
            [
                'old_password' => 'required',
                'password' => 'required|confirmed|:min:8',
                'password_confirmation' => 'required',
            ]
        );
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        } else {
            $user = Auth::user();
            if (!Hash::check($request->old_password, $user->password)) {
                return redirect()->back()->with(['error' => 'The old password is incorrect.']);
            } else {
                if ($request->old_password == $request->password) {
                    return redirect()->back()->with('error', 'Old password and New password can not be same');
                } else {
                    User::where('email', $request->email)->update(['password' => Hash::make($request->password)]);
                    Auth::logout();
                    return redirect()->route('admin-login')->with('success', 'Password changed successfully');
                }
            }
        }
    }

    // forgot password
    public function forgotPassword()
    {
        return view('admin.forgot_password');
    }

    // send forgot password link via email
    public function sentResetLink(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate);
        }
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return redirect()->back()->with('error', 'This email address does not exist');
        }
        $resetTableData = DB::table('password_reset_tokens')->where('email', $user->email)->first();
        if ($resetTableData && Carbon::parse($resetTableData->created_at)->diffInMinutes(Carbon::now()) < 10) {
            return redirect()->back()->with('error', 'Password reset link already sent, please check your email');
        }
        if ($resetTableData) {
            DB::table('password_reset_tokens')->where('email', $user->email)->delete();
        }
        $token = Str::random(128);
        DB::table('password_reset_tokens')->insert([
            'email' => $user->email,
            'token' => $token,
            'created_at' => now(),
        ]);
        Mail::to($user->email)->send(new ForgotPasswordMailer($user->name, $token));
        return redirect()->back()->with('success', 'Password reset link sent successfully to ' . $user->email);
    }

    // reset password form 
    public function resetPassword($token)
    {
        return view('admin.reset_password', ['resetToken' => $token]);
    }

    // submit new password
    public function submitResetPassword(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate);
        } else {
            $resetTabaleData = DB::table('password_reset_tokens')->where('token', $request->token)->first();
            if ($resetTabaleData) {
                if (Carbon::parse($resetTabaleData->created_at)->diffInMinutes(Carbon::now()) > 10) {
                    DB::table('password_reset_tokens')->where('token', $resetTabaleData->token)->delete();
                    return redirect()->route('admin-forgotPassword')->with('error', 'Token expired resend link');
                } else {
                    $user = User::where('email', $resetTabaleData->email)->first();
                    if ($user) {
                        if(!Hash::check($request->password, $user->password)) {
                            if (User::where('email', $user->email)->update(['password' => Hash::make($request->password)])) {
                                DB::table('password_reset_tokens')->where('email', $user->email)->delete();
                                return redirect()->route('admin-login')->with('success', 'Password updated successfully...');
                            } else {
                                return redirect()->back()->with('error', 'Password not updated');
                            }
                        } else {
                            return redirect()->back()->with('error', 'New password can not be same as old password');
                        }
                       
                    } else {
                        return redirect()->back()->with('error', 'User not found');
                    }
                }
            } else {
                return redirect()->route('admin-forgotPassword')->with('error', 'Invalid Token resend link');
            }
        }
    }

    // logout after login
    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin-login');
    }

    // logged in user profile
    public function profile($id)
    {
        $user = User::find($id);
        return view('admin.profile', ['user' => $user]);
    }

    // update profile
    public function upddate_profile(Request $request)
    {
        $validate = Validator::make($request->all(),
            [
                'name' => 'required|min:3|max:70',
                'phone' => 'required|max:15',
                'dob' => 'required',
            ]);
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        } else {
            $user = User::find($request->id);
            if ($user) {
                $user->name = $request->name;
                $user->phone = $request->phone;
                $user->dob = $request->dob;
                $user->email = $user->email;
                $user->profile_photo = $request->hasfile('profile_photo') ? $this->imageUpload($request) : $user->profile_photo;
                $user->save();
                return redirect()->back()->with('success', 'Your profile updated successfully');
            } else {
                return redirect()->back()->with('error', 'User not found');
            }
        }
    }


    // admin dashbord
    public function dashbord()
    {
        $latestContactLead = Contact::latest()->take(5)->get();
        return view('admin.dashbord', ['conatctLead' => $latestContactLead]);
    }

    // display all events
    public function events()
    {
        $events = Event::all();
        return view('admin.events', ['events' => $events]);
    }

    // display all contact lead
    public function contactLead()
    {
        $data = Contact::all();
        return view('admin.contactLead', ['data' => $data]);
    }
    // get description by ajax
    public function getDescription(Request $request)
    {
        $description = Contact::where('id', $request->id)->value('description');
        return $description;
    }

    // admin register form 
    public function register()
    {
        return view('admin.admin_register');
    }

    // profile photo upload
    public function imageUpload(Request $request)
    {
        $file = $request->file('profile_photo');
        $fileName = uniqid() . date('Ymd') . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('profile_photos'), $fileName);
        $profile_photo = "profile_photos/" . $fileName;
        return $profile_photo;
    }

    // save details
    public function saveDetails(Request $request)
    {
        $validate = Validator::make($request->all(),
            [
                'name' => 'required|max:70|min:2',
                'email' => 'required|email',
                'phone' => 'required|max:15',
                'password' => 'required|min:8|max:20|confirmed',
                'password_confirmation' => 'required',
                'dob' => 'required',
                'profile_photo' => 'required',
            ]
        );
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        } else {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = $request->password;
            $user->dob = $request->dob;
            $user->profile_photo = $this->imageUpload($request);
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
