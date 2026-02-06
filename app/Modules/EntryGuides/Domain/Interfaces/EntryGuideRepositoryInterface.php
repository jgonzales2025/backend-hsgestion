<?php

namespace App\Modules\EntryGuides\Domain\Interfaces;

use App\Modules\EntryGuides\Domain\Entities\EntryGuide;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface EntryGuideRepositoryInterface
{

      public function save(EntryGuide $entryGuide): ?EntryGuide;
      public function update(EntryGuide $entryGuide): ?EntryGuide;
      public function findAll(?string $description, ?int $status,?int $reference_document_id, ?string $reference_serie, ?string $reference_correlative, ?int $supplier_id, ?string $fecha_inicio,?string $fecha_fin): ?LengthAwarePaginator;
      public function findByCorrelative(?string $correlativo): ?EntryGuide;
      public function findById(int $id): ?EntryGuide;
      public function getLastDocumentNumber(string $serie): ?string;
      // public function findByCustomerId(int $customerId): array;
      public function findByIds(array $ids): array;
      public function allBelongToSameCustomer(array $ids): bool;
      public function updateStatus(int $id, int $status): void;
      public function findBySerieAndCorrelative(string $serie, string $correlative): ?EntryGuide; 
      public function findAllExcel(): ?Collection;

}
