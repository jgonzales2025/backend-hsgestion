<?php

namespace App\Modules\TransactionLog\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\TransactionLog\Application\UseCases\FindAllTransactionLogsUseCase;
use App\Modules\TransactionLog\Domain\Interfaces\TransactionLogRepositoryInterface;
use App\Modules\TransactionLog\Infrastructure\Resources\TransactionLogResource;

class TransactionLogController extends Controller
{
    public function __construct(private readonly TransactionLogRepositoryInterface $transactionLogRepository){}

    public function index(): array
    {
        $transactionLogUseCase = new FindAllTransactionLogsUseCase($this->transactionLogRepository);
        $transactionLog = $transactionLogUseCase->execute();

        return TransactionLogResource::collection($transactionLog)->resolve();
    }
}
