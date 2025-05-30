<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
    Schema::create('pemesanans', function (Blueprint $table) {
    $table->id();
    $table->foreignId('pelanggan_id')->constrained('pelanggans')->onDelete('cascade');
    $table->foreignId('jadwal_id')->constrained('jadwals')->onDelete('cascade');
    $table->integer('total_bayar');
    $table->enum('status', ['pending', 'dibayar', 'batal'])->default('pending');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemesanans');
    }
};
