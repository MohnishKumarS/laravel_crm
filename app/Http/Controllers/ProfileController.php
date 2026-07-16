<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
      public function index()
    {
        // return Auth::user()->email;
        return view('admin.profile');
    }

    public function updateProfile(Request $request)
    {
        // return $request->all();
        $request->validate([
            'name'  => 'required|max:25',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
        ]);

        $user = Auth::user();

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        return back()->with('message', 'Profile updated successfully.')->with('status', 'success');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->with('message', 'Current password is incorrect.')->with('status', 'danger');
        }

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('message', 'Password updated successfully.')->with('status', 'success');
    }
}
