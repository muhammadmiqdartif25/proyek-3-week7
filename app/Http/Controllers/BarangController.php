<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;

class BarangController extends Controller
{
    /**
     * Daftar barang boleh dilihat tanpa login.
     */
    public function index(): View
    {
        $produk = Product::orderBy('id_barang')->get();

        return view('barang.index', compact('produk'));
    }
}
