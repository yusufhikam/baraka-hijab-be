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
            $table->string('provinsi_name')->nullable()->after('provinsi');
            $table->string('kabupaten_name')->nullable()->after('kabupaten');
            $table->string('kecamatan_name')->nullable()->after('kecamatan');
            $table->string('kelurahan_name')->nullable()->after('kelurahan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropColumn('provinsi_name');
            $table->dropColumn('kabupaten_name');
            $table->dropColumn('kecamatan_name');
            $table->dropColumn('kelurahan_name');
        });
    }
};