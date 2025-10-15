<?php
namespace App\Modules\Articles\Domain\Entities;

class Article 
{
    private int $id;
    private string $cod_fab;
    private string $description;
    private string $short_description;
    private float $weight;
    private bool $with_deduction;
    private bool $series_enabled;
    private int $measurement_unit_id;
    private int $brand_id;
    private int $category_id;
    private string $location;
    private string $warranty;
    private float $tariff_rate;
    private bool $igv_applicable;
    private bool $plastic_bag_applicable;
    private int $min_stock;
    private int $currency_type_id;
    private float $cost_to_price_percent;
    private float $purchase_price;
    private float $public_price;
    private float $distributor_price;
    private float $authorized_price;
    private float $public_price_percent;
    private float $distributor_price_percent;
    private float $authorized_price_percent;
    private int $status;
    private ?int $user_id;
    private bool $venta;

    // Relaciones opcionales
    public ?array $brand;
    public ?array $category;
    public ?array $currencyType;
    public ?array $measurementUnit;
    public ?array $subCategory;
    public float $precioIGv;

    public function __construct(
        int $id,
        string $cod_fab,
        string $description,
        string $short_description,
        float $weight,
        bool $with_deduction,
        bool $series_enabled,
        int $measurement_unit_id,
        int $brand_id,
        int $category_id,
        string $location,
        string $warranty,
        float $tariff_rate,
        bool $igv_applicable,
        bool $plastic_bag_applicable,
        int $min_stock,
        int $currency_type_id,
        ?float $cost_to_price_percent = 0,
        float $purchase_price,
        float $public_price,
        float $distributor_price,
        float $authorized_price,
        float $public_price_percent,
        float $distributor_price_percent,
        float $authorized_price_percent,
        int $status,
        ?int $user_id = 0,
        ?array $brand = null,
        ?array $category = null,
        ?array $currencyType = null,
        ?array $measurementUnit = null,
        ?float $precioIGv = null,
        bool $venta = false,
        ?array $subCategory = null
    ) {
        $this->id = $id;
        $this->cod_fab = $cod_fab;
        $this->description = $description;
        $this->short_description = $short_description;
        $this->weight = $weight;
        $this->with_deduction = $with_deduction;
        $this->series_enabled = $series_enabled;
        $this->measurement_unit_id = $measurement_unit_id;
        $this->brand_id = $brand_id;
        $this->category_id = $category_id;
        $this->location = $location;
        $this->warranty = $warranty;
        $this->tariff_rate = $tariff_rate;
        $this->igv_applicable = $igv_applicable;
        $this->plastic_bag_applicable = $plastic_bag_applicable;
        $this->min_stock = $min_stock;
        $this->currency_type_id = $currency_type_id;
        $this->cost_to_price_percent = $cost_to_price_percent;
        $this->purchase_price = $purchase_price;
        $this->public_price = $public_price;
        $this->distributor_price = $distributor_price;
        $this->authorized_price = $authorized_price;
        $this->public_price_percent = $public_price_percent;
        $this->distributor_price_percent = $distributor_price_percent;
        $this->authorized_price_percent = $authorized_price_percent;
        $this->status = $status;
        $this->user_id = $user_id;

        $this->brand = $brand;
        $this->category = $category;
        $this->currencyType = $currencyType;
        $this->measurementUnit = $measurementUnit;
        $this->subCategory = $subCategory;

        // Calcula precioIGv si no se pasa
        $this->precioIGv = $precioIGv ?? $this->calculatePrecioIGV();

        $this->venta = $venta;
    }

    private function calculatePrecioIGV(): float
    {
        return $this->purchase_price + ($this->purchase_price * $this->tariff_rate / 100);
    }
    public function getSubCategoria(): ?array
{
    return $this->subCategory;
}
    // Getters
    public function getId(): int { return $this->id; }
    public function getCodFab(): string { return $this->cod_fab; }
    public function getDescription(): string { return $this->description; }
    public function getShortDescription(): string { return $this->short_description; }
    public function getWeight(): float { return $this->weight; }
    public function getWithDeduction(): bool { return $this->with_deduction; }
    public function getSeriesEnabled(): bool { return $this->series_enabled; }
    public function getMeasurementUnitId(): int { return $this->measurement_unit_id; }
    public function getBrandId(): int { return $this->brand_id; }
    public function getCategoryId(): int { return $this->category_id; }
    public function getLocation(): string { return $this->location; }
    public function getWarranty(): string { return $this->warranty; }
    public function getTariffRate(): float { return $this->tariff_rate; }
    public function getIgvApplicable(): bool { return $this->igv_applicable; }
    public function getPlasticBagApplicable(): bool { return $this->plastic_bag_applicable; }
    public function getMinStock(): int { return $this->min_stock; }
    public function getCurrencyTypeId(): int { return $this->currency_type_id; }
    public function getCostToPricePercent(): ?float { return $this->cost_to_price_percent; }
    public function getPurchasePrice(): float { return $this->purchase_price; }
    public function getPublicPrice(): float { return $this->public_price; }
    public function getDistributorPrice(): float { return $this->distributor_price; }
    public function getAuthorizedPrice(): float { return $this->authorized_price; }
    public function getPublicPricePercent(): float { return $this->public_price_percent; }
    public function getDistributorPricePercent(): float { return $this->distributor_price_percent; }
    public function getAuthorizedPricePercent(): float { return $this->authorized_price_percent; }
    public function getStatus(): int { return $this->status; }
    public function getUserId(): ?int { return $this->user_id; }
    public function getVenta(): bool { return $this->venta; }
    public function getPrecioIGV(): float { return $this->precioIGv; }
    public function getBrand(): ?array { return $this->brand; }
    public function getCategory(): ?array { return $this->category; }
    public function getCurrencyType(): ?array { return $this->currencyType; }
    public function getMeasurementUnit(): ?array { return $this->measurementUnit; }
    public function getSubCategory(): ?array { return $this->subCategory; }
}
