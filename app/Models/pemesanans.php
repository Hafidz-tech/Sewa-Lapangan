<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pemesanans extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'pelanggan_id',
        'jadwal_id',
        'status',
        'total_bayar'
    ];

    public function pelanggan()
    {
        return $this->belongsto(pelanggans::class, 'pelanggan_id');
    }

    public function jadwal()
    {
        return $this->belongsto(jadwals::class, 'jadwal_id');
    }
    
    public function pembayaran()
    {
        return $this->hasOne(Pembayarans::class, 'pemesanan_id');
    }
}
