<?php

namespace App\Http\Controllers;

use App\Models\Pemesanans;
use App\Models\Pelanggans;
use App\Models\Jadwals;
use App\Models\Pembayarans;
use Illuminate\Http\Request;

class PemesananController extends Controller
{
    public function index()
    {
        $pemesanans = Pemesanans::with(['pelanggan', 'jadwal.lapangan'])->latest()->get();
        return view('pemesanans.index', compact('pemesanans'));
    }

    public function create()
    {
        $pelanggans = Pelanggans::all();
        $jadwals = Jadwals::with('lapangan')->get();

        return view('pemesanans.create', compact('pelanggans', 'jadwals'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pelanggan_id' => 'required|exists:pelanggans,id',
            'jadwal_id' => 'required|exists:jadwals,id',
            'total_bayar' => 'required|numeric',
        ]);

        // Ambil jadwal beserta relasi lapangan
        $jadwal = Jadwals::with('lapangan')->findOrFail($request->jadwal_id);

        // Hitung durasi
        $jamMulai = \Carbon\Carbon::parse($jadwal->jam_mulai);
        $jamSelesai = \Carbon\Carbon::parse($jadwal->jam_selesai);
        $durasiJam = $jamSelesai->diffInMinutes($jamMulai) / 60;

        // Hitung total bayar
        $totalBayar = $jadwal->lapangan->harga_per_jam * $durasiJam;

        // Simpan pemesanan
        $pemesanan = Pemesanans::create([
    'pelanggan_id' => $request->pelanggan_id,
    'jadwal_id' => $request->jadwal_id,
    'total_bayar' => $totalBayar,
]);


        //Simpan data di pembayaran
        Pembayarans::create([
            'pemesanan_id' => $pemesanan->id,
            'status' => 'pending' //default status
        ]);

        return redirect()->route('pemesanans.index')->with('success', 'Pemesanan berhasil ditambahkan.');
    }
}
