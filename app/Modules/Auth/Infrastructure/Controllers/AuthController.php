<?php

namespace App\Modules\Auth\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Infrastructure\Requests\LoginRequest;
use App\Modules\Auth\Infrastructure\Resources\AuthUserResource;
use App\Modules\Menu\Domain\Services\UserMenuService;
use App\Modules\User\Domain\Entities\User;
use App\Modules\User\Infrastructure\Model\EloquentUser;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['username', 'password']);

        // Primer intento de autenticación sin generar token
        if (!Auth::guard('api')->validate($credentials)) {
            return response()->json(['error' => 'Credenciales inválidas'], 401);
        }

        // Obtener usuario
        $eloquentUser = EloquentUser::where('username', $request->username)->first();

        // Verificar roles del usuario
        $userRoles = \DB::table('model_has_roles')
            ->where('model_type', get_class($eloquentUser))
            ->where('model_id', $eloquentUser->id)
            ->get();

        // Si tiene más de un rol y no envió role_id, retornar lista de roles
        if ($userRoles->count() > 1 && !$request->role_id) {
            return response()->json(['message' => 'El usuario seleccionado tiene multiples roles, debe seleccionar uno para continuar.'], 200);
        }

        // Validar que el role_id pertenezca al usuario
        if ($request->role_id) {
            $hasRole = $userRoles->contains('role_id', $request->role_id);
            if (!$hasRole) {
                return response()->json(['error' => 'El rol seleccionado no pertenece al usuario'], 403);
            }
        }

        // Generar token con claims personalizados
        $customClaims = [
            'company_id' => $request->cia_id,
            'role_id' => $request->role_id ?? $userRoles->first()->role_id
        ];

        $token = Auth::guard('api')->claims($customClaims)->attempt($credentials);

        return $this->respondWithToken($token, $request->cia_id);
    }

    public function me()
    {
        $eloquentUser = Auth::guard('api')->user();

        if (!$eloquentUser) {
            return response()->json(['error' => 'No autenticado'], 401);
        }

        $eloquentUser->load(['roles', 'assignments.company', 'assignments.branch']);

        // Obtener company_id y role_id del token JWT
        $payload = Auth::guard('api')->payload();
        $companyId = $payload->get('company_id');
        $roleId = $payload->get('role_id');

        // Obtener el rol actual con el que inició sesión
        $selectedRole = $eloquentUser->roles->firstWhere('id', $roleId);
        $roleName = $selectedRole?->name;

        // Filtrar solo la asignación de la compañía con la que inició sesión
        $assignments = $eloquentUser->assignments
            ->where('company_id', $companyId)
            ->map(function ($assignment) {
                return [
                    'id' => $assignment->id,
                    'company_id' => $assignment->company_id,
                    'company_name' => $assignment->company?->company_name,
                    'branch_id' => $assignment->branch_id,
                    'branch_name' => $assignment->branch?->name,
                    'status' => ($assignment->status) == 1 ? 'Activo' : 'Inactivo',
                ];
            })
            ->values()
            ->toArray();

        $user = new User(
            id: $eloquentUser->id,
            username: $eloquentUser->username,
            firstname: $eloquentUser->firstname,
            lastname: $eloquentUser->lastname,
            password: $eloquentUser->password,
            status: $eloquentUser->status,
            roles: $roleName,
            assignment: $assignments
        );

        return response()->json([
            'user' => new AuthUserResource($user)
        ]);

    }

    public function logout()
    {
        Auth::guard('api')->logout();
        return response()->json(['message' => 'Sesión cerrada correctamente']);
    }

    public function refresh()
    {
        $eloquentUser = Auth::guard('api')->user();

        if (!$eloquentUser) {
            return response()->json(['error' => 'No autenticado'], 401);
        }

        $payload = Auth::guard('api')->payload();
        $cia_id = $payload->get('company_id');
        $role_id = $payload->get('role_id');

        $customClaims = [
            'company_id' => $cia_id,
            'role_id' => $role_id
        ];

        $token = Auth::guard('api')->claims($customClaims)->refresh();

        return $this->respondWithToken($token, $cia_id);
    }

    protected function respondWithToken($token, $cia_id)
    {
        $eloquentUser = Auth::guard('api')->user();
        $eloquentUser->refresh();
        $eloquentUser->load(['roles', 'assignments.company', 'assignments.branch']);

        // Obtener role_id del token JWT
        $payload = Auth::guard('api')->payload();
        $roleId = $payload->get('role_id');

        // Obtener el rol seleccionado
        $selectedRole = $eloquentUser->roles->firstWhere('id', $roleId);
        $roleName = $selectedRole?->name;

        // Obtener permisos del rol base
        $roleMenuIds = [];
        if ($selectedRole) {
            $roleMenuIds = \DB::table('role_has_permissions')
                ->join('menus', 'role_has_permissions.permission_id', '=', 'menus.id')
                ->where('role_has_permissions.role_id', $roleId)
                ->pluck('menus.id')
                ->toArray();
        }

        // Filtrar assignments solo de la compañía con la que inició sesión
        $assignments = $eloquentUser->assignments
            ->where('company_id', $cia_id)
            ->map(function ($assignment) {
                return [
                    'id' => $assignment->id,
                    'company_id' => $assignment->company_id,
                    'company_name' => $assignment->company?->company_name,
                    'branch_id' => $assignment->branch_id,
                    'branch_name' => $assignment->branch?->name,
                    'status' => $assignment->status,
                ];
            })
            ->values()
            ->toArray();

        $user = new User(
            id: $eloquentUser->id,
            username: $eloquentUser->username,
            firstname: $eloquentUser->firstname,
            lastname: $eloquentUser->lastname,
            password: $eloquentUser->password,
            status: $eloquentUser->status,
            roles: $roleName,
            assignment: $assignments
        );

        // Obtener permisos personalizados del usuario SOLO para el rol actual
        $userMenuPermissions = \DB::table('user_menu_permissions')
            ->where('user_id', $eloquentUser->id)
            ->where('role_id', $roleId) // ← FILTRAR POR ROL ACTUAL
            ->get();

        // Determinar qué permisos usar
        $userMenuIds = [];
        if ($userMenuPermissions->isEmpty()) {
            // Si no hay permisos personalizados, usar los del rol
            $userMenuIds = $roleMenuIds;
        } else {
            // Si hay permisos personalizados, usarlos
            $userMenuIds = $userMenuPermissions->pluck('menu_id')->toArray();
        }

        // Obtener todos los menús con sus padres
        $allMenus = \App\Models\Menu::whereIn('id', $userMenuIds)
            ->orWhereIn('id', function($query) use ($userMenuIds) {
                $query->select('parent_id')
                    ->from('menus')
                    ->whereIn('id', $userMenuIds)
                    ->whereNotNull('parent_id');
            })
            ->where('status', 1)
            ->orderBy('order')
            ->get();

        // Construir estructura jerárquica
        $parentMenus = $allMenus->whereNull('parent_id')->values();

        $formattedMenus = $parentMenus->map(function ($menu) use ($allMenus) {
            $menuArray = [
                'id' => $menu->id,
                'label' => $menu->label,
                'icon' => $menu->icon,
                'route' => $menu->route,
                'order' => $menu->order,
                'status' => $menu->status,
            ];

            $children = $allMenus->where('parent_id', $menu->id)->sortBy('order')->values();

            if ($children->isNotEmpty()) {
                $menuArray['children'] = $children->map(function ($child) {
                    return [
                        'id' => $child->id,
                        'label' => $child->label,
                        'route' => $child->route,
                        'order' => $child->order,
                        'status' => $child->status,
                    ];
                })->values()->toArray();
            }

            return $menuArray;
        })->values();

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => Auth::guard('api')->factory()->getTTL() * 60,
            'user' => new AuthUserResource($user),
            'menus' => $formattedMenus
        ]);
    }
}
