<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function form(): View
    {
        return view('login.index');
    }

    /**
     * Login memakai kolom username (sesuai rancangan tabel, bukan email).
     */
    public function login(Request $request): RedirectResponse
    {
        $kredensial = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($kredensial)) {
            throw ValidationException::withMessages([
                'username' => 'Username atau password salah.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()
            ->intended(route('barang.index'))
            ->with('sukses', 'Selamat datang, ' . Auth::user()->nama_lengkap . '.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('barang.index')
            ->with('info', 'Kamu telah keluar.');
    }
}
