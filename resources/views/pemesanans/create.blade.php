@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h4>Tambah Pemesanan</h4>
        </div>
        <div class="card-body">
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('pemesanans.store') }}" method="POST">
                @csrf

                <!-- Pelanggan -->
                <div class="mb-3">
                    <label for="pelanggan_id" class="form-label">Pelanggan</label>
                    <select name="pelanggan_id" class="form-select @error('pelanggan_id') is-invalid @enderror">
                        <option value="">-- Pilih Pelanggan --</option>
                        @foreach ($pelanggans as $p)
                            <option value="{{ $p->id }}" {{ old('pelanggan_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('pelanggan_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tanggal -->
                <div class="mb-3">
                    <label for="tanggal" class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal') }}" min="{{ date('Y-m-d') }}" class="form-control @error('tanggal') is-invalid @enderror">
                    @error('tanggal')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Lapangan -->
                <div class="mb-3">
                    <label for="lapangan_id" class="form-label">Lapangan</label>
                    <select id="lapangan_id" class="form-select">
                        <option value="">-- Pilih Lapangan --</option>
                        @foreach ($lapangans as $lapangan)
                            <option value="{{ $lapangan->id }}" {{ old('lapangan_id') == $lapangan->id ? 'selected' : '' }}>
                                {{ $lapangan->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Jadwal -->
                <div class="mb-3">
                    <label for="jadwal_id" class="form-label">Jadwal</label>
                    <select name="jadwal_id" id="jadwal_id" class="form-select @error('jadwal_id') is-invalid @enderror">
                        <option value="">-- Pilih Jadwal --</option>
                    </select>
                    @error('jadwal_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Total Bayar -->
                <div class="mb-3">
                    <label for="total_bayar" class="form-label">Total Bayar</label>
                    <input type="text" id="preview_total" class="form-control" readonly>
                    <input type="hidden" name="total_bayar" id="total_bayar">
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('pemesanans.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>

<!-- Script -->
<script>
    const lapangan = document.getElementById('lapangan_id');
    const tanggal = document.getElementById('tanggal');
    const jadwal = document.getElementById('jadwal_id');
    const previewTotal = document.getElementById('preview_total');
    const totalBayar = document.getElementById('total_bayar');

    function formatRupiah(angka) {
        return 'Rp ' + angka.toLocaleString('id-ID');
    }

    function hitungTotal() {
        const option = jadwal.options[jadwal.selectedIndex];
        if (!option || !option.value) return;

        const harga = parseFloat(option.getAttribute('data-harga'));
        const jamMulai = option.getAttribute('data-mulai');
        const jamSelesai = option.getAttribute('data-selesai');

        const mulai = jamMulai.split(':');
        const selesai = jamSelesai.split(':');
        const durasi = (parseInt(selesai[0]) * 60 + parseInt(selesai[1])) - (parseInt(mulai[0]) * 60 + parseInt(mulai[1]));
        const jam = durasi / 60;

        const total = harga * jam;

        previewTotal.value = formatRupiah(total);
        totalBayar.value = total;
    }

    function fetchJadwal() {
        const lapanganId = lapangan.value;
        const tanggalVal = tanggal.value;

        jadwal.innerHTML = '<option value="">-- Pilih Jadwal --</option>';
        previewTotal.value = '';
        totalBayar.value = '';

        if (lapanganId && tanggalVal) {
            fetch(`/get-jadwal/${lapanganId}?tanggal=${tanggalVal}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length === 0) {
                        const opt = document.createElement('option');
                        opt.text = 'Tidak ada jadwal tersedia';
                        opt.value = '';
                        jadwal.appendChild(opt);
                    } else {
                        data.forEach(j => {
                            const opt = document.createElement('option');
                            opt.value = j.id;
                            opt.text = `${j.jam_mulai} - ${j.jam_selesai}`;
                            opt.setAttribute('data-harga', j.lapangan.harga_per_jam);
                            opt.setAttribute('data-mulai', j.jam_mulai);
                            opt.setAttribute('data-selesai', j.jam_selesai);
                            jadwal.appendChild(opt);
                        });
                    }
                });
        }
    }

    lapangan.addEventListener('change', fetchJadwal);
    tanggal.addEventListener('change', fetchJadwal);
    jadwal.addEventListener('change', hitungTotal);

    // Auto load jadwal jika old value ada
    window.addEventListener('load', () => {
        if (lapangan.value && tanggal.value) {
            fetchJadwal();
        }
    });
</script>
@endsection
