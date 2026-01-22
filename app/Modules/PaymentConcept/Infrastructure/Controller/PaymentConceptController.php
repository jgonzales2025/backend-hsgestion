<?php

namespace App\Modules\PaymentConcept\Infrastructure\Controller;

use App\Http\Controllers\Controller;
use App\Modules\PaymentConcept\Application\UseCases\CreatePaymentConceptUseCase;
use App\Modules\PaymentConcept\Application\UseCases\FindAllPaymentConceptsUseCase;
use App\Modules\PaymentConcept\Application\UseCases\FindAllInfinityPaymentConceptsUseCase;
use App\Modules\PaymentConcept\Application\UseCases\FindByIdPaymentConceptUseCase;
use App\Modules\PaymentConcept\Application\UseCases\UpdatePaymentConceptUseCase;
use App\Modules\PaymentConcept\Application\UseCases\UpdateStatusPaymentConceptUseCase;
use App\Modules\PaymentConcept\Domain\Interfaces\PaymentConceptRepositoryInterface;
use App\Modules\PaymentConcept\Infrastructure\Requests\StorePaymentConceptRequest;
use App\Modules\PaymentConcept\Infrastructure\Requests\UpdatePaymentConceptRequest;
use App\Modules\PaymentConcept\Infrastructure\Resources\PaymentConceptResource;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class PaymentConceptController extends Controller
{
    public function __construct(
        private PaymentConceptRepositoryInterface $paymentConceptRepository
    ) {}

    public function index(Request $request): JsonResponse
    {
        $description = $request->query('description');
        $status = $request->query('status') !== null ? (int) $request->query('status') : null;

        $paymentConceptsUseCase = new FindAllPaymentConceptsUseCase($this->paymentConceptRepository);
        $paymentConcepts = $paymentConceptsUseCase->execute($description, $status);

        return new JsonResponse([
            'data' => PaymentConceptResource::collection($paymentConcepts)->resolve(),
            'current_page' => $paymentConcepts->currentPage(),
            'per_page' => $paymentConcepts->perPage(),
            'total' => $paymentConcepts->total(),
            'last_page' => $paymentConcepts->lastPage(),
            'next_page_url' => $paymentConcepts->nextPageUrl(),
            'prev_page_url' => $paymentConcepts->previousPageUrl(),
            'first_page_url' => $paymentConcepts->url(1),
            'last_page_url' => $paymentConcepts->url($paymentConcepts->lastPage()),
        ]);
    }

    public function findAllInfinity(Request $request): JsonResponse
    {
        $description = $request->query('description');
        $status = $request->query('status') !== null ? (int) $request->query('status') : null;

        $paymentConceptsUseCase = new FindAllInfinityPaymentConceptsUseCase($this->paymentConceptRepository);
        $paymentConcepts = $paymentConceptsUseCase->execute($description, $status);

        return new JsonResponse([
            'data' => PaymentConceptResource::collection($paymentConcepts)->resolve(),
            'next_cursor' => $paymentConcepts->nextCursor()?->encode(),
            'prev_cursor' => $paymentConcepts->previousCursor()?->encode(),
            'next_page_url' => $paymentConcepts->nextPageUrl(),
            'prev_page_url' => $paymentConcepts->previousPageUrl(),
            'per_page' => $paymentConcepts->perPage()
        ]);
    }

    public function store(StorePaymentConceptRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        $paymentConceptUseCase = new CreatePaymentConceptUseCase($this->paymentConceptRepository);
        $paymentConceptUseCase->execute($validatedData);

        return response()->json([
            'message' => 'Concepto de pago creado exitosamente',
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $paymentConceptUseCase = new FindByIdPaymentConceptUseCase($this->paymentConceptRepository);
        $paymentConcept = $paymentConceptUseCase->execute($id);

        if (!$paymentConcept) {
            return response()->json(['message' => 'Concepto de pago no encontrado'], 404);
        }

        return response()->json((new PaymentConceptResource($paymentConcept))->resolve(), 200);
    }

    public function update(int $id, UpdatePaymentConceptRequest $request): JsonResponse
    {
        $paymentConceptUseCase = new FindByIdPaymentConceptUseCase($this->paymentConceptRepository);
        $paymentConcept = $paymentConceptUseCase->execute($id);

        if (!$paymentConcept) {
            return response()->json(['message' => 'Concepto de pago no encontrado'], 404);
        }

        $paymentConceptUseCase = new UpdatePaymentConceptUseCase($this->paymentConceptRepository);
        $paymentConceptUseCase->execute($id, $request->validated());

        return response()->json([
            'message' => 'Concepto de pago actualizado exitosamente',
        ], 200);
    }

    public function updateStatus(int $id, Request $request): JsonResponse
    {
        $paymentConceptUseCase = new FindByIdPaymentConceptUseCase($this->paymentConceptRepository);
        $paymentConcept = $paymentConceptUseCase->execute($id);

        if (!$paymentConcept) {
            return response()->json(['message' => 'Concepto de pago no encontrado'], 404);
        }

        $validatedData = $request->validate([
            'status' => 'required|integer|in:0,1',
        ]);

        $paymentConceptUseCase = new UpdateStatusPaymentConceptUseCase($this->paymentConceptRepository);
        $paymentConceptUseCase->execute($id, $validatedData['status']);

        return response()->json([
            'message' => 'Concepto de pago actualizado exitosamente',
        ], 200);
    }
}
