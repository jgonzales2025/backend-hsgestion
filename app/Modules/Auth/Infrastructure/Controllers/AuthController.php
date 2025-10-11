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
        return $this->respondWithToken(Auth::guard('api')->refresh());
    }

    protected function respondWithToken($token, $cia_id)
    {
        $eloquentUser = Auth::guard('api')->user();

        // IMPORTANTE: Refrescar el usuario desde la BD para evitar caché
        $eloquentUser->refresh();

        // Cargar relaciones necesarias
        $eloquentUser->load(['assignments.company', 'assignments.branch']);

        // Obtener el rol directamente desde la BD SIN usar la relación cacheada
        $userRoleId = \DB::table('model_has_roles')
            ->where('model_type', get_class($eloquentUser))
            ->where('model_id', $eloquentUser->id)
            ->value('role_id');

        $customRole = null;

        if ($userRoleId) {
            // Consulta fresca directamente desde la tabla de roles
            $customRole = \App\Models\Role::with(['menus' => function($query) {
                $query->where('status', 1)->orderBy('order');
            }])
                ->where('id', $userRoleId)
                ->first();

            $roleName = $customRole?->name;
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

        // Pasar el rol recién cargado
        $menusData = $this->userMenuService->getUserMenusWithRestricted($eloquentUser, $customRole);

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => Auth::guard('api')->factory()->getTTL() * 60,
            'user' => new AuthUserResource($user),
            'menus' => $menusData['accessible']
        ]);
    }
}
