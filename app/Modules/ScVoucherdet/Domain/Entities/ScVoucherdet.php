<?php

namespace App\Modules\ScVoucherdet\Domain\Entities;

class ScVoucherdet
{
    private ?int $id;
    private int $cia;
    private int $codcon;
    private int $tipdoc;
    private string $glosa;
    private float $impsol;
    private float $impdol;
    private ?int $id_purchase;
    private ?int $id_sc_voucher;
    private ?int $numdoc;

    public function __construct(
        ?int $id,
        int $cia,
        int $codcon,
        int $tipdoc,
        string $glosa,
        float $impsol,
        float $impdol,
        ?int $id_purchase = null,
        ?int $id_sc_voucher = null,
        int $numdoc = null
    ) {
        $this->id = $id;
        $this->cia = $cia;
        $this->codcon = $codcon;
        $this->tipdoc = $tipdoc;
        $this->glosa = $glosa;
        $this->impsol = $impsol;
        $this->impdol = $impdol;
        $this->id_purchase = $id_purchase;
        $this->id_sc_voucher = $id_sc_voucher;
        $this->numdoc = $numdoc;

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCia(): int
    {
        return $this->cia;
    }

    public function getCodcon(): int
    {
        return $this->codcon;
    }

    public function getTipdoc(): int
    {
        return $this->tipdoc;
    }

    public function getGlosa(): string
    {
        return $this->glosa;
    }

    public function getImpsol(): float
    {
        return $this->impsol;
    }

    public function getImpdol(): float
    {
        return $this->impdol;
    }
    public function getIdPurchase(): ?int
    {
        return $this->id_purchase;
    }

    public function getIdScVoucher(): ?int
    {
        return $this->id_sc_voucher;
    }
    public function getNumdoc(): ?int
    {
        return $this->numdoc;
    }
}
