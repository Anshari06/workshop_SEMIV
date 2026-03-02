<?php

namespace App\Http\Controllers;

use App\Mail\OtpCodeMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OTPController extends Controller
{
    public function show()
    {
        return view('auth.otp');
    }

    public function verify(Request $request)
    {
        $request->validate(['otp_code' => 'required|digits:6']);

        $email = $request->session()->get('otp_email');
        if (!$email) {
            return redirect()->route('login')->withErrors(['otp' => 'Sesi OTP tidak ditemukan.']);
        }

        $user = User::where('email', $email)->first();

        // Debug: Log info untuk membantu troubleshooting
        \Log::info('OTP Verification Attempt', [
            'email' => $email,
            'otp_submitted' => $request->otp_code,
            'otp_in_db' => $user?->otp_code,
            'match' => $user?->otp_code === $request->otp_code ? 'YES' : 'NO',
        ]);

        if (!$user || $user->otp_code !== $request->otp_code) {
            return back()->withErrors(['otp' => 'OTP tidak valid. Pastikan OTP yang Anda masukkan sesuai dengan yang dikirim ke email.']);
        }

        $user->update(['otp_code' => null]);
        $request->session()->forget('otp_email');
        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function resend(Request $request)
    {
        $email = $request->session()->get('otp_email');
        $user = $email ? User::where('email', $email)->first() : null;
        
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $otp = (string) random_int(100000, 999999);
        $user->update([
            'otp_code' => $otp,
        ]);

        Mail::to($user->email)->send(new OtpCodeMail($otp));

        return response()->json(['success' => 'OTP sent']);
    }
}