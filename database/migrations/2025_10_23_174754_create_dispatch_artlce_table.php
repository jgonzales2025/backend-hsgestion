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
        Schema::create('dispatch_article', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('dispatch_id')->constrained('dispatch_notes');
            $table->foreignId('article_id')->constrained('articles');
            $table->decimal('quantity');
            $table->decimal('weight');
            $table->decimal('saldo');
            $table->string('name');
            $table->decimal('subtotal_weight');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispatch_article');
    }
};
