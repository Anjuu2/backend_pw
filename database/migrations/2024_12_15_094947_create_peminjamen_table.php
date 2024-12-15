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
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id();
            $table->integer("nomor_akun");
            $table->float("nominal_peminjaman");
            $table->date("tanggal_peminjaman");
            $table->integer("masa_peminjaman");
            $table->string("ktm");
            $table->string("deskripsi_peminjaman");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};
