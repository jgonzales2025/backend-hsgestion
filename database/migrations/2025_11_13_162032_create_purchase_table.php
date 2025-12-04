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
        Schema::create('purchase', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies');
            $table->foreignId('branch_id')->constrained('branches');
            $table->foreignId('supplier_id')->nullable();
            $table->string('serie');
            $table->string('correlative');
            $table->float('exchange_type');
            $table->foreignId('methodpayment')->constrained('payment_methods');
            $table->float('currency');
            $table->date('date');
            $table->date('date_ven');
            $table->integer('days');
            $table->string('observation');
            $table->string('detraccion');
            $table->date('fech_detraccion');
            $table->float('amount_detraccion');
            $table->boolean('is_detracion');
            $table->float('subtotal');
            $table->float('total_desc');
            $table->float('inafecto');
            $table->float('igv');
            $table->float('total');
            $table->boolean('is_igv')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase');
    }
};
