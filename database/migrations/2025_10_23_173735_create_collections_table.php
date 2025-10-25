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
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('sale_id')->constrained('sales')->onDelete('cascade');
            $table->foreignId('sale_document_type_id')->constrained('document_types')->onDelete('cascade');
            $table->string('sale_serie', 6);
            $table->string('sale_correlative', 10);
            $table->foreignId('payment_method_id')->constrained('payment_methods')->onDelete('cascade');
            $table->date('payment_date');
            $table->foreignId('currency_type_id')->constrained('currency_types')->onDelete('cascade');
            $table->decimal('parallel_rate', 15, 6);
            $table->decimal('amount', 15, 2);
            $table->decimal('change', 15, 2)->nullable();
            $table->foreignId('digital_wallet_id')->nullable()->constrained('digital_wallets')->onDelete('cascade');
            $table->foreignId('bank_id')->nullable()->constrained('banks')->onDelete('cascade');
            $table->date('operation_date')->nullable();
            $table->string('operation_number', 20)->nullable();
            $table->string('lote_number', 30)->nullable();
            $table->string('for_digits', 4)->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collections');
    }
};
