<?php

namespace App\Exceptions;

use App\Models\Product;
use RuntimeException;

/**
 * Dilempar saat pesanan tidak dapat dibuat karena isi keranjang sudah tidak
 * cocok dengan kondisi stok di database.
 *
 * Ini bisa terjadi walaupun stok sudah diperiksa saat barang dimasukkan ke
 * keranjang: keranjang disimpan di session dan bisa mengendap lama, sementara
 * stok di database terus berubah karena pembelian orang lain.
 *
 * Dibuat lewat method bernama (stokTidakCukup, barangTidakDitemukan) supaya
 * pemanggil tidak perlu menyusun pesan galat sendiri, dan supaya tiap alasan
 * punya satu tempat yang jelas.
 */
class PesananTidakDapatDibuatException extends RuntimeException
{
    public static function keranjangKosong(): self
    {
        return new self('Keranjang masih kosong, tidak ada yang bisa di-checkout.');
    }

    public static function stokTidakCukup(Product $produk, int $diminta): self
    {
        return new self(
            "Stok {$produk->nama_barang} tidak mencukupi "
            . "(tersedia {$produk->stok}, diminta {$diminta})."
        );
    }

    public static function barangTidakDitemukan(string $idBarang): self
    {
        return new self("Barang {$idBarang} tidak ditemukan.");
    }
}
