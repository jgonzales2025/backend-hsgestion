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
        Schema::create('reference_codes', function (Blueprint $table) {
            $table->id();
            $table->string('ref_code', 20);
            $table->unsignedBigInteger('article_id');
            $table->string('date_at');
            $table->tinyInteger('status')->default(1);
            $table->timestamps();

            // Clave foránea con articles
            $table->foreign('article_id')->references('id')->on('articles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reference_codes');
    }
};
