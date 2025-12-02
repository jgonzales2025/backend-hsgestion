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
        Schema::create('sc_voucherdet', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cia')->constrained('companies')->cascadeOnDelete();
            $table->integer('codcon')->nullable();

            $table->integer('tipdoc')->default(0);
            $table->string('numdoc', 20)->nullable();

            $table->string('glosa', 40)->nullable();

            $table->decimal('impsol', 10, 2)->default(0.00);
            $table->decimal('impdol', 10, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sc_voucherdet');
    }
};
