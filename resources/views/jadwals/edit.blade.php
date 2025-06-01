@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h3>Edit Jadwal</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('jadwals.update', $jadwal->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="lapangan_id" class="form-label">Pilih Lapangan</label>
                    <select name="lapangan_id" id="lapangan_id" class="form-select" @error ('lapangan_id') is-invalid @enderror> 
                        <option value="">-- Pilih Lapangan --</option>
                        @foreach($lapangans as $lapangan)
                            <option value="{{ $lapangan->id }}" {{old('lapangan_id') == $jadwal->lapangan_id == $lapangan->id ? 'selected' : '' }}>
                                {{ $lapangan->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('lapangan_')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="tanggal" class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', $jadwal->tanggal }}" class="form-control @error('tanggal') is-invalid @enderror">
                    @error('tanggal')
                    <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="jam_mulai" class="form-label">Jam Mulai</label>
                    <input type="time" name="jam_mulai" class="form-control" value="{{ old('jam_mulai', $jadwal->jam_mulai }}" class="form-control @error('jam_mulai') is-invalid @enderror">
                    @error('jam_mulai')
                    <div class="text-danger" small>{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="jam_selesai" class="form-label">Jam Selesai</label>
                    <input type="time" name="jam_selesai" class="form-control" value="{{old('jam_selesai', $jadwal->jam_selesai }}" class="form-control @error('jam_selesai') is_invalid @enderror">
                    @error('jam_selesai')
                    <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('jadwals.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
@endsection
