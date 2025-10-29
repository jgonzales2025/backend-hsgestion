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
        Schema::create('transport_companies', function (Blueprint $table) {
            $table->id();
            $table->string('ruc', 11);
            $table->string('company_name', 100);
            $table->string('address', 255);
            $table->string('nro_reg_mtc', 10);
            $table->integer('status')->default(1);
            $table->boolean('st_private')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transport_companies');
    }
};
