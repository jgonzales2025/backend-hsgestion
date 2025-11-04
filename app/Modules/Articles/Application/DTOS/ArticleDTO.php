<?php
namespace App\Modules\Articles\Application\DTOs;

use Illuminate\Http\UploadedFile;

class ArticleDTO
{ 
    public string $cod_fab;
    public string $description;
    public float $weight;
    public bool $with_deduction;
    public bool $series_enabled;
    public int $measurement_unit_id;
    public int $brand_id;
    public int $category_id;
    public ?string $location;
    public ?string $warranty;
    public float $tariff_rate;
    public bool $igv_applicable;
    public bool $plastic_bag_applicable;
    public int $min_stock;
    public int $currency_type_id; 
    public float $purchase_price;
    public float $public_price;
    public float $distributor_price;
    public float $authorized_price;
    public float $public_price_percent;
    public float $distributor_price_percent;
    public float $authorized_price_percent;
    public bool $status;
    public int $user_id;
    public bool $venta;
    public int $sub_category_id;
    public int $company_type_id;
    public string $image_url;
    public int $state_modify_article;

    public string  $filtNameEsp;
    public bool   $statusEsp;

    

    public function __construct(array $data)
    {
        $this->cod_fab = $data['cod_fab'] ?? '';
        $this->description = $data['description'] ?? '';
        $this->weight = isset($data['weight']) ? (float)$data['weight'] : 0;
        $this->with_deduction = isset($data['with_deduction']) ? filter_var($data['with_deduction'], FILTER_VALIDATE_BOOLEAN) : false;
        $this->series_enabled = isset($data['series_enabled']) ? filter_var($data['series_enabled'], FILTER_VALIDATE_BOOLEAN) : false;
        $this->measurement_unit_id = (int)($data['measurement_unit_id'] ?? 0);
        $this->brand_id = (int)($data['brand_id'] ?? null);
        $this->category_id = (int)($data['category_id'] ?? null);
        $this->location = $data['location'] ?? '';
        $this->warranty = $data['warranty'] ?? '';
        $this->tariff_rate = isset($data['tariff_rate']) ? (float)$data['tariff_rate'] : 0;
        $this->igv_applicable = isset($data['igv_applicable']) ? filter_var($data['igv_applicable'], FILTER_VALIDATE_BOOLEAN) : false;
        $this->plastic_bag_applicable = isset($data['plastic_bag_applicable']) ? filter_var($data['plastic_bag_applicable'], FILTER_VALIDATE_BOOLEAN) : false;
        $this->min_stock = (int)($data['min_stock'] ?? 0);
        $this->currency_type_id = $data['currency_type_id'] ?? null;
        $this->purchase_price = isset($data['purchase_price']) ? (float)$data['purchase_price'] : 0;
        $this->public_price = isset($data['public_price']) ? (float)$data['public_price'] : 0;
        $this->distributor_price = isset($data['distributor_price']) ? (float)$data['distributor_price'] : 0;
        $this->authorized_price = isset($data['authorized_price']) ? (float)$data['authorized_price'] : 0;
        $this->public_price_percent = isset($data['public_price_percent']) ? (float)$data['public_price_percent'] : 0;
        $this->distributor_price_percent = isset($data['distributor_price_percent']) ? (float)$data['distributor_price_percent'] : 0;
        $this->authorized_price_percent = isset($data['authorized_price_percent']) ? (float)$data['authorized_price_percent'] : 0;
        $this->status = isset($data['status']) ? filter_var($data['status'], FILTER_VALIDATE_BOOLEAN) : false;
        $this->user_id = (int)($data['user_id'] ?? 1);
        $this->venta = isset($data['venta']) ? filter_var($data['venta'], FILTER_VALIDATE_BOOLEAN) : false;
        $this->sub_category_id = (int)($data['sub_category_id'] ?? null);
        $this->company_type_id = (int)($data['company_type_id'] ?? null);
        $this->image_url = $data['image_url'] ?? '';
         $this->state_modify_article = $data['state_modify_article'] ??0;
        $this->filtNameEsp = $data['filtNameEsp'] ?? '';
        $this->statusEsp = isset($data['statusEsp']) ? filter_var($data['statusEsp'], FILTER_VALIDATE_BOOLEAN) : false;

        
    }
}
