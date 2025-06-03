<!DOCTYPE html>
<html>
<head>
    <title>Booking Lapangan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    {{-- Cek apakah halaman meminta untuk menyembunyikan navbar --}}
    @if (!View::hasSection('hideNavbar'))
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
            <a class="navbar-brand" href="{{ url('/') }}">Bookinglap</a>
            <ul class="navbar-nav me-auto">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('lapangans.index') }}">Lapangan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('jadwals.index') }}">Jadwal</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('pelanggans.index') }}">Pelanggan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('pemesanans.index') }}">Pemesanan</a>
            </li>
        </ul>

            <div class="ms-auto">
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-sm btn-outline-light">Logout</button>
                    </form>
            </div>
        </nav>
    @endif

    <div class="container mt-4">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
