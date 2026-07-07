<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\AuditLog;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); // custom login view
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);

            // ✅ Audit Log for successful login
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'Login',
                'ip_address' => $request->ip(),
                'description' => 'User logged in successfully',
            ]);

            return redirect()->intended('/home');
        }

        // ✅ Audit Log for failed login attempt
        AuditLog::create([
            'user_id' => $user->id ?? null,
            'action' => 'Failed Login',
            'ip_address' => $request->ip(),
            'description' => 'Failed login attempt for email: ' . $request->email,
        ]);

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function logout(Request $request)
    {
        // ✅ Audit Log before logout
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Logout',
            'ip_address' => $request->ip(),
            'description' => 'User logged out successfully',
        ]);

        Auth::logout();
        return redirect('/');
    }
}
