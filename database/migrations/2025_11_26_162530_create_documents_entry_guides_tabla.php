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
        Schema::create('documents_entry_guides_tabla', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entry_guide_id')->constrained('entry_guides')->cascadeOnDelete();
            $table->string('guide_serie_supplier');
            $table->string('guide_correlative_supplier');
            $table->string('invoice_serie_supplier');
            $table->string('invoice_correlative_supplier');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents_entry_guides_tabla');
    }
};
