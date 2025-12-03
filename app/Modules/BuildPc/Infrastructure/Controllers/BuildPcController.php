<?php

namespace App\Modules\BuildPc\Infrastructure\Controllers;

use App\Modules\BuildDetailPc\application\DTOS\BuildDetailPcdto;
use App\Modules\BuildDetailPc\application\UseCases\CreateBuildDetailPcUseCase;
use App\Modules\BuildDetailPc\Domain\Interface\BuildDetailPcRepositoryInterface;
use App\Modules\BuildDetailPc\Infrastructure\Resource\BuildDetailPcResource;
use App\Modules\BuildPc\Application\DTOS\BuildPcDTO;
use App\Modules\BuildPc\Application\UseCases\CreateBuildPcUseCase;
use App\Modules\BuildPc\Application\UseCases\FindAllBuildPcUseCase;
use App\Modules\BuildPc\application\UseCases\FindByIdBuildPcUseCase;
use App\Modules\BuildPc\Application\UseCases\UpdateBuildPcUseCase;
use App\Modules\BuildPc\Domain\Interface\BuildPcRepositoryInterface;
use App\Modules\BuildPc\Infrastructure\Request\CreateBuildPcRequest;
use App\Modules\BuildPc\Infrastructure\Request\UpdateBuildPcRequest;
use App\Modules\BuildPc\Infrastructure\Resource\BuildPcResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BuildPcController
{
    public function __construct(
        private readonly BuildPcRepositoryInterface $buildPcRepository,
        private readonly BuildDetailPcRepositoryInterface $buildDetailPcRepository
    ) {}

    public function index(Request $request): JsonResponse
    {
        $search = $request->query('search');
        $is_active = $request->query('is_active');

        $buildPcUseCase = new FindAllBuildPcUseCase($this->buildPcRepository);
        $buildPcs = $buildPcUseCase->execute($search, $is_active);

        $result = [];

        // Iterate over paginated   items
        foreach ($buildPcs->items() as $buildPc) {
            $details = $this->buildDetailPcRepository->findByBuildPcId($buildPc->getId());

            $result[] = array_merge(
                (new BuildPcResource($buildPc))->resolve(),
                [
                    'details' => BuildDetailPcResource::collection($details)->resolve(),
                ]
            );
        }

        // Return paginated response with navigation URLs
        return new JsonResponse([
            'data' => $result,
            'current_page' => $buildPcs->currentPage(),
            'per_page' => $buildPcs->perPage(),
            'total' => $buildPcs->total(),
            'last_page' => $buildPcs->lastPage(),
            'next_page_url' => $buildPcs->nextPageUrl(),
            'prev_page_url' => $buildPcs->previousPageUrl(),
            'first_page_url' => $buildPcs->url(1),
            'last_page_url' => $buildPcs->url($buildPcs->lastPage()),
        ]);
    }

    public function store(CreateBuildPcRequest $request): JsonResponse
    {
        $data = $request->validated();

        // Calcular total
        $totalPrice = 0;
        foreach ($data['details'] as $detail) {
            $totalPrice += $detail['quantity'] * $detail['price'];
        }

        $data['total_price'] = $totalPrice;

        // Crear PC
        $buildPcDTO = new BuildPcDTO($data);
        $buildPcUseCase = new CreateBuildPcUseCase($this->buildPcRepository);
        $buildPc = $buildPcUseCase->execute($buildPcDTO);

        // Crear detalles
        $details = $this->createDetails($buildPc, $data['details']);

        return response()->json(
            array_merge(
                (new BuildPcResource($buildPc))->resolve(),
                [
                    'details' => BuildDetailPcResource::collection($details)->resolve(),
                ]
            ),
            201
        );
    }


    public function show(int $id): JsonResponse
    {
        $buildPcUseCase = new FindByIdBuildPcUseCase($this->buildPcRepository);
        $buildPc = $buildPcUseCase->execute($id);

        if (!$buildPc) {
            return response()->json(['message' => 'Build PC no encontrado'], 404);
        }

        $details = $this->buildDetailPcRepository->findByBuildPcId($buildPc->getId());

        return response()->json(
            array_merge(
                (new BuildPcResource($buildPc))->resolve(),
                [
                    'details' => BuildDetailPcResource::collection($details)->resolve(),
                ]
            ),
            200
        );
    }

    public function update(UpdateBuildPcRequest $request, int $id): JsonResponse
    {
        $buildPcDTO = new BuildPcDTO($request->validated());
        $buildPcUseCase = new UpdateBuildPcUseCase($this->buildPcRepository);
        $buildPc = $buildPcUseCase->execute($buildPcDTO, $id);

        if (!$buildPc) {
            return response()->json(['message' => 'Build PC no encontrado'], 404);
        }

        // Delete old details and create new ones
        $this->buildDetailPcRepository->deleteByBuildPcId($buildPc->getId());
        $details = $this->createDetails($buildPc, $request->validated()['details']);

        return response()->json(
            array_merge(
                (new BuildPcResource($buildPc))->resolve(),
                [
                    'details' => BuildDetailPcResource::collection($details)->resolve(),
                ]
            ),
            200
        );
    }

    private function createDetails($buildPc, array $detailsData): array
    {
        $createDetailUseCase = new CreateBuildDetailPcUseCase($this->buildDetailPcRepository);

        return array_map(function ($item) use ($buildPc, $createDetailUseCase) {
            $detailDTO = new BuildDetailPcdto([
                'build_pc_id' => $buildPc->getId(),
                'article_id' => $item['article_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['quantity'] * $item['price'],
            ]);

            return $createDetailUseCase->execute($detailDTO);
        }, $detailsData);
    }
}
