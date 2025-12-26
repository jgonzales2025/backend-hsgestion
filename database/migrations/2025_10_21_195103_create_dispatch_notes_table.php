<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dispatch_notes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            // Relaciones clave foránea
            $table->foreignId('cia_id')->nullable()->constrained('companies');
            $table->foreignId('branch_id')->nullable()->constrained('branches');
            $table->foreignId('document_type_id')->nullable()->constrained('document_types');
            $table->string('serie')->nullable();
            $table->string('correlativo');

            $table->foreignId('emission_reason_id')->nullable()->constrained('emission_reasons');
            $table->text('description')->nullable();

            $table->foreignId('destination_branch_id')->nullable()->constrained('branches');

            $table->foreignId('transport_id')->nullable()->constrained('transport_companies');
            // $table->foreignId('document_types_id')->constrained('document_types');
            $table->text('observations')->nullable();

            $table->string('num_orden_compra', 20)->nullable();

            // Documento de referencia
            $table->string('doc_referencia')->nullable();
            $table->string('num_referencia')->nullable();
            $table->date('date_referencia')->nullable();

            $table->boolean('status')->default(true);

           $table->foreignId('cod_conductor')->nullable()->constrained('drivers');
          $table->string('license_plate')->nullable();
            $table->decimal('total_weight', 10, 2)->nullable();

            $table->string('transfer_type')->nullable();
            $table->integer('vehicle_type')->nullable()->boolean();
            $table->foreignId('destination_branch_client')->nullable()->constrained('customer_addresses')->onDelete('set null')->onUpdate('cascade');;
            $table->foreignId('customer_id')->nullable()->constrained('customers');
            $table->foreignId('supplier_id')->nullable()->constrained('customers');
            $table->integer('address_supplier_id')->nullable(); 
            $table->date('transfer_date')->nullable();
            $table->date('arrival_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispatch_notes');
    }
};
