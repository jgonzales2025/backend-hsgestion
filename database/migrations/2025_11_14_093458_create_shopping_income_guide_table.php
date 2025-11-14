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
        Schema::create('shopping_income_guide', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained('purchase');
            $table->foreignId('entry_guide_id')->constrained('entry_guides');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shopping_income_guide');
    }
};
