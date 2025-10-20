<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Show the registration form (only for passengers)
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration request
     * Only passengers can register - owners are added manually by admin
     */
    public function register(Request $request)
    {
        // Enhanced validation with custom rules and messages
        $validator = Validator::make($request->all(), [
            // Name validation: required, string, 3-255 chars, alphabets and spaces only
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'regex:/^[a-zA-Z\s]+$/',  // Only letters and spaces
            ],
            
            // Email validation: required, valid email format, unique in users table
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',  // Check uniqueness in users table's email column
            ],
            
            // Phone validation: required, Bangladesh format (11 digits starting with 01)
            'phone' => [
                'required',
                'string',
                'regex:/^01[0-9]{9}$/',  // Must be 01XXXXXXXXX (11 digits)
                'unique:users,phone',     // Phone must be unique
            ],
            
            // Password validation: minimum 8 chars, at least 1 letter and 1 number, must match confirmation
            'password' => [
                'required',
                'string',
                'min:8',                           // At least 8 characters
                'regex:/[a-zA-Z]/',                // Must contain at least one letter
                'regex:/[0-9]/',                   // Must contain at least one number
                'confirmed',                        // Must match password_confirmation field
            ],
            
            // Address validation: optional, max 500 chars
            'address' => 'nullable|string|max:500',
            
            // NID validation: optional, exactly 10 or 13 or 17 digits (BD NID formats)
            'nid' => [
                'nullable',
                'string',
                'regex:/^[0-9]{10}$|^[0-9]{13}$|^[0-9]{17}$/',  // 10, 13, or 17 digits
                'unique:users,nid',  // NID must be unique if provided
            ],
        ], [
            // Custom error messages for better user experience
            'name.regex' => 'Name can only contain letters and spaces.',
            'name.min' => 'Name must be at least 3 characters.',
            
            'phone.regex' => 'Phone must be a valid Bangladesh number (e.g., 01712345678).',
            'phone.unique' => 'This phone number is already registered.',
            
            'password.min' => 'Password must be at least 8 characters long.',
            'password.regex' => 'Password must contain at least one letter and one number.',
            
            'nid.regex' => 'NID must be either 10, 13, or 17 digits.',
            'nid.unique' => 'This NID is already registered.',
        ]);

        // If validation fails, redirect back with errors and old input (except passwords)
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password', 'password_confirmation'));
        }

        // Create new passenger user with validated data
        $user = User::create([
            'name' => trim($request->name),           // Remove extra spaces
            'email' => strtolower($request->email),   // Convert to lowercase for consistency
            'phone' => $request->phone,
            'password' => Hash::make($request->password),  // Hash password securely
            'role' => 'passenger',                    // Always passenger for self-registration
            'address' => $request->address ? trim($request->address) : null,
            'nid' => $request->nid,
            'is_active' => true,                      // New accounts are active by default
        ]);

        // Automatically login the newly registered user
        Auth::login($user);

        // Redirect to homepage with success message
        return redirect('/')
            ->with('success', 'Registration successful! Welcome, ' . $user->name . '!');
    }
}

