<?php

namespace App\Modules\PettyCashMotive\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\DocumentType\Domain\Interfaces\DocumentTypeRepositoryInterface;
use App\Modules\PettyCashMotive\Application\DTOS\PettyCashMotiveDTO;
use App\Modules\PettyCashMotive\Application\UseCases\CreatePettyCashMotive;
use App\Modules\PettyCashMotive\Application\UseCases\FindAllPettyCashMotive;
use App\Modules\PettyCashMotive\Application\UseCases\FindByIdPettyCashMotive;
use App\Modules\PettyCashMotive\Application\UseCases\UpdatePettyCashMotiveUseCase;
use App\Modules\PettyCashMotive\Application\UseCases\UpdateStatusCashMotiveUseCase;
use App\Modules\PettyCashMotive\Domain\Interface\PettyCashMotiveInterfaceRepository;
use App\Modules\PettyCashMotive\Infrastructure\Request\CreatePettyCashMotiveRequest;
use App\Modules\PettyCashMotive\Infrastructure\Request\UpdatePettyCashMotiveRequest;
use App\Modules\PettyCashMotive\Infrastructure\Resource\PettyCashMotiveResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PettyCashMotiveController extends Controller
{
    public function __construct(
        private readonly PettyCashMotiveInterfaceRepository $pettyCashMotiveInterfaceRepository,
        private readonly DocumentTypeRepositoryInterface $documentTypeInterfaceRepository
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $description = $request->query('description');
        $receipt_type = $request->query('receipt_type');
        $status = $request->query('status') !== null ? (int) $request->query('status') : null;

        $finAllPettyCashMotive = new FindAllPettyCashMotive($this->pettyCashMotiveInterfaceRepository);
        $pettyCashMotives = $finAllPettyCashMotive->execute($description, $receipt_type, $status);

        return new JsonResponse([
            'data' => PettyCashMotiveResource::collection($pettyCashMotives)->resolve(),
            'current_page' => $pettyCashMotives->currentPage(),
            'per_page' => $pettyCashMotives->perPage(),
            'total' => $pettyCashMotives->total(),
            'last_page' => $pettyCashMotives->lastPage(),
            'next_page_url' => $pettyCashMotives->nextPageUrl(),
            'prev_page_url' => $pettyCashMotives->previousPageUrl(),
            'first_page_url' => $pettyCashMotives->url(1),
            'last_page_url' => $pettyCashMotives->url($pettyCashMotives->lastPage()),
        ]);
    }


    public function indexByReceiptTypeInfinite(int $id, Request $request): JsonResponse
    {
        $paginator = $this->pettyCashMotiveInterfaceRepository->findByReceiptTypeInfinite($id, $request->query('description'));

        return new JsonResponse([
            'data' => collect($paginator->items())->map(fn ($motive) => [
                'id' => $motive->id,
                'name' => $motive->description,
                'receipt_type_id' => $motive->receipt_type,
                'receipt_type_name' => $motive->documentType?->description,
                'status' => $motive->status == 1 ? 'Activo' : 'Inactivo',
            ]),
            'next_cursor' => $paginator->nextCursor()?->encode(),
            'prev_cursor' => $paginator->previousCursor()?->encode(),
            'next_page_url' => $paginator->nextPageUrl(),
            'prev_page_url' => $paginator->previousPageUrl(),
            'per_page' => $paginator->perPage(),
        ]);
    }

    public function store(CreatePettyCashMotiveRequest $request): JsonResponse
    {
        $pettyCashMotiveDTO = new PettyCashMotiveDTO($request->validated());
        $createPettyCashMotive = new CreatePettyCashMotive($this->pettyCashMotiveInterfaceRepository, $this->documentTypeInterfaceRepository);
        $pettyCashMotive = $createPettyCashMotive->execute($pettyCashMotiveDTO);

        return response()->json(
            new PettyCashMotiveResource($pettyCashMotive),
            201
        );
    }

    public function show(int $id)
    {
        $findByIdPettyCashMotive = new FindByIdPettyCashMotive($this->pettyCashMotiveInterfaceRepository);
        $pettyCashMotive = $findByIdPettyCashMotive->execute($id);
        if (!$pettyCashMotive) {
            return response()->json(["message" => "chas motive no encontrada"]);
        }

        return response()->json(
            new PettyCashMotiveResource($pettyCashMotive),
            200
        );
    }

    public function update(int $id, UpdatePettyCashMotiveRequest $request): JsonResponse
    {
        $pettyCashMotiveDTO = new PettyCashMotiveDTO($request->validated());
        $updatePettyCashMotive = new UpdatePettyCashMotiveUseCase($this->pettyCashMotiveInterfaceRepository, $this->documentTypeInterfaceRepository);
        $pettyCashMotive = $updatePettyCashMotive->execute($pettyCashMotiveDTO, $id);

        return response()->json(
            new PettyCashMotiveResource($pettyCashMotive),
            200
        );
    }

    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $status = $request->input('status');
        $updateStatusCashMotiveUseCase = new UpdateStatusCashMotiveUseCase($this->pettyCashMotiveInterfaceRepository);
        $updateStatusCashMotiveUseCase->execute($id, $status);

        return response()->json([
            'message' => 'Estado actualizado correctamente',
        ], 200);
    }
}
