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
        Schema::create('shipping_origins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address');
            $table->bigInteger('province_id');
            $table->string('province_name');
            $table->bigInteger('city_id');
            $table->string('city_name');
            $table->bigInteger('district_id');
            $table->string('district_name');
            $table->bigInteger('subdistrict_id');
            $table->string('subdistrict_name');
            $table->string('postal_code');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_origins');
    }
};