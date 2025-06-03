<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggans extends Model
{
    use HasFactory;


    protected $fillable = [
        'nama',
        'no_hp',
        'alamat',
    ];

    public function pemesanan()
    {
        return $this->hasMany(Pemesanans::class, 'pelanggan_id');
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayarans::class);
    }
    
}
