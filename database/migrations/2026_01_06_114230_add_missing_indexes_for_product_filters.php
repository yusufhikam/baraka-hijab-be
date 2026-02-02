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
        Schema::table('products', function (Blueprint $table){
            $table->index('price');
            $table->index('created_at');
        });

        Schema::table('sub_categories', function (Blueprint $table){
            $table->index('slug');
        });

        Schema::table('product_variant_options', function (Blueprint $table){
            $table->index('is_ready');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table){
            $table->dropIndex(['price']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('sub_categories', function (Blueprint $table){
            $table->dropIndex(['slug']);
        });

        Schema::table('product_variant_options', function (Blueprint $table){
            $table->dropIndex(['is_ready']);
        });
    }
};