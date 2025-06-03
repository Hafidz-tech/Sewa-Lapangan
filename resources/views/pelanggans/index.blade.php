@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="mb-0">Daftar Pelanggan</h3>
                <a href="{{ route('pelanggans.create') }}" class="btn btn-primary">Tambah Pelanggan</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center mb-0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>No Telpon</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pelanggans as $pelanggan)
                        <tr>
                            <td>{{ $pelanggan->nama }}</td>
                            <td>{{ $pelanggan->no_hp }}</td>
                            <td>{{ $pelanggan->alamat }}</td>
                            <td>    
                                <a href="{{ route('pelanggans.edit', $pelanggan->id) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger btn-hapus" data-id="{{ $pelanggan->id }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <form id="form-hapus-{{ $pelanggan->id }}" action="{{ route('pelanggans.destroy', $pelanggan->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                <i class="bi bi-exclamation-circle" style="font-size: 1.5rem;"></i>
                                <br>
                                Belum ada data pelanggan
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
