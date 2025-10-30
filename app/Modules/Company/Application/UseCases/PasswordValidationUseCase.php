<?php

namespace App\Modules\Company\Application\UseCases;

use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;

readonly class PasswordValidationUseCase
{
    public function __construct(private readonly CompanyRepositoryInterface $companyRepository){}

    public function execute(int $id, string $password): bool
    {
        return $this->companyRepository->passwordValidation($id, $password);
    }
}
