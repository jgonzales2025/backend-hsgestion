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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_document_type_id')->constrained('customer_document_types')->onDelete('cascade');
            $table->string('doc_number', 12)->unique();
            $table->string('name', 20);
            $table->string('pat_surname', 20);
            $table->string('mat_surname', 20);
            $table->string('license', 13)->unique();
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
