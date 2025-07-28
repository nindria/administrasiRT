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
        Schema::create('data_ipls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('no_rumah_id')->constrained('no_rumahs');
            $table->decimal('amount', 10, 2);
            $table->date('payment_date');
            $table->enum('payment_method', ['cash', 'transfer'])->default('transfer');
            $table->string('receipt_path')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['ok', 'reject', 'pending'])->default('pending');
            $table->foreignId('recorded_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_ipls');
    }
};
