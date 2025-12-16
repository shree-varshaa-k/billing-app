<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class SettingController extends Controller
{
    /**
     * Show the settings page.
     */
    public function index()
    {
        $userSession = Session::get('user');

        if (!$userSession) {
            return redirect()->route('login')->with('error', 'Please log in to access settings.');
        }

        // Handle if session contains only user ID or full object
        $userId = is_object($userSession) ? $userSession->id : $userSession;

        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('login')->with('error', 'User not found.');
        }

        return view('settings', compact('user'));
    }

    /**
     * Update the logged-in user's profile.
     */
   public function update(Request $request)
   {
    $userSession = Session::get('user');
    if (!$userSession) {
        return redirect()->route('login')->with('error', 'You must be logged in to update your profile.');
    }

    // Get user ID from session (object or integer)
    $userId = is_object($userSession) ? $userSession->id : $userSession;
    $user = User::find($userId);

    if (!$user) {
        return redirect()->route('login')->with('error', 'User not found.');
    }

    // Validate input
    $validated = $request->validate([
        'username' => ['required', 'string', 'max:255'],
        'password' => ['nullable', 'string', 'min:6'],
        'logo'     => ['nullable', 'image', 'mimes:jpg,jpeg,png,svg', 'max:2048'],
    ]);

    // Update username
    $user->username = $validated['username'];

    // Update password only if provided
    if (!empty($validated['password'])) {
        $user->password = Hash::make($validated['password']);
    }

    // Handle logo upload
    if ($request->hasFile('logo')) {
        $file = $request->file('logo');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/logos'), $filename);
        $user->logo = 'uploads/logos/' . $filename;
    }

    $user->save();

    Session::put('user', $user->id);

    return redirect()->route('settings.index')->with('success', 'Profile updated successfully!');
   }

}
