<?php

namespace App\Modules\ScVoucher\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\ScVoucher\Application\DTOS\ScVoucherDTO;
use App\Modules\ScVoucher\Application\UseCases\CreateScVoucherUseCase;
use App\Modules\ScVoucher\Application\UseCases\FindAllScVoucherUseCase;
use App\Modules\ScVoucher\Application\UseCases\FindByIdScVoucherUseCase;
use App\Modules\ScVoucher\Application\UseCases\UpdateScVoucherUseCase;
use App\Modules\ScVoucher\Domain\Interface\ScVoucherRepositoryInterface;
use App\Modules\ScVoucher\Infrastructure\Request\StoreScVoucherRequest;
use App\Modules\ScVoucher\Infrastructure\Request\UpdateScVoucherRequest;
use App\Modules\ScVoucher\Infrastructure\Resource\ScVoucherResource;
use Illuminate\Http\JsonResponse;

class ScVoucherController extends Controller
{
    public function __construct(
        private ScVoucherRepositoryInterface $scVoucherRepository,
    ) {}

    public function index(): JsonResponse
    {
        $findAllUseCase = new FindAllScVoucherUseCase($this->scVoucherRepository);
        $scVouchers = $findAllUseCase->execute();

        // Return paginated response with navigation URLs
        return new JsonResponse([
            'data' => ScVoucherResource::collection($scVouchers->items())->resolve(),
            'current_page' => $scVouchers->currentPage(),
            'per_page' => $scVouchers->perPage(),
            'total' => $scVouchers->total(),
            'last_page' => $scVouchers->lastPage(),
            'next_page_url' => $scVouchers->nextPageUrl(),
            'prev_page_url' => $scVouchers->previousPageUrl(),
            'first_page_url' => $scVouchers->url(1),
            'last_page_url' => $scVouchers->url($scVouchers->lastPage()),
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $findByIdUseCase = new FindByIdScVoucherUseCase($this->scVoucherRepository);
        $scVoucher = $findByIdUseCase->execute($id);

        if (!$scVoucher) {
            return response()->json(['message' => 'ScVoucher no encontrado'], 404);
        }

        return response()->json(new ScVoucherResource($scVoucher), 200);
    }

    public function store(StoreScVoucherRequest $request): JsonResponse
    {
        $scVoucherDTO = new ScVoucherDTO($request->validated());
        $createUseCase = new CreateScVoucherUseCase($this->scVoucherRepository);
        $scVoucher = $createUseCase->execute($scVoucherDTO);

        return response()->json(new ScVoucherResource($scVoucher), 201);
    }

    public function update(UpdateScVoucherRequest $request, int $id): JsonResponse
    {
        $scVoucherDTO = new ScVoucherDTO($request->validated());
        $updateUseCase = new UpdateScVoucherUseCase($this->scVoucherRepository);
        $scVoucher = $updateUseCase->execute($scVoucherDTO, $id);

        if (!$scVoucher) {
            return response()->json(['message' => 'ScVoucher no encontrado'], 404);
        }

        return response()->json(new ScVoucherResource($scVoucher), 200);
    }
}
