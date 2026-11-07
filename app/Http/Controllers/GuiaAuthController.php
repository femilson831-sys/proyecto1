<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guia;

class GuiaAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('guia.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $guia = Guia::where('email', $request->email)->first();

        if (!$guia) {
            return back()->withErrors(['email' => 'Credenciales incorrectas']);
        }

        if ($guia->estado !== 'activo') {
            return back()->withErrors(['email' => 'Tu cuenta está inactiva']);
        }

        if (!password_verify($request->password, $guia->password)) {
            return back()->withErrors(['password' => 'Contraseña incorrecta']);
        }

        session([
            'guia_logged_in' => true,
            'guia_id' => $guia->id,
            'guia_nombre' => $guia->nombre,
            'guia_email' => $guia->email
        ]);

        return redirect()->route('guia.dashboard');
    }

    public function logout(Request $request)
    {
        session()->forget(['guia_logged_in', 'guia_id', 'guia_nombre', 'guia_email']);
        return redirect()->route('guia.login')->with('success', 'Sesión cerrada');
    }

    public function dashboard()
    {
        if (!session()->has('guia_logged_in')) {
            return redirect()->route('guia.login')->with('error', 'Sesión no válida');
        }

        $guia = Guia::find(session('guia_id'));
        
        if (!$guia) {
            session()->forget(['guia_logged_in', 'guia_id']);
            return redirect()->route('guia.login')->with('error', 'Guía no encontrado');
        }

        return view('guia.dashboard', compact('guia'));
    }
}