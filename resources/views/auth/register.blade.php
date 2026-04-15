@extends('layouts.auth')

@section('content')
<div class="login-box">
    <h2>Daftar Akun</h2>

    @if ($errors->any())
        <div class="error-msg">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('register.post') }}" method="POST">
        @csrf

        <div class="input-group">
            <input type="text" name="name" placeholder="Nama Lengkap" required>
        </div>

        <div class="input-group">
            <input type="email" name="email" placeholder="Email" required>
        </div>

        <div class="input-group">
            <input type="password" name="password" placeholder="Password" required>
        </div>

        <div class="input-group">
            <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" required>
        </div>

        <button type="submit" class="btn-login">Daftar</button>
    </form>

    <a href="{{ route('login') }}" class="forgot-link">Sudah punya akun? Login</a>
</div>
@endsection