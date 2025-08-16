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
            $table->string('nik')->primary();
            $table->string('full_name');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('status', ['Kepala Keluarga', 'Istri', 'Anak']);
            $table->string('no_kk');
            $table->string('id_rumah');
            $table->foreign('no_kk')->references('no_kk')->on('kartu_keluargas')->cascadeOnDelete();
            $table->foreign('id_rumah')->references('id_rumah')->on('rumahs')->cascadeOnDelete();
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
