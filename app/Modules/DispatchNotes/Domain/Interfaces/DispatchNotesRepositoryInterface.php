<?php

namespace App\Modules\DispatchNotes\Domain\Interfaces;

use Illuminate\Support\Collection;
use App\Modules\DispatchNotes\Domain\Entities\DispatchNote;
use Illuminate\Pagination\LengthAwarePaginator;

interface DispatchNotesRepositoryInterface
{
    public function findAll(?string $description, ?int $status, ?int $emissionReasonId, ?string $estadoSunat = null,?string $fecha_inicio, ?string $fecha_fin): LengthAwarePaginator;
    public function save(DispatchNote $dispatchNote): ?DispatchNote;
    public function findById(int $id): ?DispatchNote;
    public function update(DispatchNote $dispatchNote): ?DispatchNote;
    public function getLastDocumentNumber(string $serie): ?string;
    public function updateStatus(int $dispatchNoteId, int $status): void;
    public function findByDocumentSale(string $serie, string $correlative): ?DispatchNote;
    public function findByDocument(string $serie, string $correlative): ?DispatchNote;
    public function findAllExcel(
        ?string $description,
        ?int $status,
        ?int $emissionReasonId
    ): Collection;
}
