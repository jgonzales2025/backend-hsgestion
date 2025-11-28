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
        Schema::create('document_types', function (Blueprint $table) {
            $table->id();
            $table->integer('cod_sunat');
            $table->string('description', 40);
            $table->string('abbreviation', 10);
            $table->boolean('st_sales')->default(true);
            $table->boolean('st_purchases')->default(true);
            $table->boolean('st_collections')->default(true);
            $table->boolean('st_invoices')->default(false);
            $table->boolean('st_transfers')->default(false);
            $table->boolean('st_petty_cash')->default(false);
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_types');
    }
};
