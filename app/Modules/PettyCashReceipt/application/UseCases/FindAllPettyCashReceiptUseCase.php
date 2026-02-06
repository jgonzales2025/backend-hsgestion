<?php

namespace App\Modules\PettyCashReceipt\Application\UseCases;

use App\Modules\PettyCashReceipt\Domain\Interface\PettyCashReceiptRepositoryInterface;

class FindAllPettyCashReceiptUseCase
{
    public function __construct(private readonly PettyCashReceiptRepositoryInterface $pettyCashReceiptRepository)
    {
    }

    public function execute(?string $filter , ?int $currency_type, ?int $is_active,?string $fecha_inicio,?string $fecha_fin)
    {
        return $this->pettyCashReceiptRepository->findAll($filter, $currency_type, $is_active,$fecha_inicio,$fecha_fin);

    }
}