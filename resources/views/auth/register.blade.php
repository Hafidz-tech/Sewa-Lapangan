@extends('layouts.app')

@section('hideNavbar')
@endsection

@section('content')
<style>
    body {
        background: #f0f4f8;
    }

    .register-card {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        background: #ffffff;
    }

    .register-title {
        font-weight: 600;
        color: #2c3e50;
    }

    .form-control {
        border-radius: 0.5rem;
    }

    .btn-success {
        border-radius: 0.5rem;
        background-color: #38b000;
        border: none;
    }

    .btn-success:hover {
        background-color: #2d8600;
    }
</style>

<div class="d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card register-card p-4" style="width: 100%; max-width: 480px;">
        <h3 class="mb-4 text-center register-title">Registrasi</h3>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Nama Lengkap</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Nama Anda" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Alamat Email</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="nama@email.com" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Kata Sandi</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-success w-100 mt-2">Daftar</button>

            <div class="text-center mt-3">
                <a href="{{ route('login') }}" class="text-decoration-none">Sudah punya akun? <strong>Masuk di sini</strong></a>
            </div>
        </form>
    </div>
</div>
@endsection
