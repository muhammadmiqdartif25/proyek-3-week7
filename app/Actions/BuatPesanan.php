<?php

namespace App\Actions;

use App\Exceptions\PesananTidakDapatDibuatException;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * Membuat satu pesanan dari daftar barang yang akan dibeli.
 *
 * Seluruh aturan pembuatan pesanan ada di sini, bukan di controller:
 * pemeriksaan stok, penghitungan total, pengarsipan harga, pengurangan stok,
 * dan penomoran id_order. Controller hanya menyiapkan masukan dan mengurus
 * tampilan hasilnya.
 *
 * Transaksi database dimiliki oleh kelas ini, bukan oleh pemanggil. Itu
 * disengaja: penguncian baris produk dan penomoran id_order hanya bermakna
 * di dalam transaksi, jadi keduanya tidak boleh bergantung pada pemanggil
 * yang ingat membungkusnya.
 */
class BuatPesanan
{
    /**
     * @param  array<string, int>  $daftarBelanja  id_barang => jumlah
     *
     * @throws PesananTidakDapatDibuatException
     */
    public function jalankan(User $pembeli, string $alamatPengiriman, array $daftarBelanja): Order
    {
        if ($daftarBelanja === []) {
            throw PesananTidakDapatDibuatException::keranjangKosong();
        }

        return DB::transaction(function () use ($pembeli, $alamatPengiriman, $daftarBelanja) {
            $produk = $this->ambilProdukTerkunci($daftarBelanja);

            $total = 0;
            $rincian = [];

            foreach ($daftarBelanja as $idBarang => $jumlah) {
                $item = $produk->get($idBarang);

                if ($item === null) {
                    throw PesananTidakDapatDibuatException::barangTidakDitemukan($idBarang);
                }

                if ($item->stok < $jumlah) {
                    throw PesananTidakDapatDibuatException::stokTidakCukup($item, $jumlah);
                }

                $total += (float) $item->harga * $jumlah;

                // harga_satuan diarsipkan supaya riwayat tetap benar walau
                // harga produk berubah di kemudian hari.
                $rincian[] = [
                    'id_barang' => $item->id_barang,
                    'harga_satuan' => $item->harga,
                    'jumlah_beli' => $jumlah,
                ];

                $item->decrement('stok', $jumlah);
            }

            $order = Order::create([
                'id_order' => $this->nomorBerikutnya(),
                'id_user' => $pembeli->id_user,
                'tanggal_order' => now(),
                'total_harga' => round($total, 2),
                'alamat_pengiriman' => $alamatPengiriman,
            ]);

            $order->details()->createMany($rincian);

            return $order;
        });
    }

    /**
     * Mengambil produk yang dibeli sambil mengunci barisnya.
     *
     * Kunci ini menahan pembeli lain yang checkout pada saat yang sama,
     * sehingga stok yang diperiksa di bawah tidak berubah di tengah proses.
     *
     * @param  array<string, int>  $daftarBelanja
     * @return \Illuminate\Support\Collection<string, Product>
     */
    private function ambilProdukTerkunci(array $daftarBelanja)
    {
        return Product::whereIn('id_barang', array_keys($daftarBelanja))
            ->lockForUpdate()
            ->get()
            ->keyBy('id_barang');
    }

    /**
     * Menghasilkan id_order dengan format O + yymmdd + 6 digit urutan
     * (13 karakter), misalnya O261010000001.
     *
     * Nomor urut dihitung dari id terakhir pada hari yang sama. Baris
     * terakhir ikut dikunci supaya dua checkout yang berbarengan tidak
     * menghasilkan nomor yang sama. Karena itu method ini hanya boleh
     * dipanggil di dalam transaksi.
     */
    private function nomorBerikutnya(): string
    {
        $awalan = 'O' . now()->format('ymd');

        $terakhir = Order::where('id_order', 'like', $awalan . '%')
            ->orderByDesc('id_order')
            ->lockForUpdate()
            ->value('id_order');

        $urutan = $terakhir === null
            ? 1
            : ((int) substr($terakhir, strlen($awalan))) + 1;

        return $awalan . str_pad((string) $urutan, 6, '0', STR_PAD_LEFT);
    }
}
