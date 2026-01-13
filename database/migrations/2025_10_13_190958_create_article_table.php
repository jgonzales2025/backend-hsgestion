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

            $table->string('cod_fab', 100)->nullable();
            $table->string('description',100)->nullable();
            $table->float('weight')->default(0);
            $table->boolean('with_deduction')->default(false);
            $table->boolean('series_enabled')->default(false);

            $table->foreignId('measurement_unit_id')->nullable()->constrained('measurement_units');
            $table->foreignId('brand_id')->nullable()->constrained('brands');
            $table->foreignId('category_id')->nullable()->constrained('categories');
            $table->foreignId('sub_category_id')->nullable()->constrained('sub_categories');
            $table->foreignId('currency_type_id')->nullable()->constrained('currency_types');
            $table->foreignId('company_type_id')->nullable()->constrained('companies');
            $table->foreignId('user_id')->nullable()->constrained('users');

            $table->string('location')->nullable();
            $table->string('warranty')->nullable();

            $table->decimal('tariff_rate', 8, 2)->default(0);
            $table->boolean('igv_applicable')->default(true);
            $table->boolean('plastic_bag_applicable')->default(false);

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
            $table->integer('state_modify_article')->default(0);
            $table->string('filt_NameEsp')->nullable();
            $table->boolean('status_Esp')->default(false);

            $table->boolean('is_combo')->default(false);
            $table->string('url_supplier')->nullable();
            
            $table->timestamp('date_at')->useCurrent();
            $table->integer('article_type_id')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
