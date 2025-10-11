<?php


namespace App\Modules\Branch\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Branch\Application\DTOs\BranchDTO;
use App\Modules\Branch\Application\UseCases\FindAllBranchUseCase;
use App\Modules\Branch\Application\UseCases\FindByIdBranchUseCase;

use App\Modules\Branch\Application\UseCases\UpdateBranchUseCase;
use App\Modules\Branch\Infrastructure\Persistence\EloquentBranchRepository;
use App\Modules\Branch\Infrastructure\Requests\UpdateBranchRequest;
use App\Modules\Branch\Infrastructure\Resource\BranchResource;

use Illuminate\Http\JsonResponse;

class BranchController extends Controller
{
    protected $branchRepository;

    public function __construct()
    {
        $this->branchRepository = new EloquentBranchRepository();
    }

    public function index(): array
    {
        $branchUseCase = new FindAllBranchUseCase($this->branchRepository);
        $branches = $branchUseCase->execute();
        return BranchResource::collection($branches)->resolve();
    }

  public function showID(int $id): JsonResponse 
    {
        $branchUseCase = new FindByIdBranchUseCase($this->branchRepository);
        $branch = $branchUseCase->execute($id);

        return response()->json(
            (new BranchResource($branch))->resolve(),
            200
        );
    }

public function show(int $cia_id): JsonResponse
{
    $branches = $this->branchRepository->findByCiaId($cia_id);

    if (empty($branches)) {
        return response()->json(['error' => 'Branches not found'], 404);
    }

    return response()->json(
        BranchResource::collection($branches),
        200
    );
}
    public function update(UpdateBranchRequest $request, int $id): JsonResponse
    {
        $branchDTO = new BranchDTO(array_merge(
            $request->validated(),
            ['id' => $id]
        ));

        $branchUseCase = new UpdateBranchUseCase($this->branchRepository);
        $branchUseCase->execute($id, $branchDTO);

        $driver = $this->branchRepository->findById($id);

        return response()->json(
            (new BranchResource($driver))->resolve(),
            200
        );
    }
}