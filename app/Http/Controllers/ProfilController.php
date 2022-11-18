<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function index()
    {
        $user = User::findOrFail(Auth::id());
        $type_menu = null;
        return view('profil', compact('user', 'type_menu'));
    }

    public function update(Request $request)
    {
        $id = Auth::id();

        $request->validate([
            'name' => 'required|string|min:2|max:100',
            'email' => 'required|email|max:100|unique:users,email, ' . $id . ',id',
        ]);
        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;

        $user->save();
        return back()->with('status', 'Profil updated!');
    }

    public function password(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed|min:8',
            'password_confirmation' => 'required',
        ]);

        $user = User::findOrFail(Auth::user()->id);
        if (Hash::check($request->current_password, $user->password)) {
            $user->password = Hash::make($request->password);
        } else {
            return redirect()->back()->withInput();
        }
        $user->save();
        return back()->with('status_password', 'Password changed!');
    }
}
