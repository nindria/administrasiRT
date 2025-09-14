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
            $table->string('no_kk', 20)->primary();

            // Kepala keluarga (relasi ke data_wargas)
            $table->string('nik', 16)->nullable();
            $table->foreign('nik')->references('nik')->on('data_wargas')->cascadeOnDelete();

            $table->unsignedInteger('jumlah_anggota')->default(1);
            $table->string('foto_ktp_kepala_keluarga')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kartu_keluargas', function (Blueprint $table) {
            $table->dropForeign(['nik']);
        });

        Schema::dropIfExists('kartu_keluargas');
    }
};
