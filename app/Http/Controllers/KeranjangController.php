<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Support\Keranjang;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KeranjangController extends Controller
{
    public function index(): View
    {
        return view('keranjang.index', [
            'isi' => Keranjang::isi(),
            'total' => Keranjang::total(),
        ]);
    }

    /**
     * Menambah barang ke keranjang.
     *
     * Jumlah di keranjang tidak boleh melebihi stok, jadi total setelah
     * penambahan diperiksa terhadap stok yang tersedia.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'id_barang' => ['required', 'string', 'exists:products,id_barang'],
        ]);

        $produk = Product::findOrFail($data['id_barang']);

        if ($produk->stok < 1) {
            return back()->with('error', "Stok {$produk->nama_barang} sedang habis.");
        }

        $jumlahDiKeranjang = Keranjang::jumlahBarang($produk->id_barang);
        $jumlahBaru = $jumlahDiKeranjang + 1;

        if ($jumlahBaru > $produk->stok) {
            return back()->with(
                'error',
                "Jumlah {$produk->nama_barang} di keranjang sudah mencapai stok tersedia ({$produk->stok})."
            );
        }

        Keranjang::tambah($produk->id_barang, 1);

        return back()->with('sukses', "{$produk->nama_barang} ditambahkan ke keranjang.");
    }

    /**
     * Menetapkan jumlah sebuah barang di keranjang.
     *
     * Dipakai tombol kurang dan tambah, yang mengirim jumlah yang sudah
     * dihitung dari halaman. Nilainya tetap diperiksa di sini, karena
     * permintaan bisa datang dari mana saja, bukan hanya dari tombol itu.
     */
    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'id_barang' => ['required', 'string', 'exists:products,id_barang'],
            'jumlah' => ['required', 'integer', 'min:0'],
        ]);

        $produk = Product::findOrFail($data['id_barang']);

        // Aturan validasi 'integer' hanya memeriksa bentuknya, tidak mengubah
        // tipe: nilai dari form tetap string. Jadi harus diubah ke int dulu,
        // kalau tidak perbandingan ketat di bawah tidak pernah cocok.
        $jumlah = (int) $data['jumlah'];

        // Jumlah 0 berarti barang dikeluarkan dari keranjang. Ini terjadi saat
        // tombol kurang ditekan pada jumlah 1.
        if ($jumlah === 0) {
            Keranjang::hapus($produk->id_barang);

            return $this->kembali($request)
                ->with('info', "{$produk->nama_barang} dihapus dari keranjang.");
        }

        if ($jumlah > $produk->stok) {
            return $this->kembali($request)->with(
                'error',
                "Jumlah {$produk->nama_barang} melebihi stok tersedia ({$produk->stok})."
            );
        }

        Keranjang::ubah($produk->id_barang, $jumlah);

        return $this->kembali($request)
            ->with('sukses', "Jumlah {$produk->nama_barang} diperbarui.");
    }

    public function destroy(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'id_barang' => ['required', 'string'],
        ]);

        Keranjang::hapus($data['id_barang']);

        return $this->kembali($request)->with('info', 'Barang dihapus dari keranjang.');
    }

    /**
     * Kembali ke halaman sebelumnya, dan kalau halaman itu keranjang,
     * tambatkan gulir ke baris yang baru diubah.
     *
     * Setiap perubahan jumlah memuat ulang halaman, dan tanpa tambatan ini
     * pengguna akan terlempar kembali ke atas daftar setiap kali menekan
     * tombol kurang atau tambah.
     *
     * Kalau barangnya sudah tidak ada lagi di keranjang, tambatan dilewati:
     * barisnya sudah hilang, jadi menambatkan ke sana tidak ada gunanya.
     */
    private function kembali(Request $request): RedirectResponse
    {
        $kembali = back();

        $idBarang = $request->input('id_barang');

        if (! $idBarang || Keranjang::jumlahBarang($idBarang) < 1) {
            return $kembali;
        }

        if (str_contains((string) $request->headers->get('referer'), '/keranjang')) {
            return $kembali->withFragment('barang-'.$idBarang);
        }

        return $kembali;
    }
}
