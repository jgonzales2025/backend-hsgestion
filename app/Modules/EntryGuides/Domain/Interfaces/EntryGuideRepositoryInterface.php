<?php

namespace App\Modules\EntryGuides\Domain\Interfaces;

use App\Modules\EntryGuides\Domain\Entities\EntryGuide;

interface EntryGuideRepositoryInterface{
     
      public function save(EntryGuide $entryGuide ):?EntryGuide;
      public function update(EntryGuide $entryGuide ):?EntryGuide;
      public function findAll(?string $serie, ?string $correlativo):array;
      public function findById(int $id):?EntryGuide;
      public function getLastDocumentNumber(string $serie): ?string;
      
}