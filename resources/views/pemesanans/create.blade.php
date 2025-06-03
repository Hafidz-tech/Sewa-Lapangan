@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h4>Tambah Pemesanan</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('pemesanans.store') }}" method="POST">
                @csrf

                <!-- Pelanggan -->
                <div class="mb-3">
                    <label for="pelanggan_id" class="form-label">Pelanggan</label>
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

                <!-- Lapangan -->
                <div class="mb-3">
                    <label for="lapangan_id" class="form-label">Lapangan</label>
                    <select id="lapangan_id" class="form-select">
                        <option value="">-- Pilih Lapangan --</option>
                        @foreach($lapangans as $lapangan)
                            <option value="{{ $lapangan->id }}">{{ $lapangan->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Jadwal -->
                <div class="mb-3">
                    <label for="jadwal_id" class="form-label">Jadwal</label>
                    <select name="jadwal_id" id="jadwal_id" class="form-select @error('jadwal_id') is-invalid @enderror">
                        <option value="">-- Pilih Jadwal --</option>
                        {{-- Jadwal akan diisi lewat JavaScript --}}
                    </select>
                    @error('jadwal_id')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Total Bayar -->
                <div class="mb-3">
                    <label class="form-label">Total Bayar</label>
                    <input type="text" id="preview_total" class="form-control" readonly>
                    <input type="hidden" name="total_bayar" id="total_bayar">
                </div>

                <button type="submit" class="btn btn-success">Simpan</button>
                <a href="{{ route('pemesanans.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>

<!-- Script: Dinamis dan Hitung Total -->
<script>
    const lapanganSelect = document.getElementById('lapangan_id');
    const jadwalSelect = document.getElementById('jadwal_id');
    const previewTotal = document.getElementById('preview_total');
    const hiddenTotal = document.getElementById('total_bayar');

    const semuaJadwal = @json($jadwals);

    function formatRupiah(angka) {
        return 'Rp ' + angka.toLocaleString('id-ID');
    }

    function hitungTotal() {
        const selectedOption = jadwalSelect.options[jadwalSelect.selectedIndex];
        if (!selectedOption) return;

        const harga = parseFloat(selectedOption.getAttribute('data-harga'));
        const jamRange = selectedOption.getAttribute('data-jam');

        if (!harga || !jamRange) {
            previewTotal.value = '';
            hiddenTotal.value = '';
            return;
        }

        const [mulai, selesai] = jamRange.split('-');
        const [jamMulai, menitMulai] = mulai.split(':').map(Number);
        const [jamSelesai, menitSelesai] = selesai.split(':').map(Number);

        const totalMenit = (jamSelesai * 60 + menitSelesai) - (jamMulai * 60 + menitMulai);
        const totalJam = totalMenit / 60;
        const total = harga * totalJam;

        previewTotal.value = formatRupiah(total);
        hiddenTotal.value = total;
    }

    lapanganSelect.addEventListener('change', function () {
        const lapanganId = this.value;
        jadwalSelect.innerHTML = '<option value="">-- Pilih Jadwal --</option>';

        semuaJadwal.forEach(jadwal => {
            if (jadwal.lapangan_id == lapanganId && jadwal.status === 'tersedia') {
                const option = document.createElement('option');
                option.value = jadwal.id;
                option.textContent = `${jadwal.tanggal} (${jadwal.jam_mulai} - ${jadwal.jam_selesai})`;
                option.setAttribute('data-harga', jadwal.lapangan.harga_per_jam);
                option.setAttribute('data-jam', `${jadwal.jam_mulai}-${jadwal.jam_selesai}`);
                jadwalSelect.appendChild(option);
            }
        });

        hitungTotal();
    });

    jadwalSelect.addEventListener('change', hitungTotal);
</script>
@endsection
