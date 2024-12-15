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
        Schema::create('akuns', function (Blueprint $table) {
            $table->id();
            $table->string("npm");
            $table->string("nomor_rekening");
            $table->string("nama_akun");
            $table->float("saldo");
            $table->string("pin");
            $table->string("password");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akuns');
    }
};
