<?php

namespace App\Http\Controllers;

use App\Models\Jadwals;
use App\Models\Lapangans;
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
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
        ]);

        Jadwals::create([
            'lapangan_id' => $request->lapangan_id,
            'tanggal' => $request->tanggal,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'status' => 'tersedia', //default status
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
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
        ]);

        $jadwal->update([
            'lapangan_id' => $request->lapangan_id,
            'tanggal' => $request->tanggal,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            //status tidak diubah
        ]);

        return redirect()->route('jadwals.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(Jadwals $jadwal)
    {
        $jadwal->delete();
        return redirect()->route('jadwals.index')->with('success', 'Jadwal berhasil dihapus.');
    }
}
