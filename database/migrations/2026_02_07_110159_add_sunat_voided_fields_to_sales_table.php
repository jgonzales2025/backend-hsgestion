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
        Schema::table('sales', function (Blueprint $table) {
            $table->string('sunat_status')->nullable()->after('status')->comment('Estado del proceso SUNAT: PROCESANDO_ANULACION, ANULADO, ERROR_ANULACION, etc.');
            $table->string('sunat_ticket')->nullable()->after('sunat_status')->comment('Ticket de SUNAT para seguimiento');
            $table->text('sunat_response')->nullable()->after('sunat_ticket')->comment('Respuesta completa de SUNAT en JSON');
            $table->timestamp('sunat_voided_at')->nullable()->after('sunat_response')->comment('Fecha y hora de anulación exitosa en SUNAT');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['sunat_status', 'sunat_ticket', 'sunat_response', 'sunat_voided_at']);
        });
    }
};
