<?php
namespace App\Modules\BuildPc\application\UseCases;

use App\Modules\BuildPc\application\DTOS\BuildPcDTO;
use App\Modules\BuildPc\Domain\Entities\BuildPc;
use App\Modules\BuildPc\Domain\Interface\BuildPcRepositoryInterface;

class UpdateBuildPcUseCase
{
    public function __construct(
        private BuildPcRepositoryInterface $buildPcRepository
    ) {}
    public function execute(BuildPcDTO $data ,$id)
    {

      $buildPc = new BuildPc(
          id:$id,
          company_id:$data->company_id,
          name:$data->name,
          description:$data->description,
          user_id:$data->user_id,
          status:$data->status,
      );

      return $this->buildPcRepository->update($buildPc);   
    }
}