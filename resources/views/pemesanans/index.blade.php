@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="mb-0">Daftar Pemesanan</h3>
                <a href="{{ route('pemesanans.create') }}" class="btn btn-primary">Tambah Pemesanan</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center mb-0">
                    <thead>
                        <tr>
                            <th>Nama Pelanggan</th>
                            <th>Lapangan</th>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Total Harga</th>
                            <th>Status Pembayaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pemesanans as $pemesanan)
                        <tr>
                            <td>{{ $pemesanan->pelanggan->nama ?? '-' }}</td>
                            <td>{{ $pemesanan->jadwal->lapangan->nama ?? '-' }}</td>
                            <td>{{ $pemesanan->jadwal->tanggal ?? '-' }}</td>
                            <td>{{ $pemesanan->jadwal->jam_mulai ?? '-' }} - {{ $pemesanan->jadwal->jam_selesai ?? '-' }}</td>
                            <td>Rp {{ number_format($pemesanan->total_bayar, 0, ',', '.') }}</td>

                            {{-- Status Pembayaran --}}
                            <td>
                                @if($pemesanan->pembayaran)
                                    <span class="badge bg-{{ $pemesanan->pembayaran->status == 'paid' ? 'success' : 'warning text-dark' }}">
                                        {{ ucfirst($pemesanan->pembayaran->status) }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">Pending</span>
                                @endif
                            </td>

                            {{-- Tombol Aksi --}}
                            <td>
                                @if(!$pemesanan->pembayaran || $pemesanan->pembayaran->status != 'paid')
                                    <form action="{{ route('pembayarans.bayar', $pemesanan->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">Tandai Sudah Bayar</button>
                                    </form>
                                @else
                                    <button type="button" class="btn btn-sm btn-danger btn-hapus" data-id="{{ $pemesanan->id }}">Hapus</button>
                                    <form id="form-hapus-{{ $pemesanan->id }}" action="{{ route('pemesanans.destroy', $pemesanan->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                <i class="bi bi-exclamation-circle" style="font-size: 1.5rem;"></i>
                                <br>
                                Belum ada data pemesanan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        timer: 2000,
        showConfirmButton: false
    });
</script>
@endif
<script>
document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('.btn-hapus');
    buttons.forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data akan hilang secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-hapus-' + id).submit();
                }
            });
        });
    });
});
</script>
@endsection
