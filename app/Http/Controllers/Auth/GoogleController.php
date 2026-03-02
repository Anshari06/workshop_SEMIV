<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Mail; 

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::updateOrCreate(
            [
                'email' => $googleUser->getEmail(),
            ],
            [
                'username' => $googleUser->getName(),
                'google_id' => $googleUser->getId(),
                'password' => bcrypt('password_default')
            ]
        );

        $otp = (string) random_int(100000, 999999);
        $user->update([
            'otp_code' => $otp,
        ]);

        Mail::raw("Kode OTP kamu: $otp", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Kode OTP Login');
        });
        session(['otp_email' => $user->email]);

        return redirect()->route('otp.show');
    }
}
