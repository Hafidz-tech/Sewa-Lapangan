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
        $pemesanans = Pemesanans::with(['pelanggan', 'jadwal.lapangan', 'pembayaran'])->latest()->get();
        return view('pemesanans.index', compact('pemesanans'));
    }

    public function create()
{
    $pelanggans = Pelanggans::all();
    $lapangans = \App\Models\Lapangans::all(); // Ambil semua lapangan
    $jadwals = Jadwals::with('lapangan')->where('status', 'tersedia')->get(); // Ambil semua jadwal tersedia

    return view('pemesanans.create', compact('pelanggans', 'lapangans', 'jadwals'));
}

    public function store(Request $request)
    {
        $request->validate([
            'pelanggan_id' => 'required|exists:pelanggans,id',
            'jadwal_id' => 'required|exists:jadwals,id',
            'total_bayar' => 'required|numeric',
        ]);

        // Ambil jadwal
        $jadwal = Jadwals::with('lapangan')->findOrFail($request->jadwal_id);

        // Cek apakah jadwal sudah terpakai
        if ($jadwal->status === 'terpakai') {
            return back()->withErrors(['jadwal_id' => 'Jadwal ini sudah digunakan dalam pemesanan lain.'])->withInput();
        }

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

        // Simpan pembayaran dengan status pending
        Pembayarans::create([
            'pemesanan_id' => $pemesanan->id,
            'status' => 'pending',
        ]);

        return redirect()->route('pemesanans.index')->with('success', 'Pemesanan berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $pemesanan = Pemesanans::findOrFail($id);

        // Hapus pembayaran terkait dulu (kalau ada)
        $pemesanan->pembayaran()->delete();

        // Hapus pemesanan itu sendiri
        $pemesanan->delete();

        return redirect()->route('pemesanans.index')->with('success', 'Pemesanan berhasil dihapus.');
    }

    // Method tambahan untuk filter jadwal berdasarkan lapangan (AJAX)
    public function getJadwalsByLapangan($lapanganId)
    {
        $jadwals = Jadwals::where('lapangan_id', $lapanganId)
            ->where('status', 'tersedia')
            ->with('lapangan')
            ->get();

        return response()->json($jadwals);
    }
}
