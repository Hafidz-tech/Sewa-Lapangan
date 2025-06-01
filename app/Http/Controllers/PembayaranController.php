<?php

namespace App\Http\Controllers;

use App\Models\Pemesanans;
use App\Models\Pembayarans;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function bayar($id)
    {
        //cari pemesanan berdasarkan id
        $pemesanan = Pemesanans::findOrFail($id);

        //cari pembayaran yang terkait
        $pembayaran = Pembayarans::firstOrNew([
            'pemesanan_id' => $pemesanan->id
        ]);

        //update status agar menjadi paid   
        $pembayaran->status= 'paid';
        $pembayaran->save();

        return redirect()->route('pemesanans.index')->with('success', 'Status pembayaran berhasil diperbarui.');

    }
}
