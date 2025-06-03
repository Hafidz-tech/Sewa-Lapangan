<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwals extends Model
{
    use HasFactory;

    protected $fillable = [
        'lapangan_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'status'
    ];

    /**
     * Relasi ke model Lapangan
     */
    public function lapangan()
    {
        return $this->belongsTo(Lapangans::class, "lapangan_id");
    }

    /**
     * Relasi ke model Pemesanan (jika ada)
     */
    public function pemesanan()
    {
        return $this->hasMany(Pemesanans::class, "jadwal_id");
    }
}
