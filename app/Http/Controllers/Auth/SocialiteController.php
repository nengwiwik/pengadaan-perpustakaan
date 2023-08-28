<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    protected bool $isPenerbit = false;
    protected bool $isDosen = false;
    protected bool $isMahasiswa = false;
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

        if ($authUser) {
            // setelah login redirect ke dashboard
            return redirect()->route('homepage')->with('success', 'Login berhasil');
        }

        return to_route('login')->withErrors(["email" => "Your account is created succesfully but not activated yet."])->withInput();
    }

    public function isUndira($email): bool
    {
        $email = trim($email); // in case there's any whitespace

        $authUser = User::where('email', $email)->first();

        if (mb_substr($email, -13) === '@undira.ac.id') $this->isDosen = true;
        if (mb_substr($email, -23) === '@mahasiswa.undira.ac.id') $this->isMahasiswa = true;
        // if (mb_substr($email, -10) === '@gmail.com') $this->isPenerbit = true;
        else $this->isPenerbit = true;

        // jika sbg penerbit dan belum terdaftar, maka lempar gagal
        if ($this->isPenerbit && !$authUser) return false;
        // jika sbg penerbit dan sudah terdaftar, maka sukses
        if ($this->isPenerbit && $authUser) return true;

        // jika email dosen/mahasiswa, maka sukses
        if ($this->isDosen || $this->isMahasiswa) return true;

        // kalau development, gas sukses
        if (app()->environment(['local', 'staging'])) {
            return true;
        }

        return false;
    }

    public function findOrCreateUser($user): bool
    {
        $authUser = User::where('email', $user->email)->first();

        if ($authUser) {
            if ($authUser->hasAnyRole(['Super Admin', 'Penerbit', 'Admin Prodi'])) {
                $authUser->update([
                    'name' => $user->name,
                    'google_id' => $user->id,
                ]);

                // login user
                auth()->login($authUser, true);
                return true;
            }
        } else {
            if (!$this->isDosen) return false;

            $authUser = User::create([
                'name' => $user->name,
                'email' => $user->email,
                'google_id' => $user->id,
                'email_verified_at' => now(),
                'password' => bcrypt($user->email),
            ]);
            event(new Registered($authUser));
            return false;
        }
    }
}
