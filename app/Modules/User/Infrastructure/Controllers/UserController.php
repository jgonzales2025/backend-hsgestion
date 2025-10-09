<?php

namespace App\Modules\User\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Menu\Domain\Services\UserMenuService;
use App\Modules\User\Application\DTOs\UserDTO;
use App\Modules\User\Application\UseCases\CreateUserUseCase;
use App\Modules\User\Application\UseCases\FindAllUserByVendedor;
use App\Modules\User\Application\UseCases\FindAllUsersByAlmacen;
use App\Modules\User\Application\UseCases\FindAllUsersUseCase;
use App\Modules\User\Application\UseCases\FindAllUserUseNameCase;
use App\Modules\User\Application\UseCases\GetUserByIdUseCase;
use App\Modules\User\Application\UseCases\UpdateUserUseCase;
use App\Modules\User\Infrastructure\Model\EloquentUser;
use App\Modules\User\Infrastructure\Persistence\EloquentUserRepository;
use App\Modules\User\Infrastructure\Requests\StoreUserRequest;
use App\Modules\User\Infrastructure\Requests\UpdateUserRequest;
use App\Modules\User\Infrastructure\Resources\UserResource;
use App\Modules\UserAssignment\Application\DTOs\UserAssignmentDTO;
use App\Modules\UserAssignment\Application\UseCases\CreateUserAssignmentUseCase;
use App\Modules\UserAssignment\Application\UseCases\UpdateUserAssignmentUseCase;
use App\Modules\UserAssignment\Infrastructure\Persistence\EloquentUserAssignmentRepository;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(
        protected EloquentUserRepository $userRepository,
        protected UserMenuService $userMenuService
    ) {}

    public function store(StoreUserRequest $request): JsonResponse
    {
        $userDTO = new UserDTO($request->validated());
        $userUseCase = new CreateUserUseCase($this->userRepository);
        $user = $userUseCase->execute($userDTO);

        $eloquentUser = EloquentUser::find($user->getId());
        $eloquentUser->assignRole($request->role_id);

        if ($request->has('custom_permissions')) {
            foreach ($request->custom_permissions as $permission) {
                \App\Models\UserMenuPermission::create([
                    'user_id' => $user->getId(),
                    'menu_id' => $permission['menu_id'],
                ]);
            }
        }

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

        $eloquentUser = EloquentUser::find($user->getId());

        $menusData = $this->userMenuService->getUserMenusWithRestricted($eloquentUser);

        // Obtener permisos personalizados del usuario
        $customPermissions = \App\Models\UserMenuPermission::where('user_id', $id)
            ->select(['user_id', 'menu_id'])
            ->with(['menu:id,label'])
            ->with('menu:id,label')
            ->get()
            ->map(function ($permission) {
                return [
                    'menu_id' => $permission->menu_id,
                    'menu_label' => $permission->menu->label
                ];
            });

        return response()->json([
            'user' => new UserResource($user),
            'custom_permissions' => $customPermissions
        ]);
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

    public function findAllUsersByVendedor(): array
    {
        $userUseCase = new FindAllUserByVendedor($this->userRepository);
        $users = $userUseCase->execute();

        return UserResource::collection($users)->resolve();
    }

    public function findAllUsersByAlmacen(): array
    {
        $userUseCase = new FindAllUsersByAlmacen($this->userRepository);
        $users = $userUseCase->execute();

        return UserResource::collection($users)->resolve();
    }

    public function update(UpdateUserRequest $request, $id): JsonResponse
    {
        try {
            // 1. Actualizar el usuario
            $userDTO = new UserDTO(array_merge(
                $request->validated(),
                ['id' => $id]
            ));

            $updateUserUseCase = new UpdateUserUseCase($this->userRepository);
            $user = $updateUserUseCase->execute($id, $userDTO);

            if (!$user) {
                return response()->json([
                    'message' => 'Usuario no encontrado'
                ], 404);
            }

            // 2. Actualizar el rol
            $eloquentUser = EloquentUser::find($id);
            $eloquentUser->syncRoles([$request->role_id]); // Reemplaza el rol actual

            // 3. Actualizar las asignaciones
            $assignmentDTO = new UserAssignmentDTO([
                'user_id' => $id,
                'assignments' => $request->assignments,
                'status' => $request->status
            ]);

            $assignmentRepository = new EloquentUserAssignmentRepository();
            $updateAssignmentUseCase = new UpdateUserAssignmentUseCase($assignmentRepository);
            $updateAssignmentUseCase->execute($assignmentDTO);

            // 4. Obtener el usuario actualizado
            $userUpdated = $this->userRepository->findById($id);

            return response()->json([
                'message' => 'Usuario actualizado exitosamente',
                'user' => new UserResource($userUpdated)
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar el usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
