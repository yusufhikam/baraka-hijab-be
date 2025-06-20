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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('address_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('pending');
            $table->decimal('total_price',12,2);
            $table->string('snap_token')->nullable();
            $table->string('snap_url')->nullable();
            // e.g. bank_transfer, gopay, dll
            $table->string('payment_type')->nullable();
            // e.g. settlement, pending
            $table->string('payment_status')->nullable();
            // e.g. nomor VA atau kode bayar
            $table->string('payment_code')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
