<?php

namespace App\Modules\EntryGuides\Domain\Interfaces;

use App\Modules\EntryGuides\Domain\Entities\EntryGuide;
use Illuminate\Pagination\LengthAwarePaginator;

interface EntryGuideRepositoryInterface{
     
      public function save(EntryGuide $entryGuide ):?EntryGuide;
      public function update(EntryGuide $entryGuide ):?EntryGuide;
      public function findAll(?string $serie, ?string $correlativo):?LengthAwarePaginator;
       public function findByCorrelative( ?string $correlativo):?EntryGuide;
      public function findById(int $id):?EntryGuide;
      public function getLastDocumentNumber(string $serie): ?string;
      // public function findByCustomerId(int $customerId): array;
      public function findByIds(array $ids): array;
      public function allBelongToSameCustomer(array $ids): bool;
       
       
}
