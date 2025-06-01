@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h3 class="mb-0">Tambah Pemesanan</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('pemesanans.store') }}" method="POST">
                @csrf

                <!-- Pelanggan -->
                <div class="mb-3">
                    <label for="pelanggan_id" class="form-label">Pilih Pelanggan</label>
                    <select name="pelanggan_id" class="form-select @error('pelanggan_id') is-invalid @enderror">
                        <option value="">-- Pilih Pelanggan --</option>
                        @foreach($pelanggans as $pelanggan)
                            <option value="{{ $pelanggan->id }}" {{ old('pelanggan_id') == $pelanggan->id ? 'selected' : '' }}>
                                {{ $pelanggan->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('pelanggan_id')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Jadwal -->
                <div class="mb-3">
                    <label for="jadwal_id" class="form-label">Pilih Jadwal</label>
                    <select id="jadwal_id" name="jadwal_id" class="form-select @error('jadwal_id') is-invalid @enderror">
                        <option value="">-- Pilih Jadwal --</option>
                        @foreach($jadwals as $jadwal)
                            <option 
                                value="{{ $jadwal->id }}" 
                                data-harga="{{ $jadwal->lapangan->harga_per_jam }}"
                                data-jam="{{ $jadwal->jam_mulai }}-{{ $jadwal->jam_selesai }}"
                                {{ old('jadwal_id') == $jadwal->id ? 'selected' : '' }}>
                                {{ $jadwal->lapangan->nama }} - {{ $jadwal->tanggal }} ({{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }})
                            </option>
                        @endforeach
                    </select>
                    @error('jadwal_id')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Total Bayar (Preview) -->
                <div class="mb-3">
                    <label class="form-label">Total Bayar</label>
                    <input type="text" class="form-control" id="preview_total" readonly>
                    <!-- Hidden input agar data terkirim -->
                    <input type="hidden" name="total_bayar" id="total_bayar">
                </div>

                <button type="submit" class="btn btn-success">Simpan</button>
                <a href="{{ route('pemesanans.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>

<!-- Script Hitung Otomatis -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectJadwal = document.getElementById('jadwal_id');
    const totalPreview = document.getElementById('preview_total');
    const totalHidden = document.getElementById('total_bayar');

    function hitungTotal() {
        const selectedOption = selectJadwal.options[selectJadwal.selectedIndex];
        const hargaPerJam = parseFloat(selectedOption.getAttribute('data-harga'));
        const jamRange = selectedOption.getAttribute('data-jam');

        if (!hargaPerJam || !jamRange) {
            totalPreview.value = '';
            totalHidden.value = '';
            return;
        }

        const [jamMulai, jamSelesai] = jamRange.split('-');
        const [mulaiJam, mulaiMenit] = jamMulai.split(':').map(Number);
        const [selesaiJam, selesaiMenit] = jamSelesai.split(':').map(Number);

        const totalMenit = (selesaiJam * 60 + selesaiMenit) - (mulaiJam * 60 + mulaiMenit);
        const totalJam = totalMenit / 60;
        const total = hargaPerJam * totalJam;

        totalPreview.value = 'Rp ' + total.toLocaleString('id-ID');
        totalHidden.value = total;
    }

    selectJadwal.addEventListener('change', hitungTotal);

    if (selectJadwal.value) {
        hitungTotal();
    }
});
</script>
@endsection
