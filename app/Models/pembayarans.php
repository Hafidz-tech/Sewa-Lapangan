<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayarans extends Model
{
    use HasFactory;

    protected $fillable = [
        'pemesanan_id',
        'status',
    ];   

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanans::class, 'pemesanan_id');
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwals::class);
    }
}
    
