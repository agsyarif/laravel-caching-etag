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
        Schema::table('products', function (Blueprint $table) {
            $table->string('SKU')->nullable()->after('price');
            $table->unsignedBigInteger('product_category_id')->after('SKU');
            $table->boolean('is_publish')->default(false);
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('product_category_id');
            $table->dropColumn('is_publish');
            $table->dropColumn('SKU');
        });
    }
};
