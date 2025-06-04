<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
     public function showLoginForm()
    {
        return view('user.login');
    }
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $credentials['role'] = 'user';

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/user/home')->with('success', 'Login successful');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials or not an user.',
        ]);
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/user/login')->with('success', 'Logout successful');
    
    }


    public function showRegisterForm()
    {
        return view('user.register');
    }

    public function register(Request $request)
    {
       
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $validatedData['role'] = 'user';
        $validatedData['password'] = bcrypt($validatedData['password']);

        User::create($validatedData);

        return redirect(route('user.login'))->with('success', 'Registration successful. Please login.');

       
    }

    public function showForgetPasswordForm()
    {
        return view('user.forget-password');
    }
    public function forgetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->where('role', 'user')->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email not found in our records.'
            ]);
        }

        $request->session()->put('reset_email', $request->email);

        return redirect()->route('user.update-password')->with('success', 'Email verified! Please enter your new password.');
    }

    public function showUpdatePasswordForm(Request $request)
    {
        if (!$request->session()->has('reset_email')) {
            return redirect()->route('user.forget-password')->with('error', 'Please verify your email first.');
        }

        return view('user.update-password', [
            'email' => $request->session()->get('reset_email')
        ]);
    }
    public function updatePassword(Request $request)
    {
        if (!$request->session()->has('reset_email')) {
            return redirect()->route('user.forget-password')->with('error', 'Session expired. Please verify your email again.');
        }

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $email = $request->session()->get('reset_email');
        $user = User::where('email', $email)->where('role', 'user')->first();

        if (!$user) {
            return redirect()->route('user.forget-password')->with('error', 'email not found not found.');
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        $request->session()->forget('reset_email');

        return redirect()->route('user.login')->with('success', 'Password updated successfully! Please login with your new password.');
    }


}
