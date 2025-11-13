<?php

namespace App\Modules\DigitalWallet\Application\UseCases;

use App\Modules\Company\Application\UseCases\FindByIdCompanyUseCase;
use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;
use App\Modules\DigitalWallet\Application\DTOs\DigitalWalletDTO;
use App\Modules\DigitalWallet\Domain\Entities\DigitalWallet;
use App\Modules\DigitalWallet\Domain\Interfaces\DigitalWalletRepositoryInterface;
use App\Modules\User\Application\UseCases\GetUserByIdUseCase;
use App\Modules\User\Domain\Interfaces\UserRepositoryInterface;

readonly class CreateDigitalWalletUseCase
{
    public function __construct(
        private readonly DigitalWalletRepositoryInterface $digitalWalletRepository,
        private readonly CompanyRepositoryInterface $companyRepository,
        private readonly UserRepositoryInterface $userRepository,
    ){}

    public function execute(DigitalWalletDTO $digitalWalletDTO): DigitalWallet
    {
        $companyUseCase = new FindByIdCompanyUseCase($this->companyRepository);
        $company = $companyUseCase->execute($digitalWalletDTO->company_id);

        $userUseCase = new GetUserByIdUseCase($this->userRepository);
        $user = $userUseCase->execute($digitalWalletDTO->user_id);

        $digitalWallet = new DigitalWallet(
            id: 0,
            name: $digitalWalletDTO->name,
            phone: $digitalWalletDTO->phone,
            company: $company,
            user: $user
        );

        return $this->digitalWalletRepository->save($digitalWallet);
    }
}
