<?php

namespace App\Modules\DispatchNotes\Domain\Interfaces;

use App\Modules\DispatchNotes\Domain\Entities\DispatchNote;

interface DispatchNotesRepositoryInterface
{
    public function findAll(?string $description, ?int $status): array;
    public function save(DispatchNote $dispatchNote): ?DispatchNote;
    public function findById(int $id): ?DispatchNote;
    public function update(DispatchNote $dispatchNote): ?DispatchNote;
    public function getLastDocumentNumber(): ?string;
    public function updateStatus(int $dispatchNoteId, int $status): void;
    public function findByDocumentSale(string $serie, string $correlative): ?DispatchNote;
}