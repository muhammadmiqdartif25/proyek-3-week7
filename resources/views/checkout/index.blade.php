@extends('layouts.app')

@section('judul', 'Checkout - Toko Online')

@section('isi')
    <h1>Checkout</h1>

    <div class="blok">
        <div class="tabel-gulir">
            <table>
                <caption class="bantuan">Periksa kembali barang yang akan dibeli.</caption>

                <thead>
                    <tr>
                        <th scope="col">Barang</th>
                        <th scope="col" class="angka">Harga Satuan</th>
                        <th scope="col" class="angka">Jumlah</th>
                        <th scope="col" class="angka">Subtotal</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($isi as $baris)
                        <tr>
                            <td>{{ $baris['produk']->nama_barang }}</td>
                            <td class="angka">Rp {{ number_format($baris['produk']->harga, 0, ',', '.') }}</td>
                            <td class="angka">{{ $baris['jumlah'] }}</td>
                            <td class="angka">Rp {{ number_format($baris['subtotal'], 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <p class="total">
            <span>Total Bayar</span>
            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
        </p>
    </div>

    <form method="POST" action="{{ route('checkout.store') }}">
        @csrf

        <fieldset>
            <legend>Alamat Pengiriman</legend>

            <label for="alamat_pengiriman">Alamat lengkap</label>
            <textarea id="alamat_pengiriman" name="alamat_pengiriman" required
                @error('alamat_pengiriman') aria-invalid="true" aria-describedby="error-alamat" @enderror>{{ old('alamat_pengiriman', auth()->user()->alamat) }}</textarea>

            <p class="bantuan">Ongkos kirim diabaikan, jadi total bayar sama dengan total harga barang.</p>

            @error('alamat_pengiriman')
                <span class="error-kolom" id="error-alamat">{{ $message }}</span>
            @enderror
        </fieldset>

        <div class="aksi">
            <a class="tombol sekunder" href="{{ route('keranjang.index') }}">Kembali ke Keranjang</a>
            <button type="submit">Buat Pesanan</button>
        </div>
    </form>
@endsection
