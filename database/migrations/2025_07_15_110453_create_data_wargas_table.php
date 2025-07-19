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
            $table->id();
            $table->string('full_name');
            $table->string('family_card_number');
            $table->foreignId('no_rumah_id')->constrained('no_rumahs');
            $table->string('husband_birth_place')->nullable();
            $table->date('husband_birth_date')->nullable();
            $table->enum('married_status', ['menikah', 'belum_menikah'])->default('belum_menikah');
            $table->string('wife_name')->nullable();
            $table->string('wife_birth_place')->nullable();
            $table->date('wife_birth_date')->nullable();
            $table->integer('children_count')->default(0);
            $table->json('children_data')->nullable();
            $table->json('other_family_members')->nullable();
            $table->string('status');
            $table->string('residence_status');
            $table->string('document_path')->nullable();
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
