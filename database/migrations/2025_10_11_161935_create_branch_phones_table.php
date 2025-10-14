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
        Schema::create('branch_phones', function (Blueprint $table) {
            $table->id();

               $table->foreignId('branch_id')
                  ->constrained('branches') // referencia a tabla branches
                  ->onDelete('cascade');   // elimina los teléfonos si se borra la sucursal

            // Campo teléfono
            $table->string('phone', 12);

            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch_phones');
    }
};
