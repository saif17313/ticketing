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
     * Handle login request with enhanced validation and session management
     * Validates user credentials, role, and account status
     */
    public function login(Request $request)
    {
        // Enhanced validation with custom messages
        $validator = Validator::make($request->all(), [
            'email' => [
                'required',
                'email',
                'max:255',
            ],
            'password' => [
                'required',
                'string',
                'min:6',  // Minimum 6 characters (we'll upgrade this to 8 for new registrations)
            ],
            'role' => [
                'required',
                'in:passenger,owner',
            ],
        ], [
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'password.required' => 'Please enter your password.',
            'password.min' => 'Password must be at least 6 characters.',
            'role.required' => 'Please select account type (Passenger or Owner).',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }

        // Prepare credentials for authentication
        $credentials = [
            'email' => strtolower(trim($request->email)),  // Normalize email (lowercase, no spaces)
            'password' => $request->password,
            'role' => $request->role,      // Must match the selected role in toggle
            'is_active' => true,           // Only active accounts can login
        ];

        // Check if "Remember Me" checkbox was checked
        // filled() returns true if field exists and is not empty
        $remember = $request->filled('remember');

        // Attempt authentication with credentials
        // Second parameter controls "Remember Me" functionality
        // If true, Laravel creates a long-lived "remember_token" cookie (default 5 years)
        if (Auth::attempt($credentials, $remember)) {
            
            // Security measure: Regenerate session ID to prevent session fixation attacks
            // This creates a new session ID while keeping the session data
            $request->session()->regenerate();

            // Get the authenticated user
            $user = Auth::user();

            // Role-based redirection with personalized welcome message
            if ($user->role === 'owner') {
                // Owner users go to their management dashboard
                return redirect()->intended('/owner/dashboard')
                    ->with('success', 'Welcome back, ' . $user->name . '! 🏢');
            } else {
                // Passenger users go to homepage
                return redirect()->intended('/')
                    ->with('success', 'Welcome back, ' . $user->name . '! 🎫');
            }
        }

        // === Login Failed - Provide Helpful Error Message ===
        
        // Find user by email to determine specific failure reason
        $user = \App\Models\User::where('email', strtolower(trim($request->email)))->first();
        
        if (!$user) {
            // Email not found in database
            $errorMsg = 'No account found with this email address.';
        } elseif ($user->role !== $request->role) {
            // User exists but wrong role selected (e.g., owner trying to login as passenger)
            $errorMsg = 'Incorrect account type. Please select "' . ucfirst($user->role) . '" instead.';
        } elseif (!$user->is_active) {
            // Account exists but has been deactivated by admin
            $errorMsg = 'Your account has been deactivated. Please contact support.';
        } else {
            // Email and role correct, but password is wrong
            $errorMsg = 'Invalid password. Please try again.';
        }

        // Redirect back to login form with error message and previous email
        return redirect()->back()
            ->withInput($request->except('password'))  // Keep email but not password
            ->withErrors(['email' => $errorMsg]);
    }

    /**
     * Logout user with complete session cleanup
     * Invalidates session and regenerates CSRF token
     */
    public function logout(Request $request)
    {
        // Log the user out (removes authentication data)
        Auth::logout();
        
        // Invalidate the entire session (removes all session data)
        // This prevents old session data from being used
        $request->session()->invalidate();
        
        // Regenerate CSRF token (security measure)
        // This prevents CSRF attacks using old tokens
        $request->session()->regenerateToken();
        
        // Redirect to homepage with goodbye message
        return redirect('/')
            ->with('success', 'You have been logged out successfully. Come back soon! 👋');
    }
}

