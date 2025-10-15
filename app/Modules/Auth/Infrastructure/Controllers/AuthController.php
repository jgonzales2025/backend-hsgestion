<?php

namespace App\Modules\Auth\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Infrastructure\Requests\LoginRequest;
use App\Modules\Auth\Infrastructure\Resources\AuthUserResource;
use App\Modules\Menu\Domain\Services\UserMenuService;
use App\Modules\User\Domain\Entities\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(private UserMenuService $userMenuService) {}

    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['username', 'password']);

        if (! $token = Auth::guard('api')->attempt($credentials)) {
            return response()->json(['error' => 'Credenciales inválidas'], 401);
        }

        return $this->respondWithToken($token, $request->cia_id);
    }

    public function me()
    {
        $eloquentUser = Auth::guard('api')->user();

        if (!$eloquentUser) {
            return response()->json(['error' => 'No autenticado'], 401);
        }

        $eloquentUser->load(['roles', 'assignments']);

        $assignments = $eloquentUser->assignments->map(function ($assignment) {
            return [
                'id' => $assignment->id,
                'company_id' => $assignment->company_id,
                'company_name' => $assignment->company?->company_name,
                'branch_id' => $assignment->branch_id,
                'branch_name' => $assignment->branch?->name,
                'status' => ($assignment->status) == 1 ? 'Activo' : 'Inactivo',
            ];
        })->toArray();

        $user = new User(
            id: $eloquentUser->id,
            username: $eloquentUser->username,
            firstname: $eloquentUser->firstname,
            lastname: $eloquentUser->lastname,
            password: $eloquentUser->password,
            status: $eloquentUser->status,
            role: $eloquentUser->roles->first()?->name,
            assignments: $assignments
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
        // Obtener el usuario autenticado
        $eloquentUser = Auth::guard('api')->user();

        if (!$eloquentUser) {
            return response()->json(['error' => 'No autenticado'], 401);
        }

        // Obtener la primera asignación activa del usuario para el cia_id
        $activeAssignment = $eloquentUser->assignments()
            ->where('status', 1)
            ->first();

        $cia_id = $activeAssignment?->company_id ?? null;

        // Generar nuevo token
        $token = Auth::guard('api')->refresh();

        return $this->respondWithToken($token, $cia_id);
    }

    protected function respondWithToken($token, $cia_id)
    {
        $eloquentUser = Auth::guard('api')->user();
        $eloquentUser->refresh();
        $eloquentUser->load(['assignments.company', 'assignments.branch']);

        $userRoleId = \DB::table('model_has_roles')
            ->where('model_type', get_class($eloquentUser))
            ->where('model_id', $eloquentUser->id)
            ->value('role_id');

        $customRole = null;
        $roleMenuIds = [];

        if ($userRoleId) {
            $customRole = \App\Models\Role::with('menus')->where('id', $userRoleId)->first();
            $roleName = $customRole?->name;
            $roleMenuIds = $customRole?->menus->pluck('id')->toArray() ?? [];
        } else {
            $roleName = null;
        }

        $assignments = $eloquentUser->assignments->when($cia_id, function ($query) use ($cia_id) {
            return $query->where('company_id', $cia_id);
        })->map(function ($assignment) {
            return [
                'id' => $assignment->id,
                'company_id' => $assignment->company_id,
                'company_name' => $assignment->company?->company_name,
                'branch_id' => $assignment->branch_id,
                'branch_name' => $assignment->branch?->name,
                'status' => $assignment->status,
            ];
        })->toArray();

        $user = new User(
            id: $eloquentUser->id,
            username: $eloquentUser->username,
            firstname: $eloquentUser->firstname,
            lastname: $eloquentUser->lastname,
            password: $eloquentUser->password,
            status: $eloquentUser->status,
            role: $roleName,
            assignments: $assignments
        );

        // Obtener IDs de menús del usuario
        $userMenuIds = \DB::table('user_menu_permissions')
            ->where('user_id', $eloquentUser->id)
            ->pluck('menu_id')
            ->toArray();

        // Obtener todos los menús con sus padres si existen
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

        // Identificar permisos personalizados
        $customPermissionIds = array_diff($userMenuIds, $roleMenuIds);

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
