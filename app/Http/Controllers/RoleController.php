<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{

    public function indexPaginateInfinite(Request $request): JsonResponse
    {
        $description = $request->query('description');
        $roles = Role::when($description, fn($query) => $query->where('name', 'like', "%{$description}%"))->cursorPaginate(10);
        return new JsonResponse([
            'data' => $roles->items(),
            'next_cursor' => $roles->nextCursor()?->encode(),
            'prev_cursor' => $roles->previousCursor()?->encode(),
            'next_page_url' => $roles->nextPageUrl(),
            'prev_page_url' => $roles->previousPageUrl(),
            'per_page' => $roles->perPage()
        ]);
    }
    public function index(Request $request): JsonResponse
    {
        $description = $request->query('description');
        $roles = Role::when($description, fn($query) => $query->where('name', 'like', "%{$description}%"))->paginate(10);
        return new JsonResponse([
            'data' => $roles->items(),
            'current_page' => $roles->currentPage(),
            'per_page' => $roles->perPage(),
            'total' => $roles->total(),
            'last_page' => $roles->lastPage(),
            'next_page_url' => $roles->nextPageUrl(),
            'prev_page_url' => $roles->previousPageUrl(),
            'first_page_url' => $roles->url(1),
            'last_page_url' => $roles->url($roles->lastPage()),
        ]);
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
        $role = \App\Models\Role::with('menus')->find($id);

        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        // Obtener IDs de menús asociados al rol
        $roleMenuIds = $role->menus->pluck('id')->toArray();

        // Obtener solo los menús padres que están asociados al rol O tienen hijos asociados
        $parentMenus = $role->menus->where('parent_id', null)->sortBy('order');

        // También incluir padres cuyos hijos están asociados (aunque el padre no lo esté)
        $childrenParentIds = $role->menus->whereNotNull('parent_id')->pluck('parent_id')->unique();
        $additionalParents = \App\Models\Menu::whereIn('id', $childrenParentIds)
            ->whereNotIn('id', $roleMenuIds)
            ->get();

        $allParents = $parentMenus->merge($additionalParents)->sortBy('order')->unique('id');

        $formattedMenus = $allParents->map(function ($menu) use ($role, $roleMenuIds) {
            $formattedMenu = [
                'id' => $menu->id,
                'label' => $menu->label,
                'icon' => $menu->icon,
                'route' => $menu->route,
                'order' => $menu->order,
                'status' => $menu->status
            ];

            // Obtener solo los hijos asociados al rol
            $children = $role->menus->where('parent_id', $menu->id)->sortBy('order');

            if ($children->isNotEmpty()) {
                $formattedMenu['children'] = $children->map(function ($child) {
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

        // Extraer solo los IDs de children asociados al rol
        $allChildrenIds = $role->menus
            ->whereNotNull('parent_id')
            ->pluck('id')
            ->values()
            ->toArray();

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

    public function update(UpdateRoleRequest $request, $id)
    {
        $validatedData = $request->validated();

        $role = \App\Models\Role::where('guard_name', 'api')->findOrFail($id);

        $role->update([
            'name' => $validatedData['name'],
            'guard_name' => 'api'
        ]);

        // Sincronizar menús
        if (isset($validatedData['menus'])) {
            $role->menus()->sync($validatedData['menus']);
        }

        return response()->json([
            'message' => 'Rol actualizado exitosamente',
            'role' => $role->load('menus')
        ]);
    }
}
