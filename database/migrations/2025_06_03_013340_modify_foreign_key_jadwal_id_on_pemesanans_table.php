<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyForeignKeyJadwalIdOnPemesanansTable extends Migration
{
    public function up()
    {
        Schema::table('pemesanans', function (Blueprint $table) {
            // Drop foreign key lama
            $table->dropForeign(['jadwal_id']);
            
            // Buat foreign key baru tanpa cascade delete (restrict)
            $table->foreign('jadwal_id')
                ->references('id')
                ->on('jadwals')
                ->onDelete('restrict'); // atau kamu bisa hilangkan onDelete
        });
    }

    public function down()
    {
        Schema::table('pemesanans', function (Blueprint $table) {
            // Drop foreign key baru
            $table->dropForeign(['jadwal_id']);
            
            // Kembalikan foreign key lama dengan cascade delete
            $table->foreign('jadwal_id')
                ->references('id')
                ->on('jadwals')
                ->onDelete('cascade');
        });
    }
}
