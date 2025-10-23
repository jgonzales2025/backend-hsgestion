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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('document_type_id')->constrained('document_types')->onDelete('cascade');
            $table->string('serie', 10);
            $table->string('document_number', 10);
            $table->decimal('parallel_rate', 8, 2);
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->date('date');
            $table->date('due_date');
            $table->integer('days');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('user_sale_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('payment_type_id')->constrained('payment_types')->onDelete('cascade');
            $table->text('observations');
            $table->foreignId('currency_type_id')->constrained('currency_types')->onDelete('cascade');
            $table->decimal('subtotal', 8, 2);
            $table->decimal('inafecto', 8, 2);
            $table->decimal('igv', 8,2);
            $table->decimal('total', 8, 2);
            $table->decimal('saldo', 8, 2)->nullable();
            $table->integer('status')->default(1);
            $table->integer('payment_status')->default(0);
            $table->boolean('is_locked')->default(0);
            $table->string('serie_prof')->nullable();
            $table->string('correlative_prof')->nullable();
            $table->string('purchase_order')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
