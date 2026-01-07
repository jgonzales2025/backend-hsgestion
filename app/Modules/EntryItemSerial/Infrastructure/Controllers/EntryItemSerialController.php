<?php

namespace App\Modules\EntryItemSerial\Infrastructure\Controllers;

use App\Modules\EntryItemSerial\Application\UseCases\FindBySerialInDatabaseUseCase;
use App\Modules\EntryItemSerial\Application\UseCases\FindSerialByArticleIdUseCase;
use App\Modules\EntryItemSerial\Domain\Interface\EntryItemSerialRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EntryItemSerialController
{
    public function __construct(private readonly EntryItemSerialRepositoryInterface $entryItemSerialRepository){}

    public function findSerialByArticleId(Request $request, int $articleId): JsonResponse
    {
        $serial = $request->query('serial');
        $updated = (bool) $request->query('updated');
        $branch_id = $request->query('branch_id');
        
        $entryItemSerialUseCase = new FindSerialByArticleIdUseCase($this->entryItemSerialRepository);
        $serial = $entryItemSerialUseCase->execute($articleId, $branch_id, $updated, $serial);
        if (!$serial) {
            return response()->json(['message' => 'No se encontraron seriales para este artículo'], 404);
        }
        return response()->json($serial, 200);
    }

    public function findSerialInDatabase(Request $request): JsonResponse
    {
        $serial = $request->query('serial');
        $entryItemSerialUseCase = new FindBySerialInDatabaseUseCase($this->entryItemSerialRepository);
        $serial = $entryItemSerialUseCase->execute($serial);

        return response()->json($serial, 200);
    }
}
