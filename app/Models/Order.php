<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\WithoutIncrementing;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\OrderDetail;
use App\Models\User;

#[Fillable(['id_order', 'id_user', 'tanggal_order', 'total_harga', 'alamat_pengiriman'])]
#[WithoutIncrementing]
class Order extends Model
{
    protected $primaryKey = 'id_order';
    protected $keyType = 'string';
    //
    public function details(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'id_order', 'id_order');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * tanggal_order di-cast ke Carbon supaya bisa diformat di view
     * (tanpa ini, kolomnya masih berupa string biasa).
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_order' => 'datetime',
        ];
    }
}
