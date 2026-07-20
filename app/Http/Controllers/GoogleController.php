<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect(Request $request)
    {
        if (!config('services.google.client_id') || !config('services.google.client_secret')) {
            return redirect()->route('home')->with('error', 'Google OAuth belum dikonfigurasi: periksa GOOGLE_CLIENT_ID dan GOOGLE_CLIENT_SECRET di .env.');
        }

        if ($request->has('next')) {
            $request->session()->put('socialite_redirect', $request->query('next'));
        }

        return Socialite::driver('google')->redirect();
    }

    public function callback(Request $request)
    {
        try {
            $socialUser = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $exception) {
            Log::error('Google login callback error: ' . $exception->getMessage());
            return redirect()->route('home')->with('error', 'Gagal masuk dengan Google. Silakan coba lagi.');
        }

        $user = User::where('provider', 'google')
            ->where('provider_id', $socialUser->getId())
            ->orWhere('email', $socialUser->getEmail())
            ->first();

        if ($user) {
            $user->fill([
                'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? $socialUser->getEmail(),
                'email' => $socialUser->getEmail(),
                'provider' => 'google',
                'provider_id' => $socialUser->getId(),
                'email_verified_at' => now(),
            ]);

            if (! $user->password) {
                $user->password = bcrypt(Str::random(40));
            }

            $user->save();
        } else {
            $user = User::create([
                'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? $socialUser->getEmail(),
                'email' => $socialUser->getEmail(),
                'provider' => 'google',
                'provider_id' => $socialUser->getId(),
                'email_verified_at' => now(),
                'password' => bcrypt(Str::random(40)),
                'role' => 'customer',
            ]);
        }

        Auth::login($user, true);
        $redirect = $request->session()->pull('socialite_redirect', route('home'));

        return redirect()->to($redirect)->with('success', 'Berhasil masuk dengan Google.');
    }
}