<?php
namespace App\Modules\Articles\Application\DTOs;

class ArticleDTO
{
    public $id;
    public $cod_fab;
    public $description;
    public $short_description;
    public $weight;
    public $with_deduction;
    public $series_enabled;
    public $measurement_unit_id;
    public $brand_id;
    public $category_id;
    public $location;
    public $warranty;
    public $tariff_rate;
    public $igv_applicable;
    public $plastic_bag_applicable;
    public $min_stock;
    public $currency_type_id;
    public $cost_to_price_percent;
    public $purchase_price;
    public $public_price;
    public $distributor_price;
    public $authorized_price;
    public $public_price_percent;
    public $distributor_price_percent;
    public $authorized_price_percent;
    public $status;
    public $user_id;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? null;
        $this->cod_fab = $data['cod_fab'] ?? '';
        $this->description = $data['description'] ?? '';
        $this->short_description = $data['short_description'] ?? '';
        $this->weight = $data['weight'] ?? 0;
        $this->with_deduction = $data['with_deduction'] ?? false;
        $this->series_enabled = $data['series_enabled'] ?? false;
        $this->measurement_unit_id = $data['measurement_unit_id'] ?? null;
        $this->brand_id = $data['brand_id'] ?? null;
        $this->category_id = $data['category_id'] ?? null;
        $this->location = $data['location'] ?? '';
        $this->warranty = $data['warranty'] ?? '';
        $this->tariff_rate = $data['tariff_rate'] ?? 0;
        $this->igv_applicable = $data['igv_applicable'] ?? true;
        $this->plastic_bag_applicable = $data['plastic_bag_applicable'] ?? false;
        $this->min_stock = $data['min_stock'] ?? 0;
        $this->currency_type_id = $data['currency_type_id'] ?? null;
        $this->cost_to_price_percent = $data['cost_to_price_percent'] ?? 0;
        $this->purchase_price = $data['purchase_price'] ?? 0;
        $this->public_price = $data['public_price'] ?? 0;
        $this->distributor_price = $data['distributor_price'] ?? 0;
        $this->authorized_price = $data['authorized_price'] ?? 0;
        $this->public_price_percent = $data['public_price_percent'] ?? 0;
        $this->distributor_price_percent = $data['distributor_price_percent'] ?? 0;
        $this->authorized_price_percent = $data['authorized_price_percent'] ?? 0;
        $this->status = $data['status'] ?? 1;
        $this->user_id = $data['user_id'] ?? null;
    }
}
