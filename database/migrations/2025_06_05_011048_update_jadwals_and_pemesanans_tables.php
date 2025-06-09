<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateJadwalsAndPemesanansTables extends Migration
{
    public function up()
{
    // Hapus kolom tanggal dari jadwals
    Schema::table('jadwals', function (Blueprint $table) {
        if (Schema::hasColumn('jadwals', 'tanggal')) {
            $table->dropColumn('tanggal');
        }
    });

    // Tambah kolom tanggal ke pemesanans dan izinkan null dulu
    Schema::table('pemesanans', function (Blueprint $table) {
        $table->date('tanggal')->nullable()->after('jadwal_id');
    });
}

}
