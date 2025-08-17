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
        Schema::create('kartu_keluargas', function (Blueprint $table) {
            $table->string('no_kk')->primary();
            $table->string('nama_kepala_keluarga');
            $table->unsignedInteger('jumlah_anggota');
            $table->string('foto_ktp_kepala_keluarga')->nullable();
            
            $table->string('nik_kepala');
            
            // $table->string('id_rumah');
            // $table->foreign('id_rumah')->references('id_rumah')->on('rumahs')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kartu_keluargas');
    }
};
