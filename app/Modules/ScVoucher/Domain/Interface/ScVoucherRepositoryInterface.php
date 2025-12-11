<?php

namespace App\Modules\ScVoucher\Domain\Interface;

use App\Modules\ScVoucher\Domain\Entities\ScVoucher;

interface ScVoucherRepositoryInterface
{
    public function findById(int $id): ?ScVoucher;
    public function findAll();
    public function create(ScVoucher $scVoucher): ?ScVoucher;
    public function update(ScVoucher $scVoucher): ?ScVoucher;
    public function getLastDocumentNumber(string $serie): ?string;
}
