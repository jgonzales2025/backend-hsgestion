<?php
namespace App\Modules\Articles\Domain\Entities;

use App\Modules\Brand\Domain\Entities\Brand;
use App\Modules\Category\Domain\Entities\Category;
use App\Modules\Company\Domain\Entities\Company;
use App\Modules\CurrencyType\Domain\Entities\CurrencyType;
use App\Modules\MeasurementUnit\Domain\Entities\MeasurementUnit;
use App\Modules\SubCategory\Domain\Entities\SubCategory;
use App\Modules\User\Domain\Entities\User;

class Article
{
    private ?int $id;
    private string $cod_fab;
    private string $description;
    private float $weight;
    private bool $with_deduction;
    private bool $series_enabled;
    private string $location;
    private string $warranty;
    private float $tariff_rate;
    private bool $igv_applicable;
    private bool $plastic_bag_applicable;
    private int $min_stock;
    private float $purchase_price;
    private float $public_price;
    private float $distributor_price;
    private float $authorized_price;
    private float $public_price_percent;
    private float $distributor_price_percent;
    private float $authorized_price_percent;
    private int $status;
    private ?User $user;
    private bool $venta;

    // Relaciones opcionales
    private ?Brand $brand;
    private ?Category $category;
    private ?CurrencyType $currencyType;
    private ?MeasurementUnit $measurementUnit;
    private ?SubCategory $subCategory;

    private float $precioIGv;
    private ?Company $company;
    private ?string $image_url;


    public function __construct(
        ?int $id,
        string $cod_fab,
        string $description,
        float $weight,
        bool $with_deduction,
        bool $series_enabled,
        string $location,
        string $warranty,
        float $tariff_rate,
        bool $igv_applicable,
        bool $plastic_bag_applicable,
        int $min_stock,
        float $purchase_price,
        float $public_price,
        float $distributor_price,
        float $authorized_price,
        float $public_price_percent,
        float $distributor_price_percent,
        float $authorized_price_percent,
        int $status,
        ?Brand $brand,
        ?Category $category,
        ?CurrencyType $currencyType,
        ?MeasurementUnit $measurementUnit,
        ?User $user,
        ?float $precioIGv,
        bool $venta,
        ?SubCategory $subCategory,
        ?Company $company,
        ?string $image_url


    ) {
        $this->id = $id;
        $this->cod_fab = $cod_fab;
        $this->description = $description;
        $this->weight = $weight;
        $this->with_deduction = $with_deduction;
        $this->series_enabled = $series_enabled;
        $this->location = $location;
        $this->warranty = $warranty;
        $this->tariff_rate = $tariff_rate;
        $this->igv_applicable = $igv_applicable;
        $this->plastic_bag_applicable = $plastic_bag_applicable;
        $this->min_stock = $min_stock;
        $this->purchase_price = $purchase_price;
        $this->public_price = $public_price;
        $this->distributor_price = $distributor_price;
        $this->authorized_price = $authorized_price;
        $this->public_price_percent = $public_price_percent;
        $this->distributor_price_percent = $distributor_price_percent;
        $this->authorized_price_percent = $authorized_price_percent;
        $this->status = $status;

        $this->brand = $brand;
        $this->category = $category;
        $this->currencyType = $currencyType;
        $this->measurementUnit = $measurementUnit;
        $this->user = $user;

        $this->precioIGv = $precioIGv ?? $this->calculatePrecioIGV();

        $this->venta = $venta;
        $this->subCategory = $subCategory;
        $this->company = $company;
        $this->image_url = $image_url;
    }

    public function calculatePrecioIGV(): float
    {
        return $this->purchase_price + ($this->purchase_price * $this->tariff_rate / 100);
    }

    public function getUser(): ?User
    {
        return $this->user;
    }
    public function getSubCategory(): SubCategory|null
    {
        return $this->subCategory;
    }
    // Getters

    public function getId(): int|null
    {
        return $this->id;
    }
    public function getCodFab(): string
    {
        return $this->cod_fab;
    }
    public function getDescription(): string
    {
        return $this->description;
    }
    public function getWeight(): float
    {
        return $this->weight;
    }
    public function getWithDeduction(): bool
    {
        return $this->with_deduction;
    }
    public function getSeriesEnabled(): bool
    {
        return $this->series_enabled;
    }
    public function getLocation(): string
    {
        return $this->location;
    }
    public function getWarranty(): string
    {
        return $this->warranty;
    }
    public function getTariffRate(): float
    {
        return $this->tariff_rate;
    }
    public function getIgvApplicable(): bool
    {
        return $this->igv_applicable;
    }
    public function getPlasticBagApplicable(): bool
    {
        return $this->plastic_bag_applicable;
    }
    public function getMinStock(): int
    {
        return $this->min_stock;
    }

    public function getPurchasePrice(): float
    {
        return $this->purchase_price;
    }
    public function getPublicPrice(): float
    {
        return $this->public_price;
    }
    public function getDistributorPrice(): float
    {
        return $this->distributor_price;
    }
    public function getAuthorizedPrice(): float
    {
        return $this->authorized_price;
    }
    public function getPublicPricePercent(): float
    {
        return $this->public_price_percent;
    }
    public function getDistributorPricePercent(): float
    {
        return $this->distributor_price_percent;
    }
    public function getAuthorizedPricePercent(): float
    {
        return $this->authorized_price_percent;
    }
    public function getStatus(): int
    {
        return $this->status;
    }
    public function getVenta(): bool
    {
        return $this->venta;
    }
    public function getPrecioIGV(): float
    {
        return $this->precioIGv;
    }
    public function getBrand(): Brand|null
    {
        return $this->brand;
    }
    public function getCategory(): Category|null
    {
        return $this->category;
    }
    public function getCurrencyType(): CurrencyType|null
    {
        return $this->currencyType;
    }
    public function getMeasurementUnit(): MeasurementUnit|null
    {
        return $this->measurementUnit;
    }
    public function getCompany(): Company|null
    {
        return $this->company;
    }
    public function getImageURL(): string
    {
        return $this->image_url;
    }

}
