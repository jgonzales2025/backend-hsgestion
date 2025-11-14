<?php

namespace App\Modules\EntryItemSerial\Infrastructure\Controllers;

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

        $entryItemSerialUseCase = new FindSerialByArticleIdUseCase($this->entryItemSerialRepository);
        $serial = $entryItemSerialUseCase->execute($articleId, $serial);
        if (!$serial) {
            return response()->json(['message' => 'No se encontraron seriales para este artículo'], 404);
        }
        return response()->json($serial, 200);
    }
}
