<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    private $usersData = [
        [
            'id_user' => 'U00000000000001',
            'nama_lengkap' => 'Muhammad Miqdar Salim',
            'email' => 'mmdsalim@example.com',
            'username' => 'miqdar',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Contoh Alamat No. 123, Kota Contoh, Provinsi Contoh, 12345',
            'password' => 'admin123',
            'role' => 'admin',
        ],
        [
            'id_user' => 'U00000000000002',
            'nama_lengkap' => 'Fauzan Doe',
            'email' => 'fauzandoe@example.com',
            'username' => 'fauzan',
            'no_hp' => '081234567891',
            'alamat' => 'Jl. Contoh Alamat No. 124, Kota Contoh, Provinsi Contoh, 12346',
            'password' => 'staff123',
            'role' => 'staff',
        ],
        [
            'id_user' => 'U00000000000003',
            'nama_lengkap' => 'Rafli Doe',
            'email' => 'rafli@example.com',
            'username' => 'rafli',
            'no_hp' => '081234567892',
            'alamat' => 'Jl. Contoh Alamat No. 125, Kota Contoh, Provinsi Contoh, 12347',
            'password' => 'staff123',
            'role' => 'staff',
        ],
        [
            'id_user' => 'U00000000000004',
            'nama_lengkap' => 'Aulia Doe',
            'email' => 'aulia@example.com',
            'username' => 'aulia',
            'no_hp' => '081234567893',
            'alamat' => 'Jl. Contoh Alamat No. 126, Kota Contoh, Provinsi Contoh, 12348',
            'password' => 'cust123',
            'role' => 'customer',
        ],
        [
            'id_user' => 'U00000000000005',
            'nama_lengkap' => 'Budi Doe',
            'email' => 'budi@example.com',
            'username' => 'budi',
            'no_hp' => '081234567894',
            'alamat' => 'Jl. Contoh Alamat No. 127, Kota Contoh, Provinsi Contoh, 12349',
            'password' => 'cust123',
            'role' => 'customer',
        ],
    ];

    public function run(): void
    {
        foreach ($this->usersData as $userData) {
            $role = $userData['role'];
            unset($userData['role']);   // role bukan fillable, jangan lewat create()

            $user = User::create($userData);   // password di-hash oleh cast 'hashed'

            $user->role = $role;
            $user->save();
        }
    }
}
