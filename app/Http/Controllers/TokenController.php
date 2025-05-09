<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TokenController extends Controller
{
    public function showForm()
    {
        return view('token-form');
    }

    public function generateToken(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return back()->withErrors(['message' => 'Credenciales inválidas']);
        }

        $user = Auth::user();
        $token = $user->createToken('AppExterna')->plainTextToken;

        return back()->with('token', $token);
    }
}
