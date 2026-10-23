<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Ringkasan pengguna yang sedang login, plus daftar user untuk admin.
     *
     * Daftar user hanya diambil kalau pengguna memiliki ability manage-roles.
     * Akun admin ikut ditampilkan supaya percobaan penolakan BR-04 dapat
     * dilakukan dari halaman ini; service yang menolaknya, bukan filter di sini.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        return view('dashboard.index', [
            'user' => $user,
            'daftarUser' => $user->can('manage-roles')
                ? User::orderBy('username')
                    ->get(['id_user', 'username', 'nama_lengkap', 'role'])
                : collect(),
        ]);
    }
}
