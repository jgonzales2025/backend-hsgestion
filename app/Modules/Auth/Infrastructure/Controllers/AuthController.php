<?php

namespace App\Modules\Auth\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Infrastructure\Requests\LoginRequest;
use App\Modules\Auth\Infrastructure\Resources\AuthUserResource;
use App\Modules\User\Domain\Entities\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['username', 'password']);

        if (! $token = Auth::guard('api')->attempt($credentials)) {
            return response()->json(['error' => 'Credenciales inválidas'], 401);
        }

        return $this->respondWithToken($token);
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
        return $this->respondWithToken(Auth::guard('api')->refresh());
    }

    protected function respondWithToken($token)
    {
        $eloquentUser = Auth::guard('api')->user();

        $eloquentUser->load(['roles', 'assignments']);

        $assignments = $eloquentUser->assignments->map(function ($assignment) {
            return [
                'id' => $assignment->id,
                'company_id' => $assignment->company_id,
                'branch_id' => $assignment->branch_id,
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
            role: $eloquentUser->roles->first()?->name,
            assignments: $assignments
        );


        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => Auth::guard('api')->factory()->getTTL() * 60,
            'user' => new AuthUserResource($user),
            'menus' => $this->getUserMenus($eloquentUser),
        ]);
    }

    /**
     * Obtener menús del usuario
     */
    protected function getUserMenus($user)
    {
        $menus = \App\Models\Menu::active()
            ->main()
            ->ordered()
            ->with(['children' => function ($query) {
                $query->active()->ordered();
            }])
            ->get();

        return $menus->filter(function ($menu) use ($user) {
            return !$menu->permission || $user->can($menu->permission);
        })->map(function ($menu) use ($user) {
            $formattedMenu = [
                'id' => $menu->id,
                'label' => $menu->label,
                'icon' => $menu->icon,
                'route' => $menu->route,
                'order' => $menu->order,
                'status' => $menu->status
            ];

            if ($menu->children->isNotEmpty()) {
                $children = $menu->children->filter(function ($child) use ($user) {
                    return !$child->permission || $user->can($child->permission);
                })->map(function ($child) {
                    return [
                        'id' => $child->id,
                        'label' => $child->label,
                        'route' => $child->route,
                        'order' => $child->order,
                        'status' => $child->status
                    ];
                })->values();

                if ($children->isNotEmpty()) {
                    $formattedMenu['children'] = $children;
                }
            }

            return $formattedMenu;
        })->values();
    }
}
