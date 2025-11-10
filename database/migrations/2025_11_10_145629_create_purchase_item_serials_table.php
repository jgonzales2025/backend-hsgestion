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
        Schema::create('purchase_item_serials', function (Blueprint $table) {
            $table->id();
               $table->foreignId('purchase_guide_id')->nullable()->constrained('purchase_guide_article');
            $table->foreignId('article_id')->nullable()->constrained('articles');     
            $table->string('serial')->nullable();   
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_item_serials');
    }
};
