@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="mb-0">Edit Lapangan</h3>
            </div>
        </div>
    <form action="{{ route('lapangans.update', $lapangan->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Lapangan</label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama', $lapangan->nama) }}" class="form-control @error('nama') is-invalid @enderror">
            @error('nama')
            <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="harga_per_jam" class="form-label">Harga per Jam</label>
            <input type="number" name="harga_per_jam" class="form-control" value="{{ old('harga_per_jam', $lapangan->harga_per_jam) }}" class="form-control @error('harga_per_jam') is-invalid @enderror">
            @error('harga_per_jam')
            <div class="text-danger small">{{$message }}</div>
            @enderror
        </div>
        
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('lapangans.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
