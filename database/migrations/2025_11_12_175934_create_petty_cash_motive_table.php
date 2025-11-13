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
        Schema::create('petty_cash_motive', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            // Campos principales
            $table->string('description');
            $table->integer('receipt_type')->nullable();
            $table->integer('user_id')->nullable();
            $table->date('date')->nullable();

            // Campos de modificación
            $table->integer('user_mod')->nullable();
            $table->dateTime('date_mod')->nullable();

            // Estado
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('petty_cash_motive');
    }
};
