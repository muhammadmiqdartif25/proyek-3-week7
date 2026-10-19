<?php

namespace App\Http\Controllers;

use App\Actions\BuatPesanan;
use App\Exceptions\PesananTidakDapatDibuatException;
use App\Support\Keranjang;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(private readonly BuatPesanan $buatPesanan)
    {
    }

    public function form(): View|RedirectResponse
    {
        if (Keranjang::kosong()) {
            return redirect()
                ->route('keranjang.index')
                ->with('error', PesananTidakDapatDibuatException::keranjangKosong()->getMessage());
        }

        return view('checkout.index', [
            'isi' => Keranjang::isi(),
            'total' => Keranjang::total(),
        ]);
    }

    /**
     * Membuat pesanan dari isi keranjang.
     *
     * Controller hanya menyiapkan masukan dan mengurus hasilnya. Seluruh
     * aturan pembuatan pesanan, termasuk transaksi dan penguncian stok, ada
     * di App\Actions\BuatPesanan.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'alamat_pengiriman' => ['required', 'string', 'max:5000'],
        ]);

        try {
            $order = $this->buatPesanan->jalankan(
                $request->user(),
                $data['alamat_pengiriman'],
                Keranjang::mentah(),
            );
        } catch (PesananTidakDapatDibuatException $e) {
            return back()->with('error', $e->getMessage());
        }

        // Keranjang baru dikosongkan setelah pesanan benar-benar tersimpan,
        // supaya isinya tidak hilang kalau pembuatan pesanan gagal.
        Keranjang::kosongkan();

        return redirect()
            ->route('riwayat.index')
            ->with('sukses', "Pesanan {$order->id_order} berhasil dibuat.");
    }
}
