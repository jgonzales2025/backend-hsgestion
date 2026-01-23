<?php

namespace App\Modules\TransactionLog\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\TransactionLog\Application\UseCases\FindAllTransactionLogsUseCase;
use App\Modules\TransactionLog\Domain\Interfaces\TransactionLogRepositoryInterface;
use App\Modules\TransactionLog\Infrastructure\Resources\TransactionLogDocumentResource;
use App\Modules\TransactionLog\Infrastructure\Resources\TransactionLogResource;
use Illuminate\Http\Request;

class TransactionLogController extends Controller
{
    public function __construct(private readonly TransactionLogRepositoryInterface $transactionLogRepository){}

    public function index(): array
    {
        $transactionLogUseCase = new FindAllTransactionLogsUseCase($this->transactionLogRepository);
        $transactionLog = $transactionLogUseCase->execute();

        return TransactionLogResource::collection($transactionLog)->resolve();
    }

    public function findByDocument(Request $request): array|null
    {
        $serie = $request->query('serie');
        $correlative = $request->query('correlative');

        $transactionLog = $this->transactionLogRepository->findByDocument($serie, $correlative);

        if ($transactionLog === null) {
            return null;
        }

        return TransactionLogDocumentResource::collection($transactionLog)->resolve();
    }
}
