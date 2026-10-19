<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::create([
            'id_user' => 'U00000000000001',
            'nama_lengkap' => 'Muhammad Miqdar Salim',
            'email' => 'mmdsalim@example.com',
            'username' => 'miqdar',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Contoh Alamat No. 123, Kota Contoh, Provinsi Contoh, 12345',
            'password' => Hash::make('admin123'),
        ]);
    }
}
