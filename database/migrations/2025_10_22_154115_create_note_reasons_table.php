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
        Schema::create('note_reasons', function (Blueprint $table) {
            $table->id();
            $table->string('cod_sunat', 2);
            $table->string('description', 100);
            $table->foreignId('document_type_id')->constrained('document_types')->onDelete('cascade');
            $table->integer('stock');
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('note_reasons');
    }
};
