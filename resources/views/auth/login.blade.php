@extends('layouts.app')

@section('hideNavbar')
@endsection

@section('content')
<style>
    body {
        background: #f0f4f8;
    }

    .login-card {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        background: #ffffff;
    }

    .login-title {
        font-weight: 600;
        color: #2c3e50;
    }

    .form-control {
        border-radius: 0.5rem;
    }

    .btn-primary {
        border-radius: 0.5rem;
        background-color: #4a69bd;
        border: none;
    }

    .btn-primary:hover {
        background-color: #3b55a0;
    }
</style>

<div class="d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card login-card p-4" style="width: 100%; max-width: 420px;">
        <h3 class="mb-4 text-center login-title">Login</h3>

        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Alamat Email</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="nama@email.com" required autofocus>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Kata Sandi</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-primary w-100 mt-2">Masuk</button>

            <div class="text-center mt-3">
                <a href="{{ route('register') }}" class="text-decoration-none">Belum punya akun? <strong>Daftar di sini</strong></a>
            </div>
        </form>
    </div>
</div>
@endsection
