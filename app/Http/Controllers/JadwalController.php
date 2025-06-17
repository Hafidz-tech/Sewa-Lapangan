<?php

namespace App\Http\Controllers;

use App\Models\Jadwals;
use App\Models\Lapangans;
use App\Models\Pemesanans;
use App\Models\Pembayarans;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index()
    {
        $jadwals = Jadwals::with('lapangan')->latest()->get();
        return view('jadwals.index', compact('jadwals'));
    }

    public function create()
    {
        $lapangans = Lapangans::all();
        return view('jadwals.create', compact('lapangans'));
    }   

    public function store(Request $request)
{
    $request->validate([
        'lapangan_id' => 'required|exists:lapangans,id',
        'jam_mulai' => 'required|date_format:H:i',
        'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
    ]);

    // Cek apakah ada jadwal yang bentrok
    $conflict = Jadwals::where('lapangan_id', $request->lapangan_id)
        ->where(function ($query) use ($request) { 
            $query->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai]) //mengeceK apakah jam_mulai dari jadwal lain berada ditengah-tengah jam baru
                  ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai]) 
                  ->orWhere(function ($q) use ($request) { //meminta untuk mencari data dari jadwal lain yang jam nya mencakup seluruh waktu
                      $q->where('jam_mulai', '<=', $request->jam_mulai)
                        ->where('jam_selesai', '>=', $request->jam_selesai);
                  });
        })->exists();
        
    if ($conflict) {
        return redirect()->back()
            ->withInput() //menyimpan data pada form input
            ->with('error', 'Jadwal bentrok dengan jadwal yang sudah ada di lapangan yang sama.');
    }

    Jadwals::create([
        'lapangan_id' => $request->lapangan_id,
        'jam_mulai' => $request->jam_mulai,
        'jam_selesai' => $request->jam_selesai,
    ]);

    return redirect()->route('jadwals.index')->with('success', 'Jadwal berhasil ditambahkan.');
}


    public function edit(Jadwals $jadwal)
    {
        $lapangans = Lapangans::all();
        return view('jadwals.edit', compact('jadwal', 'lapangans'));
    }

    public function update(Request $request, Jadwals $jadwal)
    {
        $request->validate([
            'lapangan_id' => 'required|exists:lapangans,id',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
        ]);

        $jadwal->update([
            'lapangan_id' => $request->lapangan_id,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            //status tidak diubah
        ]);

        return redirect()->route('jadwals.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(Jadwals $jadwal)
    {
    // Periksa apakah ada pemesanan terkait dengan jadwal ini
    if ($jadwal->pemesanan()->exists()) {
    return redirect()->route('jadwals.index')
        ->with('error', 'Jadwal gagal dihapus karena masih memiliki pemesanan.');
    }

    // Jika tidak ada pemesanan, maka hapus
    $jadwal->delete();

    return redirect()->route('jadwals.index')->with('success', 'Jadwal berhasil dihapus.');
}


}
