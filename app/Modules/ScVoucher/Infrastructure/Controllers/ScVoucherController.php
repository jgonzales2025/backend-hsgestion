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
use App\Modules\ScVoucherdet\application\DTOS\ScVoucherdetDTO;
use App\Modules\ScVoucherdet\application\UseCases\CreateScVoucherdetUseCase;
use App\Modules\ScVoucherdet\application\UseCases\UpdateScVoucherdetUseCase;
use App\Modules\ScVoucherdet\Domain\Interface\ScVoucherdetRepositoryInterface;
use App\Modules\ScVoucherdet\Infrastructure\Resource\ScVoucherdetResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ScVoucherController extends Controller
{
    public function __construct(
        private ScVoucherRepositoryInterface $scVoucherRepository,
        private readonly ScVoucherdetRepositoryInterface $scVoucherdetRepository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $search = $request->query('search');

        $findAllUseCase = new FindAllScVoucherUseCase($this->scVoucherRepository);
        $scVouchers = $findAllUseCase->execute($search);

        // Transform collection to include details
        $data = $scVouchers->getCollection()->map(function ($scVoucher) {
            $details = $this->scVoucherdetRepository->findByVoucherId($scVoucher->getId());

            return array_merge(
                (new ScVoucherResource($scVoucher))->resolve(),
                [
                    'detail_sc_voucher' => ScVoucherdetResource::collection($details)->resolve(),
                ]
            );
        });

        // Return paginated response with navigation URLs
        return new JsonResponse([
            'data' => $data,
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

        // Get voucher details
        $details = $this->scVoucherdetRepository->findByVoucherId($id);

        return response()->json(
            array_merge(
                (new ScVoucherResource($scVoucher))->resolve(),
                [
                    'details' => ScVoucherdetResource::collection($details)->resolve(),
                ]
            ),
            200
        );
    }

    public function store(StoreScVoucherRequest $request): JsonResponse
    {
        $scVoucherDTO = new ScVoucherDTO($request->validated());
        $createUseCase = new CreateScVoucherUseCase($this->scVoucherRepository);
        $scVoucher = $createUseCase->execute($scVoucherDTO);

        $createdetailvoucher = $this->createScVoucherdet($scVoucher, $request->validated()['detail_sc_voucher']);

        return response()->json(
            array_merge(
                (new ScVoucherResource($scVoucher))->resolve(),
                [
                    'detail_sc_voucher' => ScVoucherdetResource::collection($createdetailvoucher)->resolve(),
                ]
            ),
            201
        );
    }

    public function update(UpdateScVoucherRequest $request, int $id): JsonResponse
    {
        $scVoucherDTO = new ScVoucherDTO($request->validated());
        $updateUseCase = new UpdateScVoucherUseCase($this->scVoucherRepository);
        $scVoucher = $updateUseCase->execute($scVoucherDTO, $id);

        if (!$scVoucher) {
            return response()->json(['message' => 'ScVoucher no encontrado'], 404);
        }

        // Update details if provided
        if (isset($request->validated()['detail_sc_voucher'])) {
            // Delete old details
            $this->scVoucherdetRepository->deleteByVoucherId($id);

            // Create new details
            $details = $this->createScVoucherdet($scVoucher, $request->validated()['detail_sc_voucher']);
        } else {
            // Get existing details
            $details = $this->scVoucherdetRepository->findByVoucherId($id);
        }

        return response()->json(
            array_merge(
                (new ScVoucherResource($scVoucher))->resolve(),
                [
                    'detail_sc_voucher' => ScVoucherdetResource::collection($details)->resolve(),
                ]
            ),
            200
        );
    }



    public function createScVoucherdet($voucher, array $data)
    {
        $createdetailvoucher = new CreateScVoucherdetUseCase($this->scVoucherdetRepository);

        return array_map(function ($item) use ($voucher, $createdetailvoucher) {

            // Crear relación purchase - entry_guide
            $scVoucherdetDTO = new ScVoucherdetDTO([
                'cia' => $voucher->getCia(),
                'codcon' => $item['codcon'],
                'tipdoc' => $voucher->getId(),
                'numdoc' => $item['numdoc'],
                'glosa' => $item['glosa'],
                'impsol' => $item['impsol'],
                'impdol' => $item['impdol'],
            ]);

            $svvoucherdetalle = $createdetailvoucher->execute($scVoucherdetDTO);

            return $svvoucherdetalle;
        }, $data);
    }
}
