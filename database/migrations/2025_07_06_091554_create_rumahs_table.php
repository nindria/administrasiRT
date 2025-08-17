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
        Schema::create('rumahs', function (Blueprint $table) {
            $table->string('id_rumah', 15)->primary(); // GBJ21A101 format
            $table->string('perumahan', 5);
            $table->string('jalan', 20);
            $table->string('blok', 5);
            $table->string('nomor', 5);
            $table->string('nik_kepala');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rumahs');
    }
};
