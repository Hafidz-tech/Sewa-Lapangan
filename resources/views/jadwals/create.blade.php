@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="mb-0">Tambah Jadwal</h3>
            </div>
        </div>
    <div class="card-body">
    <form action="{{ route('jadwals.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="lapangan_id" class="form-label">Lapangan</label>
            <select name="lapangan_id" class="form-select" @error('lapangan_id') is-invalid @enderror>
                <option value="">-- Pilih Lapangan --</option>  
                @foreach ($lapangans as $lapangan)
                    <option value="{{ $lapangan->id }}" {{old('lapangan_id') == $lapangan->id ? 'selected' : ''}}>{{ $lapangan->nama }}</option>
                @endforeach
            </select>
            @error('lapangan_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal</label>
            @php
                $today = date('Y-m-d');
            @endphp
            <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal') }}" min="{{ $today }}">
            @error('tanggal')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="jam_mulai" class="form-label">Jam Mulai</label>
            <input type="time" name="jam_mulai" class="form-control" value="{{ old('jam_mulai') }} @error('jam_mulai') is_invalid @enderror">
            @error('tanggal')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="jam_selesai" class="form-label">Jam Selesai</label>
            <input type="time" name="jam_selesai" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('jadwals.index') }}" class="btn btn-secondary">Batal</a>
    </form>
    </div>
</div>
@endsection
