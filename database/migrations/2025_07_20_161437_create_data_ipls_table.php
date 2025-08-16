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
            $table->string('no_rumah_id');
            $table->foreign('no_rumah_id')->references('id_rumah')->on('rumahs')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->date('payment_date');
            $table->enum('payment_method', ['cash', 'transfer'])->default('transfer');
            $table->string('receipt_path')->nullable();
            $table->text('notes')->nullable();

            // Verification fields (moved from separate migration)
            $table->string('status')->default('pending');
            $table->foreignId('recorded_by')->constrained('users');
            $table->string('verification_status')->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamp('verified_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
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
