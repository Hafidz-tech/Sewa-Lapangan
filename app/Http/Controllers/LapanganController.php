<?php

namespace App\Http\Controllers;

use App\Models\Lapangans;
use Illuminate\Http\Request;

class LapanganController extends Controller
{
    public function index()
    {
        $lapangans = Lapangans::all();
        return view('lapangans.index', compact('lapangans'));
    }

    public function create()
    {
        return view('lapangans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga_per_jam' => 'required|numeric'
        ]);

        Lapangans::create($request->all());

        return redirect()->route('lapangans.index')->with('success', 'Data lapangan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $lapangan = Lapangans::findOrFail($id);
        return view('lapangans.edit', compact('lapangan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga_per_jam' => 'required|numeric'
        ]);

        $lapangan = Lapangans::findOrFail($id);
        $lapangan->update($request->all());

        return redirect()->route('lapangans.index')->with('success', 'Data lapangan berhasil diupdate');
    }

    public function destroy($id)
    {
        $lapangan = Lapangans::findOrFail($id);
        $lapangan->delete();

        return redirect()->route('lapangans.index')->with('success', 'Data lapangan berhasil dihapus');
    }
}
