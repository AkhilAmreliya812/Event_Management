<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgotPasswordMailer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ChangingAuthCredentials extends Controller
{
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
                        if (!Hash::check($request->password, $user->password)) {
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
}
