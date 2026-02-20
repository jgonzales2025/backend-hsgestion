<?php

namespace App\Modules\Kardex\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Articles\Infrastructure\Models\EloquentArticle;
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
use App\Modules\Kardex\Infrastructure\Persistence\SaldoArticuloExport;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

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
            'company_id' => 'nullable|integer',
            'branch_id'  => 'nullable|integer',
            'product_id' => 'nullable|integer',
            'fecha'      => 'required|date',
            'fecha1'     => 'required|date',
            'categoria'  => 'nullable|integer',
            'marca'      => 'nullable|integer',
            'consulta'   => 'nullable|integer',
        ]);


        $companyId = request()->get('company_id');

        $validated['company_id'] = $companyId;


        $kardex = $this->kardexRepository->getKardexByProductId(
            companyId: (int) $validated['company_id'] ?? 1,
            productId: (int) ($validated['product_id'] ?? 0),
            branchId: (int) ($validated['branch_id'] ?? 0),
            fecha: $validated['fecha'],
            fecha1: $validated['fecha1'],
            categoria: (int) ($validated['categoria'] ?? 0),
            marca: (int) ($validated['marca'] ?? 0),
            consulta: (int) ($validated['consulta'] ?? 1),
        );
        if ($kardex === []) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontraron resultados',
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $kardex
        ]);
    }

public function consultaSaldoPorArticulo(Request $request)
{
    $request->validate([
        'sucursal'  => 'required|integer',
        'fecha'     => 'required|date_format:Y-m-d',
        'fecha1'    => 'required|date_format:Y-m-d|after_or_equal:fecha',
        'categoria' => 'sometimes|integer',
        'marca'     => 'sometimes|integer',
        'status'    => 'nullable|integer',
    ]);

    $companyId = request()->get('company_id', 1);

    $resultado = DB::select('CALL backend_hsgestion_test.sp_lista_articulos_saldo(?, ?, ?, ?, ?, ?, ?)', [
        $companyId,
        $request->input('sucursal'),
        $request->input('fecha'),
        $request->input('fecha1'),
        $request->input('categoria', 0),
        $request->input('marca',     0),
        $request->input('status'),
    ]);

    foreach ($resultado as $item) {
        $item->estado = $item->estado == 1 ? 'ACTIVO' : 'INACTIVO';
    }

    $perPage = $request->input('per_page', 10);
    $datos   = $this->paginateResult($resultado, $perPage);

    return response()->json([
        'success'        => true,
        'data'           => $datos->items(),
        'current_page'   => $datos->currentPage(),
        'per_page'       => $datos->perPage(),
        'total'          => $datos->total(),
        'last_page'      => $datos->lastPage(),
        'next_page_url'  => $datos->nextPageUrl(),
        'prev_page_url'  => $datos->previousPageUrl(),
        'first_page_url' => $datos->url(1),
        'last_page_url'  => $datos->url($datos->lastPage()),
    ]);
}
    public function consultaSaldoPorArticuloExcel(Request $request)
    {
        $request->validate([
            'sucursal'  => 'required|integer',
            'fecha'     => 'required|date_format:Y-m-d',
            'fecha1'    => 'required|date_format:Y-m-d|after_or_equal:fecha',
            'categoria' => 'sometimes|integer',
            'marca'     => 'sometimes|integer',
            'status'    => 'nullable|integer',
        ]);

        $companyId = request()->get('company_id', 1);
        $company = $this->companyRepository->findById($companyId);
        $companyName = $company ? $company->getCompanyName() : 'CYBERHOUSE TEC S.A.C.';

        $export = new SaldoArticuloExport(
            companyId: $companyId,
            branchId: (int) $request->input('sucursal'),
            fecha: $request->input('fecha'),
            fecha1: $request->input('fecha1'),
            categoria: (int) $request->input('categoria', 0),
            marca: (int) $request->input('marca', 0),
            status: (int) $request->input('status'),
            companyName: $companyName
        );

        if ($export->isEmpty()) {
            return response()->json([
                'message' => 'No se encontró información',
            ], 404);
        }

        $fileName = 'saldo_articulos_' . date('YmdHis') . '.xlsx';
        return Excel::download($export, $fileName);
    }

    public function generateExcel(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'nullable|integer',
            'company_id' => 'nullable|integer',
            'branch_id'  => 'nullable|integer',
            'fecha'      => 'nullable|date',
            'fecha1'     => 'nullable|date',
            'categoria'  => 'nullable|integer',
            'marca'      => 'nullable|integer',
            'consulta'   => 'nullable|integer',
        ]);

        $companyId = request()->get('company_id');
        $validated['company_id'] = $companyId;

        $productId = $validated['product_id'] ?? null;
        $fecha = $validated['fecha'] ?? null;
        $fecha1 = $validated['fecha1'] ?? null;

        $title = 'Kardex Físico';
        if ($productId) $title .= ' - Producto ' . $productId;
        if ($fecha && $fecha1) $title .= ' - ' . $fecha . ' a ' . $fecha1;

        $company = $this->companyRepository->findById($companyId ?? 1);
        $companyName = $company ? $company->getCompanyName() : 'CYBERHOUSE TEC S.A.C.';

        $export = new GenerateExcel(
            companyId: $companyId ?? 1,
            branchId: isset($validated['branch_id']) ? (int) $validated['branch_id'] : 0,
            productId: $productId ? (int) $productId : 0,
            fecha: $fecha,
            fecha1: $fecha1,
            categoria: isset($validated['categoria']) ? (int) $validated['categoria'] : 0,
            marca: isset($validated['marca']) ? (int) $validated['marca'] : 0,
            consulta: isset($validated['consulta']) ? (int) $validated['consulta'] : 2,
            title: $title,
            companyName: $companyName,
        );

        if ($export->isEmpty()) {
            return response()->json([
                'message' => 'No se encontró información',
            ], 404);
        }

        $fileName = 'kardex_' . ($productId ?? 'general') . '_' . date('YmdHis') . '.xlsx';
        return Excel::download($export, $fileName);
    }

    private function paginateResult(array $items, $perPage = 10): LengthAwarePaginator
    {
        $page = request()->get('page', 1);
        $itemsCollection = collect($items);

        $pagedItems = $itemsCollection->slice(($page - 1) * $perPage, $perPage)->values();

        return new LengthAwarePaginator(
            $pagedItems,
            $itemsCollection->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }
}
