<?php

namespace App\Modules\DetailPcCompatible\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Articles\Application\UseCases\FindByIdArticleUseCase;
use App\Modules\Articles\Domain\Interfaces\ArticleRepositoryInterface;
use App\Modules\DetailPcCompatible\application\DTOS\DetailPcCompatibleDTO;
use App\Modules\DetailPcCompatible\application\UseCases\CreateDetailPcCompatibleUseCase;
use App\Modules\DetailPcCompatible\application\UseCases\FindAllDetailPcCompatibleUseCase;
use App\Modules\DetailPcCompatible\application\UseCases\FindByIdDetailPcCompatibleUseCase;
use App\Modules\DetailPcCompatible\application\UseCases\UpdateDetailPcCompatibleUseCase;
use App\Modules\DetailPcCompatible\Domain\Interface\DetailPcCompatibleRepositoryInterface;
use App\Modules\DetailPcCompatible\Infrastructure\Request\CreateDetailPcCompatibleRequest;
use App\Modules\DetailPcCompatible\Infrastructure\Request\StoreByArticleRequest;
use App\Modules\DetailPcCompatible\Infrastructure\Resource\DetailPcCompatibleResource;
use Illuminate\Http\JsonResponse;

class DetailPcCompatibleController extends Controller
{
    public function __construct(
        private DetailPcCompatibleRepositoryInterface $detailPcCompatibleRepositoryInterface,
        private ArticleRepositoryInterface $articleRepositoryInterface
    ) {}

    public function index(): JsonResponse
    {
        $useCase = new FindAllDetailPcCompatibleUseCase($this->detailPcCompatibleRepositoryInterface);
        $result = $useCase->execute();

        return response()->json(
            DetailPcCompatibleResource::collection($result)->resolve(),
            200
        );
    }

    public function store(CreateDetailPcCompatibleRequest $request): JsonResponse
    {
        $data = new DetailPcCompatibleDTO($request->validated());

        $articles =  new FindByIdArticleUseCase($this->articleRepositoryInterface);
        $articleMajor = $articles->execute($data->article_major_id);

        if (!$articleMajor) {
            return response()->json([
                'message' => 'Article no encontrado'
            ], 404);
        };


        $useCase = new CreateDetailPcCompatibleUseCase($this->detailPcCompatibleRepositoryInterface);
        $result = $useCase->execute($data);

        return response()->json(
            new DetailPcCompatibleResource($result),
            201
        );
    }

    public function storeByArticle(StoreByArticleRequest $request, int $articleId): JsonResponse
    {
        // Validar que el artículo principal existe
        $articleUseCase = new FindByIdArticleUseCase($this->articleRepositoryInterface);
        $articleMajor = $articleUseCase->execute($articleId);

        if (!$articleMajor) {
            return response()->json([
                'message' => 'Artículo principal no encontrado'
            ], 404);
        }

        $validated = $request->validated();
        $accessoryIds = $validated['article_accesory_id'];
        $status = $validated['status'] ?? true;

        // IMPORTANTE: Eliminar todos los registros existentes para este article_major_id
        $this->detailPcCompatibleRepositoryInterface->deleteByArticleMajorId($articleId);

        $createdDetails = [];
        $useCase = new CreateDetailPcCompatibleUseCase($this->detailPcCompatibleRepositoryInterface);

        // Crear un registro para cada artículo accesorio
        foreach ($accessoryIds as $accessoryId) {
            // Validar que el artículo accesorio existe
            $articleAccesory = $articleUseCase->execute($accessoryId);

            if (!$articleAccesory) {
                return response()->json([
                    'message' => "Accesorio con ID {$accessoryId} no encontrado"
                ], 404);
            }

            // Crear el DTO
            $data = new DetailPcCompatibleDTO([
                'article_major_id' => $articleId,
                'article_accesory_id' => $accessoryId,
                'status' => $status
            ]);

            $result = $useCase->execute($data);
            $createdDetails[] = $result;
        }

        return response()->json(
            DetailPcCompatibleResource::collection($createdDetails)->resolve(),
            201
        );
    }

    public function update(CreateDetailPcCompatibleRequest $request, int $id): JsonResponse
    {
        $data = new DetailPcCompatibleDTO($request->validated());

        $useCase = new UpdateDetailPcCompatibleUseCase($this->detailPcCompatibleRepositoryInterface);
        $result = $useCase->execute($data, $id);

        if (!$result) {
            return response()->json(['message' => 'Accesorion Compatible no encontrado'], 404);
        }

        return response()->json(
            new DetailPcCompatibleResource($result),
            200
        );
    }

    public function show(int $id): JsonResponse
    {
        $useCase = new FindByIdDetailPcCompatibleUseCase($this->detailPcCompatibleRepositoryInterface);
        $result = $useCase->execute($id);

        if (!$result) {
            return response()->json(['message' => 'Accesorio no encontrado'], 404);
        }

        return response()->json(
            new DetailPcCompatibleResource($result),
            200
        );
    }
    public function showByArticle(int $id): JsonResponse
    {
        $findAllArticles = $this->detailPcCompatibleRepositoryInterface->findAllArticles($id);
        return response()->json(
            DetailPcCompatibleResource::collection($findAllArticles)->resolve(),
            200
        );
    }
}
