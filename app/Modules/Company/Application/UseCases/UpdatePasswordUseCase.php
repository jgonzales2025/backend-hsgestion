<?php

namespace App\Modules\Company\Application\UseCases;

use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;

readonly class UpdatePasswordUseCase
{
    public function __construct(private readonly CompanyRepositoryInterface $companyRepository){}

    public function execute(int $id, string $password): void
    {
        $this->companyRepository->updatePassword($id, $password);
    }
}
