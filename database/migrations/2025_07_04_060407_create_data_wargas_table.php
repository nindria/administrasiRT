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
            $table->string('nik', 16)->primary();
            $table->string('full_name');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('status', ['Kepala Keluarga', 'Istri', 'Anak']);
            $table->boolean('is_warga')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_wargas');
    }
};
