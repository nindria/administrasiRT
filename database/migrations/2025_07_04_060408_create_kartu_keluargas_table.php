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
<<<<<<< HEAD:database/migrations/2025_07_13_060408_create_kartu_keluargas_table.php
            $table->string('no_kk', 20)->primary();

            // Kepala keluarga (relasi ke data_wargas)
            $table->string('nik', 16)->nullable();
            $table->foreign('nik')->references('nik')->on('data_wargas')->cascadeOnDelete();

            $table->unsignedInteger('jumlah_anggota')->default(1);
            $table->string('foto_ktp_kepala_keluarga')->nullable();

=======
            $table->string('no_kk')->primary();
            $table->string('nama_kepala_keluarga');
            $table->unsignedInteger('jumlah_anggota');
            $table->string('foto_ktp_kepala_keluarga')->nullable();
            
            $table->string('nik_kepala');
            
            // $table->string('id_rumah');
            // $table->foreign('id_rumah')->references('id_rumah')->on('rumahs')->cascadeOnDelete();
>>>>>>> 136d5f610fcae3381083deb22f8526b628fb8c20:database/migrations/2025_07_04_060408_create_kartu_keluargas_table.php
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
