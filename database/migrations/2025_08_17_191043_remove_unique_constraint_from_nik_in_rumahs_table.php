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
        Schema::table('rumahs', function (Blueprint $table) {
            // Hapus foreign key constraint terlebih dahulu
            $table->dropForeign(['nik']);
            
            // Hapus unique constraint
            $table->dropUnique(['nik']);
            
            // Buat ulang foreign key constraint tanpa unique constraint
            $table->foreign('nik')->references('nik')->on('data_wargas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rumahs', function (Blueprint $table) {
            // Hapus foreign key constraint
            $table->dropForeign(['nik']);
            
            // Kembalikan unique constraint
            $table->unique('nik');
            
            // Buat ulang foreign key constraint dengan unique constraint
            $table->foreign('nik')->references('nik')->on('data_wargas')->onDelete('cascade');
        });
    }
};
