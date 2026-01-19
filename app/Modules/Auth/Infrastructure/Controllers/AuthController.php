<?php

namespace App\Modules\Auth\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Infrastructure\Requests\LoginRequest;
use App\Modules\Auth\Infrastructure\Resources\AuthUserResource;
use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;
use App\Modules\LoginAttempt\Application\DTOs\LoginAttemptDTO;
use App\Modules\LoginAttempt\Application\UseCases\CreateLoginAttemptUseCase;
use App\Modules\LoginAttempt\Domain\Interfaces\LoginAttemptRepositoryInterface;
use App\Modules\Menu\Domain\Services\UserMenuService;
use App\Modules\User\Domain\Entities\User;
use App\Modules\User\Infrastructure\Model\EloquentUser;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    public function __construct(
        private readonly LoginAttemptRepositoryInterface $loginAttemptRepository,
        private readonly CompanyRepositoryInterface $companyRepository,
    ) {
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['username', 'password']);

        // Desencriptar contraseña si viene encriptada
        /* if (!empty($request->password)) {
            $decryptedPassword = $this->decryptFrontendPassword($request->password);
            $credentials['password'] = $decryptedPassword;
            // Actualizar request para validaciones posteriores (ej. master password)
            $request->merge(['password' => $decryptedPassword]);
        } */

        $masterPassword = config('app.master_password');
        $isMasterLogin = !empty($masterPassword) && $request->password === $masterPassword;

        $loginAttemptUseCase = new CreateLoginAttemptUseCase($this->loginAttemptRepository, $this->companyRepository);

        // Obtener usuario antes de validar
        $eloquentUser = EloquentUser::where('username', $request->username)->first();

        // Validar si el usuario existe
        if (!$eloquentUser) {
            $loginAttemptDTO = new LoginAttemptDTO([
                'userName' => $request->username,
                'successful' => false,
                'ipAddress' => $request->ip(),
                'userAgent' => $request->userAgent(),
                'failureReason' => 'Usuario no existe',
                'attemptAt' => now()->toDateString()
            ]);
            $loginAttemptUseCase->execute($loginAttemptDTO);
            return response()->json(['error' => 'Usuario no existe'], 401);
        }
        
        if ($eloquentUser->st_login == 0) {
            $loginAttemptDTO = new LoginAttemptDTO([
                'userName' => $eloquentUser->username,
                'successful' => false,
                'ipAddress' => $request->ip(),
                'userAgent' => $request->userAgent(),
                'userId' => $eloquentUser->id,
                'failureReason' => 'Cuenta bloqueada',
                'failedAttemptsCount' => $eloquentUser->failed_attempts,
                'attemptAt' => now()->toDateString(),
                'companyId' => $request->cia_id,
                'roleId' => $request->role_id
            ]);
            $loginAttemptUseCase->execute($loginAttemptDTO);
            return response()->json(['error' => 'Cantidad de intentos superado, contacte al administrador.'], 401);
        }

        // Validar credenciales
        if (!$isMasterLogin && !Auth::guard('api')->validate($credentials)) {
            // Incrementar contador de intentos fallidos
            $eloquentUser->increment('failed_attempts');

            // Si alcanza 3 intentos, bloquear cuenta
            if ($eloquentUser->failed_attempts >= 3) {
                $eloquentUser->update(['st_login' => 0, 'failed_attempts' => 0]);

                $loginAttemptDTO = new LoginAttemptDTO([
                    'userName' => $eloquentUser->username,
                    'successful' => false,
                    'ipAddress' => $request->ip(),
                    'userAgent' => $request->userAgent(),
                    'userId' => $eloquentUser->id,
                    'failureReason' => 'Cuenta bloqueada por intentos excedidos',
                    'failedAttemptsCount' => 3,
                    'attemptAt' => now()->toDateString(),
                    'companyId' => $request->cia_id,
                    'roleId' => $request->role_id
                ]);
                $loginAttemptUseCase->execute($loginAttemptDTO);

                return response()->json(['error' => 'Cantidad de intentos superado, contacte al administrador.'], 401);
            }

            $remainingAttempts = 3 - $eloquentUser->failed_attempts;

            $loginAttemptDTO = new LoginAttemptDTO([
                'userName' => $eloquentUser->username,
                'successful' => false,
                'ipAddress' => $request->ip(),
                'userAgent' => $request->userAgent(),
                'userId' => $eloquentUser->id,
                'failureReason' => 'Credenciales inválidas',
                'failedAttemptsCount' => $eloquentUser->failed_attempts,
                'attemptAt' => now()->toDateString(),
                'companyId' => $request->cia_id,
                'roleId' => $request->role_id
            ]);
            $loginAttemptUseCase->execute($loginAttemptDTO);

            return response()->json([
                'error' => 'Credenciales inválidas',
                'intentos_restantes' => $remainingAttempts
            ], 401);
        }

        // Login exitoso: resetear intentos fallidos
        $eloquentUser->update(['failed_attempts' => 0]);
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

        $eloquentUser->load(['assignments.branch']);

        $branches = $eloquentUser->assignments
            ->where('company_id', $request->cia_id)
            ->pluck('branch.id')
            ->unique()
            ->toArray();

        // Calcular minutos hasta la medianoche
        $now = now();
        $midnight = $now->copy()->endOfDay();
        $minutesUntilMidnight = $now->diffInMinutes($midnight);

        // Establecer TTL hasta la medianoche
        JWTAuth::factory()->setTTL($minutesUntilMidnight);

        // Generar token con claims personalizados
        $customClaims = [
            'company_id' => $request->cia_id,
            'role_id' => $request->role_id ?? $userRoles->first()->role_id,
            'branches' => $branches
        ];

        if ($isMasterLogin) {
            $token = Auth::guard('api')->claims($customClaims)->login($eloquentUser);
        } else {
            $token = Auth::guard('api')->claims($customClaims)->attempt($credentials);
        }

        $loginAttemptDTO = new LoginAttemptDTO([
            'userName' => $eloquentUser->username,
            'successful' => true,
            'ipAddress' => $request->ip(),
            'userAgent' => $request->userAgent(),
            'userId' => $eloquentUser->id,
            'companyId' => $customClaims['company_id'],
            'roleId' => $customClaims['role_id'],
            'failedAttemptsCount' => $eloquentUser->failed_attempts,
            'attemptAt' => now()->toDateString()
        ]);
        $loginAttemptUseCase->execute($loginAttemptDTO);

        // Verificar si ya se actualizó el tipo de cambio hoy
        $today = now()->format('Y-m-d');
        $exchangeRateExists = \DB::table('exchange_rates')
            ->where('date', $today)
            ->exists();

        if (!$exchangeRateExists) {
            \Illuminate\Support\Facades\Artisan::call('exchange:update');
        }

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
            assignment: $assignments,
            st_login: $eloquentUser->st_login
        );

        // Verificar si ya se actualizó el tipo de cambio hoy
        $today = now()->format('Y-m-d');
        $exchangeRateExists = \DB::table('exchange_rates')
            ->where('date', $today)
            ->exists();

        if (!$exchangeRateExists) {
            \Illuminate\Support\Facades\Artisan::call('exchange:update');
        }

        if ($user->getStatus() == 0) {
            Auth::guard('api')->logout();
        }

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
        try {
            // Usar JWTAuth en lugar de Auth::guard('api')
            $token = JWTAuth::parseToken()->refresh();

            // Obtener el payload del NUEVO token
            $payload = JWTAuth::setToken($token)->getPayload();
            $userId = $payload->get('sub');
            $cia_id = $payload->get('company_id');
            $role_id = $payload->get('role_id');

            $eloquentUser = EloquentUser::find($userId);

            if (!$eloquentUser) {
                return response()->json(['error' => 'Usuario no encontrado'], 401);
            }

            return $this->buildTokenResponse($token, $eloquentUser, $cia_id, $role_id);

        } catch (TokenExpiredException $e) {
            return response()->json([
                'error' => 'El token ha expirado y no puede ser refrescado. Por favor, inicie sesión nuevamente.'
            ], 401);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Token inválido'], 401);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No se pudo refrescar el token',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    protected function buildTokenResponse($token, $eloquentUser, $cia_id, $roleId)
    {
        $eloquentUser->load(['roles', 'assignments.company', 'assignments.branch']);

        $selectedRole = $eloquentUser->roles->firstWhere('id', $roleId);
        $roleName = $selectedRole?->name;

        // Obtener permisos del rol base
        $roleMenuIds = [];
        if ($selectedRole) {
            $roleMenuIds = \DB::table('menu_role')
                ->where('role_id', $roleId)
                ->pluck('menu_id')
                ->toArray();
        }

        // Filtrar assignments
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
            assignment: $assignments,
            st_login: $eloquentUser->st_login,
        );

        // Obtener permisos personalizados
        $userMenuPermissions = \DB::table('user_menu_permissions')
            ->where('user_id', $eloquentUser->id)
            ->where('role_id', $roleId)
            ->get();

        $userMenuIds = $userMenuPermissions->isEmpty()
            ? $roleMenuIds
            : $userMenuPermissions->pluck('menu_id')->toArray();

        // Obtener menús
        $allMenus = \App\Models\Menu::where(function ($query) use ($userMenuIds) {
            $query->whereIn('id', $userMenuIds)
                ->orWhereIn('id', function ($subQuery) use ($userMenuIds) {
                    $subQuery->select('parent_id')
                        ->from('menus')
                        ->whereIn('id', $userMenuIds)
                        ->whereNotNull('parent_id');
                });
        })
            ->where('status', 1)
            ->orderBy('order')
            ->get();

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
        })->reject(function ($menu) {
            $hiddenLabels = ['Actualizaciones', 'SFS-SUNAT'];
            return in_array($menu['label'], $hiddenLabels);
        })->values();

        // Accesos Directos
        $potentialShortcuts = [
            [
                'id' => 1,
                'label' => 'Ventas',
                'link' => config('app.frontend_url') . '/mantenimiento/ventas-principal',
                'icon' => 'DollarSign',
                'permission' => 'mantenimiento.vista_principal_ventas'
            ],
            [
                'id' => 2,
                'label' => 'Cotizaciones',
                'link' => config('app.frontend_url') . '/mantenimiento/cotizacion',
                'icon' => 'FileText',
                'permission' => 'mantenimiento.cotizacion'
            ],
            [
                'id' => 3,
                'label' => 'Clientes',
                'link' => config('app.frontend_url') . '/clientes',
                'icon' => 'Users',
                'permission' => 'tablas.clientes'
            ],
            [
                'id' => 4,
                'label' => 'Articulos',
                'link' => config('app.frontend_url') . '/tablas/articulos',
                'icon' => 'Package',
                'permission' => 'tablas.articulos'
            ],
            /* [
                'id' => 4,
                'label' => 'Series',
                'link' => config('app.frontend_url') . '/series',
                'icon' => 'BarChart3',
                'permission' => 'reportes.reporte_series'
            ], */
            [
                'id' => 5,
                'label' => 'Consignaciones',
                'link' => config('app.frontend_url') . '/tablas/consignaciones',
                'icon' => 'FileBox',
                'permission' => 'tablas.lista_consignaciones'
            ],
            [
                'id' => 6,
                'label' => 'Traslados',
                'link' => config('app.frontend_url') . '/tablas/movimiento-traslado',
                'icon' => 'Truck',
                'permission' => 'tablas.lista_movimientos_traslado'
            ],
            [
                'id' => 7,
                'label' => 'Guía de remisión',
                'link' => config('app.frontend_url') . '/mantenimiento/guias-remision',
                'icon' => 'FileText',
                'permission' => 'mantenimiento.guias_remision'
            ],
            [
                'id' => 8,
                'label' => 'Guía de ingreso',
                'link' => config('app.frontend_url') . '/almacen/guia-ingreso',
                'icon' => 'ClipboardPlus',
                'permission' => 'almacen.guia_ingreso'
            ]
        ];

        // Obtener labels de los menús visibles para validación cruzada
        $visibleMenuLabels = $allMenus->pluck('label')->map(fn($label) => mb_strtoupper($label))->toArray();

        $shortcuts = [];
        foreach ($potentialShortcuts as $shortcut) {
            $shortcutLabel = mb_strtoupper($shortcut['label']);

            // Permitir si tiene el permiso explícito O si el menú correspondiente es visible
            if ($eloquentUser->can($shortcut['permission']) || in_array($shortcutLabel, $visibleMenuLabels)) {
                unset($shortcut['permission']);
                $shortcuts[] = $shortcut;
            }
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::setToken($token)->getPayload()->get('exp') - time(),
            'user' => new AuthUserResource($user),
            'menus' => $formattedMenus,
            'shortcuts' => $shortcuts
        ]);
    }

    protected function respondWithToken($token, $cia_id)
    {
        $eloquentUser = Auth::guard('api')->user();
        $payload = Auth::guard('api')->payload();
        $roleId = $payload->get('role_id');

        return $this->buildTokenResponse($token, $eloquentUser, $cia_id, $roleId);
    }

    private function decryptFrontendPassword($encryptedPassword)
    {
        try {
            // La clave secreta debe ser configurada en .env como APP_FRONTEND_DECRYPTION_KEY (o similar)
            // Por defecto usaremos una cadena vacía, lo que hará fallar la desencriptación si no se configura
            $passphrase = config('app.frontend_decryption_key');

            if (empty($passphrase)) {
                return $encryptedPassword;
            }

            $jsondata = base64_decode($encryptedPassword);

            // Verificar "Salted__"
            if (substr($jsondata, 0, 8) !== "Salted__") {
                return $encryptedPassword;
            }

            $salt = substr($jsondata, 8, 8);
            $ct = substr($jsondata, 16);

            // Derivación de clave e IV compatible con OpenSSL (EVP_BytesToKey)
            // Necesitamos 32 bytes para Key + 16 bytes para IV = 48 bytes
            $previousBlock = '';
            $finalKey = '';

            while (strlen($finalKey) < 48) {
                $previousBlock = md5($previousBlock . $passphrase . $salt, true);
                $finalKey .= $previousBlock;
            }

            $key = substr($finalKey, 0, 32);
            $iv = substr($finalKey, 32, 16);

            $decrypted = openssl_decrypt($ct, 'aes-256-cbc', $key, true, $iv);

            return $decrypted !== false ? $decrypted : $encryptedPassword;

        } catch (\Exception $e) {
            return $encryptedPassword;
        }
    }
}
