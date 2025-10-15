<?php

namespace App\Modules\DigitalWallet\Domain\Interfaces;

use App\Modules\DigitalWallet\Domain\Entities\DigitalWallet;

interface DigitalWalletRepositoryInterface
{
    public function findAll(): array;
    public function save(DigitalWallet $digitalWallet): ?DigitalWallet;
    public function findById(int $id): ?DigitalWallet;
    public function update(DigitalWallet $digitalWallet): ?DigitalWallet;
}
