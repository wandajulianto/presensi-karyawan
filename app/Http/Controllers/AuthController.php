<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Handle login request for 'karyawan' guard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'nik' => ['required', 'numeric'],
            'password' => ['required', 'string'],
        ]);

        // Attempt login with guard 'karyawan'
        if (Auth::guard('karyawan')->attempt($credentials)) {
            $request->session()->regenerate(); // Hindari session fixation
            return redirect()->intended('/dashboard');
        }

        // Jika gagal, redirect kembali dengan error
        return back()->with('warning', 'NIK atau Password salah.');
    }

    /**
     * Logout the authenticated karyawan user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::guard('karyawan')->logout();

        // Invalidate dan regenerate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
