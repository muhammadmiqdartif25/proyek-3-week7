@extends('layouts.app')

@section('judul', 'Keranjang Belanja - Toko Online')

@section('isi')
    <h1>Keranjang Belanja</h1>

    @if ($isi->isEmpty())
        {{-- Keadaan kosong (R-27). --}}
        <div class="kosong">
            <p>Keranjang kamu masih kosong.</p>
            <p><a class="tombol" href="{{ route('barang.index') }}">Lihat Daftar Barang</a></p>
        </div>
    @else
        <div class="tabel-gulir">
            <table>
                <caption class="bantuan">Ubah jumlah dengan tombol kurang dan tambah. Menekan kurang pada jumlah 1 mengeluarkan barang dari keranjang.</caption>

                <thead>
                    <tr>
                        <th scope="col">Barang</th>
                        <th scope="col" class="angka">Harga</th>
                        <th scope="col">Jumlah</th>
                        <th scope="col" class="angka">Subtotal</th>
                        <th scope="col"><span class="bantuan">Aksi</span></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($isi as $baris)
                        @php
                            $produk = $baris['produk'];
                            $jumlah = $baris['jumlah'];
                        @endphp

                        {{-- id dipakai sebagai sasaran gulir setelah halaman dimuat
                             ulang, supaya posisi tidak melompat ke atas. --}}
                        <tr id="barang-{{ $produk->id_barang }}">
                            <td>
                                <img src="{{ asset('img/' . $produk->gambar) }}" alt="">
                                <strong>{{ $produk->nama_barang }}</strong>
                                <div class="bantuan">Stok tersedia: {{ $produk->stok }}</div>
                            </td>

                            <td class="angka">Rp {{ number_format($produk->harga, 0, ',', '.') }}</td>

                            <td>
                                <div class="jumlah">
                                    {{-- Kurang satu. Di jumlah 1 tombol ini
                                         mengeluarkan barang dari keranjang, jadi
                                         labelnya ikut berubah supaya pengguna
                                         pembaca layar tidak terkejut. --}}
                                    <form method="POST" action="{{ route('keranjang.update') }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="id_barang" value="{{ $produk->id_barang }}">
                                        <input type="hidden" name="jumlah" value="{{ $jumlah - 1 }}">
                                        <button type="submit" class="sekunder"
                                            aria-label="{{ $jumlah <= 1
                                                ? 'Hapus ' . $produk->nama_barang . ' dari keranjang'
                                                : 'Kurangi jumlah ' . $produk->nama_barang }}">
                                            <span aria-hidden="true">&minus;</span>
                                        </button>
                                    </form>

                                    <span class="jumlah-nilai">{{ $jumlah }}</span>

                                    {{-- Tambah satu. Dimatikan saat jumlah sudah
                                         menyentuh stok, jadi batas stok terlihat
                                         sebelum pengguna menabraknya. --}}
                                    <form method="POST" action="{{ route('keranjang.update') }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="id_barang" value="{{ $produk->id_barang }}">
                                        <input type="hidden" name="jumlah" value="{{ $jumlah + 1 }}">
                                        <button type="submit" class="sekunder" @disabled($jumlah >= $produk->stok)
                                            aria-label="Tambah jumlah {{ $produk->nama_barang }}">
                                            <span aria-hidden="true">+</span>
                                        </button>
                                    </form>
                                </div>
                            </td>

                            <td class="angka">Rp {{ number_format($baris['subtotal'], 0, ',', '.') }}</td>

                            <td>
                                <form method="POST" action="{{ route('keranjang.destroy') }}">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="id_barang" value="{{ $produk->id_barang }}">
                                    <button type="submit" class="bahaya">
                                        Hapus<span class="sr-only"> {{ $produk->nama_barang }}</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <p class="total">
            <span>Total Harga</span>
            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
        </p>

        <div class="aksi">
            <a class="tombol sekunder" href="{{ route('barang.index') }}">Lanjut Belanja</a>
            <a class="tombol" href="{{ route('checkout.form') }}">Checkout</a>
        </div>
    @endif
@endsection
