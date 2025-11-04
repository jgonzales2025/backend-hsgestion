<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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

            $table->string('location')->nullable();
            $table->string('warranty')->nullable();
            $table->decimal('tariff_rate', 8, 2)->default(0);
            $table->boolean('igv_applicable')->default(true);
            $table->boolean('plastic_bag_applicable')->default(false);

            $table->foreignId('sub_category_id')->nullable()->constrained('sub_categories');
            $table->integer('min_stock')->default(0);
            $table->string('image_url')->nullable();

            $table->decimal('purchase_price', 10, 2)->default(0);
            $table->decimal('public_price', 10, 2)->default(0);
            $table->decimal('distributor_price', 10, 2)->default(0);
            $table->decimal('authorized_price', 10, 2)->default(0);

            $table->decimal('public_price_percent', 8, 2)->default(0);
            $table->decimal('distributor_price_percent', 8, 2)->default(0);
            $table->decimal('authorized_price_percent', 8, 2)->default(0);

            $table->boolean('venta')->default(true);
            $table->tinyInteger('status')->default(1);

            // Relaciones
            $table->foreignId('measurement_unit_id')->nullable()->constrained('measurement_units');
            $table->foreignId('brand_id')->nullable()->constrained('brands');
            $table->foreignId('category_id')->nullable()->constrained('categories');
            $table->foreignId('currency_type_id')->nullable()->constrained('currency_types');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('company_type_id')->nullable()->constrained('company_types');

            $table->timestamp('date_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('state_modify_article')->default(0);
            $table->string('filtNameEsp')->nullable();
            $table->boolean('statusEsp')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
