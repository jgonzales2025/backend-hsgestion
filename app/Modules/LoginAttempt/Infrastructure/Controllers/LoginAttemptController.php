<?php

namespace App\Modules\LoginAttempt\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\LoginAttempt\Application\UseCases\FindAllLoginAttemptsUseCase;
use App\Modules\LoginAttempt\Domain\Interfaces\LoginAttemptRepositoryInterface;
use App\Modules\LoginAttempt\Infrastructure\Resources\LoginAttemptResource;

class LoginAttemptController extends Controller
{
    public function __construct(private readonly LoginAttemptRepositoryInterface $loginAttemptRepository){}

    public function index(): array
    {
        $loginAttemptsUseCase = new FindAllLoginAttemptsUseCase($this->loginAttemptRepository);
        $loginAttempts = $loginAttemptsUseCase->execute();

        return LoginAttemptResource::collection($loginAttempts)->resolve();
    }
}
