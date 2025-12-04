<?php

namespace App\Modules\LoginAttempt\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\LoginAttempt\Application\UseCases\FindAllLoginAttemptsUseCase;
use App\Modules\LoginAttempt\Domain\Interfaces\LoginAttemptRepositoryInterface;
use App\Modules\LoginAttempt\Infrastructure\Resources\LoginAttemptResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoginAttemptController extends Controller
{
    public function __construct(private readonly LoginAttemptRepositoryInterface $loginAttemptRepository){}

    public function index(Request $request):JsonResponse
    {
        $description = $request->input('description');
        $roleId = $request->input('role_id');
        $loginAttemptsUseCase = new FindAllLoginAttemptsUseCase($this->loginAttemptRepository);
        $loginAttempts = $loginAttemptsUseCase->execute($description, $roleId);

        return new JsonResponse([
            'data' => LoginAttemptResource::collection($loginAttempts)->resolve(),
            'current_page' => $loginAttempts->currentPage(),
            'per_page' => $loginAttempts->perPage(),
            'total' => $loginAttempts->total(),
            'last_page' => $loginAttempts->lastPage(),
            'next_page_url' => $loginAttempts->nextPageUrl(),
            'prev_page_url' => $loginAttempts->previousPageUrl(),
            'first_page_url' => $loginAttempts->url(1),
            'last_page_url' => $loginAttempts->url($loginAttempts->lastPage()),
        ]);
    }
}
