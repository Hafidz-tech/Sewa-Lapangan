<?php

namespace App\Http\Controllers;

use App\Models\Pelanggans;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggans = Pelanggans::latest()->get();
        return view('pelanggans.index', compact('pelanggans'));
    }

    public function create()
    {
        return view('pelanggans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string|max:255',
        ]);

        Pelanggans::create($request->all());

        return redirect()->route('pelanggans.index')->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function edit(Pelanggans $pelanggan)
    {
        return view('pelanggans.edit', compact('pelanggan'));
    }

    public function update(Request $request, Pelanggans $pelanggan)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string|max:255',
        ]);

        $pelanggan->update($request->all());

        return redirect()->route('pelanggans.index')->with('success', 'Pelanggan berhasil diperbarui.');
    }

    public function destroy(Pelanggans $pelanggan)
    {
    // Cek apakah pelanggan memiliki pemesanan
    if ($pelanggan->pemesanan()->exists()) {
        return redirect()->route('pelanggans.index')
            ->with('error', 'Pelanggan tidak bisa dihapus karena masih memiliki data pemesanan.');
    }

    // Jika tidak ada pemesanan, baru hapus
    $pelanggan->delete();
    return redirect()->route('pelanggans.index')->with('success', 'Pelanggan berhasil dihapus.');
    }

}
