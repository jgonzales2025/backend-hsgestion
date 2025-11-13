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
        Schema::create('petty_cash_receipt', function (Blueprint $table) {
             $table->id('id');

            $table->foreignId('company_id')
                ->constrained('companies')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->integer('document_type');
            $table->string('series');
            $table->string('correlative');
            $table->date('date');

            $table->string('delivered_to', 191)->nullable();
            $table->unsignedBigInteger('reason_code');

            $table->integer('currency_type')->nullable();
            $table->decimal('amount', 12, 2)->default(0.00);
            $table->text('observation')->nullable();

            $table->integer('status')->default(1);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->dateTime('created_at_manual')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->dateTime('updated_at_manual')->nullable();
            $table->foreignId('branch_id')->constrained('branches');

            $table->timestamps();
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('petty_cash_receipt');
    }
};
