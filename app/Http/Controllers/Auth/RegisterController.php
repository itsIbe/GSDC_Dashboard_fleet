<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Validate input and store in $validated
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255', // 👈 Added lastname
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',

            // Additional fields
            'role' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'bu' => 'nullable|string|max:255',
        ]);

        // Create user using validated data
        $user = User::create([
            'name' => $validated['name'],
            'lastname' => $validated['lastname'], // 👈 Save lastname
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'department' => $validated['department'] ?? null,
            'location' => $validated['location'] ?? null,
            'position' => $validated['position'] ?? null,
            'bu' => $validated['bu'] ?? null,
        ]);

        // Redirect to dashboard or home
        return redirect()->route('register')->with('success', 'Registered successfully!');
    }
}
