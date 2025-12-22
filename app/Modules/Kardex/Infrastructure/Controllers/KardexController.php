<?php

namespace App\Modules\Kardex\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;
use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;
use App\Modules\Kardex\Application\DTOS\KardexDTO;
use App\Modules\Kardex\Application\UseCases\CreateKardexUseCase;
use App\Modules\Kardex\Application\UseCases\FindAllKardexUseCase;
use App\Modules\Kardex\Application\UseCases\FindByIdKardexUseCase;
use App\Modules\Kardex\Application\UseCases\UpdateKardexUseCase;
use App\Modules\Kardex\Domain\Interface\KardexRepositoryInterface;
use App\Modules\Kardex\Infrastructure\Request\StoreKardexRequest;
use App\Modules\Kardex\Infrastructure\Request\UpdateKardexRequest;
use App\Modules\Kardex\Infrastructure\Resource\KardexResource;
use App\Modules\Kardex\Infrastructure\Persistence\GenerateExcel;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Http\Request;


class KardexController extends Controller
{
    public function __construct(
        private readonly KardexRepositoryInterface $kardexRepository,
        private readonly BranchRepositoryInterface $branchRepository,
        private readonly CompanyRepositoryInterface $companyRepository
    ) {}

    public function index(): JsonResponse
    {
        $kardexesUseCase = new FindAllKardexUseCase($this->kardexRepository);
        $kardexes = $kardexesUseCase->execute();

        return response()->json([
            KardexResource::collection($kardexes)->resolve(),
        ]);
    }

    public function store(StoreKardexRequest $request): JsonResponse
    {
        $kardexDTO = new KardexDTO($request->all());
        $kardexUseCase = new CreateKardexUseCase(
            $this->kardexRepository,
            $this->branchRepository,
            $this->companyRepository,
        );
        $kardex = $kardexUseCase->execute($kardexDTO);

        return response()->json([
            (new KardexResource($kardex))->resolve(),
        ]);
    }

    public function update(UpdateKardexRequest $request, $id): JsonResponse
    {
        $kardexDTO = new KardexDTO($request->validated());
        $kardexUseCase = new UpdateKardexUseCase(
            $this->kardexRepository,
            $this->companyRepository,
            $this->branchRepository,
        );
        $kardex = $kardexUseCase->execute($kardexDTO, $id);

        return response()->json([
            (new KardexResource($kardex))->resolve(),
        ]);
    }

    public function show($id): JsonResponse
    {
        $kardexUseCase = new FindByIdKardexUseCase($this->kardexRepository);
        $kardex = $kardexUseCase->execute($id);
        return response()->json([
            (new KardexResource($kardex))->resolve(),
        ]);
    }
    public function getKardexByProduct(Request $request): JsonResponse
    {
      
        $validated = $request->validate([
            'product_id' => 'required|integer',
            'company_id' => 'required|integer',
            'branch_id'  => 'required|integer',
            'fecha'      => 'required|date',
            'fecha1'     => 'required|date',
            // 'categoria'  => 'required|integer',
            // 'marca'      => 'nullable|integer',
        ]);


         $companyId = request()->get('company_id');
        $validated['company_id'] = $companyId;

    
        $kardex = $this->kardexRepository->getKardexByProductId(
            productId: (int) $validated['product_id'],
            companyId: (int) $validated['company_id'],
            branchId: (int) $validated['branch_id'],
            fecha: $validated['fecha'],
            fecha1: $validated['fecha1'],
            // categoria: (int) $validated['categoria'],
            // marca: (int) $validated['marca'],
        );

        return response()->json([
            'success' => true,
            'data' => $kardex
        ]);
    }
    public function generateExcel(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer',
            'company_id' => 'required|integer',
            'branch_id'  => 'required|integer',
            'fecha'      => 'required|date',
            'fecha1'     => 'required|date',
            'categoria'  => 'required|integer',
            'marca'      => 'nullable|integer',
            'consulta'   => 'required|integer',
        ]);

        $companyId = request()->get('company_id');
        $validated['company_id'] = $companyId;

        $export = new GenerateExcel(
            companyId: $companyId,
            branchId: (int) $validated['branch_id'],
            productId: (int) $validated['product_id'],
            fecha: $validated['fecha'],
            fecha1: $validated['fecha1'],
            categoria: (int) $validated['categoria'],
            marca: isset($validated['marca']) ? (int) $validated['marca'] : 0,
            consulta: isset($validated['consulta']) ? (int) $validated['consulta'] : 1,
            title: 'Kardex Físico - Producto ' . $validated['product_id'] . ' - ' . $validated['fecha'] . ' a ' . $validated['fecha1'],
        );
        $fileName = 'kardex_' . $validated['product_id'] . '_' . date('YmdHis') . '.xlsx';
        return Excel::download($export, $fileName);
    }
}
