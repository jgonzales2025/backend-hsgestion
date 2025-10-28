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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('record_type_id')->constrained('record_types')->onDelete('cascade');
            $table->foreignId('customer_document_type_id')->constrained('customer_document_types')->onDelete('cascade');
            $table->string('document_number', 11)->unique();
            $table->string('company_name', 100)->nullable();
            $table->string('name', 50)->nullable();
            $table->string('lastname', 50)->nullable();
            $table->string('second_lastname', 50)->nullable();
            $table->foreignId('customer_type_id')->constrained('customer_types')->onDelete('cascade');
            $table->string('fax', 20)->nullable();
            $table->string('contact', 100)->nullable();
            $table->boolean('is_withholding_applicable')->default(false);
            $table->integer('status')->default(1);
            $table->integer('st_assigned')->default(0);
            $table->boolean('st_sales')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
