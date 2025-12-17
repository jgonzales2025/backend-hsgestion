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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('ruc', 11);
            $table->string('company_name', 80);
            $table->string('address', 150);
            $table->date('start_date');
            $table->string('ubigeo', 6);
            $table->foreignId('default_currency_type_id')->constrained('currency_types')->cascadeOnDelete();
            $table->decimal('min_profit', 4, 2);
            $table->decimal('max_profit', 4, 2);
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
