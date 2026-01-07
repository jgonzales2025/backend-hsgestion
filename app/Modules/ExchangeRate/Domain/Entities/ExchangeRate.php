<?php

namespace App\Modules\ExchangeRate\Domain\Entities;

class ExchangeRate
{
    private ?int $id;
    private ?string $date;
    private ?float $purchase_rate;
    private ?float $sale_rate;
    private float $parallel_rate;
    private ?bool $almacen;
    private ?bool $compras;
    private ?bool $ventas;
    private ?bool $cobranzas;
    private ?bool $pagos;

    public function __construct(?int $id, ?string $date, ?float $purchase_rate, ?float $sale_rate, float $parallel_rate, ?bool $almacen = false, ?bool $compras = false, ?bool $ventas = false, ?bool $cobranzas = false, ?bool $pagos = false)
    {
        $this->id = $id;
        $this->date = $date;
        $this->purchase_rate = $purchase_rate;
        $this->sale_rate = $sale_rate;
        $this->parallel_rate = $parallel_rate;
        $this->almacen = $almacen;
        $this->compras = $compras;
        $this->ventas = $ventas;
        $this->cobranzas = $cobranzas;
        $this->pagos = $pagos;
    }

    public function getId(): int|null { return $this->id; }
    public function getDate(): string|null { return $this->date; }
    public function getPurchaseRate(): float|null { return $this->purchase_rate; }
    public function getSaleRate(): float|null { return $this->sale_rate; }
    public function getParallelRate(): float { return $this->parallel_rate; }
    public function getAlmacen(): bool|null { return $this->almacen; }
    public function getCompras(): bool|null { return $this->compras; }
    public function getVentas(): bool|null { return $this->ventas; }
    public function getCobranzas(): bool|null { return $this->cobranzas; }
    public function getPagos(): bool|null { return $this->pagos; }
}
