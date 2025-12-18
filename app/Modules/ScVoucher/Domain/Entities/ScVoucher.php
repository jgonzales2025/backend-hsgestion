<?php

namespace App\Modules\ScVoucher\Domain\Entities;

use App\Modules\Bank\Domain\Entities\Bank;
use App\Modules\CurrencyType\Domain\Entities\CurrencyType;
use App\Modules\Customer\Domain\Entities\Customer;
use App\Modules\PaymentMethodsSunat\Domain\Entities\PaymentMethodSunat;
use App\Modules\PaymentType\Domain\Entities\PaymentType;

class ScVoucher
{
    private ?int $id;
    private ?int $cia;
    private string $anopr;
    private string $correlativo;
    private string $fecha;
    private ?Bank $codban;
    private ?Customer $codigo;
    private string $nroope;
    private ?string $glosa;
    private ?string $orden;
    private ?CurrencyType $tipmon;
    private float $tipcam;
    private float $total;
    private ?PaymentMethodSunat $medpag;
    private ?PaymentType $tipopago;
    private int $status;
    private int $usradi;
    private string $fecadi;
    private int $usrmod;
    private ?array $details;
    private ?array $detailVoucherpurchase;

    public function __construct(
        ?int $id,
        ?int $cia,
        string $anopr,
        string $correlativo,
        string $fecha,
        ?Bank $codban,
        ?Customer $codigo,
        string $nroope,
        ?string $glosa,
        ?string $orden,
        ?CurrencyType $tipmon,
        float $tipcam,
        float $total,
        ?PaymentMethodSunat $medpag,
        ?PaymentType $tipopago,
        int $status,
        int $usradi,
        string $fecadi,
        int $usrmod,
        ?array $details,
        ?array $detailVoucherpurchase,
    ) {
        $this->id = $id;
        $this->cia = $cia;
        $this->anopr = $anopr;
        $this->correlativo = $correlativo;
        $this->fecha = $fecha;
        $this->codban = $codban;
        $this->codigo = $codigo;
        $this->nroope = $nroope;
        $this->glosa = $glosa;
        $this->orden = $orden;
        $this->tipmon = $tipmon;
        $this->tipcam = $tipcam;
        $this->total = $total;
        $this->medpag = $medpag;
        $this->tipopago = $tipopago;
        $this->status = $status;
        $this->usradi = $usradi;
        $this->fecadi = $fecadi;
        $this->usrmod = $usrmod;
        $this->details = $details;
        $this->detailVoucherpurchase = $detailVoucherpurchase;
    }
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getCia(): ?int
    {
        return $this->cia;
    }
    public function getAnopr(): string
    {
        return $this->anopr;
    }
    public function getCorrelativo(): string
    {
        return $this->correlativo;
    }
    public function getFecha(): string
    {
        return $this->fecha;
    }
    public function getCodban(): ?Bank
    {
        return $this->codban;
    }
    public function getCodigo(): ?Customer
    {
        return $this->codigo;
    }
    public function getNroope(): string
    {
        return $this->nroope;
    }
    public function getGlosa(): ?string
    {
        return $this->glosa;
    }
    public function getOrden(): ?string
    {
        return $this->orden;
    }
    public function getTipmon(): ?CurrencyType
    {
        return $this->tipmon;
    }
    public function getTipcam(): float
    {
        return $this->tipcam;
    }
    public function getTotal(): float
    {
        return $this->total;
    }
    public function getMedpag(): ?PaymentMethodSunat
    {
        return $this->medpag;
    }
    public function getTipopago(): ?PaymentType
    {
        return $this->tipopago;
    }
    public function getStatus(): int
    {
        return $this->status;
    }
    public function getUsradi(): int
    {
        return $this->usradi;
    }
    public function getFecadi(): string
    {
        return $this->fecadi;
    }
    public function getUsrmod(): int
    {
        return $this->usrmod;
    }
    public function getDetails(): ?array
    {
        return $this->details;
    }
    public function getDetailVoucherpurchase(): ?array
    {
        return $this->detailVoucherpurchase;
    }
}
