<?php

namespace App\Modules\ScVoucher\Domain\Entities;

class ScVoucher
{
    private ?int $id;
    private ?int $cia;
    private int $anopr;
    private string $correlativo;
    private string $fecha;
    private int $codban;
    private int $codigo;
    private string $nroope;
    private ?string $glosa;
    private ?string $orden;
    private int $tipmon;
    private float $tipcam;
    private float $total;
    private int $medpag;
    private int $tipopago;
    private int $status;
    private int $usradi;
    private string $fecadi;
    private int $usrmod;

    public function __construct(
        ?int $id,
        ?int $cia,
        int $anopr,
        string $correlativo,
        string $fecha,
        int $codban,
        int $codigo,
        string $nroope,
        ?string $glosa,
        ?string $orden,
        int $tipmon,
        float $tipcam,
        float $total,
        int $medpag,
        int $tipopago,
        int $status,
        int $usradi,
        string $fecadi,
        int $usrmod,
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
    }
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getCia(): ?int
    {
        return $this->cia;
    }
    public function getAnopr(): int
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
    public function getCodban(): int
    {
        return $this->codban;
    }
    public function getCodigo(): int
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
    public function getTipmon(): int
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
    public function getMedpag(): int
    {
        return $this->medpag;
    }
    public function getTipopago(): int
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
}
