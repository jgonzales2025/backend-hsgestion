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
        Schema::create('entry_item_serials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entry_guide_id')->constrained('entry_guides')->onDelete('cascade');
            $table->foreignId('article_id')->constrained('articles')->onDelete('cascade');
            $table->string('serial');
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->integer('status')->default(1); // 0: vendido, 1: disponible, 2: en transito
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entry_item_serials');
    }
};
