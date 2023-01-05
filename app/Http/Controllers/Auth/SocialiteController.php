<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProvideCallback($provider)
    {
        try {
            // $user = Socialite::driver($provider)->stateless()->user();
            $user = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            Log::withContext(['provider' => $provider])->error($e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat login, silahkan coba lagi');
        }

        // cek Undira atau bukan
        if (!$this->isUndira($user->email)) {
            return to_route('login')->withErrors(["email" => "Are you sure the email is from Undira?"])->withInput();
        }

        // cari atau buat user dan kirim parameter user yang didapat dari socialite dan provider
        $authUser = $this->findOrCreateUser($user);

        // login user
        auth()->login($authUser, true);

        // setelah login redirect ke dashboard
        return redirect()->route('homepage')->with('success', 'Login berhasil');
    }

    public function isUndira($email)
    {
        $email = trim($email); // in case there's any whitespace

        return mb_substr($email, -13) === '@undira.ac.id'; // or mb_substr($email, -23) === '@mahasiswa.undira.ac.id';
    }

    public function findOrCreateUser($user)
    {
        $authUser = User::where('email', $user->email)->first();

        if ($authUser) {
            $authUser->update([
                'name' => $user->name,
                'google_id' => $user->id,
                'photo' => $user->avatar,
            ]);
            return $authUser;
        }

        return User::create([
            'name' => $user->name,
            'email' => $user->email,
            'google_id' => $user->id,
            'photo' => $user->avatar,
            'email_verified_at' => now(),
            'password' => bcrypt(str()->random(18)),
        ]);
    }
}
