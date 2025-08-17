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
        Schema::table('data_wargas', function (Blueprint $table) {
            $table->foreign('no_kk')
                ->references('no_kk')
                ->on('kartu_keluargas')
                ->cascadeOnDelete();

            $table->foreign('id_rumah')
                ->references('id_rumah')
                ->on('rumahs')
                ->cascadeOnDelete();
        });

        Schema::table('kartu_keluargas', function (Blueprint $table) {
            $table->foreign('nik_kepala')
                ->references('nik')
                ->on('data_wargas')
                ->cascadeOnDelete();
        });

        Schema::table('rumahs', function (Blueprint $table) {
            $table->foreign('nik_kepala')
                ->references('nik')
                ->on('data_wargas')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_wargas', function (Blueprint $table) {
            $table->dropForeign(['no_kk']);
            $table->dropForeign(['id_rumah']);
        });

        Schema::table('kartu_keluargas', function (Blueprint $table) {
            $table->dropForeign(['nik_kepala']);
        });

        Schema::table('rumahs', function (Blueprint $table) {
            $table->dropForeign(['nik_kepala']);
        });
    }
};
