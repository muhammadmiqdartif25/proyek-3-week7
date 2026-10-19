<?php

namespace App\Support;

use App\Models\Product;
use Illuminate\Support\Collection;

/**
 * Keranjang belanja berbasis session.
 *
 * Rancangan tabel pada modul tugas tidak memuat tabel keranjang, jadi isi
 * keranjang disimpan di session dengan bentuk: ['P000000001' => 2, ...]
 * (id_barang => jumlah). Harga tidak ikut disimpan agar tidak basi; harga
 * selalu dibaca ulang dari tabel products saat ditampilkan.
 */
class Keranjang
{
    private const KUNCI = 'keranjang';

    /**
     * Isi keranjang mentah: [id_barang => jumlah].
     *
     * @return array<string, int>
     */
    public static function mentah(): array
    {
        return session(self::KUNCI, []);
    }

    public static function jumlahJenis(): int
    {
        return count(self::mentah());
    }

    public static function jumlahItem(): int
    {
        return (int) array_sum(self::mentah());
    }

    public static function kosong(): bool
    {
        return self::mentah() === [];
    }

    /**
     * Isi keranjang yang sudah dilengkapi data produk.
     *
     * @return Collection<int, array{produk: Product, jumlah: int, subtotal: float}>
     */
    public static function isi(): Collection
    {
        $mentah = self::mentah();

        if ($mentah === []) {
            return collect();
        }

        $produk = Product::whereIn('id_barang', array_keys($mentah))
            ->get()
            ->keyBy('id_barang');

        return collect($mentah)
            ->map(function (int $jumlah, string $idBarang) use ($produk) {
                $item = $produk->get($idBarang);

                // Barang yang sudah dihapus dari katalog diabaikan.
                if ($item === null) {
                    return null;
                }

                return [
                    'produk' => $item,
                    'jumlah' => $jumlah,
                    'subtotal' => (float) $item->harga * $jumlah,
                ];
            })
            ->filter()
            ->values();
    }

    public static function total(): float
    {
        return (float) self::isi()->sum('subtotal');
    }

    public static function jumlahBarang(string $idBarang): int
    {
        return self::mentah()[$idBarang] ?? 0;
    }

    public static function tambah(string $idBarang, int $jumlah): void
    {
        $mentah = self::mentah();
        $mentah[$idBarang] = ($mentah[$idBarang] ?? 0) + $jumlah;

        session([self::KUNCI => $mentah]);
    }

    public static function ubah(string $idBarang, int $jumlah): void
    {
        $mentah = self::mentah();

        if ($jumlah < 1) {
            unset($mentah[$idBarang]);
        } else {
            $mentah[$idBarang] = $jumlah;
        }

        session([self::KUNCI => $mentah]);
    }

    public static function hapus(string $idBarang): void
    {
        $mentah = self::mentah();
        unset($mentah[$idBarang]);

        session([self::KUNCI => $mentah]);
    }

    public static function kosongkan(): void
    {
        session()->forget(self::KUNCI);
    }
}
