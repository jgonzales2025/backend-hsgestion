<?php

namespace App\Modules\Company\Infrastructure\Controllers;

use App\Http\Controllers\Controller;

use App\Modules\Company\Application\UseCases\FindAllCompanyUseCase;
use App\Modules\Company\Application\UseCases\FindByIdCompanyUseCase;
use App\Modules\Company\Domain\Entities\Company;
use App\Modules\Company\Infrastructure\Model\EloquentCompany;
use App\Modules\Company\Infrastructure\Persistence\EloquentCompanyRepository;


use App\Modules\Company\Infrastructure\Resources\CompanyByUserResource;
use App\Modules\Company\Infrastructure\Resources\CompanyResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyController extends Controller
{

      protected $companieRepository;

    public function __construct()
    {
        $this->companieRepository = new EloquentCompanyRepository();
    }
    public function index(): array
    {
          $companyUseCase = new FindAllCompanyUseCase($this->companieRepository);
        $company = $companyUseCase->execute();
            // Log::info('companys', $company);
        return CompanyResource::collection($company)->resolve();
    }
     
    public function indexByUser(Request $request): JsonResource
    {
        $user_id = $request->query('user_id');

        $company = EloquentCompany::with('assignments')->when($user_id, function ($query) use ($user_id) {
            $query->whereHas('assignments', function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            });
        })->get();

        return CompanyByUserResource    ::collection($company);
    }

   public function show(int $id): JsonResponse 
    {
        $branchUseCase = new FindByIdCompanyUseCase($this->companieRepository);
        $branch = $branchUseCase->execute($id);

        return response()->json(
            (new CompanyResource($branch))->resolve(),
            200
        );
    }
}
