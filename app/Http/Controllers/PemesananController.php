<?php

namespace App\Http\Controllers;

use App\Models\Pemesanans;
use App\Models\Pelanggans;
use App\Models\Jadwals;
use App\Models\Pembayarans;
use App\Models\Lapangans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // untuk menangani transaksi database
use Carbon\Carbon; // untuk menangani tanggal dan waktu

class PemesananController extends Controller
{
    public function index() //menampilkan data
    {
        $pemesanans = Pemesanans::with(['pelanggan', 'jadwal.lapangan', 'pembayaran'])->latest()->get();
        return view('pemesanans.index', compact('pemesanans'));
    }

    public function create() //menampilkan form tambah data
    {
        $pelanggans = Pelanggans::all();
        $lapangans = Lapangans::all();

        return view('pemesanans.create', compact('pelanggans', 'lapangans'));
    }

    public function store(Request $request) //menyimpan data baru yang dikirimkan melalui form
{
    $request->validate([ //memerikasa data yang dikirimkan melalui form sebelum disimpan ke database
        'pelanggan_id' => 'required|exists:pelanggans,id', //memastikan pelanggan_id ada di tabel pelanggans
        'jadwal_id' => 'required|exists:jadwals,id',
        'tanggal' => 'required|date|after_or_equal:today',
        'total_bayar' => 'required|numeric',
    ]);

    try { //memulai blok try untuk menangani kemungkinan error
        DB::beginTransaction(); //memulai transaksi database

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
        $jamMulai = Carbon::parse($jadwal->jam_mulai); //mengubah string waktu menjadi objek Carbon
        $jamSelesai = Carbon::parse($jadwal->jam_selesai); 
        $durasiJam = $jamSelesai->diffInMinutes($jamMulai) / 60; //selisih
        
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

        DB::commit(); //menyimpan semua perubahan ke database

        return redirect()->route('pemesanans.index')->with('success', 'Pemesanan berhasil ditambahkan.');
    } catch (\Exception $e) { //menangkap error jika terjadi kesalahan
        DB::rollBack(); 
        return back()->with('error', 'Terjadi kesalahan saat menyimpan data.')->withInput(); //with input untuk mengembalikan data yang sudah diisi sebelumnya
    }
}

public function getJadwal($lapangan_id, Request $request)
{
    $tanggal = $request->query('tanggal');

    $jadwals = Jadwals::with('lapangan') 
        ->where('lapangan_id', $lapangan_id) 
        ->whereDoesntHave('pemesanan', function ($query) use ($tanggal) { 
            $query->whereDate('tanggal', $tanggal) 
                ->whereHas('pembayaran', function ($q) { // mengecek apakah pemesanan pada tanggal sekian status pembayarannya paid
                    $q->where('status', 'paid');
                });
        })
        ->get(); //menjalankan filter tersebut

    return response()->json($jadwals); //mengubah data jadwals menjadi format JSON agar dapat ditampilkan di browser melalui javascript
}
    public function destroy($id) //menghapus data pemesanan
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
