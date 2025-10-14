<?php

namespace App\Http\Controllers;

use App\Http\Resources\CompanyByUserResource;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Modules\UserAssignment\Infrastructure\Models\EloquentUserAssignment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyController extends Controller
{
    public function index(): JsonResource
    {
        $companies = Company::all();

        return CompanyResource::collection($companies);
    }

    public function indexByUser(Request $request): JsonResource
    {
        $user_id = $request->query('user_id');

        $company = Company::with('assignments')->when($user_id, function ($query) use ($user_id) {
            $query->whereHas('assignments', function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            });
        })->get();

        return CompanyByUserResource::collection($company);
    }

    public function show($id): JsonResponse
    {
        $company = Company::with('branches')->find($id);

        if (!$company) {
            return response()->json(['error' => 'Company not found'], 404);
        }

        return response()->json(
            (new CompanyResource($company))->resolve(), 201);
    }
}
