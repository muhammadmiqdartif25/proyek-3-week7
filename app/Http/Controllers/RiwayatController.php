<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class RiwayatController extends Controller
{
    /**
     * Menampilkan pesanan milik pengguna yang sedang login.
     *
     * details.product di-eager load supaya tidak terjadi query berulang
     * untuk setiap baris rincian (masalah N+1).
     *
     * Diurutkan dari yang terbaru. id_order dipakai sebagai pengurut kedua
     * karena dua pesanan yang dibuat pada detik yang sama punya
     * tanggal_order identik, sehingga urutannya jadi tidak pasti.
     */
    public function index(Request $request): View
    {
        $pesanan = $request->user()
            ->orders()
            ->with(['details.product'])
            ->orderByDesc('tanggal_order')
            ->orderByDesc('id_order')
            ->get();

        return view('riwayat.index', compact('pesanan'));
    }
}
