<?php

namespace App\Modules\ScVoucher\Domain\Interface;

use App\Modules\ScVoucher\Domain\Entities\ScVoucher;

interface ScVoucherRepositoryInterface
{
    public function findById(int $id): ?ScVoucher;
    public function findAll(?string $search, ?int $status, ?string $fecha_inicio, ?string $fecha_fin);
    public function create(ScVoucher $scVoucher): ?ScVoucher;
    public function update(ScVoucher $scVoucher): ?ScVoucher;
    public function getLastDocumentNumber(string $serie): ?string;
    public function updateStatus(int $id, int $status);
    public function updateImagePath(int $id, string $path): void;
    public function getImagePath(int $id): ?string;
}
