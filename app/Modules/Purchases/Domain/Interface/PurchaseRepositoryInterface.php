<?php

namespace App\Modules\Purchases\Domain\Interface;

use App\Modules\Purchases\Domain\Entities\Purchase;

interface PurchaseRepositoryInterface{
    public function findAll(?string $description, ?string $num_doc, ?int $id_proveedr);
    public function findById(int $id):?Purchase;
    public function save(Purchase $purchase):?Purchase;
    public function update(Purchase $purchase):?Purchase;
    public function getLastDocumentNumber(int $company_id, int $branch_id, string $serie):?string;


}