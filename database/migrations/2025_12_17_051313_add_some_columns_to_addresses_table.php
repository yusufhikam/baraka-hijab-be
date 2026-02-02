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
        Schema::table('addresses', function (Blueprint $table) {
            $table->string('recipient_name')->after('postal_code');
            $table->string('phone_number')->after('recipient_name');
            $table->enum('mark_as', ['home', 'office', 'store'])->after('phone_number')->default('home');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropColumn('recipient_name');
            $table->dropColumn('phone_number');
            $table->dropColumn('mark_as');
        });
    }
};