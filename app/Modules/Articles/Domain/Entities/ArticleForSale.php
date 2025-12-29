<?php
namespace App\Modules\Articles\Domain\Entities;

use App\Modules\Brand\Domain\Entities\Brand;
use App\Modules\Category\Domain\Entities\Category;
use App\Modules\Company\Domain\Entities\Company;
use App\Modules\CurrencyType\Domain\Entities\CurrencyType;
use App\Modules\MeasurementUnit\Domain\Entities\MeasurementUnit;
use App\Modules\SubCategory\Domain\Entities\SubCategory;
use App\Modules\User\Domain\Entities\User;

class ArticleForSale
{
    private ?int $id;
    private ?string $cod_fab;
    private string $description;
    private float $weight;
    private bool $with_deduction;
    private bool $series_enabled;
    private ?string $location;
    private string $warranty;
    private float $tariff_rate;
    private bool $igv_applicable;
    private bool $plastic_bag_applicable;
    private int $min_stock;
    private float $purchase_price_pen;
    private float $public_price_pen;
    private float $distributor_price_pen;
    private float $authorized_price_pen;
    private float $purchase_price_usd;
    private float $public_price_usd;
    private float $distributor_price_usd;
    private float $authorized_price_usd;
    private float $public_price_percent;
    private float $distributor_price_percent;
    private float $authorized_price_percent;
    private int $status;
    private ?User $user;
    private bool $venta;
    private array $stock_by_branch;
    private ?string $url_supplier;

    // Relaciones opcionales
    private ?Brand $brand;
    private ?Category $category;
    private ?CurrencyType $currencyType;
    private ?MeasurementUnit $measurementUnit;
    private ?SubCategory $subCategory;

    private ?Company $company;
    private ?string $image_url;
    private ?int $state_modify_article;


    public function __construct(
        ?int $id,
        ?string $cod_fab,
        string $description,
        float $weight,
        bool $with_deduction,
        bool $series_enabled,
        ?string $location,
        string $warranty,
        float $tariff_rate,
        bool $igv_applicable,
        bool $plastic_bag_applicable,
        int $min_stock,
        float $purchase_price_pen,
        float $public_price_pen,
        float $distributor_price_pen,
        float $authorized_price_pen,
        float $purchase_price_usd,
        float $public_price_usd,
        float $distributor_price_usd,
        float $authorized_price_usd,
        float $public_price_percent,
        float $distributor_price_percent,
        float $authorized_price_percent,
        int $status,
        ?Brand $brand,
        ?Category $category,
        ?CurrencyType $currencyType,
        ?MeasurementUnit $measurementUnit,
        ?User $user,
        bool $venta,
        ?SubCategory $subCategory,
        ?Company $company,
        ?string $image_url,
        ?int $state_modify_article,
        array $stock_by_branch = [],
        ?string $url_supplier = null

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
        $this->purchase_price_pen = $purchase_price_pen;
        $this->public_price_pen = $public_price_pen;
        $this->distributor_price_pen = $distributor_price_pen;
        $this->authorized_price_pen = $authorized_price_pen;
        $this->purchase_price_usd = $purchase_price_usd;
        $this->public_price_usd = $public_price_usd;
        $this->distributor_price_usd = $distributor_price_usd;
        $this->authorized_price_usd = $authorized_price_usd;
        $this->public_price_percent = $public_price_percent;
        $this->distributor_price_percent = $distributor_price_percent;
        $this->authorized_price_percent = $authorized_price_percent;
        $this->status = $status;

        $this->brand = $brand;
        $this->category = $category;
        $this->currencyType = $currencyType;
        $this->measurementUnit = $measurementUnit;
        $this->user = $user;

        $this->venta = $venta;
        $this->subCategory = $subCategory;
        $this->company = $company;
        $this->image_url = $image_url;
        $this->state_modify_article = $state_modify_article;
        $this->stock_by_branch = $stock_by_branch;
        $this->url_supplier = $url_supplier;
    }
    public function getUrlSupplier(): ?string
    {
        return $this->url_supplier;
    }
    public function getStockByBranch(): array
    {
        return $this->stock_by_branch;
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
    public function getCodFab(): ?string
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
    public function getLocation(): ?string
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

    public function getPurchasePricePen(): float
    {
        return $this->purchase_price_pen;
    }
    public function getPublicPricePen(): float
    {
        return $this->public_price_pen;
    }
    public function getDistributorPricePen(): float
    {
        return $this->distributor_price_pen;
    }
    public function getAuthorizedPricePen(): float
    {
        return $this->authorized_price_pen;
    }
    public function getPurchasePriceUSD(): float
    {
        return $this->purchase_price_usd;
    }
    public function getPublicPriceUSD(): float
    {
        return $this->public_price_usd;
    }
    public function getDistributorPriceUSD(): float
    {
        return $this->distributor_price_usd;
    }
    public function getAuthorizedPriceUSD(): float
    {
        return $this->authorized_price_usd;
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
        return $this->image_url ?? '';
    }
    public function getstateModifyArticle(): int|null
    {
        return $this->state_modify_article;
    }

}
