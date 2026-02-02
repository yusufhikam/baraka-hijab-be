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
            $table->renameColumn('provinsi', 'province_id');
            $table->renameColumn('provinsi_name', 'province_name');

            $table->renameColumn('kabupaten', 'city_id');
            $table->renameColumn('kabupaten_name', 'city_name');

            $table->renameColumn('kecamatan', 'district_id');
            $table->renameColumn('kecamatan_name', 'district_name');

            $table->renameColumn('kelurahan', 'subdistrict_id');
            $table->renameColumn('kelurahan_name', 'subdistrict_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->renameColumn('province_id', 'provinsi');
            $table->renameColumn('province_name', 'provinsi_name');
            $table->renameColumn('city_id', 'kabupaten');
            $table->renameColumn('city_name', 'kabupaten_name');
            $table->renameColumn('district_id', 'kecamatan');
            $table->renameColumn('district_name', 'kecamatan_name');
            $table->renameColumn('subdistrict_id', 'kelurahan');
            $table->renameColumn('subdistrict_name', 'kelurahan_name');
        });
    }
};