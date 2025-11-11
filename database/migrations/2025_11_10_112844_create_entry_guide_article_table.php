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
        Schema::create('entry_guide_article', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entry_guide_id')->nullable();
            $table->foreignId('article_id')->constrained('articles');
            $table->string('description');
            $table->float('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entry_guide_article');
    }
};
