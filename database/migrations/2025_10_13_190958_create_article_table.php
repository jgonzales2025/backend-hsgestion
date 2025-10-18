<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();

            $table->string('cod_fab', 100);
            $table->string('description');
            $table->float('weight')->default(0);
            $table->boolean('with_deduction')->default(false);
            $table->boolean('series_enabled')->default(false);

            $table->unsignedBigInteger('measurement_unit_id');
            $table->unsignedBigInteger('brand_id');
            $table->unsignedBigInteger('category_id');

            $table->string('location')->nullable();
            $table->string('warranty')->nullable();
            $table->decimal('tariff_rate', 8, 2)->default(0);
            $table->boolean('igv_applicable')->default(true);
            $table->boolean('plastic_bag_applicable')->default(false);
            $table->unsignedBigInteger('sub_category_id');

            $table->integer('min_stock')->default(0);
            $table->unsignedBigInteger('currency_type_id');

          $table->decimal('purchase_price', 10, 2)->default(0);
            $table->decimal('public_price', 10, 2)->default(0);
            $table->decimal('distributor_price', 10, 2)->default(0);
            $table->decimal('authorized_price', 10, 2)->default(0);

            $table->decimal('public_price_percent', 8, 2)->default(0);
            $table->decimal('distributor_price_percent', 8, 2)->default(0);
            $table->decimal('authorized_price_percent', 8, 2)->default(0);
            $table->boolean('venta' )->default(true);

            $table->tinyInteger('status')->default(1);
            $table->unsignedBigInteger('user_id');

            $table->timestamps();

            //  Si existen las tablas relacionadas, puedes agregar las FK:
            $table->foreign('measurement_unit_id')->references('id')->on('measurement_units');
            $table->foreign('brand_id')->references('id')->on('brands');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('currency_type_id')->references('id')->on('currency_types');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamp('date_at')->default(DB::raw('CURRENT_TIMESTAMP'))->change();
   
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
        
    }
};
