<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Resources\RoleResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(): JsonResponse
    {
        $roles = Role::all();
        return response()->json($roles);
    }

    public function store(StoreRoleRequest $request)
    {
        $validatedData = $request->validated();

        $role = \App\Models\Role::create([
            'name' => $validatedData['name'],
            'guard_name' => 'api'
        ]);

        // Asociar menús al rol
        $role->menus()->sync($validatedData['menus']);

        // Obtener los permisos de los menús seleccionados
        $menus = \App\Models\Menu::whereIn('id', $validatedData['menus'])
            ->whereNotNull('permission')
            ->pluck('permission')
            ->toArray();

        // Asignar los permisos al rol
        if (!empty($menus)) {
            $role->syncPermissions($menus);
        }

        $role->load(['menus', 'permissions']);

        return response()->json([
            'role' => $role,
            'menus' => $role->menus,
            'permissions' => $role->permissions
        ], 201);
    }

    public function show($id)
    {
        $role = \App\Models\Role::with(['menus' => function ($query) {
            $query->where('parent_id', null)
                ->where('status', 1)
                ->orderBy('order')
                ->with(['children' => function ($q) {
                    $q->where('status', 1)->orderBy('order');
                }]);
        }])->find($id);

        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        $formattedMenus = $role->menus->map(function ($menu) {
            $formattedMenu = [
                'id' => $menu->id,
                'label' => $menu->label,
                'icon' => $menu->icon,
                'route' => $menu->route,
                'order' => $menu->order,
                'status' => $menu->status
            ];

            if ($menu->children->isNotEmpty()) {
                $formattedMenu['children'] = $menu->children->map(function ($child) {
                    return [
                        'id' => $child->id,
                        'label' => $child->label,
                        'route' => $child->route,
                        'order' => $child->order,
                        'status' => $child->status
                    ];
                })->values();
            }

            return $formattedMenu;
        })->values();

        // Extraer todos los IDs de children de todos los menús
        $allChildrenIds = $role->menus->flatMap(function ($menu) {
            return $menu->children->pluck('id');
        })->values()->toArray();

        return response()->json([
            'role' => new RoleResource($role),
            'menus' => $formattedMenus,
            'children_ids' => $allChildrenIds
        ]);
    }

    public function indexPermissions()
    {
        $permissions = Permission::all();
        return response()->json($permissions);
    }
}
