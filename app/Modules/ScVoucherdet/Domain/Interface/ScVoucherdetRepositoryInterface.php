<?php

namespace App\Modules\ScVoucherdet\Domain\Interface;

use App\Modules\ScVoucherdet\Domain\Entities\ScVoucherdet;

interface ScVoucherdetRepositoryInterface
{
    public function create(ScVoucherdet $scVoucherdet): ?ScVoucherdet;
    public function update(ScVoucherdet $scVoucherdet): ?ScVoucherdet;
    public function findById(int $id): ?ScVoucherdet;
    public function findAll(): array;
    public function findByVoucherId(int $voucherId): array;
    public function deleteByVoucherId(int $voucherId): void;
}
