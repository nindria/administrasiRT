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
        Schema::create('verifikasi_datawargas', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 16)->unique(); // sama dengan data_wargas
            $table->string('full_name');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('status', ['Kepala Keluarga', 'Istri', 'Anak']);
            $table->enum('verifikasi_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('catatan')->nullable(); // alasan reject, dll
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verifikasi_datawargas');
    }
};
