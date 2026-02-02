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
            $table->bigInteger('province_id')->change();
            $table->bigInteger('city_id')->change();
            $table->bigInteger('district_id')->change();
            $table->bigInteger('subdistrict_id')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->string('province_id')->change();
            $table->string('city_id')->change();
            $table->string('district_id')->change();
            $table->string('subdistrict_id')->change();
        });
    }
};