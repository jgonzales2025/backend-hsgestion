<?php
namespace App\Modules\Articles\Application\DTOs;

class ArticleDTO
{ 
    public $cod_fab;
    public $description;
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
    public $purchase_price;
    public $public_price;
    public $distributor_price;
    public $authorized_price;
    public $public_price_percent;
    public $distributor_price_percent;
    public $authorized_price_percent;
    public $status;
    public $user_id;
    public $venta;
    public $sub_category_id;
    public $company_type_id;

    public function __construct(array $data)
    {
        $this->cod_fab = $data['cod_fab'];
        $this->description = $data['description'];
        $this->weight = $data['weight'];
        $this->with_deduction = $data['with_deduction'];
        $this->series_enabled = $data['series_enabled'];
        $this->measurement_unit_id = $data['measurement_unit_id'];
        $this->brand_id = $data['brand_id'];
        $this->category_id = $data['category_id'];
        $this->location = $data['location'];
        $this->warranty = $data['warranty'];
        $this->tariff_rate = $data['tariff_rate'];
        $this->igv_applicable = $data['igv_applicable'];
        $this->plastic_bag_applicable = $data['plastic_bag_applicable'];
        $this->min_stock = $data['min_stock'];
        $this->currency_type_id = $data['currency_type_id'];
        $this->purchase_price = $data['purchase_price'];
        $this->public_price = $data['public_price'];
        $this->distributor_price = $data['distributor_price'];
        $this->authorized_price = $data['authorized_price'];
        $this->public_price_percent = $data['public_price_percent'];
        $this->distributor_price_percent = $data['distributor_price_percent'];
        $this->authorized_price_percent = $data['authorized_price_percent'];
        $this->status = $data['status'];
        $this->user_id = $data['user_id'];
        $this->venta = $data['venta'];  
        $this->sub_category_id = $data['sub_category_id'];
        $this->company_type_id = $data['company_type_id'];
    }
}
