<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index()
    {
        // Auth::logout();
        //  session()->flush();

    if (Auth::check() && Auth::user()->role === 'admin') {
        return redirect()->route('dashboard');
    }

    if (Auth::check() && Auth::user()->role === 'affiliate') {
        return redirect()->route('affiliate-portal.dashboard');
    }


    return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        // return $request->all();
        $credentials = $request->validate([
            'name' => ['required'],
            'password' => ['required'],
        ]);
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {

    $request->session()->regenerate();

    if (Auth::user()->role == 'admin') {
        return redirect()->route('dashboard');
    }

    if (Auth::user()->role == 'affiliate') {
        return redirect()->route('affiliate.self.dashboard');
    }

    Auth::logout();

    return redirect()->back()->with('status', 'danger')->with('message', 'You do not have permission to access the admin panel.');
   }

        return back()->withErrors([
            'name' => 'Invalid credentials! Please try again.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function register(Request $request)
    {
       $validate = $request->validate([
            'name' => 'required|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'user_role' => 'nullable',
        ]);

        // return $request->all();

        $role = $request->role ?? 'user';
        // return $role;

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role,
        ]);

        return redirect()->route('login')->with('status', 'success')->with('message', 'Account created successfully.');
    }
}
