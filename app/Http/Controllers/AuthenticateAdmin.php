<?php
namespace App\Http\Controllers;

use App\Mail\ForgotPasswordMailer;
use App\Mail\OtpVerificationMailer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthenticateAdmin extends Controller
{
    public function login() { return view('admin.admin_login'); }

    public function getOTP(){ return view('admin.otp_verification'); }

    public function verifyOTP(Request $request)
    {
        $validate = Validator::make($request->all(),['otp' => 'required',]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate);
        } else {

            $user = User::where('email', $request->only('email'))->first();

            if(Carbon::parse($user->updated_at)->diffInMinutes(Carbon::now()) > 10) {
                return redirect()->back()->with('error', 'OTP has been expired');
            }

            if ($request->otp == $user->verification_code) {
                User::where('email', $request->email)->update(['email_verified' => 'Y','verification_code'=>NULL]);
                $credentials = $request->only('email', 'password');
                if (Auth::attempt(($credentials))) {
                    return redirect()->route('admin-dashbord');
                }

            } else { return redirect()->back()->withErrors('otp', 'Invalid OTP'); }
        }
    }

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

            if($user && Hash::check($credentials['password'],$user->password)) {
                if ($user->email_verified == 'N') {

                    $otp = mt_rand(100000, 999999);
                    User::where('email', $request->email)->update(['verification_code' => $otp]);
                    //updtes
                    Mail::to($request->email)->send(new OtpVerificationMailer($user->name, $request->email, $otp));
    
                    $request->session()->put('email', $request->email);
                    $request->session()->put('password', $request->password);
    
                    return redirect()->route('admin-otpForm')->with('success', 'We sent you 6 digit verification code on your email please check your mailbox');
                }
    
                if (Auth::attempt($credentials)) {
                    // $user = User::where('email', $request->only('email'))->first();
    
                    return redirect()->route('admin-dashbord');
                } else {
                    return redirect()->back()->with('error', 'Invalid Credentials');
                }
            } else {
                return redirect()->back()->with('error', 'Invalid ');
            }
        }
    }

    public function changePassword() { return view('admin.change_password'); }

    public function setNewPassword(Request $request) {
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
            }

            User::where('email', $request->email)->update(['password' => Hash::make($request->password)]);
            Auth::logout();
            return redirect()->route('admin-login')->with('success', 'Password changed successfully');
        }

    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin-login');
    }

    public function forgotPassword()
    {
        return view('admin.forgot_password');
    }

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

    public function resetPassword($token)
    {

        return view('admin.reset_password', ['resetToken' => $token]);
    }

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
                        if (User::where('email', $user->email)->update(['password' => Hash::make($request->password)])) {
                            DB::table('password_reset_tokens')->where('email', $user->email)->delete();
                            return redirect()->route('admin-login')->with('success', 'Password updated successfully !! Login Now ....');
                        } else {
                            return redirect()->back()->with('error', 'Password not updated');
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
}
