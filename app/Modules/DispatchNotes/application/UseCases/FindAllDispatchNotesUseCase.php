<?php

namespace App\Modules\DispatchNotes\Application\UseCases;

use App\Modules\DispatchNotes\Domain\Interfaces\DispatchNotesRepositoryInterface;

class FindAllDispatchNotesUseCase
{

    public function __construct(private readonly DispatchNotesRepositoryInterface $dispatchNotesRepositoryInterface) {}

    public function execute(?string $description, ?int $status, ?int $emissionReasonId, ?string $estadoSunat = null, ?string $fecha_inicio , ?string $fecha_fin)
    {
        return $this->dispatchNotesRepositoryInterface->findAll($description, $status, $emissionReasonId, $estadoSunat, $fecha_inicio, $fecha_fin);
    }
}
