<?php

namespace App\Modules\DigitalWallet\Application\UseCases;

use App\Modules\DigitalWallet\Domain\Entities\DigitalWallet;
use App\Modules\DigitalWallet\Domain\Interfaces\DigitalWalletRepositoryInterface;

readonly class FindByIdDigitalWalletUseCase
{
    public function __construct(private readonly DigitalWalletRepositoryInterface $digitalWalletRepository){}

    public function execute($id): DigitalWallet
    {
        return $this->digitalWalletRepository->findById($id);
    }
}
