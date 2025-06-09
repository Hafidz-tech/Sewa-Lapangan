<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemesanans extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'pelanggan_id',
        'jadwal_id',
        'status',
        'tanggal',
        'total_bayar'
    ];

    public function pelanggan()
    {
        return $this->belongsto(Pelanggans::class, 'pelanggan_id');
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwals::class);
    }
    
    public function pembayaran()
    {
        return $this->hasOne(Pembayarans::class, 'pemesanan_id');
    }
}
