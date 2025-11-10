<?php

namespace App\Modules\EntryGuides\Infrastructure\Controllers;

use App\Http\Controllers\Controller;

use App\Modules\EntryGuides\Application\UseCases\FindAllEntryGuideUseCase;
use App\Modules\EntryGuides\Application\UseCases\FindByIdEntryGuideUseCase;
use App\Modules\EntryGuides\Domain\Interfaces\EntryGuideRepositoryInterface;
use App\Modules\EntryGuides\Infrastructure\Request\EntryGuideRequest;
use App\Modules\EntryGuides\Infrastructure\Resource\EntryGuideResource;
use Illuminate\Http\JsonResponse;


class ControllerEntryGuide extends Controller
{

    public function __construct(private readonly EntryGuideRepositoryInterface $entryGuideRepositoryInterface)
    {
    }

    public function index(): JsonResponse
    {
        $entryGuideUseCase = new FindAllEntryGuideUseCase($this->entryGuideRepositoryInterface);
        $entryGuide = $entryGuideUseCase->execute();

        return response()->json(
          EntryGuideResource::collection($entryGuide)->resolve(),
         200);
    }
    public function show($id): JsonResponse
    {
        $entryGuideUseCase = new FindByIdEntryGuideUseCase($this->entryGuideRepositoryInterface);
        $entryGuide = $entryGuideUseCase->execute($id);

        return response()->json(
            (new EntryGuideResource($entryGuide))->resolve(),
            200
        );
    }
    public function store(EntryGuideRequest $request): JsonResponse
    {
    }
    public function update(UpdateEntryGuideRequest $request, $id): JsonResponse
    {
    }

}