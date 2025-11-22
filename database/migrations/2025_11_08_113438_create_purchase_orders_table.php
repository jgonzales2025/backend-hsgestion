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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->string('serie',5);
            $table->string('correlative',8)->unique();
            $table->date('date');
            $table->date('delivery_date')->nullable();
            $table->string('contact')->nullable();
            $table->foreignId('supplier_id')->constrained('customers')->onDelete('cascade');
            $table->string('order_number_supplier')->nullable();
            $table->string('observations')->nullable();
            $table->integer('status')->default(0);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('igv', 10, 2);
            $table->decimal('total', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
