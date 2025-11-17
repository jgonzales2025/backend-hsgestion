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
        Schema::create('dispatch_article_serials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dispatch_note_id')->constrained('dispatch_notes')->onDelete('cascade');
            $table->foreignId('article_id')->constrained('articles')->onDelete('cascade');
            $table->string('serial');
            $table->integer('status')->default(1);
            $table->foreignId('emission_reasons_id')->nullable()->constrained('emission_reasons')->onDelete('cascade');
            $table->foreignId('origin_branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            $table->foreignId('destination_branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispatch_article_serials');
    }
};
