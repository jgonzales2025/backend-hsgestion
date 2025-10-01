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
        Schema::create('customer_document_types', function (Blueprint $table) {
            $table->id();
            $table->integer('cod_sunat');
            $table->string('description', 40);
            $table->string('abbreviation', 10);
            $table->boolean('st_driver')->default(true);
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_document_types');
    }
};
