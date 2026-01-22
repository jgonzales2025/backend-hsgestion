<?php

namespace App\Modules\ScVoucher\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Bank\Domain\Interfaces\BankRepositoryInterface; 
use App\Modules\ScVoucher\Application\DTOS\ScVoucherDTO;
use App\Modules\ScVoucher\Application\UseCases\CreateScVoucherUseCase;
use App\Modules\ScVoucher\Application\UseCases\FindAllScVoucherUseCase;
use App\Modules\ScVoucher\Application\UseCases\FindByIdScVoucherUseCase;
use App\Modules\ScVoucher\Application\UseCases\UpdateScVoucherUseCase;
use App\Modules\ScVoucher\Application\UseCases\UpdateStatusScVoucherUseCase;
use App\Modules\ScVoucher\Domain\Interface\ScVoucherRepositoryInterface; 
use App\Modules\ScVoucher\Infrastructure\Request\StoreScVoucherRequest;
use App\Modules\ScVoucher\Infrastructure\Request\UpdateScVoucherRequest;
use App\Modules\ScVoucher\Infrastructure\Request\UpdateStatusScVoucherRequest; 
use App\Modules\ScVoucher\Infrastructure\Resource\ScVoucherResource; 
use App\Modules\ScVoucherdet\Domain\Interface\ScVoucherdetRepositoryInterface;
use App\Modules\ScVoucherdet\Infrastructure\Resource\ScVoucherdetResource;
use App\Services\DocumentNumberGeneratorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;
use App\Modules\CurrencyType\Domain\Interfaces\CurrencyTypeRepositoryInterface;
use App\Modules\PaymentMethodsSunat\Domain\Interface\PaymentMethodSunatRepositoryInterface;
use App\Modules\PaymentType\Domain\Interfaces\PaymentTypeRepositoryInterface;

class ScVoucherController extends Controller
{
    public function __construct(
        private ScVoucherRepositoryInterface $scVoucherRepository,
        private readonly ScVoucherdetRepositoryInterface $scVoucherdetRepository,
        private DocumentNumberGeneratorService $documentNumberGeneratorService, 
        private CustomerRepositoryInterface $customerRepository,
        private CurrencyTypeRepositoryInterface $currencyTypeRepository,
        private PaymentMethodSunatRepositoryInterface $paymentMethodSunatRepository,
        private PaymentTypeRepositoryInterface $paymentTypeRepository,
        private BankRepositoryInterface $bankRepository,
    ) {}
 
    public function index(Request $request): JsonResponse
    {
        $search = $request->query('description');
        $status = $request->query('status');

        $findAllUseCase = new FindAllScVoucherUseCase($this->scVoucherRepository);
        $scVouchers = $findAllUseCase->execute($search, $status); 

        return new JsonResponse([
            'data' => ScVoucherResource::collection($scVouchers)->resolve(),
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
            return response()->json(['message' => 'Voucher no encontrado'], 404);
        } 
        return response()->json(
            (new ScVoucherResource($scVoucher))->resolve(),
            200
        );
    }

    public function store(StoreScVoucherRequest $request): JsonResponse
    {
        $scVoucherDTO = new ScVoucherDTO($request->validated());
        $createUseCase = new CreateScVoucherUseCase(
            $this->scVoucherRepository,
            $this->documentNumberGeneratorService,
            $this->customerRepository,
            $this->currencyTypeRepository,
            $this->paymentMethodSunatRepository,
            $this->paymentTypeRepository,
            $this->bankRepository
        );
        $scVoucher = $createUseCase->execute($scVoucherDTO);

        return response()->json((new ScVoucherResource($scVoucher))->resolve(), 201);
    }

    public function update(UpdateScVoucherRequest $request, int $id): JsonResponse
    {
        $scVoucherDTO = new ScVoucherDTO($request->validated());
        $updateUseCase = new UpdateScVoucherUseCase(
            $this->scVoucherRepository,
            $this->documentNumberGeneratorService,
            $this->customerRepository,
            $this->currencyTypeRepository,
            $this->paymentMethodSunatRepository,
            $this->paymentTypeRepository,
            $this->bankRepository
        );
        $scVoucher = $updateUseCase->execute($scVoucherDTO, $id); 

        if (!$scVoucher) {
            return response()->json(['message' => 'Voucher no encontrado'], 404);
        }
        return response()->json((new ScVoucherResource($scVoucher))->resolve(), 200);
    }
 
    public function updateStatus(int $id, UpdateStatusScVoucherRequest $request): JsonResponse
    {
        $updateStatusUseCase = new UpdateStatusScVoucherUseCase($this->scVoucherRepository);
        $scVoucher = $updateStatusUseCase->execute($id, $request->validated()['status']);

        if (!$scVoucher) {
            return response()->json(['message' => 'Voucher no encontrado'], 404);
        }

        return response()->json((new ScVoucherResource($scVoucher))->resolve(), 200);
    }

    public function getdetVoucher(int $id): JsonResponse
    {
        $findByIdUseCase = new FindByIdScVoucherUseCase($this->scVoucherRepository);

        $detailsByPurchase = $this->scVoucherdetRepository->getvoucherPurchase($id);
        if (empty($detailsByPurchase)) {
            return response()->json([], 200);
        }

        $groupedByVoucher = collect($detailsByPurchase)
            ->groupBy(fn($detail) => $detail->getIdScVoucher())
            ->filter(fn($details, $voucherId) => $voucherId !== null);

        $vouchersData = $groupedByVoucher->map(function ($details, $voucherId) use ($findByIdUseCase) {
            $voucher = $findByIdUseCase->execute((int) $voucherId);
            if (!$voucher) return null;

            return array_merge(
                (new ScVoucherResource($voucher))->resolve(),
                [
                    'detail_sc_voucher' => ScVoucherdetResource::collection($details)->resolve(),
        ]
            );
        })->filter()->values()->all();

        return response()->json($vouchersData, 200);
    }
}
