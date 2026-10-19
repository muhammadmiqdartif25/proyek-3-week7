<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\WithoutIncrementing;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\OrderDetail;

#[Fillable(['id_barang', 'nama_barang', 'harga', 'stok', 'deskripsi', 'gambar'])]
#[WithoutIncrementing]
class Product extends Model
{
    protected $primaryKey = 'id_barang';
    protected $keyType = 'string';
    //
    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'id_barang', 'id_barang');
    }
}
