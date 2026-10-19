@extends('layouts.app')

@section('judul', 'Masuk - Toko Online')

@section('isi')
    <h1>Masuk</h1>

    <form method="POST" action="{{ route('login.attempt') }}" class="sempit">
        @csrf

        <fieldset>
            <legend>Akun</legend>

            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="{{ old('username') }}" autofocus required
                @error('username') aria-invalid="true" aria-describedby="error-username" @enderror>

            @error('username')
                <span class="error-kolom" id="error-username">{{ $message }}</span>
            @enderror

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required
                @error('password') aria-invalid="true" aria-describedby="error-password" @enderror>

            @error('password')
                <span class="error-kolom" id="error-password">{{ $message }}</span>
            @enderror
        </fieldset>

        <div class="aksi">
            <button type="submit">Masuk</button>
        </div>
    </form>
@endsection
