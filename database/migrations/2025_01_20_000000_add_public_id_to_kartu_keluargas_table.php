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
        Schema::table('kartu_keluargas', function (Blueprint $table) {
            $table->string('public_id')->nullable()->after('foto_ktp_kepala_keluarga');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kartu_keluargas', function (Blueprint $table) {
            $table->dropColumn('public_id');
        });
    }
};