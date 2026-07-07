<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AuditLog;
AuditLog::create([
    'user_id' => Auth::id(),
    'action' => 'Logout',
    'ip_address' => request()->ip(),
    'description' => 'User logged out successfully',
]);
class LogoutController extends Controller
{
    public function logout(Request $request)
{
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login');
}

}
