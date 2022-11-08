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
        return view('profil', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request()->validate([
            'name' => 'required|string|min:2|max:100',
            'email' => 'required|email|unique:users,email, ' . $id . ',id',
        ]);
        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;

        if(request()->hasFile('photo')){
            if($user->photo && file_exists(storage_path('app/public/photos/' . $user->photo))){
            Storage::delete('app/public/photos/'.$user->photo);
        }
        $file = $request->file('photo');
        $fileName = $file->hashName().'.'.$file->getClientOriginalExtension();
        $request->photo->move(storage_path('app/public/photos'), $fileName);
        $user->photo = $fileName;
    }
        $user->save();
        return back()->with('status', 'Profil updated!');

}
}
