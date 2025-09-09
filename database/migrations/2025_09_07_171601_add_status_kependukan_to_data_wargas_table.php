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
            $table->enum('status_kependukan', ['tetap', 'tidak tetap'])->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_wargas', function (Blueprint $table) {
            $table->dropColumn('status_kependukan');
        });
    }
};
