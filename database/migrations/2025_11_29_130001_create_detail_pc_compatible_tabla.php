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
        Schema::create('detail_pc_compatible_tabla', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_major_id')->constrained('articles')->cascadeOnDelete();
            $table->foreignId('article_accesory_id')->constrained('articles')->cascadeOnDelete();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pc_compatible_tabla');
    }
};
