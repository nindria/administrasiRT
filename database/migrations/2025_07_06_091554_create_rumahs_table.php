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
            $table->string('id_rumah', 15)->primary(); // misal: GBJ21A101
            $table->string('perumahan', 50);
            $table->string('jalan', 50);
            $table->string('blok', 10);
            $table->string('nomor', 10);

            // relasi ke datawarga
            $table->string('nik', 16)->unique();
            $table->foreign('nik')->references('nik')->on('data_wargas')->onDelete('cascade');

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
