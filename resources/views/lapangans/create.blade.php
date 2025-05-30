@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="mb-0">Tambah Lapangan</h3>
            </div>
        </div>
    <div class="card-body">
    <form action="{{ route('lapangans.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Lapangan</label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" class="form-control @error('nama') is-invalid @enderror">
            @error('nama')
            <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="harga_per_jam" class="form-label">Harga per Jam</label>
            <input type="number" name="harga_per_jam" class="form-control" value="{{ old('harga_per_jam') }}" class="form-control @error('harga_per_jam') is-invalid @enderror">
            @error('harga_per_jam')
            <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('lapangans.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
    </div>
</div>
@endsection
