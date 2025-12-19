<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        return view('user.login.index');
    }

    public function auth(Request $request)
    {

        $request->validate([
            'email'    => 'required|string',
            'password' => 'required|string',
        ]);

        $email = $request->email;
        $password = $request->password;

        $credentials = filter_var($email, FILTER_VALIDATE_EMAIL)
            ? ['email' => $email, 'password' => $password]
            : ['name' => $email, 'password' => $password];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('dashboard')
                ->with('success', 'Login berhasil');
        }

        // Jika gagal
        return back()->withErrors([
            'login' => 'Email/Username atau password salah',
        ])->withInput();
    }
}
