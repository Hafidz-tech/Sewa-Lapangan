<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Ubah ENUM dengan menambahkan opsi 'gagal'
        DB::statement("ALTER TABLE pembayarans MODIFY status ENUM('pending', 'paid', 'gagal') NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        // Kembalikan ENUM ke kondisi awal (tanpa 'gagal')
        DB::statement("ALTER TABLE pembayarans MODIFY status ENUM('pending', 'paid') NOT NULL DEFAULT 'pending'");
    }
};

