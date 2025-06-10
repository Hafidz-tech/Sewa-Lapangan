<?php

namespace App\Http\Controllers;

use App\Models\Pemesanans;
use App\Models\Pelanggans;
use App\Models\Jadwals;
use App\Models\Pembayarans;
use App\Models\Lapangans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
        $lapangans = Lapangans::all();

        return view('pemesanans.create', compact('pelanggans', 'lapangans'));
    }

    public function store(Request $request)
{
    $request->validate([
        'pelanggan_id' => 'required|exists:pelanggans,id',
        'jadwal_id' => 'required|exists:jadwals,id',
        'tanggal' => 'required|date|after_or_equal:today',
        'total_bayar' => 'required|numeric',
    ]);

    try {
        DB::beginTransaction();

        // Cek apakah jadwal sudah dibayar (paid) pada tanggal yang sama
        $jadwalDigunakan = Pemesanans::where('jadwal_id', $request->jadwal_id)
            ->where('tanggal', $request->tanggal)
            ->whereHas('pembayaran', function ($query) {
                $query->where('status', 'paid');
            })
            ->exists();

        if ($jadwalDigunakan) {
            return back()->withErrors([
                'jadwal_id' => 'Jadwal ini sudah dipesan dan dibayar pada tanggal tersebut.'
            ])->withInput();
        }

        // Ambil jadwal
        $jadwal = Jadwals::with('lapangan')->findOrFail($request->jadwal_id);

        // Hitung durasi
        $jamMulai = Carbon::parse($jadwal->jam_mulai);
        $jamSelesai = Carbon::parse($jadwal->jam_selesai);
        $durasiJam = $jamSelesai->diffInMinutes($jamMulai) / 60;

        // Hitung total bayar
        $totalBayar = $jadwal->lapangan->harga_per_jam * $durasiJam;

        // Simpan pemesanan
        $pemesanan = Pemesanans::create([
            'pelanggan_id' => $request->pelanggan_id,
            'jadwal_id' => $request->jadwal_id,
            'tanggal' => $request->tanggal,
            'total_bayar' => $totalBayar,
        ]);

        // Simpan pembayaran
        Pembayarans::create([
            'pemesanan_id' => $pemesanan->id,
            'status' => 'pending',
        ]);

        DB::commit();

        return redirect()->route('pemesanans.index')->with('success', 'Pemesanan berhasil ditambahkan.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Terjadi kesalahan saat menyimpan data.')->withInput();
    }
}

public function getJadwal($lapangan_id, Request $request)
{
    $tanggal = $request->query('tanggal');

    $jadwals = Jadwals::with('lapangan')
        ->where('lapangan_id', $lapangan_id)
        ->whereDoesntHave('pemesanan', function ($query) use ($tanggal) {
            $query->whereDate('tanggal', $tanggal)
                ->whereHas('pembayaran', function ($q) {
                    $q->where('status', 'paid');
                });
        })
        ->get();

    return response()->json($jadwals);
}
    public function destroy($id)
    {
        $pemesanan = Pemesanans::with('pembayaran')->findOrFail($id);

        // Hapus pembayaran dulu
        if ($pemesanan->pembayaran) {
            $pemesanan->pembayaran->delete();
        }

        $pemesanan->delete();

        return redirect()->route('pemesanans.index')->with('success', 'Pemesanan berhasil dihapus.');
    }
}
