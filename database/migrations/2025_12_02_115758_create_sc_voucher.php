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
        Schema::create('sc_voucher', function (Blueprint $table) {
            $table->id();
            $table->integer('cia')->nullable();             // INT(2)
            $table->integer('anopr')->default(0);      // SMALLINT(4)
            $table->integer('correlativo')->default(0);     // INT(5)
            $table->date('fecha')->nullable();              // DATE

            $table->integer('codban')->default(0);     // SMALLINT(3)
            $table->integer('codigo')->default(0);          // INT(9)

            $table->string('nroope', 15)->default('0');     // VARCHAR(15)
            $table->string('glosa', 120)->nullable();        // VARCHAR(120)
            $table->string('orden', 60)->nullable();         // VARCHAR(60)

            $table->integer('tipmon')->default(0);       // INT(1)
            $table->decimal('tipcam', 6, 4)->default(0);     // DECIMAL(6,4)
            $table->decimal('total', 10, 2)->default(0);     // DECIMAL(10,2)

            $table->integer('medpag')->default(0);      // INT(3)
            $table->integer('tipopago')->default(0);     // INT(1)

            $table->integer('status')->default(0);      // SMALLINT(1)

            $table->integer('usradi')->default(0);      // SMALLINT(3)
            $table->dateTime('fecadi')->nullable();          // DATETIME

            $table->integer('usrmod')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sc_voucher');
    }
};
