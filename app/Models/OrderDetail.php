<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\WithoutIncrementing;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Order;

#[Fillable(['id_order', 'id_barang', 'harga_satuan', 'jumlah_beli'])]
#[WithoutIncrementing]
class OrderDetail extends Model
{
    protected $keyType = 'string';
    protected $primaryKey = null;
    //
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'id_order', 'id_order');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'id_barang', 'id_barang');
    }

    protected function setKeysForSaveQuery($query)
    {
        return $query->where('id_order', '=', $this->original['id_order'] ?? $this->getAttribute('id_order'))
                 ->where('id_barang', '=', $this->original['id_barang'] ?? $this->getAttribute('id_barang'));
    }
}
