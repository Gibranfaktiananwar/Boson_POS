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
        // add an image column to the products table
        Schema::table('products', function (Blueprint $table) {
            $table->string('image')->nullable()->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */

     // down() is used to rollback the changes made by the up() function.
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
};
