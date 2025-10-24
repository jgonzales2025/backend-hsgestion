<?php

namespace App\Modules\Collections\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Collections\Application\DTOs\CollectionDTO;
use App\Modules\Collections\Application\UseCases\CreateCollectionUseCase;
use App\Modules\Collections\Application\UseCases\FindAllCollectionsUseCase;
use App\Modules\Collections\Domain\Interfaces\CollectionRepositoryInterface;
use App\Modules\Collections\Infrastructure\Requests\StoreCollectionRequest;
use App\Modules\Collections\Infrastructure\Resources\CollectionResource;
use App\Modules\PaymentMethod\Domain\Interfaces\PaymentMethodRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class CollectionController extends Controller
{
    public function __construct(
        private readonly CollectionRepositoryInterface $collectionRepository,
        private readonly PaymentMethodRepositoryInterface $paymentMethodRepository,
    ){}

    public function index(): array
    {
        $collectionUseCase = new FindAllCollectionsUseCase($this->collectionRepository);
        $collections = $collectionUseCase->execute();

        return CollectionResource::collection($collections)->resolve();
    }

    public function store(StoreCollectionRequest $request): JsonResponse
    {
        $collectionDTO = new CollectionDTO($request->validated());
        $collectionUseCase = new CreateCollectionUseCase($this->collectionRepository, $this->paymentMethodRepository);
        $collection = $collectionUseCase->execute($collectionDTO);

        return response()->json((new CollectionResource($collection))->resolve(), 201);
    }
}
