<?php

namespace App\Modules\BuildDetailPc\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\BuildDetailPc\application\UseCases\CreateBuildDetailPcUseCase;
use App\Modules\BuildDetailPc\application\UseCases\FindAllBuildDetailPcUseCase;
use App\Modules\BuildDetailPc\application\UseCases\FindByIdBuildDetailPcUseCase;
use App\Modules\BuildDetailPc\application\UseCases\UpdateBuildDetailPcUseCase;
use App\Modules\BuildDetailPc\Domain\Interface\BuildDetailPcRepositoryInterface;
use App\Modules\BuildDetailPc\Infrastructure\Request\CreateBuildDetailPcRequest;
use App\Modules\BuildDetailPc\Infrastructure\Request\UpdateBuildDetailPcRequest;

class BuildDetailPcController extends Controller
{
    public function __construct(private readonly BuildDetailPcRepositoryInterface $buildDetailPcRepositoryInterface){}
    
    
    public function index()
    {
        $buildDetailPc = new FindAllBuildDetailPcUseCase($this->buildDetailPcRepositoryInterface);
        $build = $buildDetailPc->execute();

        return response()->json($build);
    }
    public function store(CreateBuildDetailPcRequest $buildDetailPc)
    {
        $buildDetailPc = $buildDetailPc->validated();
        $buildDetailPcr = new CreateBuildDetailPcUseCase($this->buildDetailPcRepositoryInterface);
        $build = $buildDetailPcr->execute($buildDetailPc);

        

        return response()->json($build);
    }
    public function show(int $id)
    {
        $buildDetailPc = new FindByIdBuildDetailPcUseCase($this->buildDetailPcRepositoryInterface);
        $build = $buildDetailPc->execute($id);

        return response()->json($build);
    }
    public function update(UpdateBuildDetailPcRequest $buildDetailPc, int $id)
    {
        $buildDetailPc = $buildDetailPc->validated();
        $buildDetailPcr = new UpdateBuildDetailPcUseCase($this->buildDetailPcRepositoryInterface);
        $build = $buildDetailPcr->execute($buildDetailPc, $id);

        return response()->json($build);
    }


}
