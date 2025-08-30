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
        Schema::table('banners', function (Blueprint $table) {
            $table->string('public_id')->nullable()->after('image');
        });
        
        Schema::table('events', function (Blueprint $table) {
            $table->string('public_id')->nullable()->after('image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn('public_id');
        });
        
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('public_id');
        });
    }
};
