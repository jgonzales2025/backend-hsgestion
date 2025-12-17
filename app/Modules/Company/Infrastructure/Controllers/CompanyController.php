<?php

namespace App\Modules\Company\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Company\Application\DTOS\UpdateCompanyDTO;
use App\Modules\Company\Application\UseCases\FindAllCompanyUseCase;
use App\Modules\Company\Application\UseCases\FindByIdCompanyUseCase;
use App\Modules\Company\Application\UseCases\UpdateCompanyUseCase;
use App\Modules\Company\Application\UseCases\UpdatePasswordUseCase;
use App\Modules\Company\Infrastructure\Model\EloquentCompany;
use App\Modules\Company\Infrastructure\Persistence\EloquentCompanyRepository;
use App\Modules\Company\Infrastructure\Request\UpdateCompanyRequest;
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
    public function index(Request $request): JsonResponse
    {
        $description = $request->query('description');
        $status = $request->query('status') !== null ? (int) $request->query('status') : null;
        $companyUseCase = new FindAllCompanyUseCase($this->companieRepository);
        $company = $companyUseCase->execute($description, $status);
        
        return new JsonResponse([
            'data' => CompanyResource::collection($company)->resolve(),
            'current_page' => $company->currentPage(),
            'per_page' => $company->perPage(),
            'total' => $company->total(),
            'last_page' => $company->lastPage(),
            'next_page_url' => $company->nextPageUrl(),
            'prev_page_url' => $company->previousPageUrl(),
            'first_page_url' => $company->url(1),
            'last_page_url' => $company->url($company->lastPage()),
        ]);
    }

    public function indexByUser(Request $request): JsonResource
    {
        $user_id = $request->query('user_id');

        $company = EloquentCompany::with('assignments')->when($user_id, function ($query) use ($user_id) {
            $query->whereHas('assignments', function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            });
        })->get();

        return CompanyByUserResource::collection($company);
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

    public function update(int $id, UpdateCompanyRequest $request): JsonResponse
    {
        $updateCompanyDTO = new UpdateCompanyDTO($request->validated());
        $updateCompanyUseCase = new UpdateCompanyUseCase($this->companieRepository);
        $updateCompanyUseCase->execute($id, $updateCompanyDTO);

        return response()->json([
            'message' => 'Compañía actualizada exitosamente.',
        ], 200);
    }

}
