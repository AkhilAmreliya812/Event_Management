<?php
namespace App\Http\Controllers;

use App\Mail\OtpVerificationMailer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthenticateAdmin extends Controller
{
    public function login()
    {
        return view('admin.admin_login');
    }

    // login user
    public function authenticate(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];

        $messages = [
            'email.required' => 'The email address is required.',
            'email.email' => 'The email address must be a valid email format.',
            'password.required' => 'The password is required.',
        ];

        $credentials = $request->only('email', 'password');
        $validate = Validator::make($request->all(), $rules, $messages);
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

    public function resendOTP(Request $request)
    {
        $email = $request->session()->get('email');
        $user = User::where('email', $email)->first();
        if ($user) {
            if (Carbon::parse($user->updated_at)->diffInMinutes(Carbon::now()) > 10) {
                $otp = mt_rand(100000, 999999);
                User::where('email', $email)->update(['verification_code' => $otp]);
                Mail::to($email)->send(new OtpVerificationMailer(($user->name), $email, $otp));
                return response()->json(
                    [
                        'status' => 'success',
                        'message' => 'OTP sent successfully',
                    ]
                );
            } else {
                return response()->json(
                    [
                        'status' => 'error',
                        'message' => 'You can resend OTP after 10 minutes',
                    ]
                );
            }
        } else {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'User not found',
                ]
            );
        }
    }

    // logout after login
    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin-login');
    }
}
