<?php

namespace App\Modules\User\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Models\UserMenuPermission;
use App\Modules\Menu\Domain\Services\UserMenuService;
use App\Modules\User\Application\DTOs\UserDTO;
use App\Modules\User\Application\UseCases\CreateUserUseCase;
use App\Modules\User\Application\UseCases\FindAllUserByVendedor;
use App\Modules\User\Application\UseCases\FindAllUsersByAlmacen;
use App\Modules\User\Application\UseCases\FindAllUsersByVendedor;
use App\Modules\User\Application\UseCases\FindAllUsersUseCase;
use App\Modules\User\Application\UseCases\FindAllUserUseNameCase;
use App\Modules\User\Application\UseCases\FindByUserNameUseCase;
use App\Modules\User\Application\UseCases\GetUserByIdUseCase;
use App\Modules\User\Application\UseCases\PasswordValidationUseCase;
use App\Modules\User\Application\UseCases\UpdateUserStLoginUseCase;
use App\Modules\User\Application\UseCases\UpdateUserUseCase;
use App\Modules\User\Infrastructure\Model\EloquentUser;
use App\Modules\User\Infrastructure\Persistence\EloquentUserRepository;
use App\Modules\User\Infrastructure\Requests\StoreUserRequest;
use App\Modules\User\Infrastructure\Requests\UpdateStLoginUserRequest;
use App\Modules\User\Infrastructure\Requests\UpdateUserRequest;
use App\Modules\User\Infrastructure\Resources\UserResource;
use App\Modules\UserAssignment\Application\DTOs\UserAssignmentDTO;
use App\Modules\UserAssignment\Application\UseCases\CreateUserAssignmentUseCase;
use App\Modules\UserAssignment\Application\UseCases\UpdateUserAssignmentUseCase;
use App\Modules\UserAssignment\Infrastructure\Persistence\EloquentUserAssignmentRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function __construct(
        protected EloquentUserRepository $userRepository,
        protected UserMenuService $userMenuService
    ) {}

    public function store(StoreUserRequest $request): JsonResponse
    {
        // 1. Crear usuario
        $userDTO = new UserDTO($request->validated());
        $userUseCase = new CreateUserUseCase($this->userRepository);
        $user = $userUseCase->execute($userDTO);

        $eloquentUser = EloquentUser::find($user->getId());

        // 2. Asignar múltiples roles
        $roleIds = collect($request->user_roles)->pluck('role_id')->toArray();
        $eloquentUser->syncRoles($roleIds);

        // 3. Procesar permisos por cada rol
        foreach ($request->user_roles as $userRole) {
            $roleId = $userRole['role_id'];
            $customPermissions = $userRole['custom_permissions'] ?? null;

            if ($customPermissions === null) {
                // Usar permisos del rol por defecto
                $role = \App\Models\Role::with('menus')->find($roleId);
                if ($role && $role->menus) {
                    foreach ($role->menus as $menu) {
                        UserMenuPermission::create([
                            'user_id' => $user->getId(),
                            'role_id' => $roleId,
                            'menu_id' => $menu->id,
                        ]);
                    }
                }
            } elseif (is_array($customPermissions) && count($customPermissions) > 0) {
                // Usar permisos personalizados
                foreach ($customPermissions as $menuId) {
                    UserMenuPermission::create([
                        'user_id' => $user->getId(),
                        'role_id' => $roleId,
                        'menu_id' => $menuId,
                    ]);
                }
            }
            // Si custom_permissions es [], no se agregan permisos
        }

        // 4. Crear asignaciones
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

        // Obtener roles y permisos agrupados
        $userRoles = $eloquentUser->roles->map(function ($role) use ($id) {
            $permissions = UserMenuPermission::where('user_id', $id)
                ->where('role_id', $role->id)
                ->pluck('menu_id')
                ->toArray();

            return [
                'role_id' => $role->id,
                'role_name' => $role->name,
                'custom_permissions' => count($permissions) > 0 ? $permissions : null
            ];
        })->toArray();

        $assignments = collect($user->getAssignment())->map(function ($assignment) {
            return [
                'id' => $assignment['id'],
                'company_id' => $assignment['company_id'],
                'company_name' => $assignment['company_name'],
                'branch_id' => $assignment['branch_id'],
                'branch_name' => $assignment['branch_name'],
                'status' => $assignment['status']
            ];
        })->toArray();

        return response()->json([
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'status' => $user->getStatus(),
            'st_login' => $user->getStLogin(),
            'user_roles' => $userRoles,
            'assignments' => $assignments
        ]);
    }

    public function findAllUsers(): array
    {
        $userUseCase = new FindAllUsersUseCase($this->userRepository);
        $users = $userUseCase->execute();

        return UserResource::collection($users)->resolve();
    }

    public function update(UpdateUserRequest $request, $id): JsonResponse
    {
        try {

            $userUseCase = new GetUserByIdUseCase($this->userRepository);
            $user = $userUseCase->execute($id);

            if (!$user) {
                return response()->json(['message' => 'Usuario no encontrado'], 404);
            }

            // 1. Actualizar usuario
            $data = $request->validated();

            if (!empty($data['password'])) {
                $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
            } else {
                unset($data['password']);
            }

            if (!empty($data['password_item'])) {
                $userValidatePassword = new PasswordValidationUseCase($this->userRepository);
                $isValid = $userValidatePassword->execute($data['password_item']);

                if (is_array($isValid)) {
                    if ($isValid['user_id'] !== $id) {
                        return response()->json([
                            'message' => 'Contraseña no válida, ingrese otra contraseña.'
                        ], 422);
                    }
                }

                $data['password_item'] = Hash::make($data['password_item']);
            } else {
                unset($data['password_item']);
            }

            $userDTO = new UserDTO($data);
            $updateUserUseCase = new UpdateUserUseCase($this->userRepository);
            $user = $updateUserUseCase->execute($id, $userDTO);

            if (!$user) {
                return response()->json(['message' => 'Usuario no encontrado'], 404);
            }

            $eloquentUser = EloquentUser::find($id);

            // 2. Sincronizar múltiples roles
            $roleIds = collect($request->user_roles)->pluck('role_id')->toArray();
            $eloquentUser->syncRoles($roleIds);

            // 3. Limpiar permisos existentes
            UserMenuPermission::where('user_id', $id)->delete();

            // 4. Procesar permisos por cada rol
            foreach ($request->user_roles as $userRole) {
                $roleId = $userRole['role_id'];
                $customPermissions = $userRole['custom_permissions'] ?? null;

                if ($customPermissions === null) {
                    // Usar permisos del rol
                    $role = \App\Models\Role::with('menus')->find($roleId);
                    if ($role && $role->menus) {
                        foreach ($role->menus as $menu) {
                            UserMenuPermission::create([
                                'user_id' => $id,
                                'role_id' => $roleId,
                                'menu_id' => $menu->id,
                            ]);
                        }
                    }
                } elseif (is_array($customPermissions) && count($customPermissions) > 0) {
                    foreach ($customPermissions as $menuId) {
                        UserMenuPermission::create([
                            'user_id' => $id,
                            'role_id' => $roleId,
                            'menu_id' => $menuId,
                        ]);
                    }
                }
            }

            // 5. Actualizar asignaciones
            $assignmentDTO = new UserAssignmentDTO([
                'user_id' => $id,
                'assignments' => $request->assignments,
                'status' => $request->status
            ]);

            $assignmentRepository = new EloquentUserAssignmentRepository();
            $updateAssignmentUseCase = new UpdateUserAssignmentUseCase($assignmentRepository);
            $updateAssignmentUseCase->execute($assignmentDTO);

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

    public function FindByUserName(string $username): JsonResponse
    {
        $userUseCase = new FindByUserNameUseCase($this->userRepository);
        $user = $userUseCase->execute($username);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json(new UserResource($user), 200);
    }

    public function updateStLogin(UpdateStLoginUserRequest $request, $id): JsonResponse
    {
        $validatedData = $request->validated();
        $userStLoginUseCase = new UpdateUserStLoginUseCase($this->userRepository);
        $userStLoginUseCase->execute($id, $validatedData['st_login']);

        return response()->json(['message' => 'Estado de login actualizado correctamente'], 200);
    }

    public function findAllUsersByVendedor(): array
    {
        $userUseCase = new FindAllUsersByVendedor($this->userRepository);
        $users = $userUseCase->execute();

        return UserResource::collection($users)->resolve();
    }

    public function validatedPassword(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'password_item' => 'required|string'
        ]);

        $userUseCase = new PasswordValidationUseCase($this->userRepository);
        $isValid = $userUseCase->execute($validatedData['password_item']);

        if (is_bool($isValid)) {
            return response()->json(['status' => $isValid], 200);
        } else {
            return response()->json([
                'status' => $isValid['status'],
                'user_authorized_id' => $isValid['user_id'],
                'user_authorized_name' => $isValid['username'],
            ], 200);
        }
    }
}
