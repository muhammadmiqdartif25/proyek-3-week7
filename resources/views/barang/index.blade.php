@extends('layouts.app')

@section('judul', 'Daftar Barang - Toko Online')

@section('isi')
    <h1>Daftar Barang</h1>

    @guest
        <p class="bantuan">
            <a href="{{ route('login') }}">Masuk</a> dulu untuk mulai berbelanja.
        </p>
    @endguest

    @if ($produk->isEmpty())
        {{-- Keadaan kosong: katalog belum diisi (R-27). --}}
        <div class="kosong">
            <p>Belum ada barang yang tersedia.</p>
        </div>
    @else
        <ul class="daftar-produk">
            @foreach ($produk as $item)
                @php $habis = $item->stok < 1; @endphp

                <li>
                    <article>
                        <img src="{{ asset('img/' . $item->gambar) }}" alt="{{ $item->nama_barang }}">

                        <h2>{{ $item->nama_barang }}</h2>

                        @if ($item->deskripsi)
                            <p class="bantuan">{{ $item->deskripsi }}</p>
                        @endif

                        <p class="harga">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>

                        @if ($habis)
                            <p class="stok-habis">Stok habis</p>
                        @else
                            <p class="stok-tersedia">Stok {{ $item->stok }}</p>
                        @endif

                        @if ($habis)
                            <button type="button" disabled>Tidak dapat dibeli</button>
                        @elseif (auth()->check())
                            <form method="POST" action="{{ route('keranjang.store') }}">
                                @csrf
                                <input type="hidden" name="id_barang" value="{{ $item->id_barang }}">
                                <button type="submit">Tambah ke Keranjang</button>
                            </form>
                        @else
                            <a class="tombol" href="{{ route('login') }}">Masuk untuk Membeli</a>
                        @endif
                    </article>
                </li>
            @endforeach
        </ul>
    @endif
@endsection
