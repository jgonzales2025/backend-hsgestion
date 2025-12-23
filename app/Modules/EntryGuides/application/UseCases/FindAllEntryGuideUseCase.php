<?php

namespace App\Modules\EntryGuides\Application\UseCases;

use App\Modules\EntryGuides\Domain\Interfaces\EntryGuideRepositoryInterface;



class FindAllEntryGuideUseCase{
     public function __construct(private readonly EntryGuideRepositoryInterface $entryGuideRepositoryInterface){}
    

     public function execute(?string $description,?int $status, ?int $reference_document_id, ?string $reference_serie, ?string $reference_correlative, ?int $supplier_id){
        return $this->entryGuideRepositoryInterface->findAll($description, $status, $reference_document_id, $reference_serie, $reference_correlative, $supplier_id);
     }
    }