<?php

namespace App\Http\Controllers;

use App\Models\Lapangans;
use Illuminate\Http\Request;

class LapanganController extends Controller
{
    // Menampilkan daftar lapangan
    public function index()
    {
        $lapangans = Lapangans::all();
        return view('lapangans.index', compact('lapangans'));
    }

    // Menyimpan lapangan baru (dari modal tambah)
    public function store(Request $request)
    {
        $request->validate([ //memeriksa data yang dikirimkan melalui form sebelum disimpan ke database
            'nama' => 'required|string|max:255|unique:Lapangans,nama', //unique memastikan nama lapangan tidak ada yang sama
            'harga_per_jam' => 'required|numeric'
        ]);

        Lapangans::create([
            'nama' => $request->nama,
            'harga_per_jam' => $request->harga_per_jam
        ]);

        return redirect()->route('lapangans.index')->with('success', 'Data lapangan berhasil ditambahkan');
    }

    // Mengupdate data lapangan (dari modal edit)
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:Lapangans,nama',
            'harga_per_jam' => 'required|numeric'
        ]);

        $lapangan = Lapangans::findOrFail($id);
        $lapangan->update([
            'nama' => $request->nama,
            'harga_per_jam' => $request->harga_per_jam
        ]);

        return redirect()->route('lapangans.index')->with('success', 'Data lapangan berhasil diupdate');
    }

   // Menghapus lapangan
    public function destroy($id)
    {
        
    $lapangan = Lapangans::findOrFail($id);

    // Cek apakah masih ada jadwal terkait
    if ($lapangan->jadwal()->exists()) {
        return redirect()->route('lapangans.index')
            ->with('error', 'Lapangan gagal dihapus karena masih memiliki jadwal yang terkait.');
    }

    $lapangan->delete();

    return redirect()->route('lapangans.index')->with('success', 'Data lapangan berhasil dihapus.');
    }

}
