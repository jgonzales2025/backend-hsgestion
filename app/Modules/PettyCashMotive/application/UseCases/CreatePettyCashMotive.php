<?php

namespace App\Modules\PettyCashMotive\Application\UseCases;

use App\Modules\PettyCashMotive\Application\DTOS\PettyCashMotiveDTO;
use App\Modules\PettyCashMotive\Domain\Entities\PettyCashMotive;
use App\Modules\PettyCashMotive\Domain\Interface\PettyCashMotiveInterfaceRepository;

class CreatePettyCashMotive
{
    public function __construct(private readonly PettyCashMotiveInterfaceRepository $pettyCashMotiveInterfaceRepository)
    {
    }

    public function execute(PettyCashMotiveDTO $pettyCashMotiveDTO): ?PettyCashMotive
    {
        $pettyCashMotive = new PettyCashMotive(
            id: null,
            company_id: $pettyCashMotiveDTO->company_id,
            description: $pettyCashMotiveDTO->description,
            receipt_type: $pettyCashMotiveDTO->receipt_type,
            user_id: $pettyCashMotiveDTO->user_id,
            date: $pettyCashMotiveDTO->date,
            user_mod: $pettyCashMotiveDTO->user_mod,
            date_mod: $pettyCashMotiveDTO->date_mod,
            status: $pettyCashMotiveDTO->status,
        );
        return $this->pettyCashMotiveInterfaceRepository->save($pettyCashMotive);

    }
}