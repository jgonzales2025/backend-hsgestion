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
        Schema::create('detail_purchase_guides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->nullable()->constrained('articles');
            $table->foreignId('purchase_id')->constrained('purchase');
            $table->string('description');
            $table->integer('cantidad');
            $table->decimal('precio_costo', 8, 2);
            $table->decimal('descuento', 8, 2);
            $table->decimal('sub_total', 8, 2);
            $table->decimal('total', 8, 4);
            $table->float('cantidad_update')->default(0);
            $table->string('process_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_purchase_guides');
    }
};
