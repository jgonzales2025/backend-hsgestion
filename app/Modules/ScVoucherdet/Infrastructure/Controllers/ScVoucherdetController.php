<?php

namespace App\Modules\ScVoucherdet\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\ScVoucher\Application\UseCases\FindByIdScVoucherUseCase;
use App\Modules\ScVoucher\Infrastructure\Request\UpdateScVoucherRequest;
use App\Modules\ScVoucherdet\application\DTOS\ScVoucherdetDTO;
use App\Modules\ScVoucherdet\application\UseCases\CreateScVoucherdetUseCase;
use App\Modules\ScVoucherdet\application\UseCases\FindAllScVoucherdetUseCase;
use App\Modules\ScVoucherdet\application\UseCases\FindByIdScVoucherdetUseCase;
use App\Modules\ScVoucherdet\application\UseCases\UpdateScVoucherdetUseCase;
use App\Modules\ScVoucherdet\Domain\Interface\ScVoucherdetRepositoryInterface;
use App\Modules\ScVoucherdet\Infrastructure\Request\StoreScVoucherdetRequest;
use App\Modules\ScVoucherdet\Infrastructure\Request\UpdateScVoucherdetRequest;
use App\Modules\ScVoucherdet\Infrastructure\Resource\ScVoucherdetResource;
use Illuminate\Http\JsonResponse;

class ScVoucherdetController extends Controller
{
    public function __construct(
        private ScVoucherdetRepositoryInterface $scVoucherdetRepository,
    ) {}

    public function index(): JsonResponse
    {
        $findAllScVoucherdetUseCase = new FindAllScVoucherdetUseCase($this->scVoucherdetRepository);
        $scVoucherdetCollection = $findAllScVoucherdetUseCase->execute();
        return response()->json(
            ScVoucherdetResource::collection($scVoucherdetCollection)->resolve(),
            200
        );
    }

    public function show(int $id): JsonResponse
    {
        $scVoucherdet = new FindByIdScVoucherdetUseCase($this->scVoucherdetRepository);
        return response()->json(
            new ScVoucherdetResource($scVoucherdet->execute($id)),
            200
        );
    }

    public function store(StoreScVoucherdetRequest $request): JsonResponse
    {
        $scVoucherdetdto = new ScVoucherdetDTO($request->validated());
        $scVoucherdet = new CreateScVoucherdetUseCase($this->scVoucherdetRepository);
        $scVoucherdeta = $scVoucherdet->execute($scVoucherdetdto);

        return response()->json(
            new ScVoucherdetResource($scVoucherdeta),
            201
        );
    }

    public function update(UpdateScVoucherdetRequest $request, int $id): JsonResponse
    {
        $scVoucherdetdto = new ScVoucherdetDTO($request->validated());
        $scVoucherdet = new UpdateScVoucherdetUseCase($this->scVoucherdetRepository);
        $scVoucherdeta = $scVoucherdet->execute($scVoucherdetdto, $id);
        return response()->json(
            new ScVoucherdetResource($scVoucherdeta),
            201
        );
    }
}
