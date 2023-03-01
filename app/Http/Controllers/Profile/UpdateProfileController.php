<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpdateProfileController extends Controller
{
    public function __invoke(Request $request)
    {
        if (!$request->has('name')) {
            $user = User::findOrFail(Auth::id());
            $type_menu = null;
            $title = "Update Profile";
            return view('profil', compact('user', 'type_menu', 'title'));
        }

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
}
