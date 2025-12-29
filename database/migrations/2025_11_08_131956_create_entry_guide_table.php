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
        Schema::create('entry_guides', function (Blueprint $table) {
            $table->id();

            // Datos principales
            $table->string('serie', 20);
            $table->string('correlative', 20);
            $table->date('date');
            $table->text('observations')->nullable();

            // Datos de referencia (orden de compra)
            $table->string('reference_serie', 20)->nullable();
            $table->string('reference_correlative', 20)->nullable();
            $table->decimal('subtotal', 10, 2)->default(0.00);
            $table->decimal('total_descuento', 10, 2)->default(0.00);
            $table->decimal('total', 10, 2)->default(0.00);
            // Estado
            $table->boolean('status')->default(true);

            $table->timestamps();

            // Llaves foráneas (ajusta los nombres de las tablas según tu estructura)
            $table->foreignId('cia_id')->constrained('companies');
            $table->foreignId('branch_id')->constrained('branches');
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('ingress_reason_id')->constrained('ingress_reasons');
            $table->boolean('update_price')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entry_guides');
    }
};
