<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AuditLog;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Log successful login (though CFR 21 Part 11 mainly applies to transactional DB actions,
            // system access can also be recorded).
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'login',
                'model_type' => 'App\Models\User',
                'model_id' => Auth::id(),
                'new_values' => json_encode(['email' => $request->email]),
                'ip_address' => $request->ip(),
                'reason' => 'User logged into MES system',
            ]);

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas son incorrectas o no están autorizadas.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
