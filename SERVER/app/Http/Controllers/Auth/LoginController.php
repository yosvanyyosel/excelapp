<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm() {
        return view('landing');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            if (Auth::user()->role === 'participant') {
                Auth::logout();
                return back()->withErrors(['username' => 'Acceso denegado. Use la aplicación móvil.']);
            }
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'username' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ]);
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    // API Login for Mobile App
    public function apiLogin(Request $request) {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = bin2hex(random_bytes(40)); // Simple token for this example
            // In a real Laravel app, use Sanctum or Passport
            
            return response()->json([
                'status' => 'success',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'pair_name' => $user->pair_name,
                    'pair_photo' => $user->pair_photo,
                    'center' => $user->center_id ? [
                        'id' => $user->center->id,
                        'name' => $user->center->name,
                        'timer' => $user->center->quiz_timer,
                    ] : null,
                ],
                'token' => $token
            ]);
        }

        return response()->json(['status' => 'error', 'message' => 'Credenciales inválidas'], 401);
    }
}
