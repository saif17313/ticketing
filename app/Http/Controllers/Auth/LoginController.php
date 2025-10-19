<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /**
     * Show the login form with toggle button for passenger/owner
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     * Validates user credentials and role (passenger or owner)
     */
    public function login(Request $request)
    {
        // Validate input fields
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
            'role' => 'required|in:passenger,owner',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }

        // Prepare credentials for authentication
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
            'role' => $request->role,  // Must match the selected role
            'is_active' => true,       // Only active users can login
        ];

        // Attempt to login
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            // Regenerate session to prevent fixation attacks
            $request->session()->regenerate();

            // Redirect based on role
            if (Auth::user()->role === 'owner') {
                return redirect()->intended('/owner/dashboard')
                    ->with('success', 'Welcome back, ' . Auth::user()->name . '!');
            } else {
                return redirect()->intended('/')
                    ->with('success', 'Welcome back, ' . Auth::user()->name . '!');
            }
        }

        // Login failed - check why
        $user = \App\Models\User::where('email', $request->email)->first();
        
        if (!$user) {
            $errorMsg = 'No account found with this email.';
        } elseif ($user->role !== $request->role) {
            $errorMsg = 'Please select the correct account type (' . ucfirst($user->role) . ').';
        } elseif (!$user->is_active) {
            $errorMsg = 'Your account has been deactivated. Contact support.';
        } else {
            $errorMsg = 'Invalid password. Please try again.';
        }

        return redirect()->back()
            ->withInput($request->except('password'))
            ->withErrors(['email' => $errorMsg]);
    }

    /**
     * Logout user and redirect to homepage
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('success', 'You have been logged out successfully.');
    }
}

