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
            $table->foreignId('reference_document_id')->constrained('document_types')->cascadeOnDelete();
            $table->string('reference_serie', 20)->nullable();
            $table->string('reference_correlative', 20)->nullable();


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
