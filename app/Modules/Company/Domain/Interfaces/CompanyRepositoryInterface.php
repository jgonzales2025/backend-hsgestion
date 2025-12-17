<?php
namespace App\Modules\Company\Domain\Interfaces;

use App\Modules\Company\Domain\Entities\Company;
use App\Modules\Company\Domain\Entities\UpdateCompany;
use PhpParser\Node\Expr\Cast\Void_;

interface CompanyRepositoryInterface
{
    public function findAllCompanys(?string $description, ?int $status);
    public function findById(int $id): ?Company;
    public function indexByUser(int $userId): array;
    public function update(int $id, UpdateCompany $updateCompany): void;

}
