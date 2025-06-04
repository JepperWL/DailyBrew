<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminLoginController extends Controller
{
    //
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $credentials['role'] = 'admin';

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/admin/dashboard')->with('success', 'Login successful');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials or not an admin.',
        ]);
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login')->with('success', 'Logout successful');
    }

    public function showForgetPasswordForm()
    {
        return view('admin.forget-password');
    }
    public function forgetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->where('role', 'admin')->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email not found in our records.'
            ]);
        }

        $request->session()->put('reset_email', $request->email);

        return redirect()->route('admin.update-password')->with('success', 'Email verified! Please enter your new password.');
    }

    public function showUpdatePasswordForm(Request $request)
    {
        if (!$request->session()->has('reset_email')) {
            return redirect()->route('admin.forget-password')->with('error', 'Please verify your email first.');
        }

        return view('admin.update-password', [
            'email' => $request->session()->get('reset_email')
        ]);
    }
    public function updatePassword(Request $request)
    {
        if (!$request->session()->has('reset_email')) {
            return redirect()->route('admin.forget-password')->with('error', 'Session expired. Please verify your email again.');
        }

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $email = $request->session()->get('reset_email');
        $user = User::where('email', $email)->where('role', 'admin')->first();

        if (!$user) {
            return redirect()->route('admin.forget-password')->with('error', 'email not found not found.');
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        $request->session()->forget('reset_email');

        return redirect()->route('admin.login')->with('success', 'Password updated successfully! Please login with your new password.');
    }

}
