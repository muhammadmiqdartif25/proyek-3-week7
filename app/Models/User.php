<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\WithoutIncrementing;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['id_user', 'nama_lengkap', 'email', 'username', 'no_hp', 'alamat', 'password'])]
#[Hidden(['password', 'remember_token'])]
#[WithoutIncrementing]
class User extends Authenticatable
{
    protected $primaryKey = 'id_user';
    protected $keyType = 'string';

    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'id_user', 'id_user');
    }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
