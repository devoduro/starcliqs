<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

/**
 * The signed-in admin's own account, plus a simple list of other admins. Every user in this
 * application is an administrator of the website.
 */
class ProfileController extends Controller
{
    public function edit()
    {
        return view('admin.profile', ['user' => auth()->user(), 'admins' => User::orderBy('name')->get()]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
        ]);

        $user->update($data);

        return back()->with('success', 'Profile updated.');
    }

    public function password(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $request->user()->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password changed.');
    }

    public function storeAdmin(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => ['required', Password::min(8)],
        ]);

        User::create(['name' => $data['name'], 'email' => $data['email'], 'password' => Hash::make($data['password'])]);

        return back()->with('success', 'Administrator added.');
    }

    public function destroyAdmin(User $user)
    {
        if ($user->is(auth()->user())) {
            return back()->with('error', 'You cannot remove your own account.');
        }

        $user->delete();

        return back()->with('success', 'Administrator removed.');
    }
}
