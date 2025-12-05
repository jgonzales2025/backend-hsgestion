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
        Schema::create('sale_article', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales')->onDelete('cascade');
            $table->foreignId('article_id')->constrained('articles')->onDelete('cascade');
            $table->string('description', 255);
            $table->integer('quantity');
            $table->decimal('unit_price', 8, 2);
            $table->decimal('public_price', 8, 2);
            $table->decimal('subtotal', 8, 2);
            $table->decimal('purchase_price', 8, 2);
            $table->decimal('costo_neto', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_article');
    }
};
