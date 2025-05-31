@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="mb-0">Tambah Pelanggan</h3>
            </div>
        </div>
    <div class="card-body">
    <form action="{{ route('pelanggans.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" class="form-control @error('nama') is-invalid @enderror">
            @error('nama')
            <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="no_hp" class="form-label">Nomor Telpon</label>
            <input type="number" name="no_hp" class="form-control" value="{{ old('no_hp') }}" class="form-control @error('no_hp') is-invalid @enderror">
            @error('no_hp')
            <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <input type="text" name="alamat" class="form-control" value="{{ old('alamat') }}" class="form-control @error('alamat') is-invalid @enderror">
            @error('alamat')
            <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('pelanggans.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
    </div>
</div>
@endsection
