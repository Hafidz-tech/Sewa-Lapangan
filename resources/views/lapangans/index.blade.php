@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="mb-0">Daftar Lapangan</h3>
                <!-- Tombol Tambah pakai modal -->
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah Lapangan</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center mb-0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Harga / Jam</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lapangans as $lapangan)
                        <tr>
                            <td>{{ $lapangan->nama }}</td>
                            <td>Rp {{ number_format($lapangan->harga_per_jam, 0, ',', '.') }}</td>
                            <td>    
                                <!-- Tombol Edit pakai modal -->
                                <button 
                                    class="btn btn-sm btn-warning btn-edit" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalEdit"
                                    data-id="{{ $lapangan->id }}"
                                    data-nama="{{ $lapangan->nama }}"
                                    data-harga="{{ $lapangan->harga_per_jam }}">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                <!-- Tombol Hapus -->
                                <button type="button" class="btn btn-sm btn-danger btn-hapus" data-id="{{ $lapangan->id }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <form id="form-hapus-{{ $lapangan->id }}" action="{{ route('lapangans.destroy', $lapangan->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">
                                <i class="bi bi-exclamation-circle" style="font-size: 1.5rem;"></i>
                                <br>
                                Belum ada data lapangan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('lapangans.store') }}" method="POST">
            @csrf   
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahLabel">Tambah Lapangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Lapangan</label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}">
                        @error('nama')
                        <div class="invalid-feedback">
                            {{ $message}}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="harga_per_jam" class="form-label">Harga per Jam</label>
                        <input type="number" name="harga_per_jam" class="form-control @error('harga_per_jam') is-invalid @enderror" value="{{ old('harga_per_jam') }}">
                        @error('harga_per_jam')
                        <div class="invalid-feedback">
                            {{ $message}}
                        </div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formEdit" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditLabel">Edit Lapangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_nama" class="form-label">Nama Lapangan</label>
                        <input type="text" name="nama" id="edit_nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_harga_per_jam" class="form-label">Harga per Jam</label>
                        <input type="number" name="harga_per_jam" id="edit_harga_per_jam" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- SweetAlert for flash messages -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '{{ session('error') }}',
        timer: 2000,
        showConfirmButton: false
    });
</script>
@endif

@if ($errors->any())
<script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal Menyimpan!',
        html: `{!! implode('<br>', $errors->all()) !!}`,
        timer: 2000,
        showConfirmButton: false
    });

    // Buka modal tambah jika validasi gagal
    var tambahModal = new bootstrap.Modal(document.getElementById('modalTambah'));
    tambahModal.show();
</script>
@endif

<script>
    // Tombol hapus dengan konfirmasi SweetAlert
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.btn-hapus').forEach(button => {
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

        // Isi data ke modal edit
        document.querySelectorAll('.btn-edit').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const nama = this.getAttribute('data-nama');
                const harga = this.getAttribute('data-harga');

                document.getElementById('edit_nama').value = nama;
                document.getElementById('edit_harga_per_jam').value = harga;
                document.getElementById('formEdit').action = `/lapangans/${id}`;
            });
        });
    });
</script>

@endsection
