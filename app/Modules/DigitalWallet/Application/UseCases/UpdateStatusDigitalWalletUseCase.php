<?php

namespace App\Modules\DigitalWallet\Application\UseCases;

use App\Modules\DigitalWallet\Domain\Interfaces\DigitalWalletRepositoryInterface;

class UpdateStatusDigitalWalletUseCase
{
    private DigitalWalletRepositoryInterface $digitalWalletRepository;

    public function __construct(DigitalWalletRepositoryInterface $digitalWalletRepository)
    {
        $this->digitalWalletRepository = $digitalWalletRepository;
    }

    public function execute(int $digitalWalletId, int $status): void
    {
        $this->digitalWalletRepository->updateStatus($digitalWalletId, $status);
    }
}
