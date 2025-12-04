<?php

namespace App\Modules\DigitalWallet\Application\UseCases;

use App\Modules\DigitalWallet\Domain\Interfaces\DigitalWalletRepositoryInterface;

readonly class FindAllDigitalWalletUseCase
{
    public function __construct(private readonly DigitalWalletRepositoryInterface $digitalWalletRepository){}

    public function execute(?string $description, ?int $status)
    {
        return $this->digitalWalletRepository->findAll($description, $status);
    }
}
