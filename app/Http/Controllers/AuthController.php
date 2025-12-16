<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validate user input
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Find user by username
        $user = User::where('username', $request->username)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // Store user info in session
            Session::put('user', $user->id);
            Session::put('username', $user->username);

            return redirect()->route('dashboard');
        }

        return back()->with('error', 'Invalid username or password');
    }

    public function logout()
    {
        Session::flush();
        return redirect('/login');
    }
}
