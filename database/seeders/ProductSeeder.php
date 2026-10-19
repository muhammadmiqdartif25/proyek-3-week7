<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

//Membuat 10 produk baru dengan id sepanjang 10 karakter, nama produk acak, harga acak antara 10000 hingga 100000, stok acak antara 1 hingga 100, deskripsi acak, dan gambar acak dari folder public/images/products.



class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    private $productsData = [
        [
            'id_barang' => 'P000000001',
            'nama_barang' => 'Produk 1',
            'harga' => 10000,
            'stok' => 10,
            'deskripsi' => 'Deskripsi produk 1',
            'gambar' => 'produk1.png',
        ],
        [
            'id_barang' => 'P000000002',
            'nama_barang' => 'Produk 2',
            'harga' => 20000,
            'stok' => 20,
            'deskripsi' => 'Deskripsi produk 2',
            'gambar' => 'produk2.png',
        ],
        [
            'id_barang' => 'P000000003',
            'nama_barang' => 'Produk 3',
            'harga' => 30000,
            'stok' => 30,
            'deskripsi' => 'Deskripsi produk 3',
            'gambar' => 'produk3.png',
        ],
        [
            'id_barang' => 'P000000004',
            'nama_barang' => 'Produk 4',
            'harga' => 40000,
            'stok' => 40,
            'deskripsi' => 'Deskripsi produk 4',
            'gambar' => 'produk4.png',
        ],
        [
            'id_barang' => 'P000000005',
            'nama_barang' => 'Produk 5',
            'harga' => 50000,
            'stok' => 50,
            'deskripsi' => 'Deskripsi produk 5',
            'gambar' => 'produk5.png',
        ],
        [
            'id_barang' => 'P000000006',
            'nama_barang' => 'Produk 6',
            'harga' => 60000,
            'stok' => 60,
            'deskripsi' => 'Deskripsi produk 6',
            'gambar' => 'produk6.png',
        ],
        [
            'id_barang' => 'P000000007',
            'nama_barang' => 'Produk 7',
            'harga' => 70000,
            'stok' => 70,
            'deskripsi' => 'Deskripsi produk 7',
            'gambar' => 'produk7.png',
        ],
        [
            'id_barang' => 'P000000008',
            'nama_barang' => 'Produk 8',
            'harga' => 80000,
            'stok' => 80,
            'deskripsi' => 'Deskripsi produk 8',
            'gambar' => 'produk8.png',
        ],
        [
            'id_barang' => 'P000000009',
            'nama_barang' => 'Produk 9',
            'harga' => 90000,
            'stok' => 90,
            'deskripsi' => 'Deskripsi produk 9',
            'gambar' => 'produk9.png',
        ],
        [
            'id_barang' => 'P000000010',
            'nama_barang' => 'Produk 10',
            'harga' => 100000,
            'stok' => 100,
            'deskripsi' => 'Deskripsi produk 10',
            'gambar' => 'produk10.png',
        ],
    ];

    public function run(): void
    {
        //
        foreach ($this->productsData as $productData) {
            Product::create($productData);
        }
    }
}
