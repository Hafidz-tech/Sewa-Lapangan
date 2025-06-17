<?php

namespace App\Http\Controllers;

use App\Models\Pemesanans;
use App\Models\Pembayarans;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    /**
     * Tandai sebuah pemesanan sebagai "paid" dan batalkan yang lainnya jika konflik jadwal.
     */
    public function bayar($id)
    {
        // Cari pemesanan berdasarkan ID
        $pemesanan = Pemesanans::with(['jadwal', 'pembayaran'])->findOrFail($id);

        // Buat atau update pembayaran menjadi paid
        $pembayaran = Pembayarans::firstOrNew([ 
            'pemesanan_id' => $pemesanan->id //mengecek id pemesanan dengan id yang di proses
        ]);

        $pembayaran->status = 'paid';
        $pembayaran->save();

        // Ambil semua pemesanan lain dengan jadwal dan tanggal sama
        $pemesanansLain = Pemesanans::where('jadwal_id', $pemesanan->jadwal_id) 
            ->where('tanggal', $pemesanan->tanggal)
            ->where('id', '!=', $pemesanan->id) //mengambil id pemesanan lain 
            ->with('pembayaran') 
            ->get();

        // Gagalkan pemesanan lain yang masih pending
        foreach ($pemesanansLain as $pesananLain) { //proses untuk pesanan lain
            if ($pesananLain->pembayaran && $pesananLain->pembayaran->status === 'pending') { 
                $pesananLain->pembayaran->update(['status' => 'gagal']); // mengupdate status pembayaran menjadi gagal
            }
        }

        return redirect()->route('pemesanans.index')->with('success', 'Status pembayaran berhasil diperbarui. Pemesanan lain dibatalkan.');
    }
}
