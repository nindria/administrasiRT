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
        Schema::create('data_wargas', function (Blueprint $table) {
            $table->string('nik', 20)->primary();
            $table->string('no_kk', 20);
            $table->string('nama');
            $table->date('tanggal_lahir');
            $table->string('tempat_lahir');
            $table->enum('status', ['Kepala Keluarga', 'Istri', 'Anak']);
            $table->foreign('no_kk')->references('no_kk')->on('kartu_keluargas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_wargas');
        Schema::table('data_wargas', function (Blueprint $table) {
            $table->dropColumn(['verification_status', 'verified_by', 'verified_at', 'rejection_reason']);
        });
    }
};
