@extends('layouts.app')

@section('judul', 'Riwayat Pesanan - Toko Online')

@section('isi')
    <h1>Riwayat Pesanan</h1>

    @if ($pesanan->isEmpty())
        {{-- Keadaan kosong (R-27). --}}
        <div class="kosong">
            <p>Kamu belum memiliki pesanan.</p>
            <p><a class="tombol" href="{{ route('barang.index') }}">Mulai Belanja</a></p>
        </div>
    @else
        @foreach ($pesanan as $order)
            <article class="blok">
                <header class="pesanan-kepala">
                    <div>
                        <h2>{{ $order->id_order }}</h2>
                        <p class="bantuan">{{ $order->tanggal_order->format('d/m/Y H:i') }} WIB</p>
                    </div>

                    <div>
                        <p class="bantuan">Total Bayar</p>
                        <p class="harga">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</p>
                    </div>
                </header>

                <div class="tabel-gulir">
                    <table>
                        <caption class="bantuan">Rincian barang pada pesanan ini.</caption>

                        <thead>
                            <tr>
                                <th scope="col">Barang</th>
                                <th scope="col" class="angka">Harga Satuan</th>
                                <th scope="col" class="angka">Jumlah</th>
                                <th scope="col" class="angka">Subtotal</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($order->details as $detail)
                                <tr>
                                    {{-- Barang bisa saja sudah dihapus dari katalog,
                                         jadi nama produk ditangani dengan aman. --}}
                                    <td>{{ $detail->product?->nama_barang ?? '(barang telah dihapus)' }}</td>
                                    <td class="angka">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                    <td class="angka">{{ $detail->jumlah_beli }}</td>
                                    <td class="angka">
                                        Rp {{ number_format($detail->harga_satuan * $detail->jumlah_beli, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <hr>

                <dl>
                    <dt>Alamat Pengiriman</dt>
                    <dd>{{ $order->alamat_pengiriman }}</dd>
                </dl>
            </article>
        @endforeach
    @endif
@endsection
