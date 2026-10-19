<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('judul', 'Toko Online')</title>
    @vite(['resources/css/app.css'])
</head>

<body>
    <header>
        <a href="{{ route('barang.index') }}" class="merek">Toko Online</a>

        {{-- aria-label dipakai karena halaman lain bisa punya <nav> sendiri. --}}
        <nav aria-label="Menu utama">
            <ul>
                <li>
                    <a href="{{ route('barang.index') }}"
                        @if (request()->routeIs('barang.*')) aria-current="page" @endif>Daftar Barang</a>
                </li>

                @auth
                    <li>
                        <a href="{{ route('keranjang.index') }}"
                            @if (request()->routeIs('keranjang.*')) aria-current="page" @endif>
                            Keranjang
                            @php $jumlahKeranjang = \App\Support\Keranjang::jumlahItem(); @endphp
                            @if ($jumlahKeranjang > 0)
                                <span class="bantuan">({{ $jumlahKeranjang }})</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('riwayat.index') }}"
                            @if (request()->routeIs('riwayat.*')) aria-current="page" @endif>Riwayat Pesanan</a>
                    </li>
                    <li>
                        {{-- Nama pengguna bukan tautan: belum ada halaman profil. --}}
                        <span class="bantuan">{{ auth()->user()->nama_lengkap }}</span>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="sekunder">Keluar</button>
                        </form>
                    </li>
                @else
                    <li>
                        <a href="{{ route('login') }}"
                            @if (request()->routeIs('login')) aria-current="page" @endif>Masuk</a>
                    </li>
                @endauth
            </ul>
        </nav>
    </header>

    <main>
        {{-- role="status" dan role="alert" membuat pembaca layar mengumumkan
             pesan ini tanpa memindahkan fokus pengguna. --}}
        @if (session('sukses'))
            <p class="notifikasi sukses" role="status">{{ session('sukses') }}</p>
        @endif

        @if (session('error'))
            <p class="notifikasi error" role="alert">{{ session('error') }}</p>
        @endif

        @if (session('info'))
            <p class="notifikasi info" role="status">{{ session('info') }}</p>
        @endif

        @yield('isi')
    </main>
</body>

</html>
