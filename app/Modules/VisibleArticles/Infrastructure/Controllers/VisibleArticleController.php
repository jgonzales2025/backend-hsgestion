<?php

namespace App\Modules\VisibleArticles\Infrastructure\Controllers;

use App\Http\Controllers\Controller;

use App\Modules\VisibleArticles\Application\DTOS\VisibleArticleDTO;
use App\Modules\VisibleArticles\Application\UseCases\FindByVisibleArticleUseCase;
use App\Modules\VisibleArticles\Application\UseCases\UpdateVisibleArticleUseCase;
use App\Modules\VisibleArticles\Domain\Interfaces\VisibleArticleRepositoryInterface;

use App\Modules\VisibleArticles\Infrastructure\Request\UpdateVisibleArticleRequest;
use App\Modules\VisibleArticles\Infrastructure\Resources\VisibleArticleResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class VisibleArticleController extends Controller
{
    public function __construct(private readonly VisibleArticleRepositoryInterface $visibleArticleRepository)
    {
    }

    public function show($id): JsonResponse
    {
        $visibleArticleUseCase = new FindByVisibleArticleUseCase($this->visibleArticleRepository);
        $visible = $visibleArticleUseCase->execute($id);

        if (!$visible) {
            return response()->json(['message' => 'Registro de artículo visible no encontrado'], 404);
        }
        return response()->json(new VisibleArticleResource($visible), 200);

    }
    public function update(UpdateVisibleArticleRequest $request, $id): JsonResponse
    {
        $visibleDTO = new VisibleArticleDTO($request->validated());

        $visibleArticleUseCase = new UpdateVisibleArticleUseCase($this->visibleArticleRepository);
        $visibleArticleUseCase->execute($id, $visibleDTO);

        return response()->json(['message' => 'Estado actualizado correctamente.']);

    }
    public function visibleBranch($id): array
    {
        $visibleDTO = $this->visibleArticleRepository->mostrar($id);

        return VisibleArticleResource::collection($visibleDTO)->resolve();
    }
}
