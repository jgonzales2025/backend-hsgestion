<?php

namespace App\Modules\User\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\User\Application\DTOs\UserDTO;
use App\Modules\User\Application\UseCases\CreateUserUseCase;
use App\Modules\User\Application\UseCases\FindAllUsersUseCase;
use App\Modules\User\Application\UseCases\FindAllUserUseNameCase;
use App\Modules\User\Application\UseCases\GetUserByIdUseCase;
use App\Modules\User\Infrastructure\Model\EloquentUser;
use App\Modules\User\Infrastructure\Persistence\EloquentUserRepository;
use App\Modules\User\Infrastructure\Requests\StoreUserRequest;
use App\Modules\User\Infrastructure\Resources\UserResource;
use App\Modules\UserAssignment\Application\DTOs\UserAssignmentDTO;
use App\Modules\UserAssignment\Application\UseCases\CreateUserAssignmentUseCase;
use App\Modules\UserAssignment\Infrastructure\Persistence\EloquentUserAssignmentRepository;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct()
    {
        $this->userRepository = new EloquentUserRepository();
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $userDTO = new UserDTO($request->validated());
        $userUseCase = new CreateUserUseCase($this->userRepository);
        $user = $userUseCase->execute($userDTO);

        $eloquentUser = EloquentUser::find($user->getId());
        $eloquentUser->assignRole($request->role_id);

        $assignmentDTO = new UserAssignmentDTO([
            'user_id' => $user->getId(),
            'assignments' => $request->assignments,
            'status' => $request->status
        ]);

        $assignmentRepository = new EloquentUserAssignmentRepository();
        $assignmentUseCase = new CreateUserAssignmentUseCase($assignmentRepository);
        $assignmentUseCase->execute($assignmentDTO);

        $userWithRole = $this->userRepository->findById($user->getId());

        return response()->json([
            'message' => 'Usuario creado exitosamente',
            'user' => new UserResource($userWithRole)
        ], 201);


    }

    public function show($id): array|JsonResponse
    {
        $user = new GetUserByIdUseCase($this->userRepository);
        $user = $user->execute($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return (new UserResource($user))->resolve();
    }

    public function findAllUserName(): JsonResponse
    {
        $userUseCase = new FindAllUserUseNameCase($this->userRepository);
        $users = $userUseCase->execute();

        return response()->json($users);
    }

    public function findAllUsers(): array
    {
        $userUseCase = new FindAllUsersUseCase($this->userRepository);
        $users = $userUseCase->execute();

        return UserResource::collection($users)->resolve();
    }
}
