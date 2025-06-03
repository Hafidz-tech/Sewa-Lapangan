<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lapangans extends Model
{
    use HasFactory;

    // Kolom yang bisa diisi (mass assignable)
    protected $fillable = [
        'nama',
        'harga_per_jam'
    ];

    public function jadwal()
{
    return $this->hasMany(Jadwals::class, 'lapangan_id');
}

}

