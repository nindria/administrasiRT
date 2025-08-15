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
        Schema::create('lokasis', function (Blueprint $table) {
            $table->string('id_rumah', 10)->primary(); // GBJ21A101 format
            $table->string('perumahan', 5); // GBJ2
            $table->string('jalan', 20); // 1
            $table->string('blok', 5); // A1
            $table->string('nomor', 5); // 01
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lokasis');
    }
};
