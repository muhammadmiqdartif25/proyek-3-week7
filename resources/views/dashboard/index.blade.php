@extends('layouts.app')

@section('judul', 'Dashboard - Toko Online')

@section('isi')
    <h1>Dashboard</h1>

    <div class="blok">
        <dl>
            <dt>Nama Lengkap</dt>
            <dd>{{ $user->nama_lengkap }}</dd>

            <dt>Username</dt>
            <dd>{{ $user->username }}</dd>

            {{-- Role ditampilkan apa adanya supaya perbedaan hak akses
                 antar akun demonstrasi mudah dibandingkan saat pengujian. --}}
            <dt>Role</dt>
            <dd>{{ $user->role }}</dd>
        </dl>
    </div>

    {{-- Tautan disaring dengan Gate yang sama seperti middleware route,
         jadi yang terlihat di sini konsisten dengan yang benar-benar
         diizinkan server. --}}
    <div class="blok">
        <h2>Menu</h2>

        <div class="aksi">
            <a class="tombol sekunder" href="{{ route('barang.index') }}">Daftar Barang</a>

            @can('use-cart')
                <a class="tombol sekunder" href="{{ route('keranjang.index') }}">Keranjang</a>
            @endcan

            <a class="tombol sekunder" href="{{ route('riwayat.index') }}">Riwayat Pesanan</a>
        </div>

        @cannot('use-cart')
            <p class="bantuan">
                Role {{ $user->role }} tidak memakai keranjang belanja.
            </p>
        @endcannot
    </div>

    {{-- Daftar user untuk admin. Server mengirim $daftarUser hanya kalau
         ability manage-roles lolos, jadi blok ini bukan satu-satunya
         penjaga: route PATCH-nya tetap memakai middleware can:manage-roles.
         Akun admin ikut tampil; penolakan BR-04 terjadi di service, bukan
         di sini, sehingga percobaannya bisa dilakukan dari halaman ini. --}}
    @can('manage-roles')
        <div class="blok">
            <h2>Manajemen Role</h2>

            <div class="tabel-gulir">
                <table>
                    <caption class="bantuan">
                        Hanya role customer dan staff yang dapat dipertukarkan.
                    </caption>

                    <thead>
                        <tr>
                            <th scope="col">Username</th>
                            <th scope="col">Nama Lengkap</th>
                            <th scope="col">Role Sekarang</th>
                            <th scope="col">Ubah Menjadi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($daftarUser as $baris)
                            <tr>
                                <td>{{ $baris->username }}</td>
                                <td>{{ $baris->nama_lengkap }}</td>
                                <td>{{ $baris->role }}</td>
                                <td>
                                    <form method="POST" action="{{ route('admin.users.role.update', $baris) }}">
                                        @csrf
                                        @method('PATCH')

                                        {{-- Label sengaja sr-only: judul kolom sudah
                                             memberi konteks, dan tiap baris butuh
                                             nama user untuk pembaca layar. --}}
                                        <label class="sr-only" for="role-{{ $baris->id_user }}">
                                            Role baru untuk {{ $baris->username }}
                                        </label>

                                        <select name="role" id="role-{{ $baris->id_user }}">
                                            <option value="customer" @selected($baris->role === 'customer')>
                                                customer
                                            </option>
                                            <option value="staff" @selected($baris->role === 'staff')>
                                                staff
                                            </option>
                                        </select>

                                        <button type="submit" class="sekunder">Simpan</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pesan dari service muncul di sini. Service memakai key 'role',
                 jadi errornya bisa berasal dari validasi Form Request maupun
                 dari penolakan aturan bisnis. --}}
            @error('role')
                <p class="error-kolom">{{ $message }}</p>
            @enderror
        </div>
    @endcan
@endsection
