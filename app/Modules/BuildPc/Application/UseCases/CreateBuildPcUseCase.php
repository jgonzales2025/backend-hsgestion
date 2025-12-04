<?php

namespace App\Modules\BuildPc\Application\UseCases;

use App\Modules\Articles\Application\UseCases\FindByIdArticleUseCase;
use App\Modules\Articles\Domain\Interfaces\ArticleRepositoryInterface;
use App\Modules\BuildPc\Application\DTOS\BuildPcDTO;
use App\Modules\BuildPc\Domain\Entities\BuildPc;
use App\Modules\BuildPc\Domain\Interface\BuildPcRepositoryInterface;

class CreateBuildPcUseCase
{
    public function __construct(
        private readonly BuildPcRepositoryInterface $buildPcRepository,
   ) {}
    public function execute(BuildPcDTO $data): ?BuildPc
    {


        $buildPc = new BuildPc(
            id: 0,
            name: $data->name,
            description: $data->description,
            total_price: $data->total_price,
            user_id: $data->user_id,
            status: $data->status,
        );

        return $this->buildPcRepository->create($buildPc);
    }
}
