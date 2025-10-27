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
        Schema::create('monthly_closures', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->integer('month');
            $table->boolean('st_purchases')->default(true);
            $table->boolean('st_cash')->default(true);
            $table->boolean('st_sales')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_closures');
    }
};
