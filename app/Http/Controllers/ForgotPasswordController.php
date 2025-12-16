<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    // Show Forgot Password form
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    // Manually reset password using username
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        // Check if username exists
        $user = DB::table('users')->where('username', $request->username)->first();

        if (!$user) {
            return back()->withErrors(['username' => 'Username not found']);
        }

        // Update password
        DB::table('users')
            ->where('username', $request->username)
            ->update(['password' => Hash::make($request->new_password)]);

        return redirect()->route('login')->with('status', 'Password reset successfully!');
    }
}
