<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UpdatePasswordController extends Controller
{
    public function __invoke(Request $request)
    {
        if (!$request->has('password')) {
            $type_menu = null;
            $title = "Change Password";
            return view('password', compact('type_menu', 'title'));
        }

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
