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
    // public $user_id;
    public $precioIGv;
    public $venta;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? null;
        $this->cod_fab = $data['cod_fab'] ?? '';
        $this->description = $data['description'] ?? '';
        $this->short_description = $data['short_description'] ?? '';
        $this->weight = isset($data['weight']) ? (float)$data['weight'] : 0;
        $this->with_deduction = isset($data['with_deduction']) ? (bool)$data['with_deduction'] : false;
        $this->series_enabled = isset($data['series_enabled']) ? (bool)$data['series_enabled'] : false;
        $this->measurement_unit_id = $data['measurement_unit_id'] ?? null;
        $this->brand_id = $data['brand_id'] ?? null;
        $this->category_id = $data['category_id'] ?? null;
        $this->location = $data['location'] ?? '';
        $this->warranty = $data['warranty'] ?? '';
        $this->tariff_rate = isset($data['tariff_rate']) ? (float)$data['tariff_rate'] : 0;
        $this->igv_applicable = isset($data['igv_applicable']) ? (bool)$data['igv_applicable'] : true;
        $this->plastic_bag_applicable = isset($data['plastic_bag_applicable']) ? (bool)$data['plastic_bag_applicable'] : false;
        $this->min_stock = $data['min_stock'] ?? 0;
        $this->currency_type_id = $data['currency_type_id'] ?? null;
        // $this->cost_to_price_percent = isset($data['cost_to_price_percent']) ? (float)$data['cost_to_price_percent'] : 0;
        $this->purchase_price = isset($data['purchase_price']) ? (float)$data['purchase_price'] : 0;
        $this->public_price = isset($data['public_price']) ? (float)$data['public_price'] : 0;
        $this->distributor_price = isset($data['distributor_price']) ? (float)$data['distributor_price'] : 0;
        $this->authorized_price = isset($data['authorized_price']) ? (float)$data['authorized_price'] : 0;
        $this->public_price_percent = isset($data['public_price_percent']) ? (float)$data['public_price_percent'] : 0;
        $this->distributor_price_percent = isset($data['distributor_price_percent']) ? (float)$data['distributor_price_percent'] : 0;
        $this->authorized_price_percent = isset($data['authorized_price_percent']) ? (float)$data['authorized_price_percent'] : 0;
        $this->status = $data['status'] ?? 1;
        // $this->user_id = $data['user_id'] ?? null;

        // ⚡ Normalización de float y bool para evitar errores de tipo
        $this->precioIGv = isset($data['precioIGv']) ? (float)$data['precioIGv'] : 0;
        $this->venta = isset($data['venta']) ? (bool)$data['venta'] : false;
    }
}
