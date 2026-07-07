<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\SendOtpMailJob;
use App\Mail\OtpMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    public function index()
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();
        // print_r($user->email);exit;

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email not found'
            ]);
        }

        $otp = rand(100000, 999999);

        $updated = $user->update([
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(10)
        ]);

        // JOB & QUEUE for emailOTP
        SendOtpMailJob::dispatch($user, $otp);
        session(['reset_email' => $user->email]);
        return redirect()->route('verify.otp')->with('message', 'OTP sent successfully! You will receive a password recovery OTP at your email address in a few minutes.')->with('status', 'success');

        // try {

        //     Mail::send('emails.otp', ['otp' => $otp], function ($mail) use ($user) {

        //         $mail->to($user->email)
        //             ->subject('Password Reset OTP');
        //     });

        //     session(['reset_email' => $user->email]);
        //     Log::info('Email send successfully to ' . $user->email);

        //     return redirect()->route('verify.otp')->with('message', 'OTP sent successfully! You will receive a password recovery OTP at your email address in a few minutes.')->with('status', 'success');
        // } catch (\Exception $e) {
        //     Log::error('Email sending failed: ' . $e->getMessage());
        //     return back()->with('message', 'Failed to send OTP')->with('status', 'danger');
        // }


    }

    public function verifyPage()
    {
        if (!session('reset_email')) {
            return redirect()->route('login');
        }
        return view('auth.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required'
        ]);

        $user = User::where('email', session('reset_email'))
            ->where('otp', $request->otp)
            ->first();

        if (!$user) {
            return back()->withErrors([
                'otp' => 'Invalid OTP'
            ]);
        }

        if (Carbon::parse($user->otp_expires_at)->isPast()) {
            return back()->withErrors([
                'otp' => 'OTP expired'
            ]);
        }

        session(['otp_verified' => true]);

        return redirect()->route('reset.password');
    }

    public function showResetPasswordForm()
    {
        if (!session('otp_verified')) {
            return redirect()->route('login')->with('message', 'Please verify OTP first.')->with('status', 'danger');
        }

        return view('auth.reset-password');
    }

    public function resetPassword(Request $request)
    {
        if (!session('otp_verified')) {
            return redirect()->route('login')->with('message', 'OTP verification failed.')->with('status', 'danger');
        }

        $request->validate([
            'password' => 'required|confirmed|min:6'
        ]);

        $user = User::where('email', session('reset_email'))->first();

        $user->update([
            'password' => Hash::make($request->password),
            'otp' => null,
            'otp_expires_at' => null
        ]);

        session()->forget(['reset_email', 'otp_verified']);

        return redirect()->route('login')->with('message', 'Password reset successfully.')->with('status', 'success');
    }
}
